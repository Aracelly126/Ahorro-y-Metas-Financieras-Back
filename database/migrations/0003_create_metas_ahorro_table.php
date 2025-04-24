<?php
class MetasAhorroTable {
    public function up($pdo) {
        $sql = "CREATE TABLE metas_ahorro (
            meta_id INT AUTO_INCREMENT PRIMARY KEY,
            meta_nombre VARCHAR(100) NOT NULL,
            meta_monto_objetivo DECIMAL(15, 2) NOT NULL,
            meta_fecha_limite DATE,
            id_categoria INT,
            id_cliente INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            CONSTRAINT fk_metas_categoria FOREIGN KEY (id_categoria) REFERENCES categorias(categoria_id) ON DELETE SET NULL ON UPDATE CASCADE,
            CONSTRAINT fk_metas_cliente FOREIGN KEY (id_cliente) REFERENCES clientes(cliente_id) ON DELETE CASCADE ON UPDATE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $pdo->exec($sql);
    }
}