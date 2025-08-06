<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = array(
            array(
                "id" => 1,
                "key" => "site_name",
                "value" => "Food Park",
                "created_at" => "2025-05-19 10:48:47",
                "updated_at" => "2025-05-19 10:48:47",
            ),
            array(
                "id" => 2,
                "key" => "site_default_currency",
                "value" => "usd",
                "created_at" => "2025-05-19 10:48:47",
                "updated_at" => "2025-07-28 04:20:08",
            ),
            array(
                "id" => 3,
                "key" => "site_currency_icon",
                "value" => "$",
                "created_at" => "2025-05-19 10:48:47",
                "updated_at" => "2025-07-28 04:20:08",
            ),
            array(
                "id" => 4,
                "key" => "site_currency_icon_position",
                "value" => "left",
                "created_at" => "2025-05-19 10:48:47",
                "updated_at" => "2025-07-28 04:15:13",
            ),
            array(
                "id" => 5,
                "key" => "pusher_app_id",
                "value" => "2017793",
                "created_at" => "2025-06-18 18:54:01",
                "updated_at" => "2025-07-04 15:32:04",
            ),
            array(
                "id" => 6,
                "key" => "pusher_key",
                "value" => "1a1121939e86811e99bf",
                "created_at" => "2025-06-18 18:54:01",
                "updated_at" => "2025-07-04 15:32:05",
            ),
            array(
                "id" => 7,
                "key" => "pusher_secret",
                "value" => "56eaeb315e25c421e148",
                "created_at" => "2025-06-18 18:54:01",
                "updated_at" => "2025-07-04 15:32:05",
            ),
            array(
                "id" => 8,
                "key" => "pusher_cluster",
                "value" => "mt1",
                "created_at" => "2025-06-18 18:54:01",
                "updated_at" => "2025-06-18 18:54:01",
            ),
            array(
                "id" => 9,
                "key" => "mail_driver",
                "value" => "smtp",
                "created_at" => "2025-07-20 08:33:53",
                "updated_at" => "2025-07-20 08:33:53",
            ),
            array(
                "id" => 10,
                "key" => "mail_host",
                "value" => "sandbox.smtp.mailtrap.io",
                "created_at" => "2025-07-20 08:33:53",
                "updated_at" => "2025-07-20 08:33:53",
            ),
            array(
                "id" => 11,
                "key" => "mail_port",
                "value" => "2525",
                "created_at" => "2025-07-20 08:33:53",
                "updated_at" => "2025-07-20 08:33:53",
            ),
            array(
                "id" => 12,
                "key" => "mail_username",
                "value" => "b1012327762e38",
                "created_at" => "2025-07-20 08:33:53",
                "updated_at" => "2025-07-20 08:33:53",
            ),
            array(
                "id" => 13,
                "key" => "mail_password",
                "value" => "434aca98874f6d",
                "created_at" => "2025-07-20 08:33:53",
                "updated_at" => "2025-07-20 08:33:53",
            ),
            array(
                "id" => 14,
                "key" => "mail_encription",
                "value" => "null",
                "created_at" => "2025-07-20 08:33:53",
                "updated_at" => "2025-07-20 08:33:53",
            ),
            array(
                "id" => 15,
                "key" => "mail_form_address",
                "value" => "food_park@example.com",
                "created_at" => "2025-07-20 08:33:53",
                "updated_at" => "2025-07-20 08:33:53",
            ),
            array(
                "id" => 16,
                "key" => "mail_receive_address",
                "value" => "food_park@example.com",
                "created_at" => "2025-07-20 08:33:53",
                "updated_at" => "2025-07-20 08:39:04",
            ),
            array(
                "id" => 17,
                "key" => "mail_encryption",
                "value" => "tls",
                "created_at" => "2025-07-20 08:39:04",
                "updated_at" => "2025-07-20 08:39:04",
            ),
            array(
                "id" => 18,
                "key" => "logo",
                "value" => "/uploads/logo-settings/media_688a83c53c673.png",
                "created_at" => "2025-07-30 20:42:45",
                "updated_at" => "2025-07-30 20:42:45",
            ),
            array(
                "id" => 19,
                "key" => "footer_logo",
                "value" => "/uploads/logo-settings/media_688a874d5b846.png",
                "created_at" => "2025-07-30 20:42:45",
                "updated_at" => "2025-07-30 20:57:49",
            ),
            array(
                "id" => 20,
                "key" => "favicon",
                "value" => "/uploads/logo-settings/media_688a83c56bdce.png",
                "created_at" => "2025-07-30 20:42:45",
                "updated_at" => "2025-07-30 20:42:45",
            ),
            array(
                "id" => 21,
                "key" => "breadcrumb",
                "value" => "/uploads/logo-settings/media_688a83c57729f.jpg",
                "created_at" => "2025-07-30 20:42:45",
                "updated_at" => "2025-07-30 20:42:45",
            ),
            array(
                "id" => 22,
                "key" => "site_email",
                "value" => "example@gmail.com",
                "created_at" => "2025-07-31 04:52:13",
                "updated_at" => "2025-07-31 04:52:13",
            ),
            array(
                "id" => 23,
                "key" => "site_phone",
                "value" => "+2347055553109",
                "created_at" => "2025-07-31 04:52:13",
                "updated_at" => "2025-07-31 04:52:13",
            ),
            array(
                "id" => 24,
                "key" => "site_color",
                "value" => "#f86f03",
                "created_at" => "2025-07-31 05:52:07",
                "updated_at" => "2025-07-31 06:00:55",
            ),
            array(
                "id" => 25,
                "key" => "seo_title",
                "value" => "Food Park",
                "created_at" => "2025-07-31 06:36:24",
                "updated_at" => "2025-07-31 06:36:24",
            ),
            array(
                "id" => 26,
                "key" => "seo_description",
                "value" => "test description",
                "created_at" => "2025-07-31 06:36:24",
                "updated_at" => "2025-07-31 06:36:24",
            ),
            array(
                "id" => 27,
                "key" => "seo_keywords",
                "value" => "food, resturant",
                "created_at" => "2025-07-31 06:36:24",
                "updated_at" => "2025-07-31 06:36:24",
            ),
        );

        \DB::table('settings')->insert($settings);
    }
}
