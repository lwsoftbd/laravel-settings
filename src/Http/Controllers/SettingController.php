<?php

namespace LWSoftBD\LwSettings\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use LWSoftBD\LwSettings\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all();
        return view('settings::index', compact('settings'));
    }

    public function search(Request $request)
    {
        if ($request->ajax()) {
            $query = $request->get('query');
            $settings = Setting::where('key', 'like', '%' . $query . '%')
                ->orWhere('value', 'like', '%' . $query . '%')
                ->get();
            return response()->json($settings);
        }
    }

    public function edit()
    {
        $settings = Setting::all()->groupBy('group');
        return view('site-settings::edit', compact('settings'));
    }

    /**
     * Update multiple settings
     */
    public function update(Request $request)
    {
        foreach ($request->settings as $key => $value) {

            // Fetch existing setting to get type
            $setting = Setting::where('key', $key)->first();
            $type = $setting?->type ?? 'text';

            // Handle old file removal if new file uploaded
            if (in_array($type, ['image', 'file']) && $setting?->value && is_a($value, \Illuminate\Http\UploadedFile::class)) {
                Storage::disk('public')->delete($setting->value);
            }

            // Cast value based on type
            $value = $this->castValueByType($type, $value);

            // Update or create
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );

            // Clear cache
            setting_forget($key);
        }

        return back()->with('success', 'Settings updated successfully!');
    }

    /**
     * Cast value based on type
     */
    protected function castValueByType(string $type, $value)
    {
        switch ($type) {
            case 'image':
            case 'file':
                if (is_a($value, \Illuminate\Http\UploadedFile::class)) {
                    $value = $value->store('settings', 'public');
                }
                break;

            case 'boolean':
                $value = $value ? '1' : '0';
                break;

            case 'json':
                $value = json_encode($value);
                break;

            case 'number':
                $value = is_numeric($value) ? $value + 0 : null;
                break;

            default:
                $value = $value;
        }

        return $value;
    }

    public function create(Request $request)
    {
        return view('site-settings::create')->with('success', 'Settings created successfully!');
    }


    /**
     * Store new setting
     */
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'group' => [
                'nullable',
                'string',
                'regex:/^[a-z0-9]+$/', // only lowercase letters and numbers
            ],
            'key'   => 'required|string|unique:settings,key',
            'type'  => 'required|in:text,image,file,boolean,json,number,date,email,url,textarea,password,color',
            'value' => 'nullable'
        ], [
            'group.regex' => 'The group may only contain lowercase letters (a-z) and numbers (0-9).',
        ]);

        $group = $request->group ?: 'unknown'; // if empty, default 'unknown'

        $value = $this->castValueByType($request->type, $request->value);

        // Save new setting
        Setting::create([
            'group' => $group,
            'key'   => $request->key,
            'type'  => $request->type,
            'value' => $value,
        ]);

        // Clear cache
        setting_forget($request->key);

        return redirect()->route('site.settings')->with('success', 'New setting created successfully');
    }


    // Clear single cache
    public function clearCache(string $key)
    {
        setting_forget($key);

        return back()->with('success', "Cache cleared for setting: {$key}");
    }

    // Clear all settings cache
    public function clearAllCache()
    {
        setting_forget_all();

        return back()->with('success', 'All settings cache cleared successfully');
    }

}