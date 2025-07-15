<?php $this->render('layouts/app-header', ['title' => $title]); ?>

    <div class="app-layout">
        <?php $this->render('layouts/app-sidebar'); ?>

        <main class="main-content">
            <div class="content-wrapper">
                <h1 class="page-title">Мои ответы</h1>

                <section class="content-section">
                    <h3 class="section-title">На проверке</h3>
                    <div class="answers-grid">
                        <?php if (!empty($uncheckedAnswers)): ?>
                            <?php foreach ($uncheckedAnswers as $answer): ?>
                                <a href="/course/<?= $answer['course_id'] ?>/lesson/<?= $answer['lesson_id'] ?>" class="answer-card-link">
                                    <div class="answer-card">
                                        <h4><?= htmlspecialchars($answer['course_title']) ?></h4>
                                        <p><?= htmlspecialchars($answer['lesson_title']) ?></p>
                                        <div class="status-badge status-submitted">На проверке</div>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Нет работ, ожидающих проверки.</p>
                        <?php endif; ?>
                    </div>
                </section>

                <section class="content-section">
                    <h3 class="section-title">Проверенные</h3>
                    <div class="answers-grid">
                        <?php if (!empty($checkedAnswers)): ?>
                            <?php foreach ($checkedAnswers as $answer): ?>
                                <a href="/course/<?= $answer['course_id'] ?>/lesson/<?= $answer['lesson_id'] ?>" class="answer-card-link">
                                    <div class="answer-card">
                                        <h4><?= htmlspecialchars($answer['course_title']) ?></h4>
                                        <p><?= htmlspecialchars($answer['lesson_title']) ?></p>
                                        <?php if ($answer['status'] === 'checked'): ?>
                                            <div class="status-badge status-checked">Принято</div>
                                        <?php else: ?>
                                            <div class="status-badge status-rejected">Отклонено</div>
                                        <?php endif; ?>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Проверенных работ пока нет.</p>
                        <?php endif; ?>
                    </div>
                </section>
            </div>
        </main>
    </div>

<?php $this->render('layouts/footer'); ?>