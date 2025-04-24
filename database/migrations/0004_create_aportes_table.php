<?php
class AportesTable {
    public function up($pdo) {
        $sql = "CREATE TABLE aportes (
            aporte_id INT AUTO_INCREMENT PRIMARY KEY,
            aporte_monto DECIMAL(15, 2) NOT NULL,
            aporte_fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
            aporte_restante DECIMAL(15, 2),
            id_meta_ahorro INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            CONSTRAINT fk_aportes_meta FOREIGN KEY (id_meta_ahorro) REFERENCES metas_ahorro(meta_id) ON DELETE CASCADE ON UPDATE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $pdo->exec($sql);
    }
}