<?php $this->render('layouts/app-header', ['title' => $course['title']]); ?>

<!-- Подключение дополнительных стилей для лендинга -->
<link rel="stylesheet" href="/public/assets/course-landing.css">

<div class="course-landing">
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-background">
            <div class="hero-gradient"></div>
            <div class="hero-pattern"></div>
        </div>
        
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <div class="course-badge">
                        <span class="badge-icon">🎓</span>
                        <span class="badge-text"><?= $type === 'masterclass' ? 'Мастер-класс' : 'Курс' ?></span>
                    </div>
                    
                    <h1 class="course-title"><?= htmlspecialchars($course['title']) ?></h1>
                    <p class="course-description"><?= htmlspecialchars($course['description']) ?></p>
                      
                    <div class="course-meta">
                        <div class="meta-item">
                            <div class="meta-icon">📊</div>
                            <div class="meta-content">
                                <span class="meta-label">Уровень</span>
                                <span class="meta-value">
                                    <?php
                                    $levels = [
                                        'beginner' => 'Начинающий',
                                        'intermediate' => 'Средний', 
                                        'advanced' => 'Продвинутый'
                                    ];
                                    echo $levels[$course['difficulty_level']] ?? 'Не указан';
                                    ?>
                                </span>
                            </div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-icon">📚</div>
                            <div class="meta-content">
                                <span class="meta-label">Уроков</span>
                                <span class="meta-value"><?= $totalLessons ?></span>
                            </div>
                        </div>
                        <?php if (!empty($course['categories'])): ?>
                        <div class="meta-item">
                            <div class="meta-icon">🏷️</div>
                            <div class="meta-content">
                                <span class="meta-label">Категории</span>
                                <span class="meta-value"><?= htmlspecialchars($course['categories']) ?></span>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="pricing-section">
                        <?php if (!empty($course['is_free']) || $course['price'] == 0): ?>
                            <div class="price-free">
                                <div class="price-amount">Бесплатно</div>
                                <div class="price-label">Навсегда</div>
                            </div>
                        <?php else: ?>
                            <div class="price-paid">
                                <div class="price-amount"><?= number_format($course['price'], 0, ',', ' ') ?> ₽</div>
                                <div class="price-label">Однократная оплата</div>
                            </div>
                        <?php endif; ?>
                        
                        <div class="cta-buttons">
                            <?php if (isset($_SESSION['user'])): ?>
                                <?php if ($hasAccess): ?>
                                    <a href="/<?= $type ?>/<?= $course['id'] ?>" class="btn btn-primary btn-large">
                                        <span class="btn-icon">🚀</span>
                                        Перейти к <?= $type === 'masterclass' ? 'мастер-классу' : 'курсу' ?>
                                    </a>
                                <?php else: ?>
                                    <a href="/payment/buy-course?course_id=<?= $course['id'] ?>" class="btn btn-primary btn-large">
                                        <span class="btn-icon">💳</span>
                                        Купить <?= $type === 'masterclass' ? 'мастер-класс' : 'курс' ?>
                                    </a>
                                <?php endif; ?>
                            <?php else: ?>
                                <a href="/auth/login" class="btn btn-primary btn-large">
                                    <span class="btn-icon">🔐</span>
                                    Войти и купить
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="hero-image">
                    <?php if (!empty($course['cover_url'])): ?>
                        <div class="course-cover-wrapper">
                            <img src="<?= htmlspecialchars($course['cover_url']) ?>" alt="<?= htmlspecialchars($course['title']) ?>" class="course-cover">
                            <div class="cover-overlay">
                                <div class="play-button">
                                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8 5.14V19.14L19 12.14L8 5.14Z" fill="white"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="course-cover-placeholder">
                            <div class="placeholder-icon">📖</div>
                            <span>Превью курса</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Course Program -->
    <section class="course-program">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Программа <?= $type === 'masterclass' ? 'мастер-класса' : 'курса' ?></h2>
                <p class="section-subtitle">Пошаговое изучение материала для достижения результата</p>
            </div>
            
            <?php if (!empty($course['modules'])): ?>
                <div class="modules-list">
                    <?php foreach ($course['modules'] as $index => $module): ?>
                        <div class="module-item">
                            <div class="module-header">
                                <div class="module-number"><?= $index + 1 ?></div>
                                <div class="module-info">
                                    <h3 class="module-title"><?= htmlspecialchars($module['title']) ?></h3>
                                    <span class="module-lessons-count"><?= count($module['lessons']) ?> уроков</span>
                                </div>
                                <div class="module-arrow">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M7 10L12 15L17 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            </div>
                            
                            <?php if (!empty($module['lessons'])): ?>
                                <div class="lessons-list">
                                    <?php foreach ($module['lessons'] as $lessonIndex => $lesson): ?>
                                        <div class="lesson-item">
                                            <div class="lesson-number"><?= $lessonIndex + 1 ?></div>
                                            <div class="lesson-content">
                                                <span class="lesson-title"><?= htmlspecialchars($lesson['title']) ?></span>
                                                <?php if (!empty($lesson['duration'])): ?>
                                                    <span class="lesson-duration"><?= $lesson['duration'] ?></span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="lesson-status">
                                                <div class="status-dot"></div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="no-content">
                    <div class="no-content-icon">📝</div>
                    <p>Программа курса пока не добавлена</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Course Benefits -->
    <section class="course-benefits">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Что вы получите</h2>
                <p class="section-subtitle">Все необходимое для успешного обучения</p>
            </div>
            
            <div class="benefits-grid">
                <div class="benefit-item">
                    <div class="benefit-icon">📚</div>
                    <h3>Структурированные знания</h3>
                    <p>Пошаговое изучение материала от основ до продвинутых техник</p>
                </div>
                <div class="benefit-item">
                    <div class="benefit-icon">💻</div>
                    <h3>Практические задания</h3>
                    <p>Закрепляйте знания на реальных проектах и получайте обратную связь</p>
                </div>
                <div class="benefit-item">
                    <div class="benefit-icon">🎯</div>
                    <h3>Домашние задания</h3>
                    <p>Выполняйте задания и получайте оценку от преподавателей</p>
                </div>
                <div class="benefit-item">
                    <div class="benefit-icon">📱</div>
                    <h3>Доступ навсегда</h3>
                    <p>Изучайте в удобном темпе, возвращайтесь к материалам в любое время</p>
                </div>
                <div class="benefit-item">
                    <div class="benefit-icon">🎓</div>
                    <h3>Сертификат</h3>
                    <p>Получите сертификат об окончании курса</p>
                </div>
                <div class="benefit-item">
                    <div class="benefit-icon">👥</div>
                    <h3>Сообщество</h3>
                    <p>Присоединяйтесь к сообществу единомышленников</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA -->
    <section class="final-cta">
        <div class="cta-background">
            <div class="cta-gradient"></div>
            <div class="cta-pattern"></div>
        </div>
        
        <div class="container">
            <div class="cta-content">
                <h2>Готовы начать обучение?</h2>
                <p>Присоединяйтесь к тысячам студентов, которые уже изменили свою карьеру</p>
                
                <div class="cta-buttons">
                    <?php if (isset($_SESSION['user'])): ?>
                        <?php if ($hasAccess): ?>
                            <a href="/<?= $type ?>/<?= $course['id'] ?>" class="btn btn-primary btn-large">
                                <span class="btn-icon">🚀</span>
                                Перейти к <?= $type === 'masterclass' ? 'мастер-классу' : 'курсу' ?>
                            </a>
                        <?php else: ?>
                            <a href="/payment/buy-course?course_id=<?= $course['id'] ?>" class="btn btn-primary btn-large">
                                <span class="btn-icon">💳</span>
                                Купить <?= $type === 'masterclass' ? 'мастер-класс' : 'курс' ?>
                            </a>
                        <?php endif; ?>
                    <?php else: ?>
                        <a href="/auth/login" class="btn btn-primary btn-large">
                            <span class="btn-icon">🔐</span>
                            Войти и купить
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
/* =============================================== */
/* COURSE LANDING STYLES - AI FIRE LMS STYLE       */
/* =============================================== */

