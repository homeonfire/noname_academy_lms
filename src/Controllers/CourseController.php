<?php
// src/Controllers/CourseController.php

class CourseController extends Controller {

    private $courseModel;

    public function __construct() {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit();
        }
        $this->courseModel = new Course();
    }

    public function index() {
        $courses = $this->courseModel->getAll();
        $userId = $_SESSION['user']['id'];
        foreach ($courses as &$course) {
            $course['hasAccess'] = $this->courseModel->hasAccess($userId, $course['id']);
        }
        $data = [
            'title' => 'Все курсы',
            'courses' => $courses
        ];
        $this->render('courses/index', $data);
    }

    /**
     * Показывает страницу курса
     * @param int $courseId
     * @param int|null $lessonId
     */
    public function show($courseId, $lessonId = null) {
        $course = $this->courseModel->getCourseWithModulesAndLessons($courseId);
        if (!$course) {
            http_response_code(404);
            echo "<h1>404 Элемент не найден</h1>";
            return;
        }
        // Проверяем доступ к курсу
        $hasAccess = $this->courseModel->hasAccess($_SESSION['user']['id'], $courseId);
        $isFree = !empty($course['is_free']) || $course['price'] == 0;
        // Если курс платный и у пользователя нет доступа - перенаправляем на лендинг
        if (!$isFree && !$hasAccess) {
            $landingUrl = "/course/{$courseId}/landing";
            header('Location: ' . $landingUrl);
            exit();
        }
        $activeLesson = null;
        if ($lessonId === null) {
            if (!empty($course['modules']) && !empty($course['modules'][0]['lessons'])) {
                $activeLesson = $course['modules'][0]['lessons'][0];
            }
        } else {
            foreach ($course['modules'] as $module) {
                foreach ($module['lessons'] as $lesson) {
                    if ($lesson['id'] == $lessonId) {
                        $activeLesson = $lesson;
                        break 2;
                    }
                }
            }
            if ($activeLesson === null) {
                http_response_code(404);
                echo "<h1>404 Урок не найден в данном курсе</h1>";
                return;
            }
        }
        $homework = null;
        $userAnswer = null;
        if ($activeLesson) {
            $homeworkModel = new Homework();
            $homeworkAnswerModel = new HomeworkAnswer();
            $homework = $homeworkModel->findByLessonId($activeLesson['id']);
            if ($homework) {
                $userAnswer = $homeworkAnswerModel->findByUserAndHomework($_SESSION['user']['id'], $homework['id']);
            }
        }
        $progressModel = new LessonProgress();
        $totalLessons = $this->courseModel->countLessons($courseId);
        $completedLessonIds = $progressModel->getCompletedLessonIdsForUser($_SESSION['user']['id'], $courseId);
        $completedCount = count($completedLessonIds);
        $progressPercentage = ($totalLessons > 0) ? round(($completedCount / $totalLessons) * 100) : 0;
        $favoriteModel = new Favorite();
        $userId = $_SESSION['user']['id'];
        $favoritedLessonsRaw = $favoriteModel->getFavoritedLessons($userId);
        $favoritedLessonIds = array_column($favoritedLessonsRaw, 'id');
        $hasAccess = $this->courseModel->hasAccess($_SESSION['user']['id'], $courseId);
        $data = [
            'title' => $course['title'],
            'course' => $course,
            'activeLesson' => $activeLesson,
            'homework' => $homework,
            'userAnswer' => $userAnswer,
            'progressPercentage' => $progressPercentage,
            'completedLessonIds' => $completedLessonIds,
            'favoritedLessonIds' => $favoritedLessonIds,
            'hasAccess' => $hasAccess
        ];
        $this->render('courses/show', $data);
    }
}