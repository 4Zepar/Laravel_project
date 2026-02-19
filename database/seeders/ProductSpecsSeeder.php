<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class ProductSpecsSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Product::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $categories = Category::all();

        $images = [
            'Mice' => [
                'https://images.unsplash.com/photo-1615663245857-ac93bb7c39e7?w=800',
                'https://images.unsplash.com/photo-1615663245857-ac93bb7c39e7?w=800',
                'https://images.unsplash.com/photo-1563297007-0686b7003af7?w=800',
            ],
            'Keyboards' => [
                'https://images.unsplash.com/photo-1598662779094-110c2bad80b5?q=80&w=1246&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'https://plus.unsplash.com/premium_photo-1679177183572-a4056053b8a2?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTd8fGtleWJvYXJkfGVufDB8fDB8fHww',
                'https://images.unsplash.com/photo-1618384887929-16ec33fab9ef?q=80&w=880&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            ],
            'Headsets' => [
                'https://images.unsplash.com/photo-1546435770-a3e426bf472b?w=800',
                'https://images.unsplash.com/photo-1612444530582-fc66183b16f7?w=800',
                'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=800',
            ],
            'Monitors' => [
                'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=800',
                'https://images.unsplash.com/photo-1551645120-d70bfe84c826?w=800',
                'https://images.unsplash.com/photo-1547119957-637f8679db1e?w=800',
            ],
        ];

        foreach ($categories as $category) {
            for ($i = 1; $i <= 3; $i++) {
                
                $categoryName = $category->name;
                $imageUrl = isset($images[$categoryName]) 
                    ? fake()->randomElement($images[$categoryName]) 
                    : 'https://images.unsplash.com/photo-1550745165-9bc0b252726f?w=800';

                $specs = match($categoryName) {
                    'Mice' => [
                        'DPI' => rand(800, 25000),
                        'Сенсор' => fake()->randomElement(['PixArt 3360', 'Hero 25K', 'Focus+']),
                        'Вес' => rand(50, 120) . ' г'
                    ],
                    'Keyboards' => [
                        'Тип' => fake()->randomElement(['Механическая', 'Мембранная', 'Оптическая']),
                        'Свитчи' => fake()->randomElement(['Cherry MX Red', 'Gateron Blue', 'Romer-G']),
                        'Подсветка' => fake()->randomElement(['RGB', 'Нет'])
                    ],
                    'Headsets' => [
                        'Тип' => fake()->randomElement(['Закрытые', 'Открытые']),
                        'Микрофон' => fake()->randomElement(['Есть', 'Нету']),
                        'Интерфейс' => 'USB / 3.5mm'
                    ],
                    'Monitors' => [
                        'Дюймы' => fake()->randomElement([24, 27, 31]),
                        'Герцовка' => fake()->randomElement([60, 144, 240]),
                        'Цвет' => fake()->randomElement(['Черный', 'Белый', 'Сиреневый']),
                    ],
                    default => [
                        'Бренд' => 'TechBrand',
                        'Гарантия' => '12 мес.'
                    ]
                };

                Product::create([
                    'category_id' => $category->id,
                    'name' => $categoryName . ' ' . fake()->words(2, true),
                    'description' => fake()->paragraph(),
                    'price' => rand(2000, 15000),
                    'image' => $imageUrl,
                    'specs' => $specs,
                ]);
            }
        }
    }
}