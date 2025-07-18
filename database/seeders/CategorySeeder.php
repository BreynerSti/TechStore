<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    $categories = [
        [
            'name' => 'Smartphones',
            'slug' => 'smartphones',
            'description' => 'Los mejores teléfonos inteligentes del mercado',
            'image' => 'categories/smartphones.jpg',
            'is_active' => true,
        ],
        [
            'name' => 'Laptops',
            'slug' => 'laptops',
            'description' => 'Computadoras portátiles para trabajo y gaming',
            'image' => 'categories/laptops.jpg',
            'is_active' => true,
        ],
        [
            'name' => 'Accesorios',
            'slug' => 'accesorios',
            'description' => 'Fundas, cargadores, audífonos y más',
            'image' => 'categories/accesorios.jpg',
            'is_active' => true,
        ],
        [
            'name' => 'Gaming',
            'slug' => 'gaming',
            'description' => 'Consolas y accesorios para gamers',
            'image' => 'categories/gaming.jpg',
            'is_active' => true,
        ],
    ];

    foreach ($categories as $category) {
        Category::create($category);
    }
}
}
