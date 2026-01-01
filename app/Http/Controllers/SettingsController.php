<?php

namespace App\Http\Controllers;

use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = SystemSetting::first() ?? new SystemSetting();
        return view('settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'system_name' => 'required|string|max:255',
            'logo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
        ]);

        $settings = SystemSetting::first() ?? new SystemSetting();

        $settings->system_name = $request->system_name;

        if ($request->hasFile('logo_path')) {
            if ($settings->logo_path) {
                Storage::delete('public/' . $settings->logo_path);
            }
            $settings->logo_path = $request->file('logo_path')->store('logos', 'public');
        }

        $settings->save();

        return redirect()->back()->with('success', 'Settings updated successfully!');
    }
}