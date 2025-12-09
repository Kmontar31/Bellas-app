<?php
/**
 * Test script to verify services endpoint
 * Access via: http://localhost/test-servicios.php?categoria=1
 */

// Load Laravel
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = \Illuminate\Http\Request::capture()
);

// Get categoria from query string
$categoria = $_GET['categoria'] ?? null;

if (!$categoria) {
    echo json_encode(['error' => 'Missing categoria parameter', 'example' => 'test-servicios.php?categoria=1']);
    exit;
}

// Get database connection
$pdo = DB::connection()->getPdo();

// Query servicios
$stmt = $pdo->prepare('SELECT id, nombre, duracion_minutos, precio FROM servicios WHERE categoria_id = ?');
$stmt->execute([$categoria]);
$servicios = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($servicios, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
