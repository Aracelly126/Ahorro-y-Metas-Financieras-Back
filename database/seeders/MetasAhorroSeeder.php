<?php
class MetasAhorroSeeder {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function run() {
        $goals = [
            [
                'meta_nombre' => 'Vacaciones en Europa',
                'meta_monto_objetivo' => 5000.00,
                'meta_fecha_limite' => '2024-06-01',
                'id_categoria' => 1,
                'id_cliente' => 1
            ],
            [
                'meta_nombre' => 'Fondo de emergencia',
                'meta_monto_objetivo' => 3000.00,
                'meta_fecha_limite' => '2023-12-31',
                'id_categoria' => 3,
                'id_cliente' => 2
            ]
        ];

        $stmt = $this->pdo->prepare("
            INSERT INTO metas_ahorro (
                meta_nombre, meta_monto_objetivo, meta_fecha_limite,
                id_categoria, id_cliente
            ) VALUES (?, ?, ?, ?, ?)
        ");

        foreach ($goals as $goal) {
            $stmt->execute(array_values($goal));
        }
    }
}