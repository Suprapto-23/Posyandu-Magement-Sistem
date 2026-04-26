<?php
// Create tmp directory for compiled views
if (!is_dir('/tmp/views')) {
    mkdir('/tmp/views', 0755, true);
}
if (!is_dir('/tmp/cache')) {
    mkdir('/tmp/cache', 0755, true);
}

chdir(dirname(__DIR__));
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
)->send();
$kernel->terminate($request, $response);