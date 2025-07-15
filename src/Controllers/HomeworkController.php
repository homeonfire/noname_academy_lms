<?php
// src/Controllers/HomeworkController.php

class HomeworkController extends Controller {

    private $homeworkAnswerModel;

    public function __construct() {
        // ИСПРАВЛЕНО: Проверяем новую структуру сессии
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit();
        }
        require_once __DIR__ . '/../Models/HomeworkAnswer.php';
        $this->homeworkAnswerModel = new HomeworkAnswer();
    }

    /**
     * Обрабатывает сдачу или пересдачу ДЗ
     */
    public function submit() {
        // ИСПРАВЛЕНО: Получаем ID из новой структуры сессии
        $userId = $_SESSION['user']['id'];
        $homeworkId = $_POST['homework_id'] ?? null;
        $answers = $_POST['answers'] ?? [];
        $lessonId = $_POST['lesson_id'] ?? null;
        $courseId = $_POST['course_id'] ?? null;

        // Формируем JSON из ответов
        $answersForJson = json_encode(array_map(function($a) {
            return ['a' => $a];
        }, $answers));

        // Проверяем, есть ли уже сданная работа
        $existingAnswer = $this->homeworkAnswerModel->findByUserAndHomework($userId, $homeworkId);

        if ($existingAnswer) {
            // Если работа была отклонена, позволяем пересдать
            if ($existingAnswer['status'] === 'rejected') {
                $this->homeworkAnswerModel->resubmit($existingAnswer['id'], $answersForJson);
            }
        } else {
            // Если работы не было, создаем новую запись
            $this->homeworkAnswerModel->create($homeworkId, $userId, $answersForJson);
        }

        // Возвращаем пользователя на страницу урока
        header('Location: /course/' . $courseId . '/lesson/' . $lessonId . '?status=submitted');
        exit();
    }
}