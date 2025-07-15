<?php $this->render('layouts/header', ['title' => $title]); ?>

    <div class="auth-container">
        <div class="auth-form-wrapper" style="max-width: 500px;">
            <div class="auth-header">
                <h1>Шаг 3 из 3: Ваши интересы</h1>
                <p>Выберите несколько навыков, которые вам интересны.</p>
            </div>

            <div class="progress-indicator-container">
                <div class="progress-step active">1. Аккаунт</div>
                <div class="progress-step active">2. Уровень</div>
                <div class="progress-step active">3. Интересы</div>
            </div>

            <form method="POST" action="/register/step3" class="auth-form">
                <?= CSRF::getTokenField() ?>
                <div class="input-group">
                    <div class="tag-checkbox-group">
                        <?php if (!empty($allCategories)): ?>
                            <?php foreach ($allCategories as $category): ?>
                                <label>
                                    <input type="checkbox" name="category_ids[]" value="<?= $category['id'] ?>">
                                    <span><?= htmlspecialchars($category['name']) ?></span>
                                </label>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Категории пока не созданы.</p>
                        <?php endif; ?>
                    </div>
                </div>
                <button type="submit" class="btn-primary">Завершить регистрацию</button>
            </form>
        </div>
    </div>

<?php $this->render('layouts/footer'); ?>