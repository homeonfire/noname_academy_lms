<?php
// src/Models/Category.php
class Category {
    private $pdo;

    public function __construct() {
        $this->pdo = getDBConnection();
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM categories ORDER BY name ASC");
        return $stmt->fetchAll();
    }

    public function create($name, $slug) {
        $stmt = $this->pdo->prepare("INSERT INTO categories (name, slug) VALUES (?, ?)");
        return $stmt->execute([$name, $slug]);
    }

    // Методы для редактирования и удаления мы добавим позже
    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function update($id, $name, $slug) {
        $stmt = $this->pdo->prepare("UPDATE categories SET name = ?, slug = ? WHERE id = ?");
        return $stmt->execute([$name, $slug, $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM categories WHERE id = ?");
        return $stmt->execute([$id]);
    }
}