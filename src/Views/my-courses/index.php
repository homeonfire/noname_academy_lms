<?php $this->render('layouts/app-header', ['title' => $title]); ?>

    <div class="app-layout">
        <?php $this->render('layouts/app-sidebar'); ?>

        <main class="main-content">
            <div class="content-wrapper">
                <div class="page-header">
                    <h1 class="page-title"><?= htmlspecialchars($title) ?></h1>
                </div>

                <?php if (!empty($courses)): ?>
                <section class="content-section">
                    <div class="catalog-grid">
                        <?php foreach ($courses as $course): ?>
                            <?php
                            // Мы передаем массив $favoritedCourseIds из контроллера,
                            // поэтому карточка будет знать, какие курсы в избранном.
                            $this->render('partials/course-card', [
                                'course' => $course,
                                'favoritedCourseIds' => $favoritedCourseIds ?? []
                            ]);
                            ?>
                        <?php endforeach; ?>
                    </div>
                </section>
                <?php else: ?>
                    <div class="placeholder-text">
                        <p>Вы пока не начали ни одного курса.</p>
                        <a href="/courses" class="btn btn-primary">Перейти к курсам</a>
                    </div>
                <?php endif; ?>

            </div>
        </main>
    </div>

<?php $this->render('layouts/footer'); ?>