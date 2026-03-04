<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Страница не найдена 404 - Автовышки36</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/404.css">
    <link rel="stylesheet" href="css/form.css">
    <link rel="stylesheet" href="css/sweetalert.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Shantell+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
</head>

<body>

    <!-- Хедер -->

    <?php include 'header.php'; ?>

    <main class="error-page">
        <h1>404</h1><br>
        <p>К сожалению, страница, которую вы ищете, не найдена.</p>
        <a href="/" class="button">Вернуться на главную</a> <!-- Кнопка возврата -->
    </main>

        <!-- Подвал -->

    <?php include 'footer.php'; ?>

    <script src="js/script.js"></script>
    <!-- Подключение последней версии jQuery из официального CDN jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/sweetalert.min.js"></script>
    <script src="js/form.js"></script>
    <script src="js/click-form.js"></script>
</body>
</html>
