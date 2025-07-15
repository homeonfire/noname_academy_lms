<?php $this->render('layouts/admin-header', ['title' => $title]); ?>

    <div class="admin-layout">
        <?php $this->render('layouts/admin-sidebar'); ?>

        <main class="main-content">
            <div class="content-wrapper">
                <h1 class="page-title">Статистика посещений</h1>
                <p class="page-subtitle">Отображаются только последние визиты для каждого уникального посетителя (по паре IP + User Agent).</p>

                <div class="admin-table-container">
                    <table class="admin-table">
                        <thead>
                        <tr>
                            <th>Дата</th>
                            <th>IP</th>
                            <th>Пользователь</th>
                            <th>Уникальный?</th>
                            <th>URL входа</th>
                            <th>Источник (Source)</th>
                            <th>Канал (Medium)</th>
                            <th>Кампания</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($visits)): ?>
                            <?php foreach ($visits as $visit): ?>
                                <tr>
                                    <td><?= date('d.m.Y H:i', strtotime($visit['visit_date'])) ?></td>
                                    <td><?= htmlspecialchars($visit['ip_address']) ?></td>
                                    <td>
                                        <?php if ($visit['user_id']): ?>
                                            <a href="/admin/users/edit/<?= $visit['user_id'] ?>"><?= htmlspecialchars($visit['user_email'] ?? 'ID: ' . $visit['user_id']) ?></a>
                                        <?php else: ?>
                                            <span style="color: #888;">Гость</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($visit['is_unique']): ?>
                                            <span style="color: #27ae60; font-weight: bold;">Да</span>
                                        <?php else: ?>
                                            Нет
                                        <?php endif; ?>
                                    </td>
                                    <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                        <a href="<?= htmlspecialchars($visit['page_url']) ?>" title="<?= htmlspecialchars($visit['page_url']) ?>" target="_blank">
                                            <?= htmlspecialchars($visit['page_url']) ?>
                                        </a>
                                    </td>
                                    <td><?= htmlspecialchars($visit['utm_source'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($visit['utm_medium'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($visit['utm_campaign'] ?? 'N/A') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8">Данных о посещениях пока нет.</td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </main>
    </div>

<?php $this->render('layouts/footer'); ?>