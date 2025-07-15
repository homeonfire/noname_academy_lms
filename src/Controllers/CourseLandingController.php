<?php
// src/Controllers/CourseLandingController.php

class CourseLandingController extends Controller {

    private $courseModel;

    public function __construct() {
        $this->courseModel = new Course();
    }

    public function show($courseId) {
        if (empty($courseId)) {
            http_response_code(404);
            echo "<h1>404 Страница не найдена (ID курса не указан)</h1>";
            return;
        }

        $courseModel = new Course();
        // Используем наш мощный метод, который получает все данные
        $course = $courseModel->findCourseForLandingPage($courseId);

        if (!$course) {
            http_response_code(404);
            echo "<h1>404 Страница не найдена (Курс не найден)</h1>";
            return;
        }

        // Рендерим шаблон
        $this->render('courses/course_landing', [
            'course' => $course,
            'layout' => 'clear'
        ]);
    }
} 