<?php

namespace Database\Seeders;

use App\Models\Template;
use Illuminate\Database\Seeder;

class TemplateSeeder extends Seeder
{
    public function run(): void
    {
        Template::create([
            'name' => 'Corporate Template',
            'slug' => 'corporate',
            'description' => 'Template untuk website company profile modern.',
            'category' => 'Company Profile',
            'preview_image' => 'templates/corporate/preview.png',
            'config_path' => 'resources/views/templates/corporate/config.json',
            'price' => 250000,
            'is_active' => true,
            'sort_order' => 1
        ]);

        Template::create([
            'name' => 'Portfolio Clean',
            'slug' => 'portfolio',
            'description' => 'Template untuk personal/agency portfolio, minimalis.',
            'category' => 'Portfolio',
            'preview_image' => 'templates/portfolio/preview.png',
            'config_path' => 'resources/views/templates/portfolio/config.json',
            'price' => 150000,
            'is_active' => true,
            'sort_order' => 2
        ]);

        Template::create([
            'name' => 'Restaurant Fresh',
            'slug' => 'restaurant',
            'description' => 'Template showcase untuk restoran atau cafe.',
            'category' => 'Restaurant',
            'preview_image' => 'templates/restaurant/preview.png',
            'config_path' => 'resources/views/templates/restaurant/config.json',
            'price' => 200000,
            'is_active' => true,
            'sort_order' => 3
        ]);
    }
}
