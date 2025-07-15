<?php
// src/Models/LessonProgress.php
class LessonProgress {
    private $pdo;
    public function __construct() {
        require_once __DIR__ . '/../Config/database.php';
        $this->pdo = getDBConnection();
    }

    // Отмечает урок как пройденный
    public function markAsCompleted($userId, $lessonId) {
        // "INSERT IGNORE" не будет вставлять запись, если она уже существует (из-за UNIQUE KEY)
        $stmt = $this->pdo->prepare("INSERT IGNORE INTO user_lesson_progress (user_id, lesson_id) VALUES (?, ?)");
        return $stmt->execute([$userId, $lessonId]);
    }

    // Получает ID всех пройденных уроков для пользователя в рамках одного курса
    public function getCompletedLessonIdsForUser($userId, $courseId) {
        $sql = "SELECT ulp.lesson_id FROM user_lesson_progress ulp
                JOIN lessons l ON ulp.lesson_id = l.id
                JOIN modules m ON l.module_id = m.id
                WHERE ulp.user_id = ? AND m.course_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId, $courseId]);
        // Возвращаем простой массив ID, например [1, 5, 8]
        return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    }
}