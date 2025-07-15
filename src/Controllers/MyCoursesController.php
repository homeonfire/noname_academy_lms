<?php
// src/Controllers/MyCoursesController.php

class MyCoursesController extends Controller {

    private $courseModel;
    private $favoriteModel;

    public function __construct() {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit();
        }
        $this->courseModel = new Course();
        $this->favoriteModel = new Favorite();
    }

    /**
     * Показывает страницу с курсами, которые начал пользователь.
     */
    public function index() {
        $userId = $_SESSION['user']['id'];

        // 1. Используем наш новый метод для получения начатых курсов
        $startedCourses = $this->courseModel->findStartedForUser($userId);

        // 2. Получаем информацию об избранном для корректного отображения карточек
        $favoritedCoursesRaw = $this->favoriteModel->getFavoritedCourses($userId);
        $favoritedCourseIds = array_column($favoritedCoursesRaw, 'id');

        // 3. Передаем все данные в новое представление
        $this->render('my-courses/index', [
            'title' => 'Мои курсы',
            'courses' => $startedCourses,
            'favoritedCourseIds' => $favoritedCourseIds
        ]);
    }
}