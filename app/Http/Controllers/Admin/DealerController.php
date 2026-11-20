<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\DealerActivity;
use App\Models\DealerApplication;
use App\Models\DealerDocument;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DealerController extends Controller
{
    public function index(Request $request): View
    {
        $query = DealerApplication::with(['assignee', 'documents']);

        if ($search = trim((string) $request->query('search'))) {
            $query->where(function ($q) use ($search) {
                $q->where('reference_number', 'like', "%{$search}%")
                  ->orWhere('business_name', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%")
                  ->orWhere('mobile_number', 'like', "%{$search}%");
            });
        }

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        $applications = $query->latest()->paginate(15)->withQueryString();

        return view('admin.dealers.index', compact('applications'));
    }

    public function show(DealerApplication $dealer): View
    {
        $dealer->load(['documents', 'activities.user', 'assignee', 'reviewer']);
        $staff = User::where('is_active', true)->get();

        return view('admin.dealers.show', compact('dealer', 'staff'));
    }

    public function update(Request $request, DealerApplication $dealer): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(array_keys(DealerApplication::STATUSES))],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'follow_up_date' => ['nullable', 'date'],
            'internal_notes' => ['nullable', 'string', 'max:2000'],
            'activity_note' => ['nullable', 'string', 'max:1000'],
        ]);

        $oldStatus = $dealer->status;
        $dealer->update([
            'status' => $validated['status'],
            'assigned_to' => $validated['assigned_to'] ?? $dealer->assigned_to,
            'follow_up_date' => $validated['follow_up_date'] ?? $dealer->follow_up_date,
            'internal_notes' => $validated['internal_notes'] ?? $dealer->internal_notes,
            'reviewed_by' => in_array($validated['status'], ['approved', 'rejected']) ? auth()->id() : $dealer->reviewed_by,
            'reviewed_at' => in_array($validated['status'], ['approved', 'rejected']) ? now() : $dealer->reviewed_at,
        ]);

        DealerActivity::create([
            'dealer_application_id' => $dealer->id,
            'user_id' => auth()->id(),
            'action' => 'status_updated',
            'old_status' => $oldStatus,
            'new_status' => $validated['status'],
            'notes' => $validated['activity_note'] ?: "Status updated from {$oldStatus} to {$validated['status']}.",
        ]);

        AuditLog::record('dealer.updated', "Updated dealer application {$dealer->reference_number} ({$validated['status']})", $dealer);

        return back()->with('success', 'Dealer application updated.');
    }

    public function downloadDocument(DealerApplication $dealer, DealerDocument $document): StreamedResponse
    {
        abort_unless($document->dealer_application_id === $dealer->id, 404);
        abort_unless(Storage::disk('local')->exists($document->file_path), 404);

        return Storage::disk('local')->download($document->file_path, $document->original_name);
    }

    public function export(): StreamedResponse
    {
        $applications = DealerApplication::latest()->get();
        AuditLog::record('dealer.exported', 'Exported dealer applications CSV (' . $applications->count() . ' records).');

        return response()->streamDownload(function () use ($applications) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Reference', 'Business Name', 'Reg No', 'Contact Person', 'Mobile', 'Email',
                'City', 'State', 'Business Type', 'Years', 'Monthly Volume', 'Outlets',
                'Partnership Type', 'Status', 'Created At',
            ]);

            foreach ($applications as $app) {
                fputcsv($handle, [
                    $app->reference_number,
                    $app->business_name,
                    $app->registration_number,
                    $app->contact_person,
                    $app->mobile_number,
                    $app->email,
                    $app->city,
                    $app->state,
                    $app->business_type,
                    $app->years_in_operation,
                    $app->estimated_monthly_volume,
                    $app->number_of_outlets,
                    $app->partnership_type,
                    $app->status_label,
                    $app->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            fclose($handle);
        }, 'everlead-dealer-applications-' . now()->format('Ymd-His') . '.csv');
    }
}
