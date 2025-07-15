<?php $this->render('layouts/admin-header', ['title' => $title]); ?>

    <div class="admin-layout">
        <?php $this->render('layouts/admin-sidebar'); ?>

        <main class="main-content">
            <div class="content-wrapper">
                <div class="admin-header">
                    <h1 class="page-title"><?= $title ?></h1>
                    <a href="/admin/static-pages" class="btn btn-secondary">Назад к списку</a>
                </div>

                <div class="admin-card">
                    <?php if (isset($_SESSION['error_message'])): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error_message']); unset($_SESSION['error_message']); ?></div>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['success_message'])): ?>
                        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success_message']); unset($_SESSION['success_message']); ?></div>
                    <?php endif; ?>

                    <form action="/admin/static-pages/update/<?= htmlspecialchars($page['slug']) ?>" method="POST" class="admin-form" id="content-form">
                        <?= CSRF::getTokenField() ?>
                        
                        <div class="input-group">
                            <label for="title">Название страницы</label>
                            <input type="text" id="title" name="title" value="<?= htmlspecialchars($page['title']) ?>" required>
                        </div>

                        <div class="input-group">
                            <label>Содержимое страницы</label>
                            <div id="editorjs" style="border: 1px solid #e0e0e0; border-radius: 8px; padding: 10px; min-height: 300px; background: #fafafa;"></div>
                            <input type="hidden" name="content_json" id="content_json_output">
                        </div>

                        <button type="submit" class="btn btn-primary">Сохранить контент</button>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script>
        window.editorData = <?= !empty($page['content']) ? $page['content'] : '{}' ?>;
    </script>


    <style>
        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }
    </style>

<script>

</script>

<?php $this->render('layouts/footer'); ?> 