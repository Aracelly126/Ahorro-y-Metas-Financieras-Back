<?php
// Mostrar errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

require './config/database.php';
require './database/MigrationRunner.php';

try {
    echo "Intentando conectar a la base de datos...\n";
    
    // Test de conexión
    $db = \Config\Database::connect();
    echo "✅ Conexión exitosa a la base de datos\n\n";
    
    // Ejecutar migraciones
    echo "Iniciando migraciones...\n";
    $migrationRunner = new MigrationRunner();
    $migrationRunner->runMigrations();
    
    // Ejecutar seeders
    echo "\nIniciando seeders...\n";
    require './database/seeders/DatabaseSeeder.php';
    $seeder = new DatabaseSeeder();
    $seeder->run();
    
    echo "\n✅ Migraciones y seeders ejecutados con éxito\n";
} catch (Exception $e) {
    echo "\n❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}