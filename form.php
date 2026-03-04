<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Форма обратной связи</title>
    <link rel="stylesheet" href="css/sweetalert.min.css">
    <link rel="stylesheet" href="css/form.css">
</head>
<body>

<div class="main_form">
    <form action="sendform.php" method="post" class="feedback-form">
        <input type="hidden" name="csrf_token" id="csrf_token">
        <h2 class="feedback-form__title">Написать нам:</h2>
        
        <div class="form-group">
            <label for="name" class="visually-hidden">Ваше имя</label>
            <input type="text" 
                   id="name"
                   class="feedback-form__input feedback-form__name" 
                   placeholder="Как вас зовут?" 
                   name="name"
                   required
                   aria-required="true">
        </div>

        <div class="form-group">
            <label for="phone" class="visually-hidden">Ваш телефон</label>
            <input type="tel" 
                   id="phone"
                   class="feedback-form__input feedback-form__tel" 
                   placeholder="Ваш телефон" 
                   name="phone"
                   required
                   aria-required="true"
                   pattern="\+7 \([0-9]{3}\) [0-9]{3}-[0-9]{2}-[0-9]{2}">
        </div>

        <div class="form-group">
            <label for="message" class="visually-hidden">Ваше сообщение</label>
            <textarea id="message" 
                     class="feedback-form__input feedback-form__message" 
                     placeholder="Опишите заказ. Примерное время аренды, удаленность." 
                     name="mail" 
                     rows="3"
                     aria-label="Описание заказа"></textarea>
        </div>

        <div class="form-group privacy-consent">
            <label class="checkbox-label">
                <input type="checkbox" 
                       name="privacy_consent" 
                       id="privacy_consent" 
                       required 
                       aria-required="true">
                <span>Я согласен с <a href="/privacy-policy.php" target="_blank">политикой конфиденциальности</a></span>
            </label>
        </div>

        <button type="submit" class="btn feedback-form__button">
            <span>Получить расчёт</span>
        </button>
    </form>
</div>

<script src="/js/sweetalert.min.js"></script>
<script type="module" src="/js/utils.js" defer></script>
<script type="module" src="/js/form.js" defer></script>
<script src="/js/csrf.js" defer></script>

</body>
</html>