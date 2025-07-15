<?php $this->renderAdminPage('layouts/admin-header', ['title' => $title]); ?>

    <div class="admin-layout">
        <?php $this->renderAdminPage('layouts/admin-sidebar'); ?>

        <main class="main-content">
            <div class="content-wrapper">
                <h1 class="page-title">Управление категориями</h1>

                <div class="admin-grid">
                    <div class="admin-card">
                        <h3>Добавить категорию</h3>
                        <form action="/admin/categories/create" method="POST" class="admin-form">
                            <?= CSRF::getTokenField() ?>
                            <div class="input-group">
                                <label for="name">Название категории</label>
                                <input type="text" id="name" name="name" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Создать</button>
                        </form>
                    </div>

                    <div class="admin-card">
                        <h3>Существующие категории</h3>
                        <div class="admin-table-container-flat">
                            <table class="admin-table">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Название</th>
                                    <th>Slug</th>
                                    <th>Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($categories as $category): ?>
                                    <tr>
                                        <td><?= $category['id'] ?></td>
                                        <td><?= htmlspecialchars($category['name']) ?></td>
                                        <td><?= htmlspecialchars($category['slug']) ?></td>
                                        <td class="actions">
                                            <button type="button" class="btn btn-sm btn-secondary edit-category-btn"
                                                    data-category-id="<?= $category['id'] ?>"
                                                    data-category-name="<?= htmlspecialchars($category['name']) ?>">
                                                Редактировать
                                            </button>
                                            <a href="/admin/categories/delete/<?= $category['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Вы уверены? Это действие нельзя будет отменить.');">Удалить</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div id="edit-category-modal" class="modal-overlay" style="display: none;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2>Редактировать категорию</h2>
                        <button type="button" class="close-modal-btn">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form id="edit-category-form" action="" method="POST" class="admin-form">
                            <?= CSRF::getTokenField() ?>
                            <div class="input-group">
                                <label for="edit-category-name">Название категории</label>
                                <input type="text" id="edit-category-name" name="name" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Сохранить</button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>

<?php $this->renderAdminPage('layouts/footer'); ?>