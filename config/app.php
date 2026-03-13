<?php

$projectRoot = realpath(__DIR__ . '/..') ?: dirname(__DIR__);
$documentRoot = isset($_SERVER['DOCUMENT_ROOT']) ? realpath($_SERVER['DOCUMENT_ROOT']) : null;

$basePath = '';
if ($documentRoot && str_starts_with($projectRoot, $documentRoot)) {
    $basePath = str_replace('\\', '/', substr($projectRoot, strlen($documentRoot)));
}

$detectedScheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$detectedHost = $_SERVER['HTTP_HOST'] ?? 'localhost';
$detectedBaseUrl = rtrim($detectedScheme . '://' . $detectedHost . rtrim($basePath, '/'), '/');

$envBaseUrl = getenv('APP_URL') ?: '';
$baseUrl = $envBaseUrl !== '' ? rtrim($envBaseUrl, '/') : $detectedBaseUrl;

return [
    'app_name' => getenv('APP_NAME') ?: 'Dynamic Tournament',
    'base_url' => $baseUrl,
    'session_name' => getenv('SESSION_NAME') ?: 'dynamic_tournament_session',
    'timezone' => getenv('APP_TIMEZONE') ?: 'UTC',
];
