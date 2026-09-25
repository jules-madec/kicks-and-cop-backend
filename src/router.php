<?php
require 'utils/splAutoload.php';

header('Content-Type: application/json');

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$segments = array_values(array_filter(explode('/', $uri)));

if (empty($segments)) {
	require 'controllers/IndexController.php';
	exit;
}

$resource = strtolower($segments[0]);
$id = $segments[1] ?? null;

$controllerFile = "controllers/{$resource}Controller.php";

if (!file_exists($controllerFile)) {
	http_response_code(404);
	echo json_encode(['error' => 'Route inconnue']);
	exit;
}

require $controllerFile;
