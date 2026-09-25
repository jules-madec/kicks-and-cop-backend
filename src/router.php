<?php
require 'utils/splAutoload.php';

header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
	http_response_code(204);
	exit;
}

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (str_starts_with($uri, '/assets/')) {
	$assetsRoot = realpath(__DIR__ . '/assets');
	$assetPath = realpath(__DIR__ . $uri);

	if ($assetsRoot && $assetPath && str_starts_with($assetPath, $assetsRoot . DIRECTORY_SEPARATOR) && is_file($assetPath)) {
		header('Content-Type: ' . (mime_content_type($assetPath) ?: 'application/octet-stream'));
		readfile($assetPath);
		exit;
	}
}

header('Content-Type: application/json');

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
