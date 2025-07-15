<?php
// src/Config/database.php

define('DB_HOST', 'localhost');
define('DB_NAME', 'alpha_aifire'); // <-- ВАШЕ ИМЯ БД
define('DB_USER', 'alpha_aifire'); // <-- ВАШ ПОЛЬЗОВАТЕЛЬ БД
define('DB_PASS', 'IPNStVhpd4T1XL08'); // <-- ВАШ ПАРОЛЬ БД

/**
 * Функция для получения соединения с базой данных (PDO)
 * @return PDO
 */
function getDBConnection() {
    file_put_contents(__DIR__ . '/../../debug.log', "[getDBConnection] called\n", FILE_APPEND);
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    try {
        file_put_contents(__DIR__ . '/../../debug.log', "[getDBConnection] returning PDO\n", FILE_APPEND);
        return new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch (\PDOException $e) {
        // В реальном проекте здесь должно быть логирование, а не вывод ошибки
        throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }
}