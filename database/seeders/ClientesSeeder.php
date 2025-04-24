<?php
class ClientesSeeder {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function run() {
        $clients = [
            [
                'cliente_correo' => 'usuario1@example.com',
                'cliente_contrasena' => password_hash('password123', PASSWORD_BCRYPT),
                'cliente_cedula' => '1234567890',
                'cliente_nombre' => 'Juan',
                'cliente_apellido' => 'Pérez',
                'cliente_genero' => 'M',
                'cliente_fec_nac' => '1990-01-15'
            ],
            [
                'cliente_correo' => 'usuario2@example.com',
                'cliente_contrasena' => password_hash('password123', PASSWORD_BCRYPT),
                'cliente_cedula' => '0987654321',
                'cliente_nombre' => 'María',
                'cliente_apellido' => 'Gómez',
                'cliente_genero' => 'F',
                'cliente_fec_nac' => '1985-05-20'
            ]
        ];

        $stmt = $this->pdo->prepare("
            INSERT INTO clientes (
                cliente_correo, cliente_contrasena, cliente_cedula,
                cliente_nombre, cliente_apellido, cliente_genero, cliente_fec_nac
            ) VALUES (
                :cliente_correo, :cliente_contrasena, :cliente_cedula,
                :cliente_nombre, :cliente_apellido, :cliente_genero, :cliente_fec_nac
            )
        ");

        foreach ($clients as $client) {
            $stmt->execute($client);
        }
    }
}