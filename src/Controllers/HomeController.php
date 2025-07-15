<?php
// src/Controllers/HomeController.php

class HomeController extends Controller {
    public function index() {
        $guideModel = new Guides();
        $guides = $guideModel->getLatest(8); // Показываем 8 последних гайдов
        $this->render('home/index', [
            'guides' => $guides,
            'title' => 'AI Fire LMS Academy — Главная'
        ]);
    }
} 