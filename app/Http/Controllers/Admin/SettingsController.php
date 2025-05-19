<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\services\SettingsService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index(): View
    {
        return view('admin.setting.index');
    }

    public function UpdateGeneralSetting(Request $request): RedirectResponse
    {
        //dd($request->all());
        $validatedData = $request->validate([
            'site_name' => 'required|max:255',
            'site_default_currency' => 'required|max:5',
            'site_currency_icon' => 'required|max:4',
            'site_currency_icon_position' => 'required|max:20',
        ]);

        // dd($validatedData);

        //? loop through validated data and check if exist update or create data
        foreach ($validatedData as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value],
            );
        }

        //? clear settings cache memory
        $settingsService = app(SettingsService::class);
        $settingsService->clearCacheSettings();

        //? flash success message
        toastr()->success('Updated Successfully');
        return redirect()->back();
    }
}