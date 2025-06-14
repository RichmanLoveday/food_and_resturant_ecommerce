<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentGatewaySetting;
use App\services\PaymentGatewaySettingService;
use App\Traits\FileUploadTrait;
use DB;
use Illuminate\Http\Request;

class PaymentGatewayController extends Controller
{
    use FileUploadTrait;

    public function index()
    {
        $paypalGateway = PaymentGatewaySetting::pluck('value', 'key')
            ->toArray();

        // dd($paypalGateway);
        return view('admin.payment-setting.index', compact('paypalGateway'));
    }


    public function paypalSettingUpdate(Request $request,)
    {
        // dd($request->all());
        $validatedData = $request->validate([
            'paypal_status' => ['required', 'boolean'],
            'paypal_account_mode' => ['required', 'in:sandbox,live'],
            'paypal_country' => ['required'],
            'paypal_currency' => ['required'],
            'paypal_rate' => ['required', 'numeric'],
            'paypal_api_key' => ['required'],
            'paypal_secret_key' => ['required'],
            'paypal_app_id' => ['required'],
        ]);

        try {
            DB::beginTransaction();

            //? check if image exist
            if ($request->hasFile('paypal_logo')) {
                $request->validate([
                    'paypal_logo' => ['nullable', 'image'],
                ]);

                //? remove old image 
                $oldPaypalLogo = PaymentGatewaySetting::where('key', 'paypal_logo')->first();
                if ($oldPaypalLogo) $this->removeImage($oldPaypalLogo->value);   //? remove image

                //? store image
                $imagePath = $this->uploadImage($request, 'paypal_logo', '/uploads/payment-gateway');

                PaymentGatewaySetting::updateOrCreate(
                    ['key' => 'paypal_logo'],
                    ['value' => $imagePath],
                );
            }

            //? loop and store in data base
            foreach ($validatedData as $key => $value) {
                PaymentGatewaySetting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value],
                );
            }

            //? commit data to database and redirect back to page
            DB::commit();
            toastr()->success('Updated successfully');

            //? clear settings cache memory
            $settingsService = app(PaymentGatewaySettingService::class);
            $settingsService->clearCacheSettings();

            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            logger("Unable to udpate paypal settings: " . $e->getMessage());

            toastr()->error('An error occured while updating data');
            return redirect()->back();
        }
    }


    public function stripeSettingUpdate(Request $request,)
    {
        // dd($request->all());
        $validatedData = $request->validate([
            'stripe_status' => ['required', 'boolean'],
            // 'stripe_account_mode' => ['required', 'in:sandbox,live'],
            'stripe_country' => ['required'],
            'stripe_currency' => ['required'],
            'stripe_rate' => ['required', 'numeric'],
            'stripe_api_key' => ['required'],
            'stripe_secret_key' => ['required'],
        ]);

        try {
            DB::beginTransaction();

            //? check if image exist
            if ($request->hasFile('stripe_logo')) {
                $request->validate([
                    'stripe_logo' => ['nullable', 'image'],
                ]);

                //? remove old image 
                $oldPaypalLogo = PaymentGatewaySetting::where('key', 'stripe_logo')->first();
                if ($oldPaypalLogo) $this->removeImage($oldPaypalLogo->value);   //? remove image

                //? store image
                $imagePath = $this->uploadImage($request, 'stripe_logo', '/uploads/payment-gateway');

                PaymentGatewaySetting::updateOrCreate(
                    ['key' => 'stripe_logo'],
                    ['value' => $imagePath],
                );
            }

            //? loop and store in data base
            foreach ($validatedData as $key => $value) {
                PaymentGatewaySetting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value],
                );
            }

            //? commit data to database and redirect back to page
            DB::commit();
            toastr()->success('Updated successfully');

            //? clear settings cache memory
            $settingsService = app(PaymentGatewaySettingService::class);
            $settingsService->clearCacheSettings();

            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            logger("Unable to udpate stripe settings: " . $e->getMessage());

            toastr()->error('An error occured while updating data');
            return redirect()->back();
        }
    }


    public function razorpaySettingUpdate(Request $request,)
    {
        // dd($request->all());
        $validatedData = $request->validate([
            'razorpay_status' => ['required', 'boolean'],
            // 'razorpay_account_mode' => ['required', 'in:sandbox,live'],
            'razorpay_country' => ['required'],
            'razorpay_currency' => ['required'],
            'razorpay_rate' => ['required', 'numeric'],
            'razorpay_api_key' => ['required'],
            'razorpay_secret_key' => ['required'],
        ]);

        try {
            DB::beginTransaction();

            //? check if image exist
            if ($request->hasFile('razorpay_logo')) {
                $request->validate([
                    'razorpay_logo' => ['nullable', 'image'],
                ]);

                //? remove old image 
                $oldPaypalLogo = PaymentGatewaySetting::where('key', 'razorpay_logo')->first();
                
                if ($oldPaypalLogo) $this->removeImage($oldPaypalLogo->value);   //? remove image

                //? store image
                $imagePath = $this->uploadImage($request, 'razorpay_logo', '/uploads/payment-gateway');

                PaymentGatewaySetting::updateOrCreate(
                    ['key' => 'razorpay_logo'],
                    ['value' => $imagePath],
                );
            }

            //? loop and store in data base
            foreach ($validatedData as $key => $value) {
                PaymentGatewaySetting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value],
                );
            }

            //? commit data to database and redirect back to page
            DB::commit();
            toastr()->success('Updated successfully');

            //? clear settings cache memory
            $settingsService = app(PaymentGatewaySettingService::class);
            $settingsService->clearCacheSettings();

            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            logger("Unable to udpate razorpay settings: " . $e->getMessage());

            toastr()->error('An error occured while updating data');
            return redirect()->back();
        }
    }
}