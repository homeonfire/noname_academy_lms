<?php
// src/Controllers/FavoriteController.php

class FavoriteController extends Controller {

    private $favoriteModel;

    // --- БЕЗ ИЗМЕНЕНИЙ ---
    public function __construct() {
        if (!isset($_SESSION['user'])) {
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                header('Content-Type: application/json');
                http_response_code(401);
                echo json_encode(['status' => 'error', 'message' => 'Требуется авторизация']);
            } else {
                header('Location: /login');
            }
            exit();
        }
        $this->favoriteModel = new Favorite();
    }

    public function toggle() {
        header('Content-Type: application/json');

        $userId = $_SESSION['user']['id'];
        $itemId = $_POST['item_id'] ?? null;
        $itemType = $_POST['item_type'] ?? null;

        if (!$itemId || !$itemType || !in_array($itemType, ['course', 'lesson', 'guide'])) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Неверные параметры']);
            exit();
        }

        // Разрешаем только itemType === 'lesson'
        if ($itemType !== 'lesson') {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Можно добавлять в избранное только уроки']);
            return;
        }

        $result = $this->favoriteModel->toggle($userId, $itemId, $itemType);

        echo json_encode(['status' => 'success', 'action' => $result]);
        exit();
    }
    // --- КОНЕЦ БЛОКА БЕЗ ИЗМЕНЕНИЙ ---

    // --- НАЧАЛО ИЗМЕНЕНИЙ ---
    public function index() {
        $userId = $_SESSION['user']['id'];
        $favoritedLessons = $this->favoriteModel->getFavoritedLessons($userId);
        $data = [
            'favoritedLessons' => $favoritedLessons
        ];
        $this->render('favorites/index', $data);
    }
    // --- КОНЕЦ ИЗМЕНЕНИЙ ---
}