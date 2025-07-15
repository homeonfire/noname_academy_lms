<?php
// src/Controllers/Admin/AdminController.php

class AdminController extends Controller {

    // --- ИСПРАВЛЕНИЕ: Объявляем свойства здесь ---
    protected $userModel;
    protected $homeworkAnswerModel;
    // ------------------------------------------

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit();
        }

        // Модели теперь доступны глобально, конструктор может быть проще
        $this->userModel = new User();
        $this->homeworkAnswerModel = new HomeworkAnswer();

        if ($_SESSION['user']['role'] !== 'admin') {
            http_response_code(403);
            die('Доступ запрещен');
        }
    }

    /**
     * Рендерит страницу админки, автоматически добавляя общие данные
     */
    protected function renderAdminPage($view, $data = []) {
        $data['submittedHomeworksCount'] = $this->homeworkAnswerModel->getSubmittedCount();
        $this->render($view, $data);
    }
}