<?php
// src/Models/Favorite.php

class Favorite {
    private $pdo;

    // --- БЕЗ ИЗМЕНЕНИЙ ---
    public function __construct() {
        $this->pdo = getDBConnection();
    }

    public function toggle($userId, $itemId, $itemType) {
        // Разрешаем только itemType === 'lesson'
        if ($itemType !== 'lesson') return false;
        if ($this->isFavorite($userId, $itemId, $itemType)) {
            $stmt = $this->pdo->prepare("DELETE FROM user_favorites WHERE user_id = ? AND item_id = ? AND item_type = ?");
            return $stmt->execute([$userId, $itemId, $itemType]);
        } else {
            $stmt = $this->pdo->prepare("INSERT INTO user_favorites (user_id, item_id, item_type) VALUES (?, ?, ?)");
            return $stmt->execute([$userId, $itemId, $itemType]);
        }
    }

    public function isFavorite($userId, $itemId, $itemType) {
        // Разрешаем только itemType === 'lesson'
        if ($itemType !== 'lesson') return false;
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM user_favorites WHERE user_id = ? AND item_id = ? AND item_type = ?");
        $stmt->execute([$userId, $itemId, $itemType]);
        return $stmt->fetchColumn() > 0;
    }
    // --- КОНЕЦ БЛОКА БЕЗ ИЗМЕНЕНИЙ ---

    // --- НАЧАЛО ИЗМЕНЕНИЙ ---
    /**
     * Получает все избранные курсы или мастер-классы для пользователя.
     * @param int $userId
     * @param string $courseType Тип контента: 'course' или 'masterclass'
     * @return array
     */
    public function getFavoritedCourses($userId, $courseType = 'course') {
        $sql = "SELECT c.*, GROUP_CONCAT(cat.name) as categories
                FROM courses c
                JOIN user_favorites uf ON c.id = uf.item_id
                LEFT JOIN course_categories cc ON c.id = cc.course_id
                LEFT JOIN categories cat ON cc.category_id = cat.id
                WHERE uf.user_id = :userId 
                  AND uf.item_type = 'course' -- item_type в избранном всегда 'course' для этих сущностей
                  AND c.type = :courseType   -- А здесь мы фильтруем по типу в таблице courses
                GROUP BY c.id
                ORDER BY uf.created_at DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':courseType', $courseType, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    // --- КОНЕЦ ИЗМЕНЕНИЙ ---

    // --- БЕЗ ИЗМЕНЕНИЙ ---
    public function getFavoritedLessons($userId) {
        $stmt = $this->pdo->prepare("SELECT l.* FROM lessons l JOIN user_favorites uf ON l.id = uf.item_id WHERE uf.user_id = ? AND uf.item_type = 'lesson'");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFavoritedGuides($userId) {
        $sql = "SELECT g.*, GROUP_CONCAT(cat.name) as categories
                FROM guides g
                JOIN user_favorites uf ON g.id = uf.item_id
                LEFT JOIN guide_categories gc ON g.id = gc.guide_id
                LEFT JOIN categories cat ON gc.category_id = cat.id
                WHERE uf.user_id = ? AND uf.item_type = 'guide'
                GROUP BY g.id
                ORDER BY uf.created_at DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
    // --- КОНЕЦ БЛОКА БЕЗ ИЗМЕНЕНИЙ ---
}