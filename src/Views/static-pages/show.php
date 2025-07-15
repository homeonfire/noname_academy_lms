<?php $this->render('layouts/app-header', ['title' => $title]); ?>

    <div class="app-layout">
        <?php $this->render('layouts/app-sidebar'); ?>

        <main class="main-content">
            <div class="content-wrapper">
                <div class="static-page-header">
                    <h1 class="page-title"><?= htmlspecialchars($page['title']) ?></h1>
                    <div class="page-meta">
                        <small>Последнее обновление: <?= date('d.m.Y H:i', strtotime($page['updated_at'])) ?></small>
                    </div>
                </div>

                <div class="static-page-content">
                    <?php
                    // Проверяем наличие контента Editor.js
                    $hasEditorJsContent = !empty(trim($page['content'] ?? '')) && ($page['content'] !== '{}');
                    
                    if ($hasEditorJsContent):
                        // Парсим JSON и отображаем через Editor.js viewer
                        ?>
                        <div id="editorjs-viewer" class="editorjs-viewer-container"></div>
                    <?php else: ?>
                        <p>Контент страницы пока не добавлен.</p>
                    <?php endif; ?>
                </div>

                <script>
                    // Данные для Editor.js (парсим JSON-строку)
                    const editorJsData = <?= !empty($page['content']) ? $page['content'] : '{}' ?>;

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
            </div>
        </main>
    </div>

    <script src="/public/assets/editor.js" defer></script>
    <script src="/public/assets/header.js" defer></script>
    <script src="/public/assets/list.js" defer></script>

<?php $this->render('layouts/footer'); ?> 