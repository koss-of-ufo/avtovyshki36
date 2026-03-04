<?php
header('Content-Type: application/json');

// Включаем отображение ошибок для отладки
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    // Загружаем bootstrap для доступа к конфигурации
    require_once __DIR__ . '/bot/src/bootstrap.php';
    require_once __DIR__ . '/bot/src/UserActivityTracker.php';
    $config = require __DIR__ . '/bot/src/config/bot.php';
    
    // Инициализируем трекер активности
    $tracker = new UserActivityTracker();

    // Функция для очистки входных данных
    function cleanInput($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    // Функция для получения реального IP пользователя
    function getRealIP() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    // Функция для получения информации о местоположении
    function getLocationInfo($ip) {
        $url = "http://ip-api.com/json/{$ip}?fields=status,message,country,regionName,city,query&lang=ru";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        
        $data = json_decode($response, true);
        
        if ($data && $data['status'] === 'success') {
            return [
                'city' => $data['city'] ?? 'Неизвестно',
                'region' => $data['regionName'] ?? 'Неизвестно',
                'country' => $data['country'] ?? 'Неизвестно',
                'ip' => $data['query']
            ];
        }
        
        return [
            'city' => 'Неизвестно',
            'region' => 'Неизвестно',
            'country' => 'Неизвестно',
            'ip' => $ip
        ];
    }

    // Проверка метода запроса
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        throw new Exception('Method Not Allowed');
    }

    // Проверка CSRF токена
    $csrfToken = $_POST['csrf_token'] ?? '';
    $csrfCookie = $_COOKIE['csrf_token'] ?? '';
    
    if (empty($csrfToken) || empty($csrfCookie) || $csrfToken !== $csrfCookie) {
        http_response_code(403);
        throw new Exception('Ошибка проверки CSRF токена');
    }

    // Проверка referer
    $referer = $_SERVER['HTTP_REFERER'] ?? '';
    $allowedDomains = ['avtovyshki36.ru', 'www.avtovyshki36.ru'];
    $refererHost = parse_url($referer, PHP_URL_HOST);
    
    if (!in_array($refererHost, $allowedDomains)) {
        http_response_code(403);
        throw new Exception('Недопустимый источник запроса');
    }

    // Проверка на подозрительную активность
    if ($tracker->isSuspiciousActivity()) {
        http_response_code(429);
        throw new Exception('Слишком много запросов. Пожалуйста, подождите.');
    }

    // Сбор и валидация данных из полей формы
    $name = isset($_POST['name']) ? cleanInput($_POST['name']) : '';
    $phone = isset($_POST['phone']) ? cleanInput($_POST['phone']) : '';
    $mail = isset($_POST['mail']) ? cleanInput($_POST['mail']) : '';

    // Базовая валидация
    if (empty($name) || empty($phone)) {
        http_response_code(400);
        throw new Exception('Пожалуйста, заполните обязательные поля');
    }

    // Получаем информацию о местоположении
    $ip = getRealIP();
    $locationInfo = getLocationInfo($ip);

    // Формируем сообщение для Telegram
    $message = "🔔 Новая заявка с сайта\n\n";
    $message .= "👤 Имя: $name\n";
    $message .= "📱 Телефон: $phone\n";
    if (!empty($mail)) {
        $message .= "💬 Сообщение: $mail\n";
    }
    $message .= "\n📍 Местоположение:\n";
    $message .= "🌆 Город: {$locationInfo['city']}\n";
    $message .= "🏠 Регион: {$locationInfo['region']}\n";
    $message .= "🌍 Страна: {$locationInfo['country']}\n";
    $message .= "🌐 IP: {$locationInfo['ip']}\n";
    $message .= "\n📅 Дата: " . date('d.m.Y H:i');

    // Логируем заявку
    $tracker->logFormRequest($name, $phone, $mail);

    // Отправляем сообщение в Telegram
    $url = "https://api.telegram.org/bot{$config['token']}/sendMessage";
    if (empty($config['token']) || empty($config['chat_id'])) {
        throw new Exception('Отсутствуют необходимые параметры конфигурации');
    }

    $params = [
        'chat_id' => $config['chat_id'],
        'text' => $message,
        'parse_mode' => 'HTML'
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    
    if ($response === false) {
        throw new Exception('Ошибка CURL: ' . curl_error($ch));
    }
    
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200) {
        throw new Exception('Ошибка HTTP: ' . $httpCode);
    }

    // Отправляем успешный ответ
    echo json_encode(['success' => true]);

} catch (Exception $e) {
    error_log("Ошибка при обработке формы: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}