<?php
class CategoriasSeeder {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function run() {
        $categories = [
            ['Viajes', 'Ahorros para viajes y vacaciones'],
            ['Educación', 'Ahorros para educación y capacitación'],
            ['Emergencias', 'Fondos para emergencias'],
            ['Hogar', 'Mejoras y compras para el hogar'],
            ['Vehículo', 'Compra o mantenimiento de vehículo']
        ];

        $stmt = $this->pdo->prepare("INSERT INTO categorias (categoria_nombre, categoria_descripcion) VALUES (?, ?)");

        foreach ($categories as $category) {
            $stmt->execute($category);
        }
    }
}