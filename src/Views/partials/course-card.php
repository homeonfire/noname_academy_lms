<?php
// src/Views/partials/course-card.php

// 1. Проверяем cover_url, как и договаривались
$coverUrl = $course['cover_url'] ?? null;
$defaultImageUrl = '/public/uploads/zaglushka.png'; // <--- ВСТАВЬ СЮДА ССЫЛКУ
$imageUrlToShow = $coverUrl ?: $defaultImageUrl;

// --- Остальной код остается без изменений ---
$difficultyLevels = [
    'beginner' => 'Начинающий',
    'intermediate' => 'Средний',
    'advanced' => 'Продвинутый'
];
$difficultyText = $difficultyLevels[$course['difficulty_level']] ?? 'Не указан';
$categories = !empty($course['categories']) ? explode(',', $course['categories']) : [];
$companyLogoUrl = $course['company_logo'] ?? null;
$linkPath = ($course['type'] === 'masterclass') ? 'masterclass' : 'course';

// Проверяем доступ к курсу (используем переданный hasAccess)
$hasAccess = $course['hasAccess'] ?? false;

// Определяем, бесплатный ли курс
$isFree = !empty($course['is_free']) || $course['price'] == 0;

// Определяем правильную ссылку для карточки
$cardLink = "/{$linkPath}/{$course['id']}";
if (!$isFree && !$hasAccess) {
    $cardLink = "/{$linkPath}/{$course['id']}/landing";
}
?>
<div class="course-card-wrapper" style="position: relative;">
    <a href="<?= $cardLink ?>" class="course-card-link">
        <div class="course-card">
            <div class="course-card-image-wrapper course-item-cover" style="<?= getRandomGradient() ?>">
                <img src="<?= htmlspecialchars($imageUrlToShow) ?>" alt="<?= htmlspecialchars($course['title']) ?>">
                <div class="course-card-overlay-icons">
                    <?php if ($companyLogoUrl): ?>
                        <img src="<?= htmlspecialchars($companyLogoUrl) ?>" alt="Company Logo" class="overlay-icon company-logo">
                    <?php endif; ?>
                </div>
                
                <!-- Цена курса -->
                <?php if (!$hasAccess && !$isFree): ?>
                    <div class="course-price-badge">
                        <?= number_format($course['price'], 0, ',', ' ') ?> ₽
                    </div>
                <?php endif; ?>
            </div>

            <div class="course-card-content">
                <h4 class="course-card-title"><?= htmlspecialchars($course['title']) ?></h4>
                <span class="course-card-difficulty tag-difficulty-<?= $course['difficulty_level'] ?>"><?= $difficultyText ?></span>

                <?php if (!empty($categories)): ?>
                    <div class="course-card-categories">
                        <?= htmlspecialchars(implode(' | ', array_slice($categories, 0, 3))) ?><?php if (count($categories) > 3) echo '...'; ?>
                    </div>
                <?php endif; ?>
                
                <!-- Кнопка действия -->

            </div>
        </div>
    </a>

</div>

<style>
.course-price-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 600;
}

.price-free {
    color: #27ae60;
}

.price-paid {
    color: #f39c12;
}

.course-card-action {
    margin-top: 15px;
    text-align: center;
}

.course-status-accessed {
    display: inline-block;
    background: #27ae60;
    color: white;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 500;
}

.btn-small {
    padding: 8px 16px;
    font-size: 0.9rem;
}

.btn-primary {
    background: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 6px;
    transition: background-color 0.3s ease;
}

.btn-primary:hover {
    background: #0056b3;
    color: white;
}
</style>