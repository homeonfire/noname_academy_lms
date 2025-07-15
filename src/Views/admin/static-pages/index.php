<?php $this->render('layouts/admin-header', ['title' => $title]); ?>

    <div class="admin-layout">
        <?php $this->render('layouts/admin-sidebar'); ?>

        <main class="main-content">
            <div class="content-wrapper">
                <div class="admin-header">
                    <h1 class="page-title"><?= $title ?></h1>
                </div>

                <div class="admin-card">
                    <div class="admin-table-container-flat">
                        <table class="admin-table">
                            <thead>
                            <tr>
                                <th>Название</th>
                                <th>URL</th>
                                <th>Последнее обновление</th>
                                <th>Действия</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (!empty($pages)): ?>
                                <?php foreach ($pages as $page): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($page['title']) ?></td>
                                        <td>
                                            <a href="/<?= htmlspecialchars($page['slug']) ?>" target="_blank">
                                                /<?= htmlspecialchars($page['slug']) ?>
                                            </a>
                                        </td>
                                        <td><?= date('d.m.Y H:i', strtotime($page['updated_at'])) ?></td>
                                        <td class="actions">
                                            <a href="/admin/static-pages/edit/<?= htmlspecialchars($page['slug']) ?>" class="btn btn-sm btn-primary">
                                                Редактировать
                                            </a>
                                            <a href="/<?= htmlspecialchars($page['slug']) ?>" target="_blank" class="btn btn-sm btn-secondary">
                                                Просмотр
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" style="text-align: center; color: #888;">
                                        Статические страницы не найдены
                                    </td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

<?php $this->render('layouts/footer'); ?> 