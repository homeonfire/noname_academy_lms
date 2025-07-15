<?php
// src/Controllers/Admin/CourseController.php

class AdminCourseController extends AdminController {

    private $courseModel;
    private $categoryModel;

    // --- БЕЗ ИЗМЕНЕНИЙ ---
    public function __construct() {
        parent::__construct();
        $this->courseModel = new Course();
        $this->categoryModel = new Category();
    }
    // --- КОНЕЦ БЛОКА БЕЗ ИЗМЕНЕНИЙ ---

    // --- НАЧАЛО ИЗМЕНЕНИЙ ---
    /**
     * Показывает список всех курсов или мастер-классов
     */
    public function index() {
        // Определяем, какой тип контента показывать, по URL
        $type = (strpos($_SERVER['REQUEST_URI'], 'masterclasses') !== false) ? 'masterclass' : 'course';
        $pageTitle = ($type === 'masterclass') ? 'Управление мастер-классами' : 'Управление курсами';

        $courses = $this->courseModel->getAll($type);
        $this->renderAdminPage('admin/courses/index', [
            'title' => $pageTitle,
            'courses' => $courses,
            'type' => $type // Передаем тип в шаблон для правильных ссылок
        ]);
    }

    /**
     * Показывает форму для создания нового элемента
     */
    public function new() {
        $type = (strpos($_SERVER['REQUEST_URI'], 'masterclasses') !== false) ? 'masterclass' : 'course';
        $pageTitle = ($type === 'masterclass') ? 'Новый мастер-класс' : 'Новый курс';

        $categories = $this->categoryModel->getAll();
        $this->renderAdminPage('admin/courses/new', [
            'title' => $pageTitle,
            'categories' => $categories,
            'type' => $type
        ]);
    }

    /**
     * Обрабатывает создание нового элемента
     */
    public function create() {
        $coverUrl = $this->handleImageUpload($_FILES['cover_url'] ?? null);
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $difficulty_level = $_POST['difficulty_level'] ?? 'beginner';
        $type = $_POST['type'] ?? 'course';
        $created_by = $_SESSION['user']['id'] ?? null;
        $price = isset($_POST['price']) ? floatval($_POST['price']) : 0.00;
        $isFree = isset($_POST['is_free']) && $_POST['is_free'] == '1';
        if ($isFree) { $price = 0.00; }
        if (empty($title)) {
            // ... обработка ошибки
        }
        $lastInsertId = $this->courseModel->create($title, $description, $difficulty_level, $type, $coverUrl, $created_by, $price, $isFree);
        if ($lastInsertId) {
            $categoryIds = $_POST['category_ids'] ?? [];
            $this->courseModel->syncCategories($lastInsertId, $categoryIds);
        }
        $redirectUrl = ($type === 'masterclass') ? '/admin/masterclasses' : '/admin/courses';
        header('Location: ' . $redirectUrl);
        exit();
    }
    // --- КОНЕЦ ИЗМЕНЕНИЙ ---


    // --- БЕЗ ИЗМЕНЕНИЙ (остальные методы) ---
    public function content($id) {
        $course = $this->courseModel->getCourseWithModulesAndLessons($id);
        if (!$course) {
            http_response_code(404);
            die('Элемент не найден');
        }
        $this->renderAdminPage('admin/courses/content', [
            'title' => 'Управление контентом',
            'course' => $course
        ]);
    }

    public function edit($id) {
        $course = $this->courseModel->findById($id);
        if (!$course) { http_response_code(404); die('Элемент не найден'); }

        $pageTitle = ($course['type'] === 'masterclass') ? 'Редактировать мастер-класс' : 'Редактировать курс';
        $categories = $this->categoryModel->getAll();
        $courseCategoryIds = $this->courseModel->getCategoryIdsForCourse($id);

        $this->renderAdminPage('admin/courses/edit', [
            'title' => $pageTitle,
            'course' => $course,
            'categories' => $categories,
            'courseCategoryIds' => $courseCategoryIds
        ]);
    }

    public function update($id) {
        $currentCourse = $this->courseModel->findById($id);
        $currentCoverPath = $currentCourse['cover_url'] ?? null;
        $coverUrl = $this->handleImageUpload($_FILES['cover_url'] ?? null, $currentCoverPath);
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $difficulty_level = $_POST['difficulty_level'] ?? 'beginner';
        $price = isset($_POST['price']) ? floatval($_POST['price']) : 0.00;
        $isFree = isset($_POST['is_free']) && $_POST['is_free'] == '1';
        if ($isFree) { $price = 0.00; }
        if (empty($title)) {
            header('Location: /admin/courses/edit/' . $id);
            exit();
        }
        $this->courseModel->update($id, $title, $description, $difficulty_level, $coverUrl, $price, $isFree);
        $categoryIds = $_POST['category_ids'] ?? [];
        $this->courseModel->syncCategories($id, $categoryIds);
        $course = $this->courseModel->findById($id);
        $redirectUrl = ($course['type'] === 'masterclass') ? '/admin/masterclasses' : '/admin/courses';
        header('Location: ' . $redirectUrl);
        exit();
    }

    public function delete($id) {
        // Перед удалением узнаем тип, чтобы правильно редиректить
        $course = $this->courseModel->findById($id);
        $redirectUrl = ($course && $course['type'] === 'masterclass') ? '/admin/masterclasses' : '/admin/courses';

        $this->courseModel->delete($id);
        header('Location: ' . $redirectUrl);
        exit();
    }
    // --- КОНЕЦ БЛОКА БЕЗ ИЗМЕНЕНИЙ ---

    private function handleImageUpload($file, $currentPath = null) {
        if (isset($file) && $file['error'] === UPLOAD_ERR_OK) {
            // Путь для сохранения обложек курсов
            $uploadDir = __DIR__ . '/../../../public/assets/uploads/courses/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            if ($currentPath && file_exists(__DIR__ . '/../../../public' . $currentPath)) {
                unlink(__DIR__ . '/../../../public' . $currentPath);
            }

            // "Очищаем" имя файла от спецсимволов и пробелов
            $fileExtension = pathinfo(basename($file['name']), PATHINFO_EXTENSION);
            $fileNameWithoutExt = pathinfo(basename($file['name']), PATHINFO_FILENAME);
            $converter = [
                'а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'e','ж'=>'zh',
                'з'=>'z','и'=>'i','й'=>'y','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o',
                'п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'h','ц'=>'c',
                'ч'=>'ch','ш'=>'sh','щ'=>'sch','ь'=>'','ы'=>'y','ъ'=>'','э'=>'e','ю'=>'yu','я'=>'ya',
                ' '=>'-'
            ];
            $cleanName = strtr(mb_strtolower($fileNameWithoutExt), $converter);
            $cleanName = preg_replace('/[^a-z0-9-]+/', '-', $cleanName);
            $cleanName = trim($cleanName, '-');
            $finalFileName = uniqid('course_') . '-' . $cleanName . '.' . $fileExtension;

            $targetPath = $uploadDir . $finalFileName;

            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                // Возвращаем путь с /public, как мы выяснили ранее
                return '/public/assets/uploads/courses/' . $finalFileName;
            }
        }
        return $currentPath;
    }
}