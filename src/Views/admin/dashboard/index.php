<?php $this->render('layouts/admin-header', ['title' => $title]); ?>

    <div class="admin-layout">
        <?php $this->render('layouts/admin-sidebar'); ?>

        <main class="main-content">
            <div class="content-wrapper">
                <h1 class="page-title">Панель администратора</h1>
                <p>Добро пожаловать! Отсюда вы можете управлять всем сайтом.</p>
            </div>
        </main>
    </div>

<?php $this->render('layouts/footer'); ?>