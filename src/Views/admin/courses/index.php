<?php $this->render('layouts/admin-header', ['title' => $title]); ?>

    <div class="admin-layout">
        <?php $this->render('layouts/admin-sidebar'); ?>

        <main class="main-content">
            <div class="content-wrapper">
                <div class="admin-header">
                    <h1 class="page-title"><?= $title ?></h1>
                    <?php
                    // Определяем текст и ссылку для кнопки
                    $buttonText = ($type === 'masterclass') ? 'Добавить мастер-класс' : 'Добавить курс';
                    $newLink = ($type === 'masterclass') ? '/admin/masterclasses/new' : '/admin/courses/new';
                    ?>
                    <a href="<?= $newLink ?>" class="btn btn-primary"><?= $buttonText ?></a>
                </div>
                <div class="admin-table-container">
                    <table class="admin-table">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Название</th>
                            <th>Дата создания</th>
                            <th>Автор (админ)</th>
                            <th>Цена</th>
                            <th>Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($courses)): ?>
                            <?php foreach ($courses as $course): ?>
                                <tr>
                                    <td><?= $course['id'] ?></td>
                                    <td><?= htmlspecialchars($course['title']) ?></td>
                                    <td><?= date('d.m.Y H:i', strtotime($course['created_at'])) ?></td>
                                    <td>
                                        <?php if (!empty($course['admin_email'])): ?>
                                            <?= htmlspecialchars($course['admin_email']) ?>
                                            <?php if (!empty($course['admin_first_name']) || !empty($course['admin_last_name'])): ?>
                                                <br><span style="color:#888; font-size:12px;">
                                                    <?= htmlspecialchars(trim(($course['admin_first_name'] ?? '') . ' ' . ($course['admin_last_name'] ?? ''))) ?>
                                                </span>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span style="color:#888;">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($course['is_free']) || $course['price'] == 0): ?>
                                            <span style="color: #27ae60; font-weight: bold;">Бесплатно</span>
                                        <?php else: ?>
                                            <?= number_format($course['price'], 2, ',', ' ') ?> ₽
                                        <?php endif; ?>
                                    </td>
                                    <td class="actions">
                                        <a href="/admin/courses/content/<?= $course['id'] ?>" class="btn btn-primary">Управление</a>
                                        <a href="/admin/courses/edit/<?= $course['id'] ?>" class="btn btn-secondary">Редактировать</a>
                                        <a href="/admin/courses/delete/<?= $course['id'] ?>" class="btn btn-danger" onclick="return confirm('Вы уверены, что хотите удалить этот элемент?');">Удалить</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4">Элементы еще не созданы.</td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

<?php $this->render('layouts/footer'); ?>