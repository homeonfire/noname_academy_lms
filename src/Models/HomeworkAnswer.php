<?php
// src/Models/HomeworkAnswer.php

class HomeworkAnswer {
    private $pdo;

    public function __construct() {
        require_once __DIR__ . '/../Config/database.php';
        $this->pdo = getDBConnection();
    }

    /**
     * Находит ответ пользователя на конкретное ДЗ
     * @param int $userId
     * @param int $homeworkId
     * @return mixed
     */
    public function findByUserAndHomework($userId, $homeworkId) {
        $stmt = $this->pdo->prepare("SELECT * FROM homework_answers WHERE user_id = ? AND homework_id = ? LIMIT 1");
        $stmt->execute([$userId, $homeworkId]);
        return $stmt->fetch();
    }

    /**
     * Создает новую запись с ответом
     * @param int $homeworkId
     * @param int $userId
     * @param string $answersJson
     * @return bool
     */
    public function create($homeworkId, $userId, $answersJson) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO homework_answers (homework_id, user_id, answers) VALUES (:homework_id, :user_id, :answers)"
        );
        return $stmt->execute([
            'homework_id' => $homeworkId,
            'user_id' => $userId,
            'answers' => $answersJson
        ]);
    }

    /**
     * Получает количество ДЗ, ожидающих проверки
     * @return int
     */
    public function getSubmittedCount() {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM homework_answers WHERE status = 'submitted'");
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    /**
     * Получает все ДЗ со статусом 'submitted'
     * @return array
     */
    public function getAllSubmitted() {
        $sql = "SELECT ha.id, ha.submitted_at, u.email as user_email, l.title as lesson_title, c.title as course_title
            FROM homework_answers ha
            JOIN users u ON ha.user_id = u.id
            JOIN homeworks h ON ha.homework_id = h.id
            JOIN lessons l ON h.lesson_id = l.id
            JOIN modules m ON l.module_id = m.id
            JOIN courses c ON m.course_id = c.id
            WHERE ha.status = 'submitted'
            ORDER BY ha.submitted_at ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Получает полную информацию по одной сданной работе
     * @param int $submissionId ID из таблицы homework_answers
     * @return mixed
     */
    /**
     * Получает полную информацию по одной сданной работе
     * @param int $submissionId ID из таблицы homework_answers
     * @return mixed
     */
    public function getSubmissionDetails($submissionId) {
        // ИСПРАВЛЕНО: Добавлены ha.user_id и h.lesson_id в SELECT
        $sql = "SELECT 
                ha.id, ha.answers, ha.status, ha.user_id, 
                u.email as user_email, 
                h.questions, h.lesson_id,
                l.title as lesson_title
            FROM homework_answers ha
            JOIN users u ON ha.user_id = u.id
            JOIN homeworks h ON ha.homework_id = h.id
            JOIN lessons l ON h.lesson_id = l.id
            WHERE ha.id = ?
            LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$submissionId]);
        return $stmt->fetch();
    }

    /**
     * Обновляет статус и комментарий для сданной работы
     * @param int $submissionId
     * @param string $status ('checked' или 'rejected')
     * @param string $comment
     * @return bool
     */
    public function updateStatus($submissionId, $status, $comment) {
        $stmt = $this->pdo->prepare(
            "UPDATE homework_answers SET status = :status, comment = :comment, checked_at = NOW() WHERE id = :id"
        );
        return $stmt->execute([
            'status' => $status,
            'comment' => $comment,
            'id' => $submissionId
        ]);
    }

    /**
     * Получает все ДЗ для конкретного пользователя
     * @param int $userId
     * @return array
     */
    public function getAllForUser($userId) {
        $sql = "SELECT 
                ha.id, ha.status, ha.submitted_at, ha.checked_at,
                l.id as lesson_id,
                l.title as lesson_title,
                c.id as course_id, 
                c.title as course_title
            FROM homework_answers ha
            JOIN homeworks h ON ha.homework_id = h.id
            JOIN lessons l ON h.lesson_id = l.id
            JOIN modules m ON l.module_id = m.id
            JOIN courses c ON m.course_id = c.id
            WHERE ha.user_id = ?
            ORDER BY ha.submitted_at DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    /**
     * Получает проверенные ДЗ (checked или rejected) с пагинацией
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getCheckedPaginated($limit = 10, $offset = 0) {
        $sql = "SELECT ha.id, ha.checked_at, ha.status, u.email as user_email, l.title as lesson_title, c.title as course_title
            FROM homework_answers ha
            JOIN users u ON ha.user_id = u.id
            JOIN homeworks h ON ha.homework_id = h.id
            JOIN lessons l ON h.lesson_id = l.id
            JOIN modules m ON l.module_id = m.id
            JOIN courses c ON m.course_id = c.id
            WHERE ha.status IN ('checked', 'rejected')
            ORDER BY ha.checked_at DESC
            LIMIT :limit OFFSET :offset";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Получает общее количество проверенных ДЗ
     * @return int
     */
    public function getCheckedCount() {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM homework_answers WHERE status IN ('checked', 'rejected')");
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }
}