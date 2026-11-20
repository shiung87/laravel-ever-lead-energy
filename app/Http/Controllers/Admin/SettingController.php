<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\WebsiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        $settings = WebsiteSetting::orderBy('group')->orderBy('id')->get()->groupBy('group');

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $inputs = $request->input('settings', []);
        foreach ($inputs as $key => $value) {
            WebsiteSetting::where('key', $key)->update(['value' => $value]);
        }

        // Handle boolean checkboxes unchecked
        $booleanKeys = WebsiteSetting::where('type', 'boolean')->pluck('key');
        foreach ($booleanKeys as $bKey) {
            if (! array_key_exists($bKey, $inputs)) {
                WebsiteSetting::where('key', $bKey)->update(['value' => '0']);
            }
        }

        WebsiteSetting::clearCache();
        AuditLog::record('settings.updated', 'Updated website, WhatsApp, SEO, and system settings.');

        return back()->with('success', 'Settings saved successfully.');
    }
}
