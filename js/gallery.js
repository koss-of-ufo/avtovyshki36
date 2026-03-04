document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('imageModal');
    const modalImg = document.getElementById('modalImage');
    const closeBtn = document.getElementsByClassName('close')[0];
    const prevBtn = document.querySelector('.prev-btn');
    const nextBtn = document.querySelector('.next-btn');
    const galleryItems = document.querySelectorAll('.gallery-item img');
    let currentImageIndex = 0;

    // Открытие модального окна при клике на изображение
    galleryItems.forEach((img, index) => {
        img.addEventListener('click', function() {
            modal.style.display = 'block';
            modalImg.src = this.src;
            currentImageIndex = index;
            document.body.style.overflow = 'hidden'; // Запрещаем прокрутку страницы
            updateNavigationButtons();
        });
    });

    // Обработка клика на кнопку "Предыдущее изображение"
    prevBtn.addEventListener('click', function() {
        currentImageIndex = (currentImageIndex - 1 + galleryItems.length) % galleryItems.length;
        modalImg.src = galleryItems[currentImageIndex].src;
        updateNavigationButtons();
    });

    // Обработка клика на кнопку "Следующее изображение"
    nextBtn.addEventListener('click', function() {
        currentImageIndex = (currentImageIndex + 1) % galleryItems.length;
        modalImg.src = galleryItems[currentImageIndex].src;
        updateNavigationButtons();
    });

    // Обработка нажатий клавиш
    document.addEventListener('keydown', function(e) {
        if (modal.style.display === 'block') {
            if (e.key === 'ArrowLeft') {
                prevBtn.click();
            } else if (e.key === 'ArrowRight') {
                nextBtn.click();
            } else if (e.key === 'Escape') {
                closeModal();
            }
        }
    });

    // Закрытие модального окна при клике на крестик
    closeBtn.addEventListener('click', closeModal);

    // Закрытие модального окна при клике вне изображения
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModal();
        }
    });

    function closeModal() {
        modal.style.display = 'none';
        document.body.style.overflow = ''; // Возвращаем прокрутку страницы
    }

    function updateNavigationButtons() {
        // Анимация смены изображения
        modalImg.style.opacity = '0';
        setTimeout(() => {
            modalImg.style.opacity = '1';
        }, 50);
    }

    // Анимация появления изображений при прокрутке
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.1
    };

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.gallery-item').forEach(item => {
        observer.observe(item);
    });
}); 