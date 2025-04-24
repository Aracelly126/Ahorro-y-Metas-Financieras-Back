<?php
class AlertasRecibidasTable {
    public function up($pdo) {
        $sql = "CREATE TABLE alertas_recibidas (
            alerta_id INT AUTO_INCREMENT PRIMARY KEY,
            alerta_nombre VARCHAR(100) NOT NULL,
            alerta_mensaje TEXT NOT NULL,
            alerta_fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
            id_meta_ahorro INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            CONSTRAINT fk_alertas_meta FOREIGN KEY (id_meta_ahorro) REFERENCES metas_ahorro(meta_id) ON DELETE CASCADE ON UPDATE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $pdo->exec($sql);
    }
}