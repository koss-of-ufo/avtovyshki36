<?php

class UserActivityTracker {
    // Константы для типов заявок
    const TYPE_CALL = 'call';           // Заявка на звонок
    const TYPE_FORM = 'form';           // Форма обратной связи
    const TYPE_TELEGRAM = 'telegram';    // Заявка из Telegram
    const TYPE_VK = 'vk';               // Заявка из ВКонтакте
    const TYPE_WHATSAPP = 'whatsapp';   // Заявка из WhatsApp
    
    // Путь к файлу журнала
    private $logFile;
    private $activityFile;
    
    // Конструктор
    public function __construct($logFile = null, $activityFile = null) {
        $this->logFile = $logFile ?: __DIR__ . '/../logs/leads.log';
        $this->activityFile = $activityFile ?: __DIR__ . '/../logs/activity.log';
        
        // Создаем директорию для логов, если она не существует
        $logDir = dirname($this->logFile);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
    }
    
    /**
     * Логирует действие пользователя
     */
    public function logActivity($type, $data = []) {
        $timestamp = date('Y-m-d H:i:s');
        $ip = $this->getRealIP();
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
        
        $logEntry = [
            'timestamp' => $timestamp,
            'type' => $type,
            'ip' => $ip,
            'user_agent' => $userAgent,
            'data' => $data
        ];
        
        $logLine = json_encode($logEntry) . PHP_EOL;
        return file_put_contents($this->activityFile, $logLine, FILE_APPEND) !== false;
    }
    
    /**
     * Логирует заявку
     */
    public function logLead($type, $data = []) {
        $timestamp = date('Y-m-d H:i:s');
        $ip = $this->getRealIP();
        
        $logLine = sprintf(
            "[%s] | %s | IP: %s | Data: %s" . PHP_EOL,
            $timestamp,
            $type,
            $ip,
            json_encode($data, JSON_UNESCAPED_UNICODE)
        );
        
        return file_put_contents($this->logFile, $logLine, FILE_APPEND) !== false;
    }
    
    /**
     * Получает реальный IP пользователя
     */
    private function getRealIP() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
    
    /**
     * Проверяет, не является ли активность подозрительной
     */
    public function isSuspiciousActivity($ip = null) {
        $ip = $ip ?: $this->getRealIP();
        $timeWindow = 3600; // 1 час
        $maxRequests = 10;  // Максимальное количество запросов за час
        
        $activities = file_exists($this->activityFile) ? 
            file($this->activityFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];
        
        $recentActivities = 0;
        $currentTime = time();
        
        foreach ($activities as $activity) {
            $data = json_decode($activity, true);
            if ($data && 
                $data['ip'] === $ip && 
                (strtotime($data['timestamp']) > ($currentTime - $timeWindow))) {
                $recentActivities++;
            }
        }
        
        return $recentActivities > $maxRequests;
    }
    
    /**
     * Записывает информацию о форме обратной связи
     */
    public function logFormRequest($name, $phone, $message = '') {
        return $this->logLead(self::TYPE_FORM, [
            'name' => $name,
            'phone' => $phone,
            'message' => $message
        ]);
    }
    
    /**
     * Записывает информацию о заявке из Telegram
     */
    public function logTelegramRequest($username, $message) {
        return $this->logLead(self::TYPE_TELEGRAM, [
            'username' => $username,
            'message' => $message
        ]);
    }
    
    /**
     * Записывает информацию о заявке из VK
     */
    public function logVKRequest($username, $message) {
        return $this->logLead(self::TYPE_VK, [
            'username' => $username,
            'message' => $message
        ]);
    }
    
    /**
     * Записывает информацию о заявке из WhatsApp
     */
    public function logWhatsAppRequest($phone, $message) {
        return $this->logLead(self::TYPE_WHATSAPP, [
            'phone' => $phone,
            'message' => $message
        ]);
    }
} 