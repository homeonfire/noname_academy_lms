<?php $this->render('layouts/admin-header', ['title' => $title]); ?>

    <div class="admin-layout">
        <?php $this->render('layouts/admin-sidebar'); ?>

        <main class="main-content">
            <div class="content-wrapper">
                <div class="admin-header">
                    <h1 class="page-title">Управление контентом: "<?= htmlspecialchars($course['title']) ?>"</h1>
                    <a href="/admin/courses" class="btn btn-secondary">Назад к курсам</a>
                </div>

                <div class="admin-card">
                    <div class="admin-header">
                        <h3>Модули и уроки</h3>
                    </div>

                    <form action="/admin/modules/create" method="POST" class="add-module-form">
                        <?= CSRF::getTokenField() ?>
                        <input type="hidden" name="course_id" value="<?= $course['id'] ?>">
                        <input type="text" name="title" placeholder="Название нового модуля" required>
                        <button type="submit" class="btn btn-primary">Добавить модуль</button>
                    </form>

                    <div class="modules-list">
                        <?php if (!empty($course['modules'])): ?>
                            <?php foreach ($course['modules'] as $module): ?>
                                <div class="module-item">
                                    <div class="module-header">
                                        <form action="/admin/modules/update" method="POST" class="edit-module-form">
                                            <?= CSRF::getTokenField() ?>
                                            <input type="hidden" name="course_id" value="<?= $course['id'] ?>">
                                            <input type="hidden" name="module_id" value="<?= $module['id'] ?>">
                                            <input type="text" name="title" value="<?= htmlspecialchars($module['title']) ?>" required>
                                            <button type="submit" class="btn-sm btn-secondary">Сохранить</button>
                                        </form>
                                        <div class="actions">
                                            <a href="/admin/modules/delete/<?= $module['id'] ?>/course/<?= $course['id'] ?>" class="btn-sm btn-danger" onclick="return confirm('Вы уверены? Все уроки внутри этого модуля также будут удалены!');">Удалить</a>
                                        </div>
                                    </div>
                                    <ul class="lessons-list">
                                        <?php if (!empty($module['lessons'])): ?>
                                            <?php foreach ($module['lessons'] as $lesson): ?>
                                                <li class="lesson-item">
                                                    <form action="/admin/lessons/update" method="POST" class="edit-lesson-form" id="edit-lesson-form-<?= $lesson['id'] ?>">
                                                        <?= CSRF::getTokenField() ?>
                                                        <input type="hidden" name="course_id" value="<?= $course['id'] ?>">
                                                        <input type="hidden" name="lesson_id" value="<?= $lesson['id'] ?>">
                                                        <input type="text" name="title" value="<?= htmlspecialchars($lesson['title']) ?>" class="lesson-title-input">
                                                    </form>

                                                    <div class="actions">
                                                        <button type="submit" form="edit-lesson-form-<?= $lesson['id'] ?>" class="btn-sm btn-secondary">Сохранить</button>
                                                        <a href="/admin/lessons/edit-content/<?= $lesson['id'] ?>?course_id=<?= $course['id'] ?>" class="btn-sm btn-secondary">Контент</a>
                                                        <a href="/admin/lessons/delete/<?= $lesson['id'] ?>/course/<?= $course['id'] ?>" class="btn-sm btn-danger" onclick="return confirm('Вы уверены?');">Удалить</a>
                                                    </div>
                                                </li>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                        <li class="add-lesson-row">
                                            <form action="/admin/lessons/create" method="POST" class="add-lesson-form">
                                                <?= CSRF::getTokenField() ?>
                                                <input type="hidden" name="course_id" value="<?= $course['id'] ?>">
                                                <input type="hidden" name="module_id" value="<?= $module['id'] ?>">
                                                <input type="text" name="title" placeholder="Название нового урока" required>
                                                <input type="hidden" name="content_json" value="{}">
                                                <button type="submit" class="btn btn-secondary btn-sm">Добавить урок</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>В этом курсе пока нет модулей.</p>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </main>
    </div>

<?php $this->render('layouts/footer'); ?>