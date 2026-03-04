<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Галерея - Автовышки в Воронеже</title>
    <meta name="description" content="Галерея фотографий наших автовышек в работе. Предлагаем аренду автовышек высотой до 30 метров в Воронеже и области.">
    <link rel="stylesheet" href="css/gallery.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/form.css">
    <link rel="stylesheet" href="css/sweetalert.min.css">
</head>
<body class="gallery-page">
    <?php include 'header.php'; ?>
    
    <!-- Модальная форма -->
    <div id="modalForm">
        <span id="closeModal">&times;</span> <!-- Кнопка для закрытия формы -->
        <!-- Подключение формы через include -->
        <?php include 'form.php'; ?>
    </div>

    <main class="gallery-container">
        <h1 class="gallery-title">Наша техника в работе</h1>
        <p class="gallery-description">
            Здесь представлены фотографии наших автовышек на различных объектах. 
            Мы выполняем работы любой сложности на высоте до 30 метров.
        </p>

        <div class="gallery-grid">
            <div class="gallery-item">
                <img src="img/vyshka.png" alt="Автовышка на объекте">
                <div class="gallery-item-overlay">
                    <p>Автовышка на рабочем объекте</p>
                </div>
            </div>
            <div class="gallery-item">
                <img src="img/image1.jpg" alt="Работа автовышки">
                <div class="gallery-item-overlay">
                    <p>Выполнение высотных работ</p>
                </div>
            </div>
            <div class="gallery-item">
                <img src="img/image2.jpg" alt="Автовышка в действии">
                <div class="gallery-item-overlay">
                    <p>Монтажные работы на высоте</p>
                </div>
            </div>
            <div class="gallery-item">
                <img src="img/image3.jpg" alt="Высотные работы">
                <div class="gallery-item-overlay">
                    <p>Обслуживание объектов</p>
                </div>
            </div>
            <div class="gallery-item">
                <img src="img/image4.jpg" alt="Аренда автовышки">
                <div class="gallery-item-overlay">
                    <p>Профессиональное оборудование</p>
                </div>
            </div>
            <div class="gallery-item">
                <img src="img/autovysh-fon-2.jpg" alt="Автовышка в Воронеже">
                <div class="gallery-item-overlay">
                    <p>Работы в городской среде</p>
                </div>
            </div>
        </div>
    </main>

    <!-- Модальное окно для просмотра изображений -->
    <div id="imageModal" class="modal">
        <span class="close">&times;</span>
        <button class="nav-btn prev-btn">&#10094;</button>
        <img id="modalImage" class="modal-content">
        <button class="nav-btn next-btn">&#10095;</button>
    </div>

    <?php include 'footer.php'; ?>
    
    <script src="js/gallery.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/sweetalert.min.js"></script>
    <script src="js/form.js"></script>
    <script src="js/click-form.js"></script>
    <script src="js/tawk.to.js"></script>
</body>
</html> 