<?php
class Controller {
    public function render($view, $data = []) {
        // Превращаем ключи массива в переменные (например, $data['title'] станет $title)
        extract($data);

        // Формируем путь к файлу вида
        $viewPath = __DIR__ . '/../Views/' . $view . '.php';

        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            // Если файл не найден, выводим ошибку
            die("Ошибка: файл вида не найден по пути: " . $viewPath);
        }
    }
}