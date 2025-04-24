<?php
class AportesSeeder {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function run() {
        $contributions = [
            [200.00, 4800.00, 1],
            [300.00, 4500.00, 1],
            [500.00, 2500.00, 2]
        ];

        $stmt = $this->pdo->prepare("
            INSERT INTO aportes (
                aporte_monto, aporte_restante, id_meta_ahorro
            ) VALUES (?, ?, ?)
        ");

        foreach ($contributions as $contribution) {
            $stmt->execute($contribution);
        }
    }
}