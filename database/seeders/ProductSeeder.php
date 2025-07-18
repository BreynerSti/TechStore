<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run()
{
    $products = [
        // Smartphones
        [
            'name' => 'iPhone 15 Pro',
            'slug' => 'iphone-15-pro',
            'description' => 'El iPhone más avanzado con chip A17 Pro y cámara profesional de 48MP. Incluye Titanio de grado aeroespacial y pantalla Super Retina XDR de 6.1 pulgadas.',
            'short_description' => 'iPhone 15 Pro con 128GB de almacenamiento',
            'price' => 99.99,
            'compare_price' => 120.00,
            'stock' => 25,
            'sku' => 'IP15P-128-BLK',
            'is_active' => true,
            'is_featured' => true,
            'main_image' => 'products/iphone-15-pro.jpg',
            'category_id' => 1,
        ],
        [
            'name' => 'Samsung Galaxy S24',
            'slug' => 'samsung-galaxy-s24',
            'description' => 'Smartphone Samsung con IA integrada y cámara de 50MP. Pantalla Dynamic AMOLED 2X de 6.2 pulgadas y procesador Snapdragon 8 Gen 3.',
            'short_description' => 'Galaxy S24 256GB con IA Galaxy',
            'price' => 89.99,
            'compare_price' => 110.00,
            'stock' => 30,
            'sku' => 'SAM-S24-256',
            'is_active' => true,
            'is_featured' => true,
            'main_image' => 'products/galaxy-s24.jpg',
            'category_id' => 1,
        ],
        [
            'name' => 'Xiaomi Redmi Note 13',
            'slug' => 'xiaomi-redmi-note-13',
            'description' => 'Smartphone de gama media con excelente relación calidad-precio. Cámara de 108MP y batería de 5000mAh.',
            'short_description' => 'Redmi Note 13 128GB con cámara 108MP',
            'price' => 49.99,
            'stock' => 45,
            'sku' => 'XIA-RN13-128',
            'is_active' => true,
            'main_image' => 'products/redmi-note-13.jpg',
            'category_id' => 1,
        ],
        
        // Laptops
        [
            'name' => 'MacBook Air M3',
            'slug' => 'macbook-air-m3',
            'description' => 'Laptop ultradelgada con chip M3 y batería de hasta 18 horas. Pantalla Liquid Retina de 13.6 pulgadas y hasta 24GB de memoria unificada.',
            'short_description' => 'MacBook Air 13" con chip M3',
            'price' => 99.99,
            'compare_price' => 120.00,
            'stock' => 15,
            'sku' => 'MBA-M3-13',
            'is_active' => true,
            'is_featured' => true,
            'main_image' => 'products/macbook-air-m3.jpg',
            'category_id' => 2,
        ],
        [
            'name' => 'Dell XPS 13',
            'slug' => 'dell-xps-13',
            'description' => 'Laptop premium con procesador Intel i7 y pantalla InfinityEdge. Diseño elegante en aluminio y fibra de carbono.',
            'short_description' => 'Dell XPS 13 Intel i7 16GB RAM',
            'price' => 89.99,
            'stock' => 12,
            'sku' => 'DELL-XPS13-I7',
            'is_active' => true,
            'main_image' => 'products/dell-xps-13.jpg',
            'category_id' => 2,
        ],
        [
            'name' => 'Lenovo ThinkPad X1 Carbon',
            'slug' => 'lenovo-thinkpad-x1-carbon',
            'description' => 'Laptop empresarial ultraligera con procesador Intel i5 y pantalla de 14 pulgadas. Ideal para profesionales.',
            'short_description' => 'ThinkPad X1 Carbon Intel i5 8GB RAM',
            'price' => 59.99,
            'stock' => 18,
            'sku' => 'LEN-TPX1-I5',
            'is_active' => true,
            'main_image' => 'products/thinkpad-x1.jpg',
            'category_id' => 2,
        ],
        
        // Accesorios
        [
            'name' => 'AirPods Pro 2',
            'slug' => 'airpods-pro-2',
            'description' => 'Audífonos inalámbricos con cancelación activa de ruido y audio espacial. Hasta 6 horas de reproducción con una carga.',
            'short_description' => 'AirPods Pro con cancelación de ruido',
            'price' => 19.99,
            'compare_price' => 25.00,
            'stock' => 50,
            'sku' => 'APP-PRO2-WHT',
            'is_active' => true,
            'is_featured' => true,
            'main_image' => 'products/airpods-pro-2.jpg',
            'category_id' => 3,
        ],
        [
            'name' => 'Cargador Inalámbrico Samsung',
            'slug' => 'cargador-inalambrico-samsung',
            'description' => 'Cargador inalámbrico de 15W compatible con dispositivos Samsung y otros smartphones con carga Qi.',
            'short_description' => 'Cargador inalámbrico 15W Samsung',
            'price' => 9.99,
            'stock' => 35,
            'sku' => 'SAM-WC-15W',
            'is_active' => true,
            'main_image' => 'products/cargador-samsung.jpg',
            'category_id' => 3,
        ],
        [
            'name' => 'Fundas iPhone 15 Pro',
            'slug' => 'fundas-iphone-15-pro',
            'description' => 'Set de 3 fundas protectoras para iPhone 15 Pro. Materiales premium con protección contra caídas.',
            'short_description' => 'Set de 3 fundas para iPhone 15 Pro',
            'price' => 5.99,
            'stock' => 60,
            'sku' => 'FUND-IP15P-3PK',
            'is_active' => true,
            'main_image' => 'products/fundas-iphone.jpg',
            'category_id' => 3,
        ],
        
        // Gaming
        [
            'name' => 'PlayStation 5',
            'slug' => 'playstation-5',
            'description' => 'Consola de videojuegos de nueva generación con SSD ultrarrápido y gráficos 4K. Incluye controlador DualSense.',
            'short_description' => 'PS5 con controlador DualSense incluido',
            'price' => 79.99,
            'compare_price' => 100.00,
            'stock' => 8,
            'sku' => 'SONY-PS5-STD',
            'is_active' => true,
            'is_featured' => true,
            'main_image' => 'products/ps5.jpg',
            'category_id' => 4,
        ],
        [
            'name' => 'Nintendo Switch OLED',
            'slug' => 'nintendo-switch-oled',
            'description' => 'Consola híbrida con pantalla OLED de 7 pulgadas. Perfecta para jugar en casa y en movimiento.',
            'short_description' => 'Nintendo Switch con pantalla OLED',
            'price' => 39.99,
            'stock' => 20,
            'sku' => 'NIN-SW-OLED',
            'is_active' => true,
            'main_image' => 'products/nintendo-switch.jpg',
            'category_id' => 4,
        ],
        [
            'name' => 'Teclado Mecánico Gaming',
            'slug' => 'teclado-mecanico-gaming',
            'description' => 'Teclado mecánico RGB con switches Cherry MX Red. Ideal para gaming y programación.',
            'short_description' => 'Teclado mecánico RGB con switches Cherry MX',
            'price' => 14.99,
            'stock' => 25,
            'sku' => 'TEC-MECH-RGB',
            'is_active' => true,
            'main_image' => 'products/teclado-gaming.jpg',
            'category_id' => 4,
        ],
    ];

    foreach ($products as $product) {
        Product::create($product);
    }
}
}
