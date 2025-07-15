<?php
// src/Controllers/StaticPageController.php

class StaticPageController extends Controller {
    
    private $staticPageModel;

    public function __construct() {
        $this->staticPageModel = new StaticPage();
    }

    /**
     * Отображает статическую страницу
     * @param string $slug
     */
    public function show($slug) {
        $page = $this->staticPageModel->findBySlug($slug);
        
        if (!$page) {
            http_response_code(404);
            echo "<h1>404 Страница не найдена</h1>";
            return;
        }

        $title = $page['title'];
        $this->render('static-pages/show', [
            'title' => $title,
            'page' => $page
        ]);
    }
} 