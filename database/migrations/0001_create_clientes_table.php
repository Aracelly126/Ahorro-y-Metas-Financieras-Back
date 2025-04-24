<?php
class ClientesTable {
    public function up($pdo) {
        $sql = "CREATE TABLE clientes (
            cliente_id INT AUTO_INCREMENT PRIMARY KEY,
            cliente_correo VARCHAR(100) NOT NULL UNIQUE,
            cliente_contrasena VARCHAR(255) NOT NULL,
            cliente_cedula VARCHAR(20) NOT NULL UNIQUE,
            cliente_nombre VARCHAR(100) NOT NULL,
            cliente_apellido VARCHAR(100) NOT NULL,
            cliente_genero CHAR(1),
            cliente_fec_nac DATE,
            cliente_foto_path VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $pdo->exec($sql);
    }
}