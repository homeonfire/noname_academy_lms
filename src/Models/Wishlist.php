<?php
// src/Models/Wishlist.php

class Wishlist {
    private $pdo;

    public function __construct() {
        require_once __DIR__ . '/../Config/database.php';
        $this->pdo = getDBConnection();
    }

    /**
     * Добавляет курс в список желаний пользователя.
     * @param int $userId
     * @param int $courseId
     * @return bool
     */
    public function addCourseToWishlist($userId, $courseId) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO user_wishlist_courses (user_id, course_id) VALUES (?, ?)"
        );
        return $stmt->execute([$userId, $courseId]);
    }

    /**
     * Удаляет курс из списка желаний пользователя.
     * @param int $userId
     * @param int $courseId
     * @return bool
     */
    public function removeCourseFromWishlist($userId, $courseId) {
        $stmt = $this->pdo->prepare(
            "DELETE FROM user_wishlist_courses WHERE user_id = ? AND course_id = ?"
        );
        return $stmt->execute([$userId, $courseId]);
    }

    /**
     * Получает все курсы из списка желаний пользователя.
     * @param int $userId
     * @return array
     */
    public function getWishlistedCoursesForUser($userId) {
        $sql = "SELECT c.*, GROUP_CONCAT(cat.name) as categories
                FROM courses c
                JOIN user_wishlist_courses uwc ON c.id = uwc.course_id
                LEFT JOIN course_categories cc ON c.id = cc.course_id
                LEFT JOIN categories cat ON cc.category_id = cat.id
                WHERE uwc.user_id = ?
                GROUP BY c.id
                ORDER BY uwc.created_at DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    /**
     * Проверяет, находится ли курс в списке желаний пользователя.
     * @param int $userId
     * @param int $courseId
     * @return bool
     */
    public function isCourseInWishlist($userId, $courseId) {
        $stmt = $this->pdo->prepare(
            "SELECT COUNT(*) FROM user_wishlist_courses WHERE user_id = ? AND course_id = ?"
        );
        $stmt->execute([$userId, $courseId]);
        return $stmt->fetchColumn() > 0;
    }
}