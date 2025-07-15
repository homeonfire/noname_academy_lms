<?php
// src/Controllers/Admin/StaticPageController.php

class AdminStaticPageController extends AdminController {
    
    private $staticPageModel;

    public function __construct() {
        parent::__construct();
        $this->staticPageModel = new StaticPage();
    }

    /**
     * Список всех статических страниц
     */
    public function index() {
        $pages = $this->staticPageModel->getAll();
        $title = 'Управление статическими страницами';
        
        $this->renderAdminPage('admin/static-pages/index', [
            'title' => $title,
            'pages' => $pages
        ]);
    }

    /**
     * Редактирование страницы
     * @param string $slug
     */
    public function edit($slug) {
        $page = $this->staticPageModel->findBySlug($slug);
        
        if (!$page) {
            header('Location: /admin/static-pages');
            exit();
        }

        $title = 'Редактирование: ' . $page['title'];
        
        $this->renderAdminPage('admin/static-pages/edit', [
            'title' => $title,
            'page' => $page
        ]);
    }

    /**
     * Обновление страницы
     * @param string $slug
     */
    public function update($slug) {
        $title = $_POST['title'] ?? '';
        $contentJson = $_POST['content_json'] ?? '{}';

        if (empty($title)) {
            $_SESSION['error_message'] = 'Название страницы обязательно для заполнения.';
            header('Location: /admin/static-pages/edit/' . $slug);
            exit();
        }

        // Валидация JSON
        if (!empty($contentJson) && json_decode($contentJson) === null && $contentJson !== '{}') {
            $_SESSION['error_message'] = 'Некорректный формат контента (JSON).';
            header('Location: /admin/static-pages/edit/' . $slug);
            exit();
        }

        if ($this->staticPageModel->update($slug, $title, $contentJson)) {
            $_SESSION['success_message'] = 'Страница успешно обновлена!';
        } else {
            $_SESSION['error_message'] = 'Ошибка при обновлении страницы.';
        }

        header('Location: /admin/static-pages/edit/' . $slug);
        exit();
    }
} 