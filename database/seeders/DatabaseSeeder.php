<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Slider;
use App\Models\User;
use App\Models\WhyChooseUs;
use Database\Factories\WhyChooseUsFactory;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            UserSeeder::class,
            WhyChooseUsTitleSeeder::class,
            CategorySeeder::class,
        ]);

        Slider::factory(4)->create();
        WhyChooseUs::factory(3)->create();
        Product::factory(10)->create();
    }
}