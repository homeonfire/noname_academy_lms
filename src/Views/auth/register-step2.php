<?php $this->render('layouts/header', ['title' => $title]); ?>

    <div class="auth-container">
        <div class="auth-form-wrapper" style="max-width: 500px;">
            <div class="auth-header">
                <h1>Шаг 2 из 3: Ваш уровень</h1>
                <p>Это поможет нам рекомендовать вам подходящие курсы.</p>
            </div>

            <div class="progress-indicator-container">
                <div class="progress-step active">1. Аккаунт</div>
                <div class="progress-step active">2. Уровень</div>
                <div class="progress-step">3. Интересы</div>
            </div>

            <form method="POST" action="/register/step2" class="auth-form">
                <?= CSRF::getTokenField() ?>
                <div class="input-group">
                    <div class="tag-checkbox-group">
                        <label>
                            <input type="radio" name="experience_level" value="beginner" checked>
                            <span>Начинающий</span>
                        </label>
                        <label>
                            <input type="radio" name="experience_level" value="intermediate">
                            <span>Средний</span>
                        </label>
                        <label>
                            <input type="radio" name="experience_level" value="advanced">
                            <span>Продвинутый</span>
                        </label>
                    </div>
                </div>
                <button type="submit" class="btn-primary">Продолжить</button>
            </form>
        </div>
    </div>

<?php $this->render('layouts/footer'); ?>