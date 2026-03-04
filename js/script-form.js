$(document).ready(function() {
        // Открыть форму при нажатии на кнопку
        $('#callButton').click(function() {
            $('#callbackForm').fadeIn(300);
        });

        // Закрыть форму при нажатии на кнопку "Закрыть"
        $('#closeForm').click(function() {
            $('#callbackForm').fadeOut(300);
        });
    });