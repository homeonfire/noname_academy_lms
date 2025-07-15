<?php $this->render('layouts/admin-header', ['title' => $title]); ?>

    <div class="admin-layout">
        <?php $this->render('layouts/admin-sidebar'); ?>

        <main class="main-content">
            <div class="content-wrapper">
                <div class="admin-header">
                    <h1 class="page-title">Редактировать курс: <?= htmlspecialchars($course['title']) ?></h1>
                    <a href="/admin/courses" class="btn btn-secondary">Назад к списку</a>
                </div>

                <div class="admin-card">
                    <form action="/admin/courses/update/<?= $course['id'] ?>" method="POST" class="admin-form" enctype="multipart/form-data">
                        <?= CSRF::getTokenField() ?>
                        <div class="input-group">
                            <label for="title">Название курса</label>
                            <input type="text" id="title" name="title" value="<?= htmlspecialchars($course['title']) ?>" required>
                        </div>
                        <div class="input-group">
                            <label for="description">Описание</label>
                            <textarea id="description" name="description" rows="5"><?= htmlspecialchars($course['description']) ?></textarea>
                        </div>
                        <div class="input-group">
                            <label>Уровень сложности</label>
                            <div class="tag-checkbox-group">
                                <?php
                                $levels = [
                                    'beginner' => 'Начинающий',
                                    'intermediate' => 'Средний',
                                    'advanced' => 'Продвинутый'
                                ];
                                foreach ($levels as $value => $label):
                                    ?>
                                    <label>
                                        <input type="radio" name="difficulty_level" value="<?= $value ?>" <?= ($course['difficulty_level'] ?? 'beginner') === $value ? 'checked' : '' ?>>
                                        <span><?= $label ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="input-group">
                            <label>Категории</label>
                            <div class="tag-checkbox-group">
                                <?php foreach ($categories as $category): ?>
                                    <label>
                                        <?php
                                        // Проверяем, есть ли ID этой категории в массиве категорий курса
                                        $isChecked = in_array($category['id'], $courseCategoryIds);
                                        ?>
                                        <input type="checkbox" name="category_ids[]" value="<?= $category['id'] ?>" <?= $isChecked ? 'checked' : '' ?>>
                                        <span><?= htmlspecialchars($category['name']) ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="input-group">
                            <?php if (!empty($course['cover_url'])): ?>
                                <label>Текущая обложка</label>
                                <div style="margin-bottom: 10px;">
                                    <img src="<?= htmlspecialchars($course['cover_url']) ?>" alt="Текущая обложка" style="max-width: 200px; border-radius: 8px;">
                                </div>
                            <?php endif; ?>

                            <label for="cover_url" style="width: 100%; margin-top: 5px;">Загрузить новую обложку</label>
                            <input type="file" id="cover_url" name="cover_url" accept="image/*">
                        </div>
                        <div class="input-group">
                            <label for="price">Цена (₽)</label>
                            <input type="number" id="price" name="price" min="0" step="0.01" value="<?= isset($course['price']) ? htmlspecialchars($course['price']) : '0.00' ?>">
                        </div>
                        <div class="input-group">
                            <label>
                                <input type="checkbox" id="is_free" name="is_free" value="1" <?= (!empty($course['is_free']) || $course['price'] == 0) ? 'checked' : '' ?>>
                                Бесплатный курс
                            </label>
                        </div>
                        <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const isFree = document.getElementById('is_free');
                            const price = document.getElementById('price');
                            function togglePrice() {
                                if (isFree.checked) {
                                    price.value = '0.00';
                                    price.setAttribute('readonly', 'readonly');
                                } else {
                                    price.removeAttribute('readonly');
                                }
                            }
                            isFree.addEventListener('change', togglePrice);
                            togglePrice();
                        });
                        </script>
                        <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                    </form>
                </div>

            </div>
        </main>
    </div>

<?php $this->render('layouts/footer'); ?>