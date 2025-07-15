<?php
$this->render('layouts/app-header', ['title' => $title]);

// Декодируем JSON-данные
$questions = json_decode($submission['questions'], true);
$answers = json_decode($submission['answers'], true);
?>

    <div class="app-layout">
        <?php $this->render('layouts/app-sidebar'); ?>

        <main class="main-content">
            <div class="content-wrapper">
                <div class="check-header">
                    <div>
                        <h1 class="page-title">Проверка работы</h1>
                        <p class="page-subtitle">Студент: <strong><?= htmlspecialchars($submission['user_email']) ?></strong></p>
                        <p class="page-subtitle">Урок: <strong><?= htmlspecialchars($submission['lesson_title']) ?></strong></p>
                    </div>
                    <a href="/homework-check" class="btn btn-secondary">Назад к списку</a>
                </div>

                <div class="homework-check-container">
                    <?php foreach ($questions as $index => $questionItem): ?>
                        <div class="qa-block">
                            <div class="question-block">
                                <span class="qa-label">Вопрос <?= $index + 1 ?></span>
                                <p><?= htmlspecialchars($questionItem['q']) ?></p>
                            </div>
                            <div class="answer-block">
                                <span class="qa-label">Ответ студента</span>
                                <p><?= nl2br(htmlspecialchars($answers[$index]['a'] ?? '')) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="check-actions">
                    <form action="/homework-check/<?= $submission['id'] ?>" method="POST" class="check-actions">
                        <?= CSRF::getTokenField() ?>
                        <div class="input-group" style="width: 100%;">
                            <label for="comment">Комментарий для студента (необязательно)</label>
                            <textarea id="comment" name="comment" rows="4" placeholder="Например: Отличная работа! или Попробуй подумать над вторым вопросом еще раз."></textarea>
                        </div>
                        <div class="check-buttons">
                            <button type="submit" name="approve" class="btn btn-success">Одобрить</button>
                            <button type="submit" name="reject" class="btn btn-danger">Отклонить</button>
                        </div>
                    </form>
                </div>

            </div>
        </main>
    </div>

<?php $this->render('layouts/footer'); ?>