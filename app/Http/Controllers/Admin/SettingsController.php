<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\MailSettingsService;
use App\services\SettingsService;
use App\Traits\FileUploadTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Ramsey\Collection\Set;

class SettingsController extends Controller
{
    use FileUploadTrait;

    public function index(): View
    {
        // dd(config('mail'));
        return view('admin.setting.index');
    }

    public function UpdateGeneralSetting(Request $request): RedirectResponse
    {
        //dd($request->all());
        $validatedData = $request->validate([
            'site_name' => 'required|max:255',
            'site_email' => 'nullable|max:255',
            'site_phone' => 'nullable|max:255',
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



    public function UpdatePusherSetting(Request $request)
    {
        // dd($request->all());
        $validatedData = $request->validate([
            'pusher_app_id' => 'required',
            'pusher_key' => 'required',
            'pusher_secret' => 'required',
            'pusher_cluster' => 'required',
        ]);

        //? loop and update validated data for pusher settings
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


    public function updateMailSettings(Request $request, MailSettingsService $mailSettingsService)
    {
        // dd($request->all());
        $validatedData = $request->validate([
            'mail_driver' => 'required',
            'mail_host' => 'required',
            'mail_port' => 'required',
            'mail_username' => 'required',
            'mail_password' => 'required',
            'mail_encryption' => 'required',
            'mail_form_address' => 'required',
            'mail_receive_address' => 'required',
        ]);

        //? loop and update validated data for pusher settings
        foreach ($validatedData as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value],
            );
        }


        //? clear settings cache memory
        $mailSettingsService->clearCacheSettings();

        //? flash success message
        toastr()->success('Updated Successfully');
        return redirect()->back();
    }



    public function updateLogoSettings(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'logo'        => ['nullable', 'image', 'max:1000'],
            'footer_logo' => ['nullable', 'image', 'max:1000'],
            'favicon'     => ['nullable', 'image', 'max:1000'],
            'breadcrumb'  => ['nullable', 'image', 'max:1000'],
        ]);

        DB::beginTransaction();

        try {
            foreach ($validatedData as $key => $value) {
                // 4️⃣ Update setting in DB
                $setting = Setting::updateOrCreate(
                    ['key' => $key],
                    // ['value' => $imagePath]
                );


                //? check if file exist
                if ($request->file($key)) {
                    // 3️⃣ Remove old image
                    // $oldPath = config('settings.' . $key);
                    $this->removeImage($setting, 'logo-settings');

                    // 2️⃣ Upload image if present
                    $imagePath = $this->uploadImage(
                        $request,
                        $key,
                        $setting,
                        'logo-settings'
                    );

                    if (!empty($imagePath)) {
                        //? update setting logo image
                        $setting->value = $imagePath;
                        $setting->save();
                    }
                }
            }

            DB::commit();

            // 5️⃣ Clear cache
            $settingsService = app(SettingsService::class);
            $settingsService->clearCacheSettings();

            toastr()->success('Updated Successfully');
            return redirect()->back();
        } catch (\Throwable $e) {
            DB::rollBack();

            logger()->error('Logo settings update failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id() ?? null,
            ]);

            toastr()->error('Something went wrong while updating logo settings');
            return redirect()->back()->withInput();
        }
    }



    public function updateAppearanceSetting(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'site_color' => ['required'],
        ]);


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


    public function updateSeoSetting(Request $request)
    {
        $validatedData = $request->validate([
            'seo_title' => ['required', 'max:255'],
            'seo_description' => ['nullable', 'max:600'],
            'seo_keywords' => ['nullable'],
        ]);


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