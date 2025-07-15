<?php
// src/Controllers/Admin/ModuleController.php

class AdminModuleController extends AdminController {

    private $moduleModel;

    public function __construct() {
        parent::__construct();
        $this->moduleModel = new Module();
    }

    /**
     * Обрабатывает создание нового модуля
     */
    public function create() {
        $courseId = $_POST['course_id'] ?? null;
        $title = $_POST['title'] ?? '';

        if (empty($title) || empty($courseId)) {
            // Просто возвращаем пользователя назад, если данные некорректны
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        }

        $this->moduleModel->create($courseId, $title);

        // Перенаправляем обратно на страницу управления контентом
        header('Location: /admin/courses/content/' . $courseId);
        exit();
    }

    /**
     * Показывает форму редактирования модуля
     * (Мы не будем делать отдельную страницу, а покажем форму прямо на месте с помощью JS в будущем,
     * но контроллер для обработки нам все равно нужен)
     */
// public function edit($id) { ... }

    /**
     * Обрабатывает обновление модуля
     */
    public function update() {
        $moduleId = $_POST['module_id'] ?? null;
        $courseId = $_POST['course_id'] ?? null; // для редиректа
        $title = $_POST['title'] ?? '';

        if (!empty($title) && !empty($moduleId)) {
            $this->moduleModel->update($moduleId, $title);
        }

        header('Location: /admin/courses/content/' . $courseId);
        exit();
    }

    /**
     * Обрабатывает удаление модуля
     */
    public function delete($id, $courseId) {
        $this->moduleModel->delete($id);
        header('Location: /admin/courses/content/' . $courseId);
        exit();
    }
}