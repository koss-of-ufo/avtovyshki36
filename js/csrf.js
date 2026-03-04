// Функция для генерации случайного токена
function generateCSRFToken() {
    const array = new Uint8Array(32);
    window.crypto.getRandomValues(array);
    return Array.from(array, byte => byte.toString(16).padStart(2, '0')).join('');
}

// Функция для установки cookie
function setCookie(name, value, days = 1) {
    const date = new Date();
    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
    const expires = "expires=" + date.toUTCString();
    document.cookie = name + "=" + value + ";" + expires + ";path=/;SameSite=Strict";
}

// Функция для получения значения cookie
function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
    return '';
}

// Инициализация CSRF-токена при загрузке страницы
document.addEventListener('DOMContentLoaded', function() {
    // Проверяем существующий токен в cookie
    let csrfToken = getCookie('csrf_token');
    
    // Если токена нет, генерируем новый
    if (!csrfToken) {
        csrfToken = generateCSRFToken();
        setCookie('csrf_token', csrfToken);
    }
    
    // Добавляем токен ко всем формам на странице
    document.querySelectorAll('form').forEach(form => {
        let input = form.querySelector('input[name="csrf_token"]');
        if (!input) {
            input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'csrf_token';
            form.appendChild(input);
        }
        input.value = csrfToken;
    });

    // Добавляем токен к AJAX запросам
    const originalXHR = window.XMLHttpRequest;
    function newXHR() {
        const xhr = new originalXHR();
        const originalOpen = xhr.open;
        xhr.open = function() {
            const result = originalOpen.apply(this, arguments);
            this.setRequestHeader('X-CSRF-Token', csrfToken);
            return result;
        };
        return xhr;
    }
    window.XMLHttpRequest = newXHR;
}); 