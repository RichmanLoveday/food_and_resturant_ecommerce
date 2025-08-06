<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentGatewaySettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $payment_gateway_settings = array(
            array(
                "id" => 1,
                "key" => "paypal_logo",
                "value" => "/uploads/payment-gateway/media_684c07fa00147.jpg",
                "created_at" => "2025-06-04 19:18:08",
                "updated_at" => "2025-06-13 11:14:02",
            ),
            array(
                "id" => 2,
                "key" => "paypal_status",
                "value" => "1",
                "created_at" => "2025-06-04 19:18:08",
                "updated_at" => "2025-06-15 06:09:10",
            ),
            array(
                "id" => 3,
                "key" => "paypal_account_mode",
                "value" => "sandbox",
                "created_at" => "2025-06-04 19:18:08",
                "updated_at" => "2025-06-04 19:18:08",
            ),
            array(
                "id" => 4,
                "key" => "paypal_country",
                "value" => "IN",
                "created_at" => "2025-06-04 19:18:08",
                "updated_at" => "2025-06-14 19:29:34",
            ),
            array(
                "id" => 5,
                "key" => "paypal_currency",
                "value" => "INR",
                "created_at" => "2025-06-04 19:18:08",
                "updated_at" => "2025-06-14 17:58:02",
            ),
            array(
                "id" => 6,
                "key" => "paypal_rate",
                "value" => "1",
                "created_at" => "2025-06-04 19:18:08",
                "updated_at" => "2025-06-12 14:24:13",
            ),
            array(
                "id" => 7,
                "key" => "paypal_api_key",
                "value" => "AcDyJgTagQcY27-30Ki2xtj0b2FJ4HZJnCmcjmPjlNbDCtDZY-cSMXsbABU_XsaqcMT1Ippt133F67nD",
                "created_at" => "2025-06-04 19:18:08",
                "updated_at" => "2025-06-06 15:55:12",
            ),
            array(
                "id" => 8,
                "key" => "paypal_secret_key",
                "value" => "ELV29y4f3Fuwgs8xGktUXlyjM_SUnFc8qSRac0CxOkYyUywLNHT-kwNmnUtL0lkDppsOqFB96BuL7gtD",
                "created_at" => "2025-06-04 19:18:08",
                "updated_at" => "2025-06-06 17:24:15",
            ),
            array(
                "id" => 9,
                "key" => "paypal_app_id",
                "value" => "jndjnsdjksd",
                "created_at" => "2025-06-13 10:32:43",
                "updated_at" => "2025-06-13 10:32:43",
            ),
            array(
                "id" => 10,
                "key" => "stripe_logo",
                "value" => "/uploads/payment-gateway/media_684c10dd9abcb.png",
                "created_at" => "2025-06-13 11:51:57",
                "updated_at" => "2025-06-13 11:51:57",
            ),
            array(
                "id" => 11,
                "key" => "stripe_status",
                "value" => "1",
                "created_at" => "2025-06-13 11:51:57",
                "updated_at" => "2025-06-13 11:51:57",
            ),
            array(
                "id" => 12,
                "key" => "stripe_country",
                "value" => "US",
                "created_at" => "2025-06-13 11:51:57",
                "updated_at" => "2025-06-13 11:51:57",
            ),
            array(
                "id" => 13,
                "key" => "stripe_currency",
                "value" => "USD",
                "created_at" => "2025-06-13 11:51:57",
                "updated_at" => "2025-06-13 11:51:57",
            ),
            array(
                "id" => 14,
                "key" => "stripe_rate",
                "value" => "1",
                "created_at" => "2025-06-13 11:51:57",
                "updated_at" => "2025-06-13 11:51:57",
            ),
            array(
                "id" => 15,
                "key" => "stripe_api_key",
                "value" => "pk_test_51RZWChFWwvUMq1vTtSJ7iU6QZcrAKNC9Kqe7Pq7cSlmCzZwa825L143YwZNiKsL2wa4TOPXfHZkHX7Cm4vcKbcPX00ms95H3IP",
                "created_at" => "2025-06-13 11:51:57",
                "updated_at" => "2025-06-13 11:59:01",
            ),
            array(
                "id" => 16,
                "key" => "stripe_secret_key",
                "value" => "sk_test_51RZWChFWwvUMq1vTpZ7nSH5v9R88fQJ2IrIqVtON2BbchrPFiWa1sciFzwRFWvnHf9hyxZd5WWwZjCZLI1dlPAT100mSJpAG1T",
                "created_at" => "2025-06-13 11:51:57",
                "updated_at" => "2025-06-13 11:59:38",
            ),
            array(
                "id" => 17,
                "key" => "razorpay_logo",
                "value" => "/uploads/payment-gateway/media_684c3d637879f.png",
                "created_at" => "2025-06-13 15:01:55",
                "updated_at" => "2025-06-13 15:01:55",
            ),
            array(
                "id" => 18,
                "key" => "razorpay_status",
                "value" => "1",
                "created_at" => "2025-06-13 15:01:55",
                "updated_at" => "2025-07-22 13:28:33",
            ),
            array(
                "id" => 19,
                "key" => "razorpay_country",
                "value" => "IN",
                "created_at" => "2025-06-13 15:01:55",
                "updated_at" => "2025-06-14 20:28:54",
            ),
            array(
                "id" => 20,
                "key" => "razorpay_currency",
                "value" => "INR",
                "created_at" => "2025-06-13 15:01:55",
                "updated_at" => "2025-06-14 20:28:54",
            ),
            array(
                "id" => 21,
                "key" => "razorpay_rate",
                "value" => "86.25",
                "created_at" => "2025-06-13 15:01:55",
                "updated_at" => "2025-06-14 20:28:54",
            ),
            array(
                "id" => 22,
                "key" => "razorpay_api_key",
                "value" => "rzp_test_K7CipNQYyyMPiS",
                "created_at" => "2025-06-13 15:01:55",
                "updated_at" => "2025-06-13 15:12:04",
            ),
            array(
                "id" => 23,
                "key" => "razorpay_secret_key",
                "value" => "zSBmNMorJrirOrnDrbOd1ALO",
                "created_at" => "2025-06-13 15:01:55",
                "updated_at" => "2025-06-13 15:12:04",
            ),
        );


        \DB::table('payment_gateway_settings')->insert($payment_gateway_settings);
    }
}