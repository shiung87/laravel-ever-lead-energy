<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\CustomerLead;
use App\Models\LeadActivity;
use App\Models\LeadAssignment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LeadController extends Controller
{
    public function index(Request $request): View
    {
        $query = CustomerLead::with(['assignee', 'product']);

        if (auth()->user()->hasRole('sales-executive')) {
            $query->where('assigned_to', auth()->id());
        }

        if ($search = trim((string) $request->query('search'))) {
            $query->where(function ($q) use ($search) {
                $q->where('reference_number', 'like', "%{$search}%")
                  ->orWhere('full_name', 'like', "%{$search}%")
                  ->orWhere('mobile_number', 'like', "%{$search}%")
                  ->orWhere('vehicle_brand', 'like', "%{$search}%")
                  ->orWhere('vehicle_model', 'like', "%{$search}%");
            });
        }

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        if ($enquiryType = $request->query('enquiry_type')) {
            $query->where('enquiry_type', $enquiryType);
        }

        if ($assignedTo = $request->query('assigned_to')) {
            $query->where('assigned_to', $assignedTo);
        }

        $leads = $query->latest()->paginate(15)->withQueryString();
        $salesStaff = User::where('is_active', true)->whereHas('role', fn ($q) => $q->whereIn('slug', ['sales-manager', 'sales-executive', 'admin', 'super-admin']))->get();

        return view('admin.leads.index', compact('leads', 'salesStaff'));
    }

    public function show(CustomerLead $lead): View
    {
        if (auth()->user()->hasRole('sales-executive') && $lead->assigned_to !== auth()->id()) {
            abort(403, 'You can only view leads assigned to you.');
        }

        $lead->load(['product', 'promotion', 'assignee', 'activities.user', 'assignments.assignee', 'assignments.assigner']);
        $salesStaff = User::where('is_active', true)->whereHas('role', fn ($q) => $q->whereIn('slug', ['sales-manager', 'sales-executive', 'admin', 'super-admin']))->get();

        return view('admin.leads.show', compact('lead', 'salesStaff'));
    }

    public function updateStatus(Request $request, CustomerLead $lead): RedirectResponse
    {
        if (auth()->user()->hasRole('sales-executive') && $lead->assigned_to !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => ['required', Rule::in(array_keys(CustomerLead::STATUSES))],
            'follow_up_date' => ['nullable', 'date'],
            'internal_notes' => ['nullable', 'string', 'max:2000'],
            'activity_note' => ['nullable', 'string', 'max:1000'],
        ]);

        $oldStatus = $lead->status;
        $lead->update([
            'status' => $validated['status'],
            'follow_up_date' => $validated['follow_up_date'] ?? $lead->follow_up_date,
            'internal_notes' => $validated['internal_notes'] ?? $lead->internal_notes,
        ]);

        LeadActivity::create([
            'customer_lead_id' => $lead->id,
            'user_id' => auth()->id(),
            'action' => 'status_updated',
            'old_status' => $oldStatus,
            'new_status' => $validated['status'],
            'notes' => $validated['activity_note'] ?: "Status changed from {$oldStatus} to {$validated['status']}.",
            'follow_up_date' => $validated['follow_up_date'] ?? null,
        ]);

        AuditLog::record('lead.status_updated', "Updated lead {$lead->reference_number} status to {$validated['status']}", $lead);

        return back()->with('success', 'Lead status and activity updated.');
    }

    public function assign(Request $request, CustomerLead $lead): RedirectResponse
    {
        $validated = $request->validate([
            'assigned_to' => ['required', 'exists:users,id'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $lead->update(['assigned_to' => $validated['assigned_to']]);

        LeadAssignment::create([
            'customer_lead_id' => $lead->id,
            'assigned_to' => $validated['assigned_to'],
            'assigned_by' => auth()->id(),
            'notes' => $validated['notes'] ?? null,
        ]);

        $assignee = User::find($validated['assigned_to']);
        LeadActivity::create([
            'customer_lead_id' => $lead->id,
            'user_id' => auth()->id(),
            'action' => 'assigned',
            'notes' => "Assigned to {$assignee->name}." . ($validated['notes'] ? " Note: {$validated['notes']}" : ''),
        ]);

        AuditLog::record('lead.assigned', "Assigned lead {$lead->reference_number} to {$assignee->name}", $lead);

        return back()->with('success', "Lead assigned to {$assignee->name}.");
    }

    public function export(Request $request): StreamedResponse
    {
        $query = CustomerLead::with('assignee')->latest();
        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        $leads = $query->get();

        AuditLog::record('lead.exported', 'Exported customer leads CSV (' . $leads->count() . ' records).');

        return response()->streamDownload(function () use ($leads) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Reference', 'Full Name', 'Mobile', 'Email', 'State', 'City',
                'Vehicle', 'Battery Requirement', 'Enquiry Type', 'Status',
                'Assigned To', 'UTM Source', 'UTM Campaign', 'Created At',
            ]);

            foreach ($leads as $lead) {
                fputcsv($handle, [
                    $lead->reference_number,
                    $lead->full_name,
                    $lead->mobile_number,
                    $lead->email,
                    $lead->state,
                    $lead->city,
                    trim("{$lead->vehicle_brand} {$lead->vehicle_model} {$lead->vehicle_year}"),
                    $lead->battery_requirement,
                    $lead->enquiry_type_label,
                    $lead->status_label,
                    $lead->assignee?->name ?? 'Unassigned',
                    $lead->utm_source ?? 'direct',
                    $lead->utm_campaign ?? '',
                    $lead->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            fclose($handle);
        }, 'everlead-customer-leads-' . now()->format('Ymd-His') . '.csv');
    }
}
