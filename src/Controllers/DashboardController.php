<?php
// src/Controllers/DashboardController.php

class DashboardController extends Controller {

    public function __construct() {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit();
        }
    }

    public function index() {
        $courseModel = new Course();
        $favoriteModel = new Favorite();
        $userId = $_SESSION['user']['id'];

        // 1. Получаем курсы, которые пользователь уже начал
        $startedCourses = $courseModel->findStartedForUser($userId);

        // 2. Получаем ID избранных курсов (если нужно для отображения)
        // (но избранные курсы больше не нужны, можно убрать)
        // $favoritedCoursesRaw = $favoriteModel->getFavoritedCourses($userId);
        // $favoritedCourseIds = array_column($favoritedCoursesRaw, 'id');

        // 3. Получаем последние курсы
        $featuredCourse = $courseModel->getFeaturedCourse();
        $latestCourses = $courseModel->getLatest('course', 5);

        $data = [
            'title' => 'Дашборд',
            'startedCourses' => $startedCourses,
            'featuredCourse' => $featuredCourse,
            'latestCourses' => $latestCourses
        ];

        $this->render('dashboard/index', $data);
    }
}