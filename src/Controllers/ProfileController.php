<?php
// src/Controllers/ProfileController.php

class ProfileController extends Controller {

    private $userModel;
    private $categoryModel; // Добавлено свойство для модели категорий

    public function __construct() {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit();
        }

        $this->userModel = new User();
        $this->categoryModel = new Category(); // Создаем экземпляр модели категорий
    }

    /**
     * Показывает страницу профиля
     */
    public function index() {
        $userId = $_SESSION['user']['id'];
        $user = $this->userModel->findById($userId);

        // Получаем все доступные категории для выбора
        $allCategories = $this->categoryModel->getAll();

        // Получаем ID категорий, которые пользователь уже выбрал
        $preferredCategoryIds = $this->userModel->getPreferredCategoryIds($userId);

        $data = [
            'title' => 'Мой профиль',
            'user' => $user,
            'allCategories' => $allCategories, // Передаем все категории
            'preferredCategoryIds' => $preferredCategoryIds // Передаем ID выбранных
        ];

        $this->render('profile/index', $data);
    }

    /**
     * Обрабатывает обновление профиля
     */
    public function update() {
        $userId = $_SESSION['user']['id'];
        $currentUser = $this->userModel->findById($userId);

        // --- ЛОГИКА ЗАГРУЗКИ АВАТАРА (без изменений) ---
        $avatarPath = $currentUser['avatar_path']; // Старый путь по умолчанию
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/uploads/avatars/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileName = uniqid() . '-' . basename($_FILES['avatar']['name']);
            $targetPath = $uploadDir . $fileName;

            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (in_array($_FILES['avatar']['type'], $allowedTypes)) {
                if (move_uploaded_file($_FILES['avatar']['tmp_name'], $targetPath)) {
                    $avatarPath = '/public/uploads/avatars/' . $fileName;
                    if (!empty($currentUser['avatar_path']) && strpos($currentUser['avatar_path'], 'default-avatar.png') === false) {
                        $oldAvatarFullPath = __DIR__ . '/../../' . ltrim($currentUser['avatar_path'], '/');
                        if (file_exists($oldAvatarFullPath)) {
                            unlink($oldAvatarFullPath);
                        }
                    }
                }
            }
        }

        // --- Обновляем ОСНОВНЫЕ ДАННЫЕ пользователя ---
        $data = [
            'first_name' => $_POST['first_name'] ?? '',
            'last_name' => $_POST['last_name'] ?? '',
            'experience_level' => $_POST['experience_level'] ?? 'beginner',
            'avatar_path' => $avatarPath
        ];
        $this->userModel->update($userId, $data);

        // --- Обновляем ИНТЕРЕСУЮЩИЕ КАТЕГОРИИ ---
        $categoryIds = $_POST['category_ids'] ?? [];
        $this->userModel->syncPreferredCategories($userId, $categoryIds);

        // --- Обновляем данные в сессии ---
        $updatedUser = $this->userModel->findById($userId);
        $_SESSION['user'] = $updatedUser;

        header('Location: /profile');
        exit();
    }
}