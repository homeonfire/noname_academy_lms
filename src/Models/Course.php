<?php
// src/Models/Course.php

class Course {

    private $pdo;

    // --- БЕЗ ИЗМЕНЕНИЙ ---
    public function __construct() {
        $this->pdo = getDBConnection();
    }
    // --- КОНЕЦ БЛОКА БЕЗ ИЗМЕНЕНИЙ ---


    // --- НАЧАЛО ИЗМЕНЕНИЙ ---
    /**
     * Получает несколько последних курсов с их категориями
     * @param int $limit Количество элементов для получения
     * @return array
     */
    public function getLatest($limit = 5) {
        $sql = "SELECT c.*, GROUP_CONCAT(cat.name) as categories
            FROM courses c
            LEFT JOIN course_categories cc ON c.id = cc.course_id
            LEFT JOIN categories cat ON cc.category_id = cat.id
            GROUP BY c.id
            ORDER BY c.created_at DESC
            LIMIT :limit";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    // --- КОНЕЦ ИЗМЕНЕНИЙ ---


    // --- БЕЗ ИЗМЕНЕНИЙ ---
    public function getFeaturedCourse() {
        // Пока что просто берем самый первый курс для примера
        $stmt = $this->pdo->prepare("SELECT * FROM courses ORDER BY id ASC LIMIT 1");
        $stmt->execute();
        return $stmt->fetch();
    }

    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM courses WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getCourseWithModulesAndLessons($id) {
        $course = $this->findById($id);
        if (!$course) {
            return false;
        }

        // Получаем модули
        $stmtModules = $this->pdo->prepare("SELECT * FROM modules WHERE course_id = ? ORDER BY order_number ASC");
        $stmtModules->execute([$id]);
        $modules = $stmtModules->fetchAll();

        // Для каждого модуля получаем его уроки
        $stmtLessons = $this->pdo->prepare("SELECT * FROM lessons WHERE module_id = ? ORDER BY order_number ASC");
        foreach ($modules as $key => $module) {
            $stmtLessons->execute([$module['id']]);
            $modules[$key]['lessons'] = $stmtLessons->fetchAll();
        }

        $course['modules'] = $modules;
        return $course;
    }
    // --- КОНЕЦ БЛОКА БЕЗ ИЗМЕНЕНИЙ ---


    // --- НАЧАЛО ИЗМЕНЕНИЙ ---
    /**
     * Получает все курсы с их категориями
     * @return array
     */
    public function getAll() {
        $sql = "SELECT c.*, GROUP_CONCAT(cat.name) as categories, u.email as admin_email, u.first_name as admin_first_name, u.last_name as admin_last_name
            FROM courses c
            LEFT JOIN course_categories cc ON c.id = cc.course_id
            LEFT JOIN categories cat ON cc.category_id = cat.id
            LEFT JOIN users u ON c.created_by = u.id
            GROUP BY c.id
            ORDER BY c.created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Создает новый курс
     * @param string $title
     * @param string $description
     * @param string $difficulty_level
     * @param float $price Цена курса
     * @param bool $isFree Бесплатный ли курс
     * @return string|false
     */
    public function create($title, $description, $difficulty_level, $cover_url = null, $created_by = null, $price = 0.00, $isFree = true) {
        $stmt = $this->pdo->prepare("INSERT INTO courses (title, description, difficulty_level, cover_url, created_by, price, is_free) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $description, $difficulty_level, $cover_url, $created_by, $price, $isFree]);
        return $this->pdo->lastInsertId();
    }
    // --- КОНЕЦ ИЗМЕНЕНИЙ ---


    // --- БЕЗ ИЗМЕНЕНИЙ ---
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM courses WHERE id = ?");
        return $stmt->execute([$id]);
    }
    // --- КОНЕЦ БЛОКА БЕЗ ИЗМЕНЕНИЙ ---


    // --- НАЧАЛО ИЗМЕНЕНИЙ ---
    /**
     * Обновляет существующий курс или мастер-класс
     * @param int $id
     * @param string $title
     * @param string $description
     * @param string $difficulty_level
     * @param float $price Цена курса
     * @param bool $isFree Бесплатный ли курс
     * @return bool
     */
    public function update($id, $title, $description, $difficulty_level, $cover_url = null, $price = 0.00, $isFree = true) {
        $stmt = $this->pdo->prepare("UPDATE courses SET title = ?, description = ?, difficulty_level = ?, cover_url = ?, price = ?, is_free = ? WHERE id = ?");
        return $stmt->execute([$title, $description, $difficulty_level, $cover_url, $price, $isFree, $id]);
    }
    // --- КОНЕЦ ИЗМЕНЕНИЙ ---


    // --- БЕЗ ИЗМЕНЕНИЙ ---
    public function countLessons($courseId) {
        $stmt = $this->pdo->prepare(
            "SELECT COUNT(l.id) FROM lessons l
         JOIN modules m ON l.module_id = m.id
         WHERE m.course_id = ?"
        );
        $stmt->execute([$courseId]);
        return (int) $stmt->fetchColumn();
    }

    public function getCategoryIdsForCourse($courseId) {
        $stmt = $this->pdo->prepare("SELECT category_id FROM course_categories WHERE course_id = ?");
        $stmt->execute([$courseId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    }

    public function syncCategories($courseId, $categoryIds) {
        $stmtDelete = $this->pdo->prepare("DELETE FROM course_categories WHERE course_id = ?");
        $stmtDelete->execute([$courseId]);

        if (!empty($categoryIds)) {
            $stmtInsert = $this->pdo->prepare("INSERT INTO course_categories (course_id, category_id) VALUES (?, ?)");
            foreach ($categoryIds as $categoryId) {
                $stmtInsert->execute([$courseId, $categoryId]);
            }
        }
    }
    // --- КОНЕЦ БЛОКА БЕЗ ИЗМЕНЕНИЙ ---

    /**
     * Находит все курсы и мастер-классы, которые пользователь "начал"
     * (т.е. сдал хотя бы одно домашнее задание).
     * @param int $userId ID пользователя
     * @return array
     */
    public function findStartedForUser($userId) {
        $sql = "
        SELECT DISTINCT c.*
        FROM courses c
        JOIN modules m ON c.id = m.course_id
        JOIN lessons l ON m.id = l.module_id
        JOIN homeworks h ON l.id = h.lesson_id
        JOIN homework_answers ha ON h.id = ha.homework_id
        WHERE ha.user_id = ?
        ORDER BY c.id DESC
    ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    /**
     * Проверяет, имеет ли пользователь доступ к курсу
     * @param int $userId ID пользователя
     * @param int $courseId ID курса
     * @return bool
     */
    public function hasAccess($userId, $courseId) {
        $course = $this->findById($courseId);
        if (!$course) {
            return false;
        }
        // Для альфа-версии: доступ есть всегда, если курс существует
        return true;
    }

    /**
     * Получает курсы, доступные пользователю
     * @param int $userId ID пользователя
     * @return array
     */
    public function getAvailableForUser($userId) {
        $sql = "
        SELECT c.*, 
               CASE 
                   WHEN c.is_free = 1 THEN 'free'
                   WHEN p.status = 'completed' THEN 'paid'
                   ELSE 'locked'
               END as access_status
        FROM courses c
        LEFT JOIN payments p ON c.id = p.course_id AND p.user_id = ? AND p.status = 'completed'
        ORDER BY c.created_at DESC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    /**
     * Находит курс по ID и сразу подтягивает данные автора (создателя).
     * @param int $id ID курса
     * @return array|false
     */
    /**
     * Получает ВСЕ данные для страницы курса/лендинга одним запросом.
     * Включает инфо о курсе, авторе, модулях и уроках.
     * @param int $id ID курса
     * @return array|false
     */
    public function findCourseForLandingPage($id) {
        // Сначала получаем курс и автора
        $sql = "
        SELECT
            c.*,
            u.first_name AS author_first_name,
            u.last_name AS author_last_name,
            u.avatar_path AS author_avatar_path
        FROM
            courses c
        LEFT JOIN
            users u ON c.created_by = u.id
        WHERE
            c.id = ?
    ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        $course = $stmt->fetch();

        if (!$course) {
            return false;
        }

        // Теперь, если курс найден, подтягиваем его модули
        $stmtModules = $this->pdo->prepare("SELECT * FROM modules WHERE course_id = ? ORDER BY order_number ASC");
        $stmtModules->execute([$id]);
        $modules = $stmtModules->fetchAll();

        // А для каждого модуля подтягиваем его уроки
        $stmtLessons = $this->pdo->prepare("SELECT id, title FROM lessons WHERE module_id = ? ORDER BY order_number ASC");
        foreach ($modules as $key => $module) {
            $stmtLessons->execute([$module['id']]);
            $modules[$key]['lessons'] = $stmtLessons->fetchAll();
        }

        $course['modules'] = $modules; // Добавляем модули с уроками в основной массив курса
        return $course;
    }
}