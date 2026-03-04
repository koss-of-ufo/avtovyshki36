<?php
// Включаем отображение ошибок
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Загружаем переменные окружения
$envFile = __DIR__ . '/../.env';
if (!file_exists($envFile)) {
    die('.env file not found');
}

$envContent = file_get_contents($envFile);
if ($envContent === false) {
    die('Cannot read .env file');
}

$lines = explode("\n", $envContent);
foreach ($lines as $line) {
    if (empty(trim($line))) continue;
    
    // Проверяем формат строки
    if (strpos($line, '=') === false) {
        error_log("Invalid line in .env: $line", 3, __DIR__ . '/error.log');
        continue;
    }
    
    putenv(trim($line));
}

// Функция для получения значения из окружения
function env($key, $default = null) {
    $value = getenv($key);
    if ($value === false) {
        error_log("Environment variable not found: $key", 3, __DIR__ . '/error.log');
        return $default;
    }
    return $value;
}

// Загружаем конфигурацию
$configFile = __DIR__ . '/config/bot.php';
if (!file_exists($configFile)) {
    die('Config file not found');
}

return require $configFile; 