<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SectionTitlesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $section_titles = array(
            array(
                "id" => 1,
                "key" => "why_choose_us_top_title",
                "value" => NULL,
                "created_at" => NULL,
                "updated_at" => "2025-07-11 11:35:14",
            ),
            array(
                "id" => 2,
                "key" => "why_choose_us_main_title",
                "value" => NULL,
                "created_at" => NULL,
                "updated_at" => "2025-07-11 11:35:14",
            ),
            array(
                "id" => 3,
                "key" => "why_choose_us_sub_title",
                "value" => NULL,
                "created_at" => NULL,
                "updated_at" => "2025-07-11 11:35:15",
            ),
            array(
                "id" => 4,
                "key" => "daily_offer_top_title",
                "value" => "daily offer",
                "created_at" => "2025-07-10 11:13:38",
                "updated_at" => "2025-07-10 11:13:38",
            ),
            array(
                "id" => 5,
                "key" => "daily_offer_main_title",
                "value" => "up to 75% off for this day",
                "created_at" => "2025-07-10 11:13:38",
                "updated_at" => "2025-07-10 11:13:38",
            ),
            array(
                "id" => 6,
                "key" => "daily_offer_sub_title",
                "value" => "Objectively pontificate quality models before intuitive information. Dramatically recaptiualize multifunctional materials.",
                "created_at" => "2025-07-10 11:13:38",
                "updated_at" => "2025-07-10 11:13:38",
            ),
            array(
                "id" => 7,
                "key" => "chefs_top_title",
                "value" => "our team",
                "created_at" => "2025-07-10 17:55:49",
                "updated_at" => "2025-07-10 17:55:49",
            ),
            array(
                "id" => 8,
                "key" => "chefs_main_title",
                "value" => "meet our expert chefs",
                "created_at" => "2025-07-10 17:55:49",
                "updated_at" => "2025-07-10 17:55:49",
            ),
            array(
                "id" => 9,
                "key" => "chefs_sub_title",
                "value" => "Objectively pontificate quality models before intuitive information. Dramatically recaptiualize multifunctional materials.",
                "created_at" => "2025-07-10 17:55:49",
                "updated_at" => "2025-07-10 17:55:49",
            ),
            array(
                "id" => 10,
                "key" => "testimonial_main_title",
                "value" => "our customar feedbacks",
                "created_at" => "2025-07-11 11:36:03",
                "updated_at" => "2025-07-11 11:36:53",
            ),
            array(
                "id" => 11,
                "key" => "testimonial_sub_title",
                "value" => "Objectively pontificate quality models before intuitive information. Dramatically recaptiualize multifunctional materials.",
                "created_at" => "2025-07-11 11:36:03",
                "updated_at" => "2025-07-11 11:36:53",
            ),
            array(
                "id" => 12,
                "key" => "testimonial_top_title",
                "value" => "testimonial",
                "created_at" => "2025-07-11 11:37:54",
                "updated_at" => "2025-07-11 11:37:54",
            ),
        );

        \DB::table('section_titles')->insert($section_titles);
    }
}
