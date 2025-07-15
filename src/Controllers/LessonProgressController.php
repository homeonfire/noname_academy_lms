<?php
// src/Controllers/LessonProgressController.php
class LessonProgressController extends Controller {
    public function complete($lessonId) {
        if (!isset($_SESSION['user'])) { header('Location: /login'); exit(); }

        require_once __DIR__ . '/../Models/LessonProgress.php';
        $progressModel = new LessonProgress();
        $progressModel->markAsCompleted($_SESSION['user']['id'], $lessonId);

        // Возвращаем пользователя на предыдущую страницу
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }
}