<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    public function index(): View
    {
        $testimonials = Testimonial::orderBy('sort_order')->latest()->paginate(15);

        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:120'],
            'customer_role' => ['nullable', 'string', 'max:100'],
            'vehicle_info' => ['nullable', 'string', 'max:120'],
            'city' => ['nullable', 'string', 'max:80'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'quote' => ['required', 'string', 'max:1000'],
        ]);

        $testimonial = Testimonial::create(array_merge($validated, [
            'is_featured' => $request->boolean('is_featured', true),
            'is_active' => true,
        ]));

        AuditLog::record('testimonial.created', "Added testimonial from {$testimonial->customer_name}", $testimonial);

        return back()->with('success', 'Testimonial added.');
    }

    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        $testimonial->delete();
        AuditLog::record('testimonial.deleted', "Deleted testimonial from {$testimonial->customer_name}", $testimonial);

        return back()->with('success', 'Testimonial removed.');
    }
}
