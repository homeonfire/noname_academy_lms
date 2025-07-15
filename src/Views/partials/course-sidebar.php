<div class="course-progress">
    <h4>Прогресс курса</h4>
    <div class="progress-bar"><div class="progress-bar-fill" style="width: 0%;"></div></div>
    <span>0% пройдено</span>
</div>
<div class="course-content-list">
    <h4>Содержание курса</h4>
    <ul>
        <?php foreach ($course['modules'] as $module): ?>
            <li class="module-item"><?= htmlspecialchars($module['title']) ?></li>
            <?php foreach ($module['lessons'] as $lesson): ?>
                <a href="/course/<?= $course['id'] ?>/lesson/<?= $lesson['id'] ?>" class="lesson-link">
                    <li class="lesson-item <?= ($activeLesson && $lesson['id'] === $activeLesson['id']) ? 'active' : '' ?>">
                        <?= htmlspecialchars($lesson['title']) ?>
                    </li>
                </a>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </ul>
</div>