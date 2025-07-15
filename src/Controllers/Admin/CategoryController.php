<?php
// src/Controllers/Admin/CategoryController.php
require_once __DIR__ . '/AdminController.php';

class AdminCategoryController extends AdminController {

    private $categoryModel;

    public function __construct() {
        parent::__construct();
        $this->categoryModel = new Category();
    }

    /**
     * Показывает страницу со списком всех категорий и формой добавления
     */
    public function index() {
        $categories = $this->categoryModel->getAll();
        $this->renderAdminPage('admin/categories/index', [
            'title' => 'Управление категориями',
            'categories' => $categories
        ]);
    }

    /**
     * Обрабатывает создание новой категории
     */
    public function create() {
        $name = $_POST['name'] ?? '';
        // ИСПРАВЛЕНО: Используем новую, умную функцию для создания слага
        $slug = $this->generateSlug($name);

        if (!empty($name) && !empty($slug)) {
            $this->categoryModel->create($name, $slug);
        }

        header('Location: /admin/categories');
        exit();
    }

    /**
     * Новая приватная функция для генерации правильного слага
     * @param string $string
     * @return string
     */
    private function generateSlug($string) {
        $converter = [
            'а' => 'a',   'б' => 'b',   'в' => 'v',   'г' => 'g',   'д' => 'd',
            'е' => 'e',   'ё' => 'e',   'ж' => 'zh',  'з' => 'z',   'и' => 'i',
            'й' => 'y',   'к' => 'k',   'л' => 'l',   'м' => 'm',   'н' => 'n',
            'о' => 'o',   'п' => 'p',   'р' => 'r',   'с' => 's',   'т' => 't',
            'у' => 'u',   'ф' => 'f',   'х' => 'h',   'ц' => 'c',   'ч' => 'ch',
            'ш' => 'sh',  'щ' => 'sch', 'ь' => '',    'ы' => 'y',   'ъ' => '',
            'э' => 'e',   'ю' => 'yu',  'я' => 'ya',
        ];

        $string = mb_strtolower($string);
        $string = strtr($string, $converter);
        $string = preg_replace('/[^a-z0-9-]+/', '-', $string); // Заменяем все не-латинские символы
        $string = trim($string, '-'); // Убираем лишние дефисы по краям
        return $string;
    }

    /**
     * Показывает форму редактирования категории
     */
    public function edit($id) {
        $category = $this->categoryModel->findById($id);
        if (!$category) {
            http_response_code(404);
            die('Категория не найдена');
        }
        $this->renderAdminPage('admin/categories/edit', [
            'title' => 'Редактировать категорию',
            'category' => $category
        ]);
    }

    /**
     * Обрабатывает обновление категории
     */
    public function update($id) {
        $name = $_POST['name'] ?? '';
        $slug = $this->generateSlug($name); // Используем наш существующий метод

        if (!empty($name) && !empty($slug)) {
            $this->categoryModel->update($id, $name, $slug);
        }

        header('Location: /admin/categories');
        exit();
    }

    /**
     * Обрабатывает удаление категории
     */
    public function delete($id) {
        $this->categoryModel->delete($id);
        header('Location: /admin/categories');
        exit();
    }
}