.course-landing {
    background: var(--background-color);
    color: var(--primary-text-color);
    font-family: 'Raleway', sans-serif;
}

/* Hero Section */
.hero-section {
    position: relative;
    padding: 120px 0 80px;
    overflow: hidden;
}

.hero-background {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 1;
}

.hero-gradient {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, #8473FF 0%, #5B4B8A 50%, #3A2E5A 100%);
}

.hero-pattern {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: 
        radial-gradient(circle at 20% 80%, rgba(255,255,255,0.1) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(255,255,255,0.1) 0%, transparent 50%),
        radial-gradient(circle at 40% 40%, rgba(255,255,255,0.05) 0%, transparent 50%);
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
    position: relative;
    z-index: 2;
}

.hero-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 80px;
    align-items: center;
}

.course-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    padding: 8px 16px;
    border-radius: 20px;
    margin-bottom: 24px;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.badge-icon {
    font-size: 16px;
}

.badge-text {
    font-size: 14px;
    font-weight: 500;
    color: rgba(255, 255, 255, 0.9);
}

.course-title {
    font-size: 3.5rem;
    font-weight: 700;
    margin-bottom: 24px;
    line-height: 1.1;
    background: linear-gradient(135deg, #FFFFFF 0%, #E0E0E0 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.course-description {
    font-size: 1.25rem;
    line-height: 1.6;
    margin-bottom: 40px;
    color: rgba(255, 255, 255, 0.9);
    font-weight: 400;
}

.course-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 24px;
    margin-bottom: 40px;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 12px;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    padding: 16px 20px;
    border-radius: 12px;
    border: 1px solid rgba(255, 255, 255, 0.2);
    min-width: 160px;
}

