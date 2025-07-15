<?php
// src/Models/Homework.php
// Если вы используете глобальное пространство имен, как мы договорились:
// namespace App\Models; // Убедитесь, что эта строка ЗАКОММЕНТИРОВАНА или УДАЛЕНА, если у вас нет namespace
class Homework {
    private $pdo;
    public function __construct() {
        require_once __DIR__ . '/../Config/database.php';
        $this->pdo = getDBConnection();
    }

    public function findByLessonId($lessonId) {
        $stmt = $this->pdo->prepare("SELECT * FROM homeworks WHERE lesson_id = ?");
        $stmt->execute([$lessonId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createOrUpdate($lessonId, $questionsJson) {
        $homework = $this->findByLessonId($lessonId);
        if ($homework) {
            // Обновляем
            $stmt = $this->pdo->prepare("UPDATE homeworks SET questions = ? WHERE lesson_id = ?");
            return $stmt->execute([$questionsJson, $lessonId]);
        } else {
            // Создаем
            $stmt = $this->pdo->prepare("INSERT INTO homeworks (lesson_id, questions) VALUES (?, ?)");
            return $stmt->execute([$lessonId, $questionsJson]);
        }
    }

    /**
     * Удаляет домашнее задание по ID урока
     * @param int $lessonId
     * @return bool
     */
    public function deleteByLessonId($lessonId) { // ДОБАВЛЕН ЭТОТ МЕТОД
        $stmt = $this->pdo->prepare("DELETE FROM homeworks WHERE lesson_id = ?");
        return $stmt->execute([$lessonId]);
    }
}