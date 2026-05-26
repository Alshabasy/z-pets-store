<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = \App\Models\Setting::pluck('value', 'key')->toArray();

        return view('dashboard.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $input = $request->all();
        foreach (['social_facebook', 'social_instagram', 'social_tiktok'] as $field) {
            if (array_key_exists($field, $input) && $input[$field] === '') {
                $input[$field] = null;
            }
        }
        $request->merge($input);

        $data = $request->validate([
            'store_name'          => 'required|string|max:255',
            'store_tagline'       => 'nullable|string|max:255',
            'whatsapp_number'     => 'required|string|max:20',
            'free_delivery_above' => 'nullable|numeric|min:0',
            'delivery_note'       => 'nullable|string|max:500',
            'shop_address'        => 'nullable|string|max:500',
            'social_facebook'     => 'nullable|url|max:255',
            'social_instagram'    => 'nullable|url|max:255',
            'social_tiktok'       => 'nullable|url|max:255',
        ]);

        foreach ($data as $key => $value) {
            \App\Models\Setting::where('key', $key)->update(['value' => $value]);
        }

        session()->flash('success', 'Settings saved successfully!');

        return redirect()->route('dashboard.settings.index');
    }
}
