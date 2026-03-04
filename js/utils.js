// Утилиты для работы с формами и UI элементами
const utils = {
    // Валидация телефонного номера
    validatePhone: (phone) => {
        const phoneRegex = /^(\+7|7|8)?[\s\-]?\(?[489][0-9]{2}\)?[\s\-]?[0-9]{3}[\s\-]?[0-9]{2}[\s\-]?[0-9]{2}$/;
        return phoneRegex.test(phone);
    },

    // Очистка строки от спецсимволов
    cleanString: (str) => {
        return str.replace(/[^\w\s@.-]/gi, '');
    },

    // Показ сообщения об ошибке
    showError: (message) => {
        swal({
            title: "Ошибка!",
            text: message,
            icon: "error",
            button: "OK",
        });
    },

    // Показ сообщения об успехе
    showSuccess: (message) => {
        swal({
            title: "Успешно!",
            text: message,
            icon: "success",
            button: "OK",
        });
    },

    // Блокировка кнопки во время отправки
    toggleButtonState: (button, disabled) => {
        button.disabled = disabled;
        button.style.opacity = disabled ? '0.5' : '1';
    }
};

export default utils;
