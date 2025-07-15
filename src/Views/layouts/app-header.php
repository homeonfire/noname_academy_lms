<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'AI Fire LMS' ?></title>
    <link rel="stylesheet" href="/public/assets/styles.css" id="theme-stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&family=Raleway:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <script src="/public/assets/favorites.js" defer></script>
    <?php if (isset($_SESSION['user'])): ?>
        <meta name="csrf-token" content="<?= CSRF::generateToken() ?>">
        <div data-user-id="<?= $_SESSION['user']['id'] ?>" style="display: none;"></div>
    <?php endif; ?>
    <script src="/public/assets/websocket-client.js" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const themeStylesheet = document.getElementById('theme-stylesheet');
            const themeSwitcherBtn = document.getElementById('theme-switcher-btn');
            const themeIndicator = themeSwitcherBtn.querySelector('.theme-indicator');

            // Получаем сохраненную тему из localStorage или устанавливаем темную по умолчанию
            let currentTheme = localStorage.getItem('theme') || 'dark'; // 'dark' соответствует styles.css

            // Функция для применения темы
            const applyTheme = (theme) => {
                if (theme === 'light') {
                    themeStylesheet.href = '/public/assets/styles-day.css';
                    themeIndicator.textContent = '☀️'; // Солнышко для светлой темы
                    themeIndicator.title = 'Текущая тема: Светлая';
                } else { // 'dark'
                    themeStylesheet.href = '/public/assets/styles.css';
                    themeIndicator.textContent = '🌙'; // Месяц для темной темы
                    themeIndicator.title = 'Текущая тема: Темная';
                }
                localStorage.setItem('theme', theme);
                currentTheme = theme;
            };

            // Применяем тему при загрузке страницы
            applyTheme(currentTheme);

            // Обработчик клика по кнопке
            themeSwitcherBtn.addEventListener('click', (e) => {
                e.preventDefault(); // Предотвращаем переход по ссылке
                if (currentTheme === 'dark') {
                    applyTheme('light');
                } else {
                    applyTheme('dark');
                }
            });
        });
    </script>
</head>
<body>