.meta-icon {
    font-size: 20px;
    opacity: 0.8;
}

.meta-content {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.meta-label {
    font-size: 12px;
    color: rgba(255, 255, 255, 0.7);
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.meta-value {
    font-weight: 600;
    font-size: 14px;
    color: rgba(255, 255, 255, 0.95);
}

.pricing-section {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(20px);
    padding: 32px;
    border-radius: 20px;
    border: 1px solid rgba(255, 255, 255, 0.2);
    margin-bottom: 32px;
}

.price-free, .price-paid {
    text-align: center;
    margin-bottom: 24px;
}

.price-amount {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 8px;
    background: linear-gradient(135deg, #FFFFFF 0%, #E0E0E0 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.price-label {
    font-size: 16px;
    color: rgba(255, 255, 255, 0.8);
    font-weight: 500;
}

.cta-buttons {
    text-align: center;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    padding: 18px 32px;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    font-size: 16px;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    position: relative;
    overflow: hidden;
}

.btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

.btn:hover::before {
    left: 100%;
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary-accent-color) 0%, var(--primary-accent-hover-color) 100%);
    color: white;
    box-shadow: 0 8px 32px rgba(132, 115, 255, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 40px rgba(132, 115, 255, 0.4);
}

.btn-large {
    padding: 20px 40px;
    font-size: 18px;
}

.btn-icon {
    font-size: 20px;
}

.hero-image {
    text-align: center;
}

.course-cover-wrapper {
    position: relative;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
    transition: transform 0.3s ease;
}

.course-cover-wrapper:hover {
    transform: translateY(-5px);
}

.course-cover {
    width: 100%;
    height: auto;
    display: block;
}

.cover-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.course-cover-wrapper:hover .cover-overlay {
    opacity: 1;
}

.play-button {
    width: 80px;
    height: 80px;
    background: rgba(255, 255, 255, 0.9);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.play-button:hover {
    background: white;
    transform: scale(1.1);
}

.course-cover-placeholder {
    width: 100%;
    height: 400px;
    background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
    border-radius: 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    border: 2px dashed rgba(255, 255, 255, 0.3);
}

.placeholder-icon {
    font-size: 48px;
    margin-bottom: 16px;
    opacity: 0.7;
}

.course-cover-placeholder span {
    font-size: 16px;
    color: rgba(255, 255, 255, 0.7);
    font-weight: 500;
}

/* Course Program Section */
.course-program {
    padding: 100px 0;
    background: var(--container-color);
}

.section-header {
    text-align: center;
    margin-bottom: 60px;
}

.section-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 16px;
    background: linear-gradient(135deg, var(--primary-accent-color) 0%, var(--primary-accent-hover-color) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.section-subtitle {
    font-size: 1.1rem;
    color: var(--secondary-text-color);
    max-width: 600px;
    margin: 0 auto;
}

.modules-list {
    max-width: 900px;
    margin: 0 auto;
}

.module-item {
    background: var(--background-color);
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 20px;
    border: 1px solid var(--border-color);
    transition: all 0.3s ease;
}

.module-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
    border-color: var(--primary-accent-color);
}

.module-header {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 20px;
    cursor: pointer;
    user-select: none;
}

.module-number {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, var(--primary-accent-color) 0%, var(--primary-accent-hover-color) 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 16px;
    color: white;
    flex-shrink: 0;
}

.module-info {
    flex: 1;
}

.module-title {
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 4px;
    color: var(--primary-text-color);
}

.module-lessons-count {
    font-size: 14px;
    color: var(--secondary-text-color);
    font-weight: 500;
}

.module-arrow {
    color: var(--secondary-text-color);
    transition: transform 0.3s ease;
}

.module-item.active .module-arrow {
    transform: rotate(90deg);
}

.lessons-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
    padding-left: 56px;
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease, opacity 0.3s ease;
    opacity: 0;
}

.module-item.active .lessons-list {
    max-height: 500px;
    opacity: 1;
    margin-top: 20px;
}

.lesson-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    background: rgba(255, 255, 255, 0.02);
    border-radius: 8px;
    border: 1px solid rgba(255, 255, 255, 0.05);
    transition: all 0.3s ease;
}

.lesson-item:hover {
    background: rgba(255, 255, 255, 0.05);
    border-color: rgba(255, 255, 255, 0.1);
}

.lesson-number {
    width: 24px;
    height: 24px;
    background: rgba(132, 115, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: 600;
    color: var(--primary-accent-color);
    flex-shrink: 0;
}

.lesson-content {
    flex: 1;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.lesson-title {
    font-weight: 500;
    color: var(--primary-text-color);
    font-size: 14px;
}

.lesson-duration {
    font-size: 12px;
    color: var(--secondary-text-color);
    font-weight: 500;
}

.lesson-status {
    display: flex;
    align-items: center;
}

.status-dot {
    width: 8px;
    height: 8px;
    background: var(--secondary-text-color);
    border-radius: 50%;
    opacity: 0.5;
}

.no-content {
    text-align: center;
    padding: 60px 20px;
    color: var(--secondary-text-color);
}

.no-content-icon {
    font-size: 48px;
    margin-bottom: 16px;
    opacity: 0.5;
}

/* Course Benefits Section */
.course-benefits {
    padding: 100px 0;
    background: var(--background-color);
}

.benefits-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
    margin-top: 60px;
}

.benefit-item {
    background: var(--container-color);
    padding: 32px;
    border-radius: 16px;
    text-align: center;
    border: 1px solid var(--border-color);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.benefit-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(135deg, var(--primary-accent-color) 0%, var(--primary-accent-hover-color) 100%);
    transform: scaleX(0);
    transition: transform 0.3s ease;
}

.benefit-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.2);
    border-color: var(--primary-accent-color);
}

