<?php $this->render('layouts/app-header', ['title' => $title]); ?>

    <div class="app-layout">
        <?php $this->render('layouts/app-sidebar'); ?>

        <main class="main-content">
            <div class="content-wrapper">
                <h1 class="page-title">Работы на проверку (<?= count($submissions) ?>)</h1>

                <div class="admin-table-container-dark">
                    <table class="admin-table-dark">
                        <thead>
                        <tr>
                            <th>Студент</th>
                            <th>Курс / Урок</th>
                            <th>Дата сдачи</th>
                            <th>Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($submissions)): ?>
                            <?php foreach ($submissions as $submission): ?>
                                <tr>
                                    <td><?= htmlspecialchars($submission['user_email']) ?></td>
                                    <td>
                                        <strong><?= htmlspecialchars($submission['course_title']) ?></strong><br>
                                        <small><?= htmlspecialchars($submission['lesson_title']) ?></small>
                                    </td>
                                    <td><?= date('d.m.Y H:i', strtotime($submission['submitted_at'])) ?></td>
                                    <td class="actions">
                                        <a href="/homework-check/<?= $submission['id'] ?>" class="btn btn-primary">Проверить</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4">Новых работ на проверку нет.</td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="content-wrapper" style="margin-top: 40px;">
                <h1 class="page-title">Проверенные работы (<?= $checkedTotal ?>)</h1>

                <div class="admin-table-container-dark">
                    <table class="admin-table-dark">
                        <thead>
                        <tr>
                            <th>Студент</th>
                            <th>Курс / Урок</th>
                            <th>Дата проверки</th>
                            <th>Статус</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($checkedList)): ?>
                            <?php foreach ($checkedList as $item): ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['user_email']) ?></td>
                                    <td>
                                        <strong><?= htmlspecialchars($item['course_title']) ?></strong><br>
                                        <small><?= htmlspecialchars($item['lesson_title']) ?></small>
                                    </td>
                                    <td><?= $item['checked_at'] ? date('d.m.Y H:i', strtotime($item['checked_at'])) : '-' ?></td>
                                    <td>
                                        <?php if ($item['status'] === 'checked'): ?>
                                            <span style="color: #27ae60; font-weight: bold;">Принято</span>
                                        <?php elseif ($item['status'] === 'rejected'): ?>
                                            <span style="color: #e74c3c; font-weight: bold;">Отклонено</span>
                                        <?php endif; ?>
                                        <a href="/homework-check/<?= $item['id'] ?>" class="btn btn-sm btn-secondary" style="margin-left: 10px;">Просмотр</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4">Проверенных работ пока нет.</td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php if ($checkedPages > 1): ?>
                    <div class="pagination-wrapper" style="margin-top: 20px;">
                        <?php for ($i = 1; $i <= $checkedPages; $i++): ?>
                            <a href="/homework-check?page=<?= $i ?>" class="btn btn-sm <?= $i == $checkedPage ? 'btn-primary' : 'btn-secondary' ?>" style="margin-right: 5px;">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

<?php $this->render('layouts/footer'); ?>