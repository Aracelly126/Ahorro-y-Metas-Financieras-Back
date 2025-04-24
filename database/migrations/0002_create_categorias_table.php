<?php
class CategoriasTable {
    public function up($pdo) {
        $sql = "CREATE TABLE categorias (
            categoria_id INT AUTO_INCREMENT PRIMARY KEY,
            categoria_nombre VARCHAR(100) NOT NULL,
            categoria_descripcion TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $pdo->exec($sql);
    }
}