.benefit-item:hover::before {
    transform: scaleX(1);
}

.benefit-icon {
    font-size: 3rem;
    margin-bottom: 24px;
    display: block;
}

.benefit-item h3 {
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 16px;
    color: var(--primary-text-color);
}

.benefit-item p {
    color: var(--secondary-text-color);
    line-height: 1.6;
    font-size: 14px;
}

/* Final CTA Section */
.final-cta {
    position: relative;
    padding: 100px 0;
    overflow: hidden;
}

.cta-background {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 1;
}

.cta-gradient {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, #3A2E5A 0%, #5B4B8A 50%, #8473FF 100%);
}

.cta-pattern {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: 
        radial-gradient(circle at 80% 80%, rgba(255,255,255,0.1) 0%, transparent 50%),
        radial-gradient(circle at 20% 20%, rgba(255,255,255,0.1) 0%, transparent 50%);
}

.cta-content {
    position: relative;
    z-index: 2;
    text-align: center;
    color: white;
}

.cta-content h2 {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 24px;
    background: linear-gradient(135deg, #FFFFFF 0%, #E0E0E0 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.cta-content p {
    font-size: 1.25rem;
    margin-bottom: 40px;
    color: rgba(255, 255, 255, 0.9);
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

/* Responsive Design */
@media (max-width: 768px) {
    .hero-content {
        grid-template-columns: 1fr;
        gap: 40px;
    }
    
    .course-title {
        font-size: 2.5rem;
    }
    
    .course-meta {
        flex-direction: column;
        gap: 16px;
    }
    
    .meta-item {
        min-width: auto;
    }
    
    .benefits-grid {
        grid-template-columns: 1fr;
    }
    
    .cta-content h2 {
        font-size: 2rem;
    }
    
    .section-title {
        font-size: 2rem;
    }
    
    .lessons-list {
        padding-left: 20px;
    }
}

@media (max-width: 480px) {
    .hero-section {
        padding: 80px 0 60px;
    }
    
    .course-title {
        font-size: 2rem;
    }
    
    .course-description {
        font-size: 1.1rem;
    }
    
    .pricing-section {
        padding: 24px;
    }
    
    .price-amount {
        font-size: 2.5rem;
    }
    
    .btn-large {
        padding: 16px 24px;
        font-size: 16px;
    }
}
</style>

<?php $this->render('layouts/footer'); ?>

<!-- Подключение JavaScript для интерактивных эффектов -->
<script src="/public/assets/course-landing.js"></script> 