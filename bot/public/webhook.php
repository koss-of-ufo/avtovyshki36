<?php
require_once __DIR__ . '/../src/bootstrap.php';
require_once __DIR__ . '/../src/UserActivityTracker.php';

// Получаем конфигурацию
$config = require __DIR__ . '/../src/config/bot.php';

// Инициализируем трекер активности
$tracker = new UserActivityTracker();

// Функция для отправки сообщения в Telegram
function sendTelegramMessage($chatId, $message, $token) {
    $url = "https://api.telegram.org/bot{$token}/sendMessage";
    $data = [
        'chat_id' => $chatId,
        'text' => $message,
        'parse_mode' => 'HTML'
    ];

    $options = [
        'http' => [
            'method' => 'POST',
            'header' => 'Content-Type: application/x-www-form-urlencoded',
            'content' => http_build_query($data)
        ]
    ];

    $context = stream_context_create($options);
    return file_get_contents($url, false, $context);
}

// Функция проверки на спам
function isSpam($text, $config) {
    $spamProtection = $config['spam_protection'];
    
    // Проверка длины сообщения
    $length = mb_strlen($text);
    if ($length < $spamProtection['min_message_length'] || 
        $length > $spamProtection['max_message_length']) {
        return true;
    }
    
    // Проверка на спам-слова
    foreach ($config['spam_words'] as $word) {
        if (mb_stripos($text, $word) !== false) {
            return true;
        }
    }
    
    // Проверка на множество ссылок
    $urlCount = preg_match_all('/http[s]?:\/\//', $text);
    if ($urlCount > $spamProtection['max_links_per_message']) {
        return true;
    }
    
    // Проверка на повторяющиеся символы
    if (preg_match('/(.)\1{' . $spamProtection['max_repeated_chars'] . ',}/', $text)) {
        return true;
    }
    
    return false;
}

// Функция проверки CSRF токена
function validateCSRFToken() {
    if (!isset($_POST['csrf_token']) || !isset($_COOKIE['csrf_token'])) {
        return false;
    }
    return $_POST['csrf_token'] === $_COOKIE['csrf_token'];
}

// Функция проверки referer
function validateReferer($config) {
    if (!isset($_SERVER['HTTP_REFERER'])) {
        return false;
    }
    
    $referer = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
    return in_array($referer, $config['security']['allowed_referers']);
}

// Получаем данные
$data = json_decode(file_get_contents('php://input'), true);

// Проверяем, что это POST запрос от формы
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
    // Проверка CSRF токена
    if ($config['security']['enable_csrf'] && !validateCSRFToken()) {
        http_response_code(403);
        die(json_encode(['success' => false, 'error' => 'Неверный CSRF токен']));
    }
    
    // Проверка referer
    if ($config['security']['check_referer'] && !validateReferer($config)) {
        http_response_code(403);
        die(json_encode(['success' => false, 'error' => 'Недопустимый источник запроса']));
    }
    
    // Проверка на подозрительную активность
    if ($config['security']['block_suspicious_ips'] && $tracker->isSuspiciousActivity()) {
        http_response_code(429);
        die(json_encode(['success' => false, 'error' => 'Слишком много запросов']));
    }
    
    $name = $_POST['name'] ?? 'Не указано';
    $phone = $_POST['phone'] ?? 'Не указано';
    $message = $_POST['message'] ?? 'Не указано';
    
    // Формируем текст сообщения
    $text = "🔔 Новая заявка с сайта {$config['site_name']}\n\n";
    $text .= "👤 Имя: {$name}\n";
    $text .= "📱 Телефон: {$phone}\n";
    $text .= "💬 Сообщение: {$message}\n";
    
    // Проверяем на спам
    if (!isSpam($text, $config)) {
        // Логируем активность
        $tracker->logFormRequest($name, $phone, $message);
        
        // Отправляем сообщение
        sendTelegramMessage($config['chat_id'], $text, $config['token']);
        echo json_encode(['success' => true]);
    } else {
        // Логируем спам-активность
        $tracker->logActivity('spam_detected', [
            'name' => $name,
            'phone' => $phone,
            'message' => $message
        ]);
        
        echo json_encode(['success' => false, 'error' => 'Обнаружен спам']);
    }
    exit;
}

// Обработка webhook от Telegram
if (isset($data['message'])) {
    $message = $data['message'];
    $chatId = $message['chat']['id'];
    $text = $message['text'] ?? '';
    
    // Логируем сообщение из Telegram
    $tracker->logTelegramRequest(
        $message['from']['username'] ?? 'unknown',
        $text
    );
    
    // Обработка команд бота
    if ($text === '/start') {
        sendTelegramMessage($chatId, "Бот активирован", $config['token']);
    }
} 