<?php $this->render('layouts/app-header', ['title' => $title]); ?>

    <div class="app-layout">
        <?php $this->render('layouts/app-sidebar'); ?>

        <main class="main-content">
            <div class="content-wrapper">
                <h1 class="page-title">Профиль</h1>

                <form action="/profile/update" method="POST" class="profile-form" enctype="multipart/form-data">
                    <?= CSRF::getTokenField() ?>
                    <div class="form-section">
                        <h3 class="section-title">Детали профиля</h3>
                        <p class="section-subtitle">Здесь вы можете обновить информацию о себе.</p>

                        <div class="input-group">
                            <label>Аватар</label>
                            <div style="display: flex; align-items: center; gap: 20px;">
                                <img src="<?= htmlspecialchars($user['avatar_path'] ?? '/public/assets/images/default-avatar.png') ?>" alt="Avatar" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover;">
                                <input type="file" name="avatar" id="avatar">
                            </div>
                        </div>

                        <div class="input-group">
                            <label for="first_name">Имя</label>
                            <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($user['first_name'] ?? '') ?>">
                        </div>
                        <div class="input-group">
                            <label for="last_name">Фамилия</label>
                            <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($user['last_name'] ?? '') ?>">
                        </div>
                        <div class="input-group">
                            <label for="email">Email адрес</label>
                            <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" disabled>
                            <small>Email нельзя изменить.</small>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3 class="section-title">Ваш фокус</h3>
                        <p class="section-subtitle">Эта информация поможет нам рекомендовать вам подходящие курсы.</p>

                        <div class="input-group">
                            <label>Ваш уровень</label>
                            <div class="tag-checkbox-group">
                                <label>
                                    <input type="radio" name="experience_level" value="beginner" <?= ($user['experience_level'] ?? '') === 'beginner' ? 'checked' : '' ?>>
                                    <span>Начинающий</span>
                                </label>
                                <label>
                                    <input type="radio" name="experience_level" value="intermediate" <?= ($user['experience_level'] ?? '') === 'intermediate' ? 'checked' : '' ?>>
                                    <span>Средний</span>
                                </label>
                                <label>
                                    <input type="radio" name="experience_level" value="advanced" <?= ($user['experience_level'] ?? '') === 'advanced' ? 'checked' : '' ?>>
                                    <span>Продвинутый</span>
                                </label>
                            </div>
                        </div>

                        <div class="input-group">
                            <label>Интересующие навыки</label>
                            <div class="tag-checkbox-group">
                                <?php foreach ($allCategories as $category): ?>
                                    <label>
                                        <?php
                                        // Проверяем, есть ли ID этой категории в массиве выбранных
                                        $isChecked = in_array($category['id'], $preferredCategoryIds);
                                        ?>
                                        <input type="checkbox" name="category_ids[]" value="<?= $category['id'] ?>" <?= $isChecked ? 'checked' : '' ?>>
                                        <span><?= htmlspecialchars($category['name']) ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn-primary">Сохранить изменения</button>
                </form>
            </div>
        </main>
    </div>

<?php $this->render('layouts/footer'); ?>