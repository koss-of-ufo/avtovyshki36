jQuery(document).ready(function() {
    // Открываем модальное окно по клику на кнопку
    jQuery("#showFormButton").click(function() {
        jQuery("#overlay, #modalForm").fadeIn(); // Плавно показываем затемнение и форму
    });

    // Закрываем модальное окно по клику на кнопку закрытия или затемнение
    jQuery("#closeModal, #overlay").click(function() {
        jQuery("#overlay, #modalForm").fadeOut(); // Плавно скрываем затемнение и форму
    });
});