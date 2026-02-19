<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $categories = ['Keyboards', 'Mice', 'Monitors', 'Headsets'];

        foreach ($categories as $cat) {
            $category = Category::create([
                'name' => $cat,
                'slug' => strtolower($cat)
            ]);

            Product::factory(5)->create([
                'category_id' => $category->id
        ]);
    }
    }
}
