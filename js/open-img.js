$(document).ready(function() {
    // Скрываем лайтбокс при первой загрузке страницы
    $('#lightbox').hide();

    const slides = $('.slide');
    let currentSlideIndex = -1; // Устанавливаем индекс слайда как -1, чтобы лайтбокс не открывался автоматически

    // Открытие Lightbox при клике на изображение
    $('.slide').on('click', function() {
        currentSlideIndex = slides.index(this); // Получаем индекс слайда
        openLightbox(currentSlideIndex);
    });

    // Закрытие Lightbox при клике на крестик
    $('#close-lightbox').on('click', closeLightbox);

    // Перелистывание на следующее изображение
    $('#next-slide').on('click', function() {
        currentSlideIndex = (currentSlideIndex + 1) % slides.length; // Перелистывание на следующий слайд
        openLightbox(currentSlideIndex);
    });

    // Перелистывание на предыдущее изображение
    $('#prev-slide').on('click', function() {
        currentSlideIndex = (currentSlideIndex - 1 + slides.length) % slides.length; // Перелистывание на предыдущий слайд
        openLightbox(currentSlideIndex);
    });

    // Функции управления Lightbox
    function openLightbox(index) {
        const imgSrc = slides.eq(index).attr('src'); // Получаем путь к изображению
        $('#lightbox-img').attr('src', imgSrc); // Устанавливаем изображение в Lightbox
        $('#lightbox').fadeIn(); // Показываем Lightbox
    }

    function closeLightbox() {
        $('#lightbox').fadeOut(); // Скрываем Lightbox
    }
});
