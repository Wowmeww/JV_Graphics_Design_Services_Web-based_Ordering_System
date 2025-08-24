<?php

namespace App\Http\Controllers;

use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class SystemSettingController extends Controller
{
    public function edit()
    {
        $systemSettings = SystemSetting::whereIn('key', ['app_name', 'app_name_short', 'app_logo'])->get();

        return Inertia::render('profile/SystemSettings', [
            'systemSettings' => $systemSettings
        ]);
    }
    public function update(Request $request)
    {
        $withLogo = $request->hasFile('app_logo');
        $validated = $request->validate([
            'app_name' => ['required', 'string', 'max:255'],
            'app_name_short' => ['required', 'string', 'max:255'],
            'app_logo' => ['required', $withLogo ? 'image' : 'string', 'max:1024'],
        ]);


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

        return to_route('system.settings')->with('status', [
            'type' => 'success',
            'message' => 'Updated'
        ]);
    }
}
