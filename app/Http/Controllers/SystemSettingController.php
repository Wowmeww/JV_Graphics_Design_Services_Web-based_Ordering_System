<?php

namespace App\Http\Controllers;

use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Validation\Rule;

class SystemSettingController extends Controller
{
    public function edit()
    {
        $settings = SystemSetting::pluck('value', 'key')->toArray();
        $settings['app_logo'] = $settings['app_logo'] ? '/storage/' . $settings['app_logo'] : '/favicon.jpg';
        $settings['app_about'] = $settings['app_about'] ? json_decode($settings['app_about']) : [];

        return Inertia::render('profile/SystemSettings', [
            'settings' => $settings
        ]);
    }
    public function update(Request $request)
    {
        $user = $request->user();
        $withLogo = $request->hasFile('app_logo');
        $validated = $request->validate([
            'app_name' => ['required', 'string', 'max:255'],
            'app_name_short' => ['required', 'string', 'max:255'],
            'app_logo' => ['required', $withLogo ? 'image' : 'string', 'max:1024'],
            'app_email' => ['required', 'email', 'lowercase', 'max:200', Rule::unique('users', 'email')->ignore($user->id)],
            'app_phone' => ['required', 'string', 'regex:/^(\+?63|0)?9\d{9}$/'],
            'landing_page_title' => ['required', 'string', 'max:255'],
            'landing_page_subtitle' => ['nullable', 'string', 'max:255'],
            'app_about' => ['nullable', 'string'],
        ]);

        // dd($validated['app_about']);


        if ($withLogo) {
            $path = Storage::disk('public')->put('/logo', $request->file('app_logo'));
            if ($path) SystemSetting::firstOrCreate(['key' => 'app_logo'])
                ->update(['value' => $path]);
        } elseif ($validated['app_logo'] === 'delete') {
            Storage::disk('public')->delete(SystemSetting::firstOrCreate(['key' => 'app_logo'])->value);
            SystemSetting::firstOrCreate(['key' => 'app_logo'])
                ->update(['value' => null]);
        };
        foreach ($validated as $key => $value) {
            if ($key !== 'app_logo') {
                SystemSetting::firstOrCreate(['key' => $key])->update(['value' => $value]);
            }
        }

        return back()->with('status', [
            'type' => 'success',
            'message' => 'System Settings Updated'
        ]);
    }
}
