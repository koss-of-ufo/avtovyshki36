<?php
// Включаем отображение ошибок
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Функция для проверки доступа к файлу
function checkFileAccess($path) {
    $result = [
        'exists' => file_exists($path),
        'readable' => is_readable($path),
        'writable' => is_writable($path),
        'permissions' => substr(sprintf('%o', fileperms($path)), -4),
        'owner' => posix_getpwuid(fileowner($path))['name'] ?? 'unknown',
        'group' => posix_getgrgid(filegroup($path))['name'] ?? 'unknown'
    ];
    return $result;
}

// Отладочная информация
echo "<pre>\n";
echo "=== System Info ===\n";
echo "PHP Version: " . phpversion() . "\n";
echo "Server Software: " . $_SERVER['SERVER_SOFTWARE'] . "\n";
echo "Current directory: " . __DIR__ . "\n";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "\n";
echo "Script filename: " . $_SERVER['SCRIPT_FILENAME'] . "\n\n";

echo "=== File Paths ===\n";
echo "Bootstrap path: " . __DIR__ . '/../src/bootstrap.php' . "\n";
echo "Env file path: " . __DIR__ . '/../.env' . "\n";
echo "Error log path: " . __DIR__ . '/../src/error.log' . "\n\n";

echo "=== File Access Info ===\n";
echo "Bootstrap.php:\n";
print_r(checkFileAccess(__DIR__ . '/../src/bootstrap.php'));
echo "\n.env:\n";
print_r(checkFileAccess(__DIR__ . '/../.env'));
echo "\nerror.log:\n";
print_r(checkFileAccess(__DIR__ . '/../src/error.log'));
echo "</pre>\n";

try {
    require_once __DIR__ . '/../src/bootstrap.php';

    // Получаем конфигурацию
    $config = require __DIR__ . '/../src/config/bot.php';

    // Проверяем, что токен получен
    if (empty($config['token'])) {
        throw new Exception('Token is empty');
    }

    // URL вашего webhook.php
    $webhookUrl = 'https://avtovyshki36.ru/bot/public/webhook.php';

    // Формируем URL для установки вебхука
    $url = "https://api.telegram.org/bot{$config['token']}/setWebhook?url={$webhookUrl}";

    // Добавляем контекст для HTTPS
    $opts = [
        'http' => [
            'method' => 'GET',
            'header' => [
                'Content-Type: application/json',
                'Accept: application/json'
            ]
        ],
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false
        ]
    ];

    $context = stream_context_create($opts);
    
    // Отправляем запрос
    $response = file_get_contents($url, false, $context);
    
    if ($response === false) {
        throw new Exception('Failed to get response from Telegram API');
    }

    // Выводим результат
    echo $response;

} catch (Exception $e) {
    // Логируем ошибку
    error_log("Error in set-webhook.php: " . $e->getMessage(), 3, __DIR__ . '/../src/error.log');
    // Выводим ошибку
    echo "Error: " . $e->getMessage();
} 