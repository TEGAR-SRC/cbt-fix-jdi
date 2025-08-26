<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
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
            'remove_logo' => 'nullable|boolean'
        ]);

        $this->saveSetting('site_name', $data['site_name']);
        $this->saveSetting('cbt_name', $data['cbt_name']);

        if ($request->boolean('remove_logo')) {
            $this->saveSetting('school_logo', null);
        } elseif ($request->hasFile('logo')) {
            // Simpler: simpan langsung ke public/uploads agar langsung bisa diakses
            $file = $request->file('logo');
            File::ensureDirectoryExists(public_path('uploads'));
            $name = 'logo_'.time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $name);
            $publicPath = '/uploads/'.$name; // langsung bisa dipakai oleh browser
            $this->saveSetting('school_logo', $publicPath);
            $this->saveSetting('logo_cache_bust', time());
        }

        if (!$request->hasFile('logo') && !$request->boolean('remove_logo')) {
            \Log::info('Settings update: no logo file received', [ 'fields' => array_keys($request->all()) ]);
        }

        return back()->with('success', 'Settings saved');
    }

    private function saveSetting($key, $value)
    {
        Setting::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
