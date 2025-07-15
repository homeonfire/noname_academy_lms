<?php
// src/Core/CSRF.php

class CSRF {
    
    /**
     * Генерирует CSRF токен
     * @return string
     */
    public static function generateToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    /**
     * Проверяет CSRF токен
     * @param string $token
     * @return bool
     */
    public static function verifyToken($token) {
        if (!isset($_SESSION['csrf_token'])) {
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $token);
    }
    
    /**
     * Обновляет CSRF токен
     * @return string
     */
    public static function refreshToken() {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        return $_SESSION['csrf_token'];
    }
    
    /**
     * Получает HTML для скрытого поля с токеном
     * @return string
     */
    public static function getTokenField() {
        $token = self::generateToken();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
    }
    
    /**
     * Проверяет POST запрос на CSRF токен
     * @return bool
     */
    public static function checkPostRequest() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return true; // Не POST запрос, пропускаем
        }
        
        $token = $_POST['csrf_token'] ?? null;
        if (!$token) {
            return false;
        }
        
        return self::verifyToken($token);
    }
} 