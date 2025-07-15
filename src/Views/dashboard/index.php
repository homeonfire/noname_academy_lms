<?php $this->render('layouts/app-header', ['title' => $title]); ?>

<div class="app-layout">
    <?php $this->render('layouts/app-sidebar'); ?>

    <main class="main-content">
        <div class="content-wrapper">
            <div class="page-header">
                <h1 class="page-title">Добро пожаловать, <?= htmlspecialchars($_SESSION['user']['first_name'] ?? 'студент') ?>!</h1>
                <p class="page-subtitle">Готовы к новым знаниям?</p>
            </div>

            <?php if (!empty($startedCourses)): ?>
                <section class="content-section">
                    <div class="section-header">
                        <h3 class="section-title">Продолжить обучение</h3>
                        <div class="slider-controls">
                            <button class="slider-btn prev-btn"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left h-5 w-5"><path d="m15 18-6-6 6-6"></path></svg></button>
                            <button class="slider-btn next-btn"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right h-5 w-5"><path d="m9 18 6-6-6-6"></path></svg></button>
                        </div>
                    </div>
                    <div class="catalog-grid">
                        <?php foreach ($startedCourses as $course): ?>
                            <?php $this->render('partials/course-card', [
                                'course' => $course,
                            ]); ?>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php elseif ($featuredCourse): ?>
                <div class="promo-block" style="position: relative;">
                    <div class="promo-thumbnail">
                        <img src="https://placehold.co/400x225/2A2A2A/FFFFFF?text=<?= urlencode(substr($featuredCourse['title'], 0, 10)) ?>" alt="<?= htmlspecialchars($featuredCourse['title']) ?>">
                        <span class="promo-badge">ПОЛУЧИТЬ СЕРТИФИКАТ</span>
                    </div>
                    <div class="promo-details">
                        <h2><?= htmlspecialchars($featuredCourse['title']) ?></h2>
                        <p><?= htmlspecialchars($featuredCourse['description']) ?></p>
                        <a href="/course/<?= $featuredCourse['id'] ?>" class="btn-primary">Начать курс</a>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (!empty($latestCourses)): ?>
                <section class="content-section">
                    <div class="section-header">
                        <h3 class="section-title">Новые курсы</h3>
                        <div class="slider-controls">
                            <button class="slider-btn prev-btn"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left h-5 w-5"><path d="m15 18-6-6 6-6"></path></svg></button>
                            <button class="slider-btn next-btn"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right h-5 w-5"><path d="m9 18 6-6-6-6"></path></svg></button>
                        </div>
                    </div>
                    <div class="catalog-grid">
                        <?php foreach ($latestCourses as $course): ?>
                            <?php $this->render('partials/course-card', [
                                'course' => $course,
                            ]); ?>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>
        </div>
    </main>
</div>

<?php $this->render('layouts/footer'); ?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sections = document.querySelectorAll('.content-section');

        sections.forEach(section => {
            const slider = section.querySelector('.catalog-grid');
            const prevBtn = section.querySelector('.prev-btn');
            const nextBtn = section.querySelector('.next-btn');

            if (!slider || !prevBtn || !nextBtn) return;

            const scrollAmount = 320; // Ширина одной карточки (300px) + отступ (20px)

            nextBtn.addEventListener('click', () => {
                slider.scrollBy({ left: scrollAmount, behavior: 'smooth' });
            });

            prevBtn.addEventListener('click', () => {
                slider.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
            });
        });
    });
</script>