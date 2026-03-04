// Конфигурация текстов
const cookieConsentConfig = {
    title: 'Мы используем файлы cookie',
    description: 'Продолжая пользоваться сайтом, вы соглашаетесь с <a href="/privacy-policy">политикой обработки cookie и персональных данных</a>.',
    buttonText: 'Согласен'
};

document.addEventListener('DOMContentLoaded', function() {
    // Создаем элемент уведомления
    const cookieConsent = document.createElement('div');
    cookieConsent.id = 'cookieConsent';
    
    // Генерируем HTML содержимое
    cookieConsent.innerHTML = `
        <span class="title">${cookieConsentConfig.title}</span>
        <span class="description">${cookieConsentConfig.description}</span>
        <button id="cookieConsentBtn">${cookieConsentConfig.buttonText}</button>
    `;

    // Добавляем элемент в конец body
    document.body.appendChild(cookieConsent);

    const cookieConsentBtn = document.getElementById('cookieConsentBtn');

    // Проверяем, есть ли уже согласие
    if (!localStorage.getItem('cookieConsent')) {
        // Показываем уведомление с небольшой задержкой
        setTimeout(() => {
            cookieConsent.classList.add('show');
        }, 1000);
    }

    // Обработчик клика по кнопке
    cookieConsentBtn.addEventListener('click', function() {
        localStorage.setItem('cookieConsent', 'true');
        cookieConsent.classList.remove('show');
        
        // Плавно скрываем уведомление
        cookieConsent.style.animation = 'slideDown 0.3s ease-out forwards';
        
        setTimeout(() => {
            cookieConsent.style.display = 'none';
        }, 300);
    });
}); 