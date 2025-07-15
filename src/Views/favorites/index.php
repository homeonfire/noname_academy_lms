<?php $this->render('layouts/app-header', ['title' => $title]); ?>

    <div class="app-layout">
        <?php $this->render('layouts/app-sidebar'); ?>

        <main class="main-content">
            <div class="content-wrapper">
                <h1 class="page-title">Мое избранное</h1>

                <section class="content-section">
                    <h3 class="section-title">Избранные уроки (<?= count($favoritedLessons) ?>)</h3>
                    <?php if (!empty($favoritedLessons)): ?>
                        <div class="answers-grid">
                            <?php foreach ($favoritedLessons as $lesson): ?>
                                <a href="/course/<?= $lesson['course_id'] ?>/lesson/<?= $lesson['id'] ?>" class="answer-card-link">
                                    <div class="answer-card">
                                        <h4><?= htmlspecialchars($lesson['course_title']) ?></h4>
                                        <p><?= htmlspecialchars($lesson['title']) ?></p>
                                        <div class="status-badge status-submitted" style="background-color: #3a3a3a; color: #e0e0e0;">Урок</div>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p>Вы еще не добавляли уроки в избранное.</p>
                    <?php endif; ?>
                </section>

            </div>
        </main>
    </div>

<?php $this->render('layouts/footer'); ?>