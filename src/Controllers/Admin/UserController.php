<?php
// src/Controllers/Admin/UserController.php

class AdminUserController extends AdminController {
    private $visitModel; // Добавляем свойство для модели визитов
    private $categoryModel; // Добавляем свойство для модели категорий
    public function __construct() {
        parent::__construct();
        $this->visitModel = new Visit();
        $this->categoryModel = new Category();
    }

    /**
     * Показывает список всех пользователей
     */
    public function index() {
        $users = $this->userModel->getAll();
        $this->renderAdminPage('admin/users/index', [
            'title' => 'Управление пользователями',
            'users' => $users
        ]);
    }

    /**
     * Обрабатывает смену роли пользователя
     */
    public function changeRole() {
        $userId = $_POST['user_id'] ?? null;
        $role = $_POST['role'] ?? null;

        // Дополнительная проверка, чтобы админ не мог случайно изменить свою роль
        if ($userId && $userId != $_SESSION['user']['id']) {
            $this->userModel->updateRole($userId, $role);
        }

        // Возвращаем на страницу со списком пользователей
        header('Location: /admin/users');
        exit();
    }

    // Добавьте этот метод в класс UserController (в админке)
    /**
     * Возвращает данные пользователя в формате JSON для модального окна
     */
    public function getUserJson($id) {
        $user = $this->userModel->findById($id);
        if ($user) {
            // Отправляем заголовок, что это JSON
            header('Content-Type: application/json');
            // Убираем хэш пароля для безопасности
            unset($user['password_hash']);
            echo json_encode($user);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Пользователь не найден']);
        }
        exit(); // Важно завершить выполнение скрипта
    }

    // Добавьте этот метод в класс UserController (в админке)
    public function updateUser($id) {
        if ($id == $_SESSION['user']['id'] && $_POST['role'] !== 'admin') {
            header('Location: /admin/users/show/' . $id);
            exit();
        }

        $data = [
            'first_name' => $_POST['first_name'] ?? '',
            'last_name' => $_POST['last_name'] ?? '',
            'role' => $_POST['role'] ?? 'user',
        ];

        $this->userModel->updateUserData($id, $data);

        // Редирект обратно на карточку пользователя
        header('Location: /admin/users/show/' . $id);
        exit();
    }

    /**
     * Показывает детальную карточку пользователя.
     * @param int $id
     */
    public function show($id) {
        $user = $this->userModel->findById($id);
        if (!$user) {
            http_response_code(404);
            die('Пользователь не найден');
        }

        // Данные для карточки
        $firstUtm = $this->visitModel->getFirstUtmByUserId($id);
        $preferredCategoryIds = $this->userModel->getPreferredCategoryIds($id);
        $allCategories = $this->categoryModel->getAll();

        // Данные для пагинации
        $page = 1;
        $limit = 10;
        $visits = $this->visitModel->getVisitsByUserId($id, $page, $limit);
        $totalVisits = $this->visitModel->countVisitsByUserId($id);
        $totalPages = ceil($totalVisits / $limit);

        $this->renderAdminPage('admin/users/show', [
            'title' => 'Карточка пользователя: ' . $user['email'],
            'user' => $user,
            'firstUtm' => $firstUtm,
            'preferredCategoryIds' => $preferredCategoryIds,
            'allCategories' => $allCategories,
            'visits' => $visits,
            'currentPage' => $page,
            'totalPages' => $totalPages
        ]);
    }

    public function getVisitsJson($id) {
        $page = $_GET['page'] ?? 1;
        $limit = 10;

        $visits = $this->visitModel->getVisitsByUserId($id, (int)$page, $limit);

        header('Content-Type: application/json');
        echo json_encode($visits);
        exit();
    }
}