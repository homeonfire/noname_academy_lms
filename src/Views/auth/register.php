<?php
$title = 'Регистрация';
$this->render('layouts/header', ['title' => $title]);
?>

    <div class="auth-container">
        <div class="auth-form-wrapper">
            <div class="auth-header">
                <h1>Создать аккаунт</h1>
                <p>Присоединяйтесь к нашему сообществу</p>
            </div>

            <div class="progress-indicator-container">
                <div class="progress-step active">1. Аккаунт</div>
                <div class="progress-step">2. Уровень</div>
                <div class="progress-step">3. Интересы</div>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="/register" class="auth-form">
                <?= CSRF::getTokenField() ?>
                <div class="input-group">
                    <label for="email">Email адрес</label>
                    <input type="email" name="email" id="email" required>
                </div>
                <div class="input-group">
                    <label for="password">Пароль</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <div class="input-group">
                    <label for="password_confirm">Подтвердите пароль</label>
                    <input type="password" name="password_confirm" id="password_confirm" required>
                </div>
                <button type="submit" class="btn-primary">Зарегистрироваться</button>
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
                <p>Уже есть аккаунт? <a href="/login">Войти</a></p>
            </div>
        </div>
    </div>

<?php
$this->render('layouts/footer');
?>