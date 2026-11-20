<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\HomepageBanner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BannerController extends Controller
{
    public function index(): View
    {
        $banners = HomepageBanner::orderBy('sort_order')->get();

        return view('admin.banners.index', compact('banners'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'subtitle' => ['nullable', 'string', 'max:500'],
            'badge_text' => ['nullable', 'string', 'max:80'],
            'image' => ['nullable', 'image', 'max:4096'],
            'cta_primary_text' => ['nullable', 'string', 'max:80'],
            'cta_primary_url' => ['nullable', 'string', 'max:255'],
            'cta_secondary_text' => ['nullable', 'string', 'max:80'],
            'cta_secondary_url' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('banners', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $banner = HomepageBanner::create($validated);
        AuditLog::record('banner.created', "Created homepage banner {$banner->title}", $banner);

        return back()->with('success', 'Homepage banner added.');
    }

    public function toggle(HomepageBanner $banner): RedirectResponse
    {
        $banner->update(['is_active' => ! $banner->is_active]);
        AuditLog::record('banner.toggled', "Toggled banner {$banner->title}", $banner);

        return back()->with('success', 'Banner status updated.');
    }

    public function destroy(HomepageBanner $banner): RedirectResponse
    {
        if ($banner->image && Storage::disk('public')->exists($banner->image)) {
            Storage::disk('public')->delete($banner->image);
        }
        $banner->delete();
        AuditLog::record('banner.deleted', "Deleted banner {$banner->title}", $banner);

        return back()->with('success', 'Banner deleted.');
    }
}
