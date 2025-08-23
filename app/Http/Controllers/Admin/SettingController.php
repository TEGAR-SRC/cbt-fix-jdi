<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class SettingController extends Controller
{
    public function index()
    {
        $settings = collect([
            'site_name' => '',
            'cbt_name' => '',
            'school_logo' => '',
        ]);

        Setting::query()->get()->each(function ($s) use (&$settings) {
            $settings[$s->key] = $s->value;
        });

        return Inertia::render('Admin/Settings/Index', [
            'settings' => $settings,
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'site_name' => 'required|string|max:255',
            'cbt_name' => 'required|string|max:255',
            'logo' => 'nullable|image|max:2048',
        ]);

        $this->saveSetting('site_name', $data['site_name']);
        $this->saveSetting('cbt_name', $data['cbt_name']);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('public/logos');
            $url = Storage::url($path);
            $this->saveSetting('school_logo', $url);
        }

        return back()->with('success', 'Settings saved');
    }

    private function saveSetting($key, $value)
    {
        Setting::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
