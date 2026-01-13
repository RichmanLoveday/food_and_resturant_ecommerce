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


    public function paypalSettingUpdate(Request $request)
    {
        $validatedData = $request->validate([
            'paypal_status'        => ['required', 'boolean'],
            'paypal_account_mode'  => ['required', 'in:sandbox,live'],
            'paypal_country'       => ['required'],
            'paypal_currency'      => ['required'],
            'paypal_rate'          => ['required', 'numeric'],
            'paypal_api_key'       => ['required'],
            'paypal_secret_key'    => ['required'],
            'paypal_app_id'        => ['required'],
        ]);

        DB::beginTransaction();

        try {
            /**
             * 1️⃣ Handle PayPal Logo Upload
             */
            if ($request->hasFile('paypal_logo')) {

                $request->validate([
                    'paypal_logo' => ['nullable', 'image'],
                ]);

                // Create / fetch setting FIRST (Spatie requirement)
                $paymentSetting = PaymentGatewaySetting::updateOrCreate(
                    ['key' => 'paypal_logo'],
                    // ['value' => ''] // temporary
                );

                // Remove old image
                $this->removeImage(
                    $paymentSetting,
                    'payment-gateway'
                );

                // Upload new image
                $imagePath = $this->uploadImage(
                    $request,
                    'paypal_logo',
                    $paymentSetting,
                    'payment-gateway'
                );

                if (!is_null($imagePath)) {
                    $paymentSetting->value = $imagePath;
                    $paymentSetting->save();
                }
            }

            /**
             * 2️⃣ Store PayPal settings
             */
            foreach ($validatedData as $key => $value) {
                PaymentGatewaySetting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value]
                );
            }

            DB::commit();

            // Clear cached settings
            app(PaymentGatewaySettingService::class)->clearCacheSettings();

            toastr()->success('Updated successfully');
            return redirect()->back();
        } catch (\Throwable $e) {
            DB::rollBack();

            logger()->error('Unable to update PayPal settings', [
                'error' => $e->getMessage(),
            ]);

            toastr()->error('An error occurred while updating data');
            return redirect()->back()->withInput();
        }
    }




    public function stripeSettingUpdate(Request $request)
    {
        $validatedData = $request->validate([
            'stripe_status'     => ['required', 'boolean'],
            'stripe_country'    => ['required'],
            'stripe_currency'   => ['required'],
            'stripe_rate'       => ['required', 'numeric'],
            'stripe_api_key'    => ['required'],
            'stripe_secret_key' => ['required'],
        ]);

        DB::beginTransaction();

        try {
            /**
             * 1️⃣ Handle Stripe Logo Upload
             */
            if ($request->hasFile('stripe_logo')) {

                $request->validate([
                    'stripe_logo' => ['nullable', 'image'],
                ]);

                // Create / fetch setting FIRST (Spatie requirement)
                $paymentSetting = PaymentGatewaySetting::updateOrCreate(
                    ['key' => 'stripe_logo'],
                    // ['value' => ''] // temporary
                );

                // Remove old image safely
                $this->removeImage(
                    $paymentSetting,
                    'payment-gateway'
                );

                // Upload new image
                $imagePath = $this->uploadImage(
                    $request,
                    'stripe_logo',
                    $paymentSetting,
                    'payment-gateway'
                );

                if (!is_null($imagePath)) {
                    $paymentSetting->value = $imagePath;
                    $paymentSetting->save();
                }
            }

            /**
             * 2️⃣ Store Stripe settings
             */
            foreach ($validatedData as $key => $value) {
                PaymentGatewaySetting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value]
                );
            }

            DB::commit();

            // Clear cached settings
            app(PaymentGatewaySettingService::class)->clearCacheSettings();

            toastr()->success('Updated successfully');
            return redirect()->back();
        } catch (\Throwable $e) {
            DB::rollBack();

            logger()->error('Unable to update Stripe settings', [
                'error' => $e->getMessage(),
            ]);

            toastr()->error('An error occurred while updating data');
            return redirect()->back()->withInput();
        }
    }



    public function razorpaySettingUpdate(Request $request)
    {
        $validatedData = $request->validate([
            'razorpay_status'   => ['required', 'boolean'],
            'razorpay_country'  => ['required'],
            'razorpay_currency' => ['required'],
            'razorpay_rate'     => ['required', 'numeric'],
            'razorpay_api_key'  => ['required'],
            'razorpay_secret_key' => ['required'],
        ]);

        DB::beginTransaction();

        try {
            /**
             * 1️⃣ Handle Razorpay Logo Upload
             */
            if ($request->hasFile('razorpay_logo')) {

                $request->validate([
                    'razorpay_logo' => ['nullable', 'image'],
                ]);

                // Get or create setting row FIRST (important for Spatie)
                $paymentSetting = PaymentGatewaySetting::updateOrCreate(
                    ['key' => 'razorpay_logo'],
                    // ['value' => ''] // temporary
                );

                // Remove old image (if exists)
                $this->removeImage(
                    $paymentSetting,
                    'payment-gateway'
                );

                // Upload new image
                $imagePath = $this->uploadImage(
                    $request,
                    'razorpay_logo',
                    $paymentSetting,
                    'payment-gateway'
                );

                if (!is_null($imagePath)) {
                    $paymentSetting->value = $imagePath;
                    $paymentSetting->save();
                }
            }

            /**
             * 2️⃣ Store Razorpay settings
             */
            foreach ($validatedData as $key => $value) {
                PaymentGatewaySetting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value]
                );
            }

            DB::commit();

            // Clear cached settings
            app(PaymentGatewaySettingService::class)->clearCacheSettings();

            toastr()->success('Updated successfully');
            return redirect()->back();
        } catch (\Throwable $e) {
            DB::rollBack();

            logger()->error('Unable to update Razorpay settings', [
                'error' => $e->getMessage(),
            ]);

            toastr()->error('An error occurred while updating data');
            return redirect()->back()->withInput();
        }
    }
}
