<?php
$this->render('layouts/app-header', ['title' => $title]);

$embedUrl = $activeLesson ? getVideoEmbedUrl($activeLesson['content_url']) : null;
// Поле content_text больше не используется для основного текста, теперь это content_json
// $hasText = $activeLesson && !empty(trim($activeLesson['content_text'] ?? ''));
$hasEditorJsContent = $activeLesson && !empty(trim($activeLesson['content_json'] ?? '')) && ($activeLesson['content_json'] !== '{}'); // Проверяем, что контент не пустой JSON
$isSubmitted = !empty($userAnswer);
$isLocked = $isSubmitted && in_array($userAnswer['status'], ['submitted', 'checked']);
$isLessonFavorite = $activeLesson && isset($favoritedLessonIds) && in_array($activeLesson['id'], $favoritedLessonIds);
?>

    <div class="app-layout">
        <?php $this->render('layouts/app-sidebar'); ?>

        <main class="main-content">
            <div class="course-page-wrapper">
                <div class="lesson-content-area">
                    <div class="lesson-header">
                        <a href="/dashboard" class="back-link"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left w-4 h-4"><path d="m15 18-6-6 6-6"></path></svg> Назад к <?= ($type === 'masterclass') ? 'мастер-классам' : 'курсам' ?></a>
                        <h1 class="lesson-title">
                            <?php if ($activeLesson && $type === 'course'): ?>
                                <button
                                        class="favorite-toggle-btn <?= $isLessonFavorite ? 'active' : '' ?>"
                                        data-item-id="<?= $activeLesson['id'] ?>"
                                        data-item-type="lesson"
                                        title="Добавить урок в избранное">
                                    <svg viewBox="0 0 24 24"><path d="M12,21.35L10.55,20.03C5.4,15.36 2,12.27 2,8.5C2,5.41 4.42,3 7.5,3C9.24,3 10.91,3.81 12,5.08C13.09,3.81 14.76,3 16.5,3C19.58,3 22,5.41 22,8.5C22,12.27 18.6,15.36 13.45,20.03L12,21.35Z"></path></svg>
                                </button>
                            <?php endif; ?>
                            <span>
                            <?= htmlspecialchars($course['title']) ?>:
                            <span class="lesson-subtitle"><?= $activeLesson ? htmlspecialchars($activeLesson['title']) : 'Выберите урок' ?></span>
                        </span>
                        </h1>
                    </div>

                    <?php if (isset($_SESSION['error_message'])): ?>
                        <div class="alert alert-danger" style="margin-bottom: 24px;">
                            <?= htmlspecialchars($_SESSION['error_message']); unset($_SESSION['error_message']); ?>
                        </div>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['success_message'])): ?>
                        <div class="alert alert-success" style="margin-bottom: 24px;">
                            <?= htmlspecialchars($_SESSION['success_message']); unset($_SESSION['success_message']); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($isSubmitted): ?>
                        <?php
                        $statusText = ''; $statusClass = ''; $comment = !empty($userAnswer['comment']) ? htmlspecialchars($userAnswer['comment']) : '';
                        switch ($userAnswer['status']) {
                            case 'checked': $statusText = 'Принято'; $statusClass = 'alert-success'; break;
                            case 'rejected': $statusText = 'Отклонено (можно пересдать)'; $statusClass = 'alert-danger'; break;
                            default: $statusText = 'Отправлено на проверку'; $statusClass = 'alert-info'; break;
                        }
                        ?>
                        <div class="alert <?= $statusClass ?>" style="margin-bottom: 24px;">
                            <span class="alert-icon"><?php if($userAnswer['status'] === 'checked') echo '✅'; elseif($userAnswer['status'] === 'rejected') echo '❌'; else echo 'ℹ️'; ?></span>
                            <div>
                                <p><strong>Статус:</strong> <?= $statusText ?></p>
                                <?php if ($comment): ?>
                                    <p style="margin-top: 8px;"><strong>Комментарий преподавателя:</strong> <?= $comment ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="lesson-viewer">
                        <?php if ($embedUrl): ?>
                            <div class="video-player-responsive">
                                <iframe src="<?= $embedUrl ?>" frameborder="0" allow="autoplay; fullscreen; picture-in-picture; encrypted-media; gyroscope; accelerometer; clipboard-write; screen-wake-lock;" allowfullscreen></iframe>
                            </div>
                        <?php elseif (!$hasEditorJsContent): // Если нет видео и нет контента Editor.js, показываем заглушку ?>
                            <p>Контент для этого урока еще не добавлен.</p>
                        <?php endif; ?>
                    </div>
                    <?php if ($hasEditorJsContent): ?>
                        <div class="lesson-description" style="margin-top: 20px;">
                            <h3>Содержание урока</h3>
                            <div id="editorjs-viewer" class="editorjs-viewer-container"></div>
                        </div>
                    <?php endif; ?>
                    <?php
                    // Старый блок content_text, если он был отдельно.
                    // Если content_text не используется, этот блок можно убрать или оставить закомментированным.
                    // Если вы хотите, чтобы старый content_text отображался ВМЕСТЕ с Editor.js (если есть),
                    // то $hasText нужно определить снова и не убирать этот блок.
                    // Сейчас предполагается, что content_json заменяет content_text.
                    /*
                    <?php if ($embedUrl && $hasText): ?>
                        <div class="lesson-description">
                            <h3>Материалы к уроку</h3>
                            <p><?= nl2br(htmlspecialchars($activeLesson['content_text'])) ?></p>
                        </div>
                    <?php endif; ?>
                    */
                    ?>

                    <?php if (!empty($course['description'])): ?>
                        <div class="lesson-description">
                            <h3>О курсе: <?= htmlspecialchars($course['title']) ?></h3>
                            <p><?= htmlspecialchars($course['description']) ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if ($homework && !empty($homework['questions'])): ?>
                        <div class="lesson-description">
                            <h3>Домашнее задание</h3>
                            <?php
                            $questions = json_decode($homework['questions'], true);
                            $answers = $isSubmitted ? json_decode($userAnswer['answers'], true) : [];
                            ?>
                            <form action="/homework/submit" method="POST">
                                <?= CSRF::getTokenField() ?>
                                <input type="hidden" name="homework_id" value="<?= $homework['id'] ?>">
                                <input type="hidden" name="course_id" value="<?= $course['id'] ?>">
                                <input type="hidden" name="lesson_id" value="<?= $activeLesson['id'] ?>">

                                <?php foreach ($questions as $index => $item): ?>
                                    <div class="homework-group">
                                        <label>Вопрос <?= $index + 1 ?>:</label>
                                        <p><?= htmlspecialchars($item['q']) ?></p>
                                        <textarea name="answers[]" placeholder="Ваш ответ..." <?= $isLocked ? 'readonly' : '' ?>><?= $isSubmitted ? htmlspecialchars($answers[$index]['a'] ?? '') : '' ?></textarea>
                                    </div>
                                <?php endforeach; ?>

                                <?php if (!$isLocked): ?>
                                    <button type="submit" class="btn-primary" style="margin-top: 20px;"><?= $isSubmitted ? 'Пересдать на проверку' : 'Сдать на проверку' ?></button>
                                <?php endif; ?>
                            </form>
                        </div>
                    <?php endif; ?>

                    <?php
                    if ($activeLesson && !$homework && !in_array($activeLesson['id'], $completedLessonIds)):
                        ?>
                        <div class="complete-lesson-wrapper">
                            <form action="/lesson/complete/<?= $activeLesson['id'] ?>" method="POST">
                                <?= CSRF::getTokenField() ?>
                                <button type="submit" class="btn-primary">Отметить как пройденный</button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="course-sidebar">
                    <?php if (!$hasAccess): ?>
                        <!-- Блок покупки курса -->
                        <div class="course-purchase-block" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 12px; margin-bottom: 20px;">
                            <h4 style="margin: 0 0 15px 0; font-size: 18px;">Получите доступ к курсу</h4>
                            
                            <?php if ($course['is_free']): ?>
                                <p style="margin: 0 0 15px 0; opacity: 0.9;">Этот курс бесплатный</p>
                                <a href="/payment/buy-course?course_id=<?= $course['id'] ?>" 
                                   class="btn-primary" 
                                   style="display: block; text-align: center; background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); color: white; text-decoration: none; padding: 12px; border-radius: 8px; transition: all 0.3s ease;">
                                    Получить бесплатно
                                </a>
                            <?php else: ?>
                                <div style="margin: 0 0 15px 0;">
                                    <span style="font-size: 24px; font-weight: bold;"><?= number_format($course['price'], 0, ',', ' ') ?> ₽</span>
                                    <span style="opacity: 0.8; margin-left: 5px;">за курс</span>
                                </div>
                                <a href="/payment/buy-course?course_id=<?= $course['id'] ?>" 
                                   class="btn-primary" 
                                   style="display: block; text-align: center; background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); color: white; text-decoration: none; padding: 12px; border-radius: 8px; transition: all 0.3s ease;">
                                    Купить курс
                                </a>
                            <?php endif; ?>
                            
                            <div style="margin-top: 15px; font-size: 12px; opacity: 0.8;">
                                <div style="margin-bottom: 5px;">✓ Доступ навсегда</div>
                                <div style="margin-bottom: 5px;">✓ Все уроки курса</div>
                                <div style="margin-bottom: 5px;">✓ Домашние задания</div>
                                <div>✓ Сертификат по окончании</div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($type === 'course' && $hasAccess): ?>
                        <div class="course-progress">
                            <h4>Прогресс курса</h4>
                            <div class="progress-bar"><div class="progress-bar-fill" style="width: <?= $progressPercentage ?>%;"></div></div>
                            <span><?= $progressPercentage ?>% пройдено</span>
                        </div>
                    <?php endif; ?>

                    <div class="course-content-list">
                        <h4><?= ($type === 'masterclass') ? 'Программа мастер-класса' : 'Содержание курса' ?></h4>
                        <div class="module-sidebar">
                            <?php foreach ($course['modules'] as $module): ?>
                                <div class="module-item">
                                    <p class="module-name"><?= htmlspecialchars($module['title']) ?></p>
                                <?php foreach ($module['lessons'] as $lesson): ?>
                                    <?php
                                    $linkPath = ($type === 'masterclass') ? 'masterclass' : 'course';
                                    ?>
                                    <a href="/<?= $linkPath ?>/<?= $course['id'] ?>/lesson/<?= $lesson['id'] ?>" class="lesson-link <?= ($activeLesson && $lesson['id'] === $activeLesson['id']) ? 'active' : '' ?>">
                                        <p class="lesson-item <?= ($activeLesson && $lesson['id'] === $activeLesson['id']) ? 'active' : '' ?>">
                                            <?php if (in_array($lesson['id'], $completedLessonIds)): ?>
                                                <span class="lesson-completed-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check ml-2 w-4 h-4"><path d="M20 6 9 17l-5-5"></path></svg>
</svg>
</span>
                                            <?php endif; ?>
                                            <?= htmlspecialchars($lesson['title']) ?>
                                        </p>
                                    </a>
                                <?php endforeach; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <script src="/public/assets/editor.js" defer></script>
    <script src="/public/assets/header.js" defer></script>
    <script src="/public/assets/list.js" defer></script>
    <script>
        // Данные для Editor.js (парсим JSON-строку)
        const editorJsData = <?= !empty($activeLesson['content_json']) ? $activeLesson['content_json'] : '{}' ?>;

        // Функция для инициализации Editor.js в режиме только для чтения
        function initializeEditorViewer() {
            const editorViewerHolder = document.getElementById('editorjs-viewer');
            // Проверяем наличие элемента и что данные не являются пустым объектом
            if (editorViewerHolder && Object.keys(editorJsData).length > 0) {
                try {
                    const editorViewer = new EditorJS({
                        holder: 'editorjs-viewer',
                        readOnly: true, // Включаем режим только для чтения
                        data: editorJsData,
                        minHeight: 100,
                        style: 'padding-bottom: 0px;',
                        tools: { // Обязательно перечислите все используемые блоки, даже в режиме readOnly
                            header: Header,
                            list: EditorjsList
                        }
                    });
                    console.log("Editor.js viewer initialized successfully.");
                } catch (e) {
                    console.error("Error initializing Editor.js viewer:", e);
                    // Можно скрыть элемент, если произошла ошибка инициализации
                    editorViewerHolder.style.display = 'none';
                }
            } else if (editorViewerHolder) {
                // Если нет контента Editor.js, скрываем контейнер
                editorViewerHolder.style.display = 'none';
                console.log("No Editor.js content to display for viewer, hiding container.");
            }
        }

        // Ждем загрузки всех скриптов (включая defer-скрипты Editor.js)
        // и DOM-дерева
        window.addEventListener('load', () => {
            initializeEditorViewer();
        });
    </script>
<?php $this->render('layouts/footer'); ?>