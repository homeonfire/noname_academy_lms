<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($course['title']) ?> - Онлайн-курс</title>

    <link rel="stylesheet" href="/public/assets/css/landing.css">
    <script src="/public/assets/js/landing.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>

<div class="landing-wrapper">
    <header class="hero-section">
        <div class="hero-shape shape-1"></div>
        <div class="hero-shape shape-2"></div>
        <div class="hero-shape shape-3"></div>

        <div class="hero-content">
            <div class="hero-text">
                <span class="hero-pre-title">Онлайн-курс</span>
                <h1 class="hero-title"><?= htmlspecialchars($course['title']) ?></h1>
                <div class="hero-buttons">
                    <a href="/payment/initiate?course_id=<?= $course['id'] ?>" class="btn btn-primary btn-lg">Начать обучение</a>
                    <a href="#program" class="btn btn-secondary btn-lg">Программа курса</a>
                </div>
            </div>
            <div class="hero-image-wrapper">
                <img src="<?= htmlspecialchars($course['cover_url'] ?? '/assets/images/placeholder.jpg') ?>" alt="<?= htmlspecialchars($course['title']) ?>" class="hero-image">
            </div>
        </div>
    </header>
    <main class="landing-main-content">
        <section class="course-info-block">
            <div class="course-info-author">
                <img src="<?= htmlspecialchars($course['author_avatar_path'] ?? '/public/assets/images/default-avatar.png') ?>" alt="Фото эксперта">
                <div class="author-name-title">
                    <span class="author-name"><?= htmlspecialchars($course['author_first_name'] . ' ' . $course['author_last_name']) ?></span>
                    <span class="author-title">Эксперт</span>
                </div>
            </div>
            <div class="course-info-main">
                <h2 class="course-info-title"><?= htmlspecialchars($course['title']) ?></h2>
                <p class="course-info-description">
                    Здесь должно быть более развернутое описание курса, которое мы пока не получаем из БД. Оно объясняет ключевые преимущества и результаты обучения.
                </p>
            </div>
            <div class="course-info-rating">
                <div class="rating-stars">★★★★☆</div>
                <span class="rating-value">4.8</span>
                <span class="rating-reviews">(84 отзыва)</span>
            </div>
        </section>
        <section id="program" class="course-program-section">
            <h2 class="section-title">Содержание курса</h2>
            <div class="accordion">

                <?php if (!empty($course['modules'])): ?>
                    <?php foreach ($course['modules'] as $module): ?>
                        <div class="accordion-item">
                            <button class="accordion-header">
                                <span class="module-title"><?= htmlspecialchars($module['title']) ?></span>
                                <span class="accordion-icon"></span>
                            </button>
                            <div class="accordion-content">
                                <ul class="lessons-list">
                                    <?php if (!empty($module['lessons'])): ?>
                                        <?php foreach ($module['lessons'] as $lesson): ?>
                                            <li class="lesson-item">
                                                <span class="lesson-number"><?= $lesson['order_number'] ?></span>
                                                <?= htmlspecialchars($lesson['title']) ?>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <li class="lesson-item-empty">Уроков в этом модуле пока нет.</li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Программа этого курса скоро появится.</p>
                <?php endif; ?>

            </div>
        </section>
    </main>
    <main id="program">
    </main>

</div>

</body>
</html>