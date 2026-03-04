let currentSlideIndex = 1;
showSlides(currentSlideIndex);

function showSlides(n) {
    let slides = document.querySelectorAll('.slide');
    let dots = document.querySelectorAll('.dot');

    if (n > slides.length) {
        currentSlideIndex = 1;
    }
    if (n < 1) {
        currentSlideIndex = slides.length;
    }

    slides.forEach(slide => slide.style.display = 'none');
    dots.forEach(dot => dot.classList.remove('active'));

    slides[currentSlideIndex - 1].style.display = 'block';
    dots[currentSlideIndex - 1].classList.add('active');
}

function currentSlide(n) {
    showSlides(currentSlideIndex = n);
}

setInterval(() => {
    currentSlide(currentSlideIndex += 1);
}, 5000); // Автопереключение через 5 секунд

// Увеличение изображения при клике
document.querySelectorAll('.slide').forEach(img => {
    img.addEventListener('click', function() {
        if (!img.classList.contains('fullscreen')) {
            img.classList.add('fullscreen');
            img.style.width = '100%';
            img.style.height = 'auto';
        } else {
            img.classList.remove('fullscreen');
            img.style.width = '100%';
        }
    });
});