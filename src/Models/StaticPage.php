<?php
// src/Models/StaticPage.php

class StaticPage {
    private $db;

    public function __construct() {
        require_once __DIR__ . '/../Config/database.php';
        $this->db = getDBConnection();
    }

    /**
     * Получает страницу по slug
     * @param string $slug
     * @return array|null
     */
    public function findBySlug($slug) {
        $stmt = $this->db->prepare("SELECT * FROM static_pages WHERE slug = ?");
        $stmt->execute([$slug]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Получает все страницы
     * @return array
     */
    public function getAll() {
        $stmt = $this->db->prepare("SELECT * FROM static_pages ORDER BY title");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Обновляет содержимое страницы
     * @param string $slug
     * @param string $title
     * @param string $content_json
     * @return bool
     */
    public function update($slug, $title, $content_json) {
        $stmt = $this->db->prepare("UPDATE static_pages SET title = ?, content = ?, updated_at = NOW() WHERE slug = ?");
        return $stmt->execute([$title, $content_json, $slug]);
    }

    /**
     * Создает новую страницу
     * @param string $slug
     * @param string $title
     * @param string $content_json
     * @return bool
     */
    public function create($slug, $title, $content_json) {
        $stmt = $this->db->prepare("INSERT INTO static_pages (slug, title, content, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
        return $stmt->execute([$slug, $title, $content_json]);
    }

    /**
     * Проверяет существование страницы
     * @param string $slug
     * @return bool
     */
    public function exists($slug) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM static_pages WHERE slug = ?");
        $stmt->execute([$slug]);
        return $stmt->fetchColumn() > 0;
    }
} 