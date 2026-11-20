<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\ContactEnquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactEnquiryController extends Controller
{
    public function index(): View
    {
        $enquiries = ContactEnquiry::latest()->paginate(20);

        return view('admin.enquiries.index', compact('enquiries'));
    }

    public function update(Request $request, ContactEnquiry $enquiry): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:new,read,replied,closed'],
            'admin_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $enquiry->update($validated);
        AuditLog::record('contact_enquiry.updated', "Updated contact enquiry {$enquiry->reference_number}", $enquiry);

        return back()->with('success', 'Enquiry updated.');
    }
}
