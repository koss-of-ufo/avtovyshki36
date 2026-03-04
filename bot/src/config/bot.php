<?php
return [
    'token' => env('BOT_TOKEN'),
    'chat_id' => env('CHAT_ID'),
    'site_name' => env('SITE_NAME'),
    
    // Настройки защиты от спама
    'spam_protection' => [
        'max_requests_per_hour' => 10,
        'message_timeout' => 60,
        'min_message_length' => 2,
        'max_message_length' => 1000,
        'max_links_per_message' => 2,
        'max_repeated_chars' => 4,
    ],
    
    // Расширенный список спам-слов
    'spam_words' => [
        // Азартные игры
        'casino', 'lottery', 'betting', 'gambling', 'slot', 'poker',
        // Криптовалюты
        'bitcoin', 'crypto', 'ethereum', 'binance', 'wallet', 'mining',
        // Взрослый контент
        'porn', 'xxx', 'adult', 'sex', '18+', 'escort',
        // Финансовые махинации
        'quick money', 'fast cash', 'easy money', 'make money online',
        // Фармацевтика
        'viagra', 'cialis', 'pharmacy', 'diet pills',
        // Другое
        'free hosting', 'free domain', 'cheap hosting', 'cheap domain',
        'replica', 'fake', 'counterfeit', 'phishing'
    ],
    
    // Разрешенные домены для запросов
    'allowed_origins' => [
        'avtovyshki36.ru',
        'www.avtovyshki36.ru'
    ],
    
    // Настройки безопасности
    'security' => [
        'enable_csrf' => true,
        'csrf_token_lifetime' => 86400, // 24 часа
        'check_referer' => true,
        'allowed_referers' => [
            'avtovyshki36.ru',
            'www.avtovyshki36.ru'
        ],
        'log_suspicious_activity' => true,
        'block_suspicious_ips' => true,
        'rate_limiting' => [
            'enabled' => true,
            'max_requests' => 100,
            'time_window' => 3600 // 1 час
        ]
    ],
    
    // Настройки логирования
    'logging' => [
        'enabled' => true,
        'log_dir' => __DIR__ . '/../../logs',
        'activity_log' => 'activity.log',
        'error_log' => 'error.log',
        'leads_log' => 'leads.log'
    ]
]; 