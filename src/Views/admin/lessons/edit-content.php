<?php $this->render('layouts/admin-header', ['title' => $title]); ?>

    <div class="admin-layout">
        <?php $this->render('layouts/admin-sidebar'); ?>

        <main class="main-content">
            <div class="content-wrapper">
                <div class="admin-header">
                    <h1 class="page-title">Контент урока: "<?= htmlspecialchars($lesson['title']) ?>"</h1>
                    <a href="javascript:history.back()" class="btn btn-secondary">Назад</a>
                </div>

                <div class="admin-card">
                    <form id="content-form" action="/admin/lessons/save-content/<?= $lesson['id'] ?>" method="POST" class="admin-form">
                        <?= CSRF::getTokenField() ?>
                        <input type="hidden" name="course_id" value="<?= $_GET['course_id'] ?? '' ?>">

                        <div class="input-group">
                            <label for="content_url">Ссылка на видео</label>
                            <input type="url" id="content_url" name="content_url" value="<?= htmlspecialchars($lesson['content_url'] ?? '') ?>" placeholder="Оставьте пустым, если нет видео">
                        </div>

                        <div class="input-group">
                            <label>Текст урока</label>
                            <div id="editorjs" style="border: 1px solid #e0e0e0; border-radius: 8px; padding: 10px; min-height: 300px;background: #fafafa;"></div>
                            <input type="hidden" name="content_json" id="content_json_output">
                        </div>

                        <div class="input-group">
                            <label>Вопросы для домашнего задания</label>
                            <div id="homework-questions-container">
                                <?php
                                $questions = $homework ? json_decode($homework['questions'], true) : [];
                                if (!empty($questions)) {
                                    foreach ($questions as $item) {
                                        echo '<div class="homework-question-row" style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">';
                                        echo '<input type="text" name="homework_questions[]" class="homework-question-input" value="'.htmlspecialchars($item['q']).'" placeholder="Введите вопрос">';
                                        echo '<button type="button" class="btn btn-sm btn-danger remove-question-btn" title="Удалить вопрос" style="padding: 4px 10px;">&times;</button>';
                                        echo '</div>';
                                    }
                                }
                                ?>
                            </div>
                            <button type="button" id="add-homework-question" class="btn btn-secondary" style="margin-top:10px;">+ Добавить вопрос</button>
                        </div>

                        <button type="submit" class="btn btn-primary">Сохранить контент</button>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script>
        window.editorData = <?= !empty($lesson['content_json']) ? $lesson['content_json'] : '{}' ?>;
    </script>

    <script>
        document.getElementById('add-homework-question').addEventListener('click', function() {
            const container = document.getElementById('homework-questions-container');
            const div = document.createElement('div');
            div.className = 'homework-question-row';
            div.style.display = 'flex';
            div.style.alignItems = 'center';
            div.style.gap = '10px';
            div.style.marginBottom = '8px';
            div.innerHTML = '<input type="text" name="homework_questions[]" class="homework-question-input" placeholder="Введите вопрос">' +
                '<button type="button" class="btn btn-sm btn-danger remove-question-btn" title="Удалить вопрос" style="padding: 4px 10px;">&times;</button>';
            container.appendChild(div);
        });

        // Делегирование для удаления (работает и для новых, и для старых)
        document.getElementById('homework-questions-container').addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-question-btn')) {
                e.target.closest('.homework-question-row').remove();
            }
        });
    </script>
<?php $this->render('layouts/footer'); ?>