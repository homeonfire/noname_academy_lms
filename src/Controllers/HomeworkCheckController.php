<?php
// src/Controllers/HomeworkCheckController.php

class HomeworkCheckController extends Controller {

    private $homeworkAnswerModel;

    public function __construct() {
        // Проверяем, что пользователь авторизован и является админом
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            // Если нет - отправляем на главную
            header('Location: /dashboard');
            exit();
        }
        require_once __DIR__ . '/../Models/HomeworkAnswer.php';
        $this->homeworkAnswerModel = new HomeworkAnswer();
    }

    /**
     * Показывает страницу со списком ДЗ на проверку
     */
    public function index() {
        $submissions = $this->homeworkAnswerModel->getAllSubmitted();

        // --- Пагинация для проверенных работ ---
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $perPage = 10;
        $offset = ($page - 1) * $perPage;
        $checkedTotal = $this->homeworkAnswerModel->getCheckedCount();
        $checkedPages = (int)ceil($checkedTotal / $perPage);
        $checkedList = $this->homeworkAnswerModel->getCheckedPaginated($perPage, $offset);

        $data = [
            'title' => 'Проверка ДЗ',
            'submissions' => $submissions,
            'checkedList' => $checkedList,
            'checkedPage' => $page,
            'checkedPages' => $checkedPages,
            'checkedTotal' => $checkedTotal
        ];

        $this->render('homeworks/check-list', $data);
    }

    /**
     * Показывает страницу для проверки конкретного ДЗ
     */
    public function show($submissionId) {
        $submission = $this->homeworkAnswerModel->getSubmissionDetails($submissionId);

        if (!$submission) {
            http_response_code(404);
            die('Сданная работа не найдена');
        }

        $data = [
            'title' => 'Проверка работы',
            'submission' => $submission
        ];

        $this->render('homeworks/check-single', $data);
    }

    /**
     * Обрабатывает проверку ДЗ (одобрение/отклонение)
     */
    public function processCheck($submissionId) {
        $comment = $_POST['comment'] ?? '';
        $status = isset($_POST['approve']) ? 'checked' : (isset($_POST['reject']) ? 'rejected' : null);

        if ($status) {
            // --- НАЧАЛО НОВОЙ ЛОГИКИ ---
            // Получаем информацию о сданной работе, чтобы узнать user_id и lesson_id
            $submission = $this->homeworkAnswerModel->getSubmissionDetails($submissionId);
            if ($submission && $status === 'checked') {
                // Если работа одобрена, отмечаем урок как пройденный

                $progressModel = new LessonProgress();
                $progressModel->markAsCompleted($submission['user_id'], $submission['lesson_id']);
            }
            // --- КОНЕЦ НОВОЙ ЛОГИКИ ---

            // Обновляем статус самого ДЗ
            $this->homeworkAnswerModel->updateStatus($submissionId, $status, $comment);
        }

        header('Location: /homework-check');
        exit();
    }
}