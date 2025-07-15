<?php
// src/Controllers/AuthController.php

class AuthController extends Controller {

    private $userModel;
    private $categoryModel; // Добавляем модель категорий

    // --- НАЧАЛО ИЗМЕНЕНИЙ В КОНСТРУКТОРЕ ---
    public function __construct() {
        $this->userModel = new User();
        $this->categoryModel = new Category(); // Создаем экземпляр
    }
    // --- КОНЕЦ ИЗМЕНЕНИЙ ---

    // --- БЕЗ ИЗМЕНЕНИЙ ---
    public function showLoginPage() {
        $this->render('auth/login');
    }

    public function showRegistrationPage() {
        $this->render('auth/register');
    }
    // --- КОНЕЦ БЛОКА БЕЗ ИЗМЕНЕНИЙ ---

    // --- НАЧАЛО ИЗМЕНЕНИЙ ---
    /**
     * Шаг 1: Регистрация email и пароля
     */
    public function registerUser() {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';
        $error = '';

        if (empty($email) || empty($password)) {
            $error = "Все поля обязательны для заполнения.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Некорректный формат email.";
        } elseif ($password !== $password_confirm) {
            $error = "Пароли не совпадают.";
        } elseif (strlen($password) < 6) {
            $error = "Пароль должен быть не менее 6 символов.";
        } elseif ($this->userModel->findByEmail($email)) {
            $error = "Пользователь с таким email уже существует.";
        }

        if ($error) {
            $this->render('auth/register', ['error' => $error]);
            return;
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $userId = $this->userModel->create($email, $passwordHash); // Получаем ID созданного пользователя

        if ($userId) {
            // Сразу авторизуем пользователя, чтобы связать с ним следующие шаги
            $user = $this->userModel->findById($userId);
            $_SESSION['user'] = $user;
            // Перенаправляем на второй шаг регистрации
            header('Location: /register/step2');
            exit();
        } else {
            $this->render('auth/register', ['error' => 'Произошла ошибка при регистрации. Попробуйте снова.']);
        }
    }

    /**
     * Шаг 2: Отображение страницы выбора уровня навыка
     */
    public function showStep2() {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit();
        }
        $this->render('auth/register-step2', ['title' => 'Шаг 2: Ваш уровень']);
    }

    /**
     * Шаг 2: Обработка выбора уровня навыка
     */
    public function processStep2() {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit();
        }

        $userId = $_SESSION['user']['id'];
        $data = [
            'experience_level' => $_POST['experience_level'] ?? 'beginner'
        ];

        // Используем специальный метод для обновления только нужных полей
        $this->userModel->updateUserProfileData($userId, $data);

        header('Location: /register/step3');
        exit();
    }

    /**
     * Шаг 3: Отображение страницы выбора интересующих навыков
     */
    public function showStep3() {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit();
        }

        $allCategories = $this->categoryModel->getAll();

        $this->render('auth/register-step3', [
            'title' => 'Шаг 3: Ваши интересы',
            'allCategories' => $allCategories
        ]);
    }

    /**
     * Шаг 3: Обработка выбора интересующих навыков
     */
    public function processStep3() {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit();
        }

        $userId = $_SESSION['user']['id'];
        $categoryIds = $_POST['category_ids'] ?? [];

        $this->userModel->syncPreferredCategories($userId, $categoryIds);

        // Регистрация завершена, отправляем на дашборд
        header('Location: /dashboard');
        exit();
    }
    // --- КОНЕЦ ИЗМЕНЕНИЙ ---

    // --- БЕЗ ИЗМЕНЕНИЙ ---
    public function loginUser() {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = $this->userModel->findByEmail($email);

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user'] = $user;
            header('Location: /dashboard');
            exit();
        } else {
            $this->render('auth/login', ['error' => 'Неверный email или пароль.']);
        }
    }

    public function logout() {
        session_unset();
        session_destroy();
        header('Location: /login');
        exit();
    }
    // --- КОНЕЦ БЛОКА БЕЗ ИЗМЕНЕНИЙ ---
}