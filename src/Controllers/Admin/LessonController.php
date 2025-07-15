<?php
// src/Controllers/Admin/LessonController.php

class AdminLessonController extends AdminController {

    private $lessonModel;
    private $homeworkModel;

    public function __construct() {
        parent::__construct();
        $this->lessonModel = new Lesson();
        $this->homeworkModel = new Homework();
    }

    /**
     * Обрабатывает создание нового урока
     */
    public function create() {
        $courseId = $_POST['course_id'] ?? null;
        $moduleId = $_POST['module_id'] ?? null;
        $title = $_POST['title'] ?? '';
        $contentJson = $_POST['content_json'] ?? null; // ДОБАВЛЕНО: получение content_json

        if (empty($title) || empty($moduleId) || empty($courseId)) {
            // Можно добавить сообщение об ошибке в сессию
            $_SESSION['error_message'] = 'Название урока и модуль обязательны.';
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        }

        // Вызываем create метод модели с новым параметром content_json
        // ИЗМЕНЕНО: Передача contentJson
        $this->lessonModel->create($moduleId, $title, $contentJson);

        $_SESSION['success_message'] = 'Урок "' . htmlspecialchars($title) . '" успешно создан!'; // ДОБАВЛЕНО: Сообщение об успехе
        header('Location: /admin/courses/content/' . $courseId);
        exit();
    }

    public function update() {
        $courseId = $_POST['course_id'] ?? null;
        $lessonId = $_POST['lesson_id'] ?? null;
        $title = $_POST['title'] ?? '';

        if (!empty($title) && !empty($lessonId)) {
            $this->lessonModel->update($lessonId, $title);
            $_SESSION['success_message'] = 'Название урока успешно обновлено.'; // ДОБАВЛЕНО: Сообщение об успехе
        } else {
            $_SESSION['error_message'] = 'Название урока не может быть пустым.'; // ДОБАВЛЕНО: Сообщение об ошибке
        }

        header('Location: /admin/courses/content/' . $courseId);
        exit();
    }

    public function delete($id, $courseId) {
        $this->lessonModel->delete($id);
        $_SESSION['success_message'] = 'Урок успешно удален.'; // ДОБАВЛЕНО: Сообщение об успехе
        header('Location: /admin/courses/content/' . $courseId);
        exit();
    }

    public function editContent($id) {
        $lesson = $this->lessonModel->findById($id);
        $homework = $this->homeworkModel->findByLessonId($id);

        if (!$lesson) {
            http_response_code(404); die('Урок не найден');
        }

        $this->render('admin/lessons/edit-content', [
            'title' => 'Редактировать контент урока',
            'lesson' => $lesson,
            'homework' => $homework
        ]);
    }

    public function saveContent($id) {
        $courseId = $_POST['course_id'] ?? null;
        $contentUrl = $_POST['content_url'] ?? null;
        $contentJson = $_POST['content_json'] ?? null; // ИЗМЕНЕНО: Теперь получаем content_json

        // Валидация contentJson (можно доработать, если нужно)
        if (!empty($contentJson) && json_decode($contentJson) === null && $contentJson !== '{}') {
            $_SESSION['error_message'] = 'Некорректный формат контента урока (JSON).';
            header('Location: /admin/lessons/edit-content/' . $id . '?course_id=' . $courseId);
            exit();
        }

        $homeworkQuestions = $_POST['homework_questions'] ?? [];
        $filteredQuestions = array_filter($homeworkQuestions, function($q) {
            return !empty(trim($q));
        });
        $questionsForJson = array_map(function($q) {
            return ['q' => $q];
        }, $filteredQuestions);

        if (!empty($questionsForJson)) {
            $this->homeworkModel->createOrUpdate($id, json_encode($questionsForJson));
        } else {
            // Если вопросы пустые, можно добавить логику удаления ДЗ
            $this->homeworkModel->deleteByLessonId($id); // ДОБАВЛЕНО: Удаление ДЗ, если вопросов нет
        }

        // ИЗМЕНЕНО: Передаем contentJson вместо contentText
        if ($this->lessonModel->updateContent($id, $contentUrl, $contentJson)) {
            $_SESSION['success_message'] = 'Контент урока успешно обновлен!';
        } else {
            $_SESSION['error_message'] = 'Ошибка при обновлении контента урока.';
        }

        header('Location: /admin/courses/content/' . $courseId);
        exit();
    }
}