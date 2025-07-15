<?php
// src/Controllers/MyAnswersController.php

class MyAnswersController extends Controller {

    private $homeworkAnswerModel;

    public function __construct() {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit();
        }
        require_once __DIR__ . '/../Models/HomeworkAnswer.php';
        $this->homeworkAnswerModel = new HomeworkAnswer();
    }

    public function index() {
        $allAnswers = $this->homeworkAnswerModel->getAllForUser($_SESSION['user']['id']);

        // Разделяем ответы на две группы
        $uncheckedAnswers = [];
        $checkedAnswers = [];
        foreach ($allAnswers as $answer) {
            if ($answer['status'] === 'submitted') {
                $uncheckedAnswers[] = $answer;
            } else {
                $checkedAnswers[] = $answer;
            }
        }

        $data = [
            'title' => 'Мои ответы',
            'uncheckedAnswers' => $uncheckedAnswers,
            'checkedAnswers' => $checkedAnswers
        ];

        $this->render('my-answers/index', $data);
    }
}