<?php
// src/Models/User.php

class User {

    private $pdo;

    // --- БЕЗ ИЗМЕНЕНИЙ ---
    public function __construct() {
        require_once __DIR__ . '/../Config/database.php';
        $this->pdo = getDBConnection();
    }

    public function findByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }
    // --- КОНЕЦ БЛОКА БЕЗ ИЗМЕНЕНИЙ ---

    // --- НАЧАЛО ИЗМЕНЕНИЙ ---
    /**
     * Создание нового пользователя
     * @param string $email
     * @param string $passwordHash
     * @return string|false
     */
    public function create($email, $passwordHash) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO users (email, password_hash) VALUES (?, ?)"
        );
        $success = $stmt->execute([$email, $passwordHash]);

        if ($success) {
            // Возвращаем ID только что созданного пользователя
            return $this->pdo->lastInsertId();
        }
        return false;
    }
    // --- КОНЕЦ ИЗМЕНЕНИЙ ---

    // --- БЕЗ ИЗМЕНЕНИЙ ---
    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function update($id, $data) {
        $stmt = $this->pdo->prepare(
            "UPDATE users SET
            first_name = :first_name,
            last_name = :last_name,
            experience_level = :experience_level,
            avatar_path = :avatar_path
         WHERE id = :id"
        );

        $stmt->bindValue(':first_name', $data['first_name']);
        $stmt->bindValue(':last_name', $data['last_name']);
        $stmt->bindValue(':experience_level', $data['experience_level']);
        $stmt->bindValue(':avatar_path', $data['avatar_path']);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function getAll() {
        $stmt = $this->pdo->prepare("SELECT id, first_name, last_name, email, role, created_at FROM users ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function updateRole($id, $role) {
        if (!in_array($role, ['user', 'admin'])) {
            return false;
        }
        $stmt = $this->pdo->prepare("UPDATE users SET role = :role WHERE id = :id");
        return $stmt->execute(['role' => $role, 'id' => $id]);
    }

    public function updateUserData($id, $data) {
        $stmt = $this->pdo->prepare(
            "UPDATE users SET
            first_name = :first_name,
            last_name = :last_name,
            role = :role
         WHERE id = :id"
        );
        return $stmt->execute([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'role' => $data['role'],
            'id' => $id
        ]);
    }

    public function getPreferredCategoryIds($userId) {
        $stmt = $this->pdo->prepare("SELECT category_id FROM user_preferred_categories WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    }

    public function syncPreferredCategories($userId, $categoryIds) {
        $stmtDelete = $this->pdo->prepare("DELETE FROM user_preferred_categories WHERE user_id = ?");
        $stmtDelete->execute([$userId]);

        if (!empty($categoryIds)) {
            $stmtInsert = $this->pdo->prepare("INSERT INTO user_preferred_categories (user_id, category_id) VALUES (?, ?)");
            foreach ($categoryIds as $categoryId) {
                if (is_numeric($categoryId)) {
                    $stmtInsert->execute([$userId, $categoryId]);
                }
            }
        }
    }

    public function updateUserProfileData($id, $data) {
        $setClauses = [];
        foreach ($data as $key => $value) {
            $setClauses[] = "$key = :$key";
        }
        $sql = "UPDATE users SET " . implode(', ', $setClauses) . " WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        $data['id'] = $id;

        return $stmt->execute($data);
    }
    // --- КОНЕЦ БЛОКА БЕЗ ИЗМЕНЕНИЙ ---
}