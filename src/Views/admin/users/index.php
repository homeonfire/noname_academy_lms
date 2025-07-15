<?php $this->renderAdminPage('layouts/admin-header', ['title' => $title]); ?>

<div class="admin-layout">
    <?php $this->renderAdminPage('layouts/admin-sidebar'); ?>

    <main class="main-content">
        <div class="content-wrapper">
            <h1 class="page-title">Управление пользователями</h1>

            <div class="admin-table-container">
                <table class="admin-table">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Имя</th>
                        <th>Роль</th>
                        <th>Дата регистрации</th>
                        <th>Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= $user['id'] ?></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></td>
                                <td><?= $user['role'] === 'admin' ? 'Администратор' : 'Пользователь' ?></td>
                                <td><?= date('d.m.Y H:i', strtotime($user['created_at'])) ?></td>
                                <td class="actions">

                                        <a href="/admin/users/show/<?= $user['id'] ?>" class="btn btn-sm btn-primary">Карточка</a>
                                   
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">Пользователи не найдены.</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>