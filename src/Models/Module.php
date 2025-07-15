<?php
// src/Models/Module.php

class Module {

    private $pdo;

    public function __construct() {
        require_once __DIR__ . '/../Config/database.php';
        $this->pdo = getDBConnection();
    }

    /**
     * Создает новый модуль для курса
     * @param int $courseId
     * @param string $title
     * @return bool
     */
    public function create($courseId, $title) {
        // Определяем следующий порядковый номер
        $stmtOrder = $this->pdo->prepare("SELECT MAX(order_number) as max_order FROM modules WHERE course_id = ?");
        $stmtOrder->execute([$courseId]);
        $maxOrder = $stmtOrder->fetchColumn();
        $newOrder = $maxOrder + 1;

        $stmt = $this->pdo->prepare(
            "INSERT INTO modules (course_id, title, order_number) VALUES (:course_id, :title, :order_number)"
        );

        return $stmt->execute([
            'course_id' => $courseId,
            'title' => $title,
            'order_number' => $newOrder
        ]);
    }

    /**
     * Обновляет модуль
     * @param int $id
     * @param string $title
     * @return bool
     */
    public function update($id, $title) {
        $stmt = $this->pdo->prepare("UPDATE modules SET title = :title WHERE id = :id");
        return $stmt->execute(['title' => $title, 'id' => $id]);
    }

    /**
     * Удаляет модуль
     * @param int $id
     * @return bool
     */
    public function delete($id) {
        // При удалении модуля, уроки внутри него удалятся автоматически
        // благодаря ON DELETE CASCADE в структуре БД
        $stmt = $this->pdo->prepare("DELETE FROM modules WHERE id = ?");
        return $stmt->execute([$id]);
    }
}