<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class categoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'category_name' => 'Viaje',
                'category_description' => 'Fondos para viaje'
            ],
            [
                'category_name' => 'Fondos de emergencia',
                'category_description' => 'Fondos para una emergencia'
            ],
            [
                'category_name' => 'Educación',
                'category_description' => 'Cursos, estudios y formación profesional'
            ],
            [
                'category_name' => 'Salud',
                'category_description' => 'Gastos médicos y bienestar personal'
            ],
            [
                'category_name' => 'Hogar',
                'category_description' => 'Mejoras y mantenimiento del hogar'
            ],
            [
                'category_name' => 'Transporte',
                'category_description' => 'Compra de vehículo o transporte público'
            ],
            [
                'category_name' => 'Tecnología',
                'category_description' => 'Dispositivos electrónicos y equipos tecnológicos'
            ],
            [
                'category_name' => 'Retiro',
                'category_description' => 'Ahorro a largo plazo para la jubilación'
            ],
            [
                'category_name' => 'Inversiones',
                'category_description' => 'Capital para oportunidades de inversión'
            ],
            [
                'category_name' => 'Regalos',
                'category_description' => 'Para ocasiones especiales y celebraciones'
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
