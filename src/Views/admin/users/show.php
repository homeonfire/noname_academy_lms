<?php $this->render('layouts/admin-header', ['title' => $title]); ?>

<div class="admin-layout">
    <?php $this->render('layouts/admin-sidebar'); ?>

    <main class="main-content">
        <div class="content-wrapper">
            <div class="admin-header">
                <h1 class="page-title"><?= $title ?></h1>
                <a href="/admin/users" class="btn btn-secondary">Назад к списку</a>
            </div>

            <div class="user-card-layout">
                <div class="user-card-main">
                    <div class="admin-card">
                        <h3>Основная информация</h3>
                        <form action="/admin/users/update/<?= $user['id'] ?>" method="POST" class="admin-form">
                            <?= CSRF::getTokenField() ?>
                            <div class="input-group">
                                <label>Аватар</label>
                                <img src="<?= htmlspecialchars($user['avatar_path'] ?? '/public/assets/images/default-avatar.png') ?>" alt="Avatar" style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; margin-top: 5px;">
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
                                <label for="email">Email (нельзя изменить)</label>
                                <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" disabled>
                            </div>
                            <div class="input-group">
                                <label for="role">Роль</label>
                                <select id="role" name="role">
                                    <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>Пользователь</option>
                                    <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Администратор</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Сохранить</button>
                        </form>
                    </div>
                    <div class="admin-card">
                        <h3>Лог входов</h3>
                        <div class="admin-table-container">
                            <table class="admin-table" id="visits-log-table">
                                <thead>
                                <tr>
                                    <th>Дата</th>
                                    <th>IP</th>
                                    <th>URL</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($visits as $visit): ?>
                                    <tr>
                                        <td><?= date('d.m.Y H:i', strtotime($visit['visit_date'])) ?></td>
                                        <td><?= htmlspecialchars($visit['ip_address']) ?></td>
                                        <td><?= htmlspecialchars($visit['page_url']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php if ($totalPages > 1): ?>
                            <div class="pagination-wrapper">
                                <button id="load-more-visits" class="btn btn-secondary" data-user-id="<?= $user['id'] ?>" data-total-pages="<?= $totalPages ?>" data-current-page="1">Загрузить еще</button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="user-card-sidebar">
                    <div class="admin-card">
                        <h3>Аналитика</h3>
                        <h4>Первый источник (UTM):</h4>
                        <?php if ($firstUtm): ?>
                            <ul>
                                <li><strong>Source:</strong> <?= htmlspecialchars($firstUtm['utm_source'] ?? 'N/A') ?></li>
                                <li><strong>Medium:</strong> <?= htmlspecialchars($firstUtm['utm_medium'] ?? 'N/A') ?></li>
                                <li><strong>Campaign:</strong> <?= htmlspecialchars($firstUtm['utm_campaign'] ?? 'N/A') ?></li>
                            </ul>
                        <?php else: ?>
                            <p>UTM-меток не зафиксировано.</p>
                        <?php endif; ?>
                    </div>
                    <div class="admin-card">
                        <h3>Навыки и уровень</h3>
                        <div class="input-group" style="margin-bottom: 20px;">
                            <label style="margin-bottom: 8px; display: block; color: #555; font-size: 14px; font-weight: 500;">Уровень</label>
                            <?php
                            $levels = [
                                'beginner' => 'Начинающий',
                                'intermediate' => 'Средний',
                                'advanced' => 'Продвинутый'
                            ];
                            $userLevel = $levels[$user['experience_level']] ?? ucfirst($user['experience_level']);
                            ?>
                            <span class="tag-display"><?= htmlspecialchars($userLevel) ?></span>
                        </div>

                        <div class="input-group">
                            <label style="margin-bottom: 8px; display: block; color: #555; font-size: 14px; font-weight: 500;">Интересы</label>
                            <div class="tag-checkbox-group">
                                <?php
                                $userCategories = [];
                                foreach ($allCategories as $category) {
                                    if (in_array($category['id'], $preferredCategoryIds)) {
                                        $userCategories[] = $category['name'];
                                    }
                                }
                                ?>
                                <?php if (!empty($userCategories)): ?>
                                    <?php foreach ($userCategories as $categoryName): ?>
                                        <span class="tag-display"><?= htmlspecialchars($categoryName) ?></span>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p style="margin-top: 5px; color: #888;">Не указаны.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>