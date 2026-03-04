import utils from './utils.js';

class FeedbackForm {
    constructor(formSelector) {
        this.form = document.querySelector(formSelector);
        this.submitButton = this.form.querySelector('button[type="submit"]');
        this.nameInput = this.form.querySelector('input[name="name"]');
        this.phoneInput = this.form.querySelector('input[name="phone"]');
        this.messageInput = this.form.querySelector('textarea[name="mail"]');
        this.privacyConsent = this.form.querySelector('input[name="privacy_consent"]');
        this.csrfToken = this.form.querySelector('input[name="csrf_token"]');
        
        this.initialize();
    }

    initialize() {
        this.form.addEventListener('submit', this.handleSubmit.bind(this));
        this.phoneInput.addEventListener('input', this.formatPhoneNumber.bind(this));
    }

    formatPhoneNumber(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 0) {
            value = '+7' + value.substring(1);
            value = value.replace(/(\d{1})(\d{3})(\d{3})(\d{2})(\d{2})/, '$1 ($2) $3-$4-$5');
        }
        e.target.value = value;
    }

    validateForm() {
        if (!this.nameInput.value.trim()) {
            utils.showError('Пожалуйста, введите ваше имя');
            return false;
        }

        if (!utils.validatePhone(this.phoneInput.value)) {
            utils.showError('Пожалуйста, введите корректный номер телефона');
            return false;
        }

        if (!this.privacyConsent.checked) {
            utils.showError('Пожалуйста, согласитесь с политикой конфиденциальности');
            return false;
        }

        if (!this.csrfToken.value) {
            utils.showError('Ошибка безопасности: отсутствует CSRF-токен');
            return false;
        }

        return true;
    }

    async handleSubmit(e) {
        e.preventDefault();

        if (!this.validateForm()) {
            return;
        }

        utils.toggleButtonState(this.submitButton, true);

        try {
            const formData = new FormData(this.form);
            const response = await fetch('/sendform.php', {
                method: 'POST',
                body: formData,
                credentials: 'same-origin', // Важно для работы с CSRF
                headers: {
                    'X-CSRF-Token': this.csrfToken.value
                }
            });

            if (!response.ok) {
                if (response.status === 403) {
                    throw new Error('Ошибка безопасности: недействительный CSRF-токен. Пожалуйста, обновите страницу.');
                }
                throw new Error('Ошибка сети');
            }

            const result = await response.json();

            if (result.success) {
                utils.showSuccess('Спасибо! Мы свяжемся с вами в ближайшее время.');
                this.form.reset();
            } else {
                throw new Error(result.message || 'Произошла ошибка при отправке формы');
            }
        } catch (error) {
            utils.showError(error.message);
            console.error('Error:', error);
        } finally {
            utils.toggleButtonState(this.submitButton, false);
        }
    }
}

// Инициализация формы
document.addEventListener('DOMContentLoaded', () => {
    new FeedbackForm('.feedback-form');
});