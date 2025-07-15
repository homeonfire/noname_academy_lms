<?php
// Задаем заголовок страницы
$title = 'Вход в аккаунт';

// Подключаем шапку
$this->render('layouts/header', ['title' => $title]);
?>

    <div class="auth-container">
        <div class="auth-form-wrapper">
            <div class="auth-header">
                <h1>С возвращением!</h1>
                <p>Войдите в свой аккаунт</p>
            </div>

            <?php if (isset($_GET['status']) && $_GET['status'] === 'registered'): ?>
                <div class="alert alert-success">
                    Вы успешно зарегистрировались! Теперь можете войти.
                </div>
            <?php endif; ?>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="/login" class="auth-form">
                <?= CSRF::getTokenField() ?>
                <div class="input-group">
                    <label for="email">Email адрес</label>
                    <input type="email" name="email" id="email" required>
                </div>
                <div class="input-group">
                    <div class="label-wrapper">
                        <label for="password">Пароль</label>
                        <a href="#" class="forgot-password">Забыли пароль?</a>
                    </div>
                    <input type="password" name="password" id="password" required>
                </div>
                <button type="submit" class="btn-primary">Войти</button>
            </form>

            <script>
                // Вывод CSRF токена в консоль для отладки
                document.addEventListener('DOMContentLoaded', function() {
                    const csrfToken = document.querySelector('input[name="csrf_token"]');
                    if (csrfToken) {
                        console.log('🔐 CSRF Token:', csrfToken.value);
                        console.log('📝 Token length:', csrfToken.value.length);
                        console.log('🆔 Token preview:', csrfToken.value.substring(0, 20) + '...');
                    } else {
                        console.warn('⚠️ CSRF token not found in form');
                    }
                });
            </script>

            <div class="auth-footer">
                <p>Нет аккаунта? <a href="/register">Зарегистрируйтесь</a></p>
            </div>
        </div>
    </div>

<?php
// Подключаем подвал
$this->render('layouts/footer');
?>