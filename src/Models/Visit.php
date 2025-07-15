<?php
// src/Models/Visit.php

class Visit {
    private $pdo;

    // --- БЕЗ ИЗМЕНЕНИЙ ---
    public function __construct() {
        require_once __DIR__ . '/../Config/database.php';
        $this->pdo = getDBConnection();
    }

    public function log($data) {
        $sql = "INSERT INTO visits
                    (ip_address, user_agent, is_unique, user_id, page_url, utm_source, utm_medium, utm_campaign, utm_term, utm_content)
                VALUES
                    (:ip_address, :user_agent, :is_unique, :user_id, :page_url, :utm_source, :utm_medium, :utm_campaign, :utm_term, :utm_content)";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':ip_address', $data['ip_address']);
        $stmt->bindValue(':user_agent', $data['user_agent']);
        $stmt->bindValue(':is_unique', $data['is_unique'], PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $data['user_id'], PDO::PARAM_INT);
        $stmt->bindValue(':page_url', $data['page_url']);
        $stmt->bindValue(':utm_source', $data['utm_source']);
        $stmt->bindValue(':utm_medium', $data['utm_medium']);
        $stmt->bindValue(':utm_campaign', $data['utm_campaign']);
        $stmt->bindValue(':utm_term', $data['utm_term']);
        $stmt->bindValue(':utm_content', $data['utm_content']);

        return $stmt->execute();
    }

    public function getLatestUniqueVisits() {
        $sql = "
            SELECT v.*, u.email as user_email
            FROM visits v
            INNER JOIN (
                SELECT MAX(id) as max_id
                FROM visits
                GROUP BY ip_address, user_agent
            ) AS latest_visits ON v.id = latest_visits.max_id
            LEFT JOIN users u ON v.user_id = u.id
            ORDER BY v.visit_date DESC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFirstUtmByUserId($userId) {
        $sql = "SELECT utm_source, utm_medium, utm_campaign, utm_term, utm_content 
                FROM visits 
                WHERE user_id = ? AND utm_source IS NOT NULL 
                ORDER BY visit_date ASC 
                LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function countVisitsByUserId($userId) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM visits WHERE user_id = ?");
        $stmt->execute([$userId]);
        return (int) $stmt->fetchColumn();
    }
    // --- КОНЕЦ БЛОКА БЕЗ ИЗМЕНЕНИЙ ---

    // --- НАЧАЛО ИЗМЕНЕНИЙ ---
    /**
     * Получает визиты пользователя с пагинацией.
     * @param int $userId
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function getVisitsByUserId($userId, $page = 1, $limit = 10) {
        $offset = ($page - 1) * $limit;

        // Заменяем '?' на именованный плейсхолдер ':user_id'
        $sql = "SELECT * FROM visits WHERE user_id = :user_id ORDER BY visit_date DESC LIMIT :limit OFFSET :offset";

        $stmt = $this->pdo->prepare($sql);

        // Привязываем все параметры по имени
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // --- КОНЕЦ ИЗМЕНЕНИЙ ---
}