<?php
// src/Controllers/Admin/VisitController.php

class AdminVisitController extends AdminController {

    private $visitModel;

    public function __construct() {
        parent::__construct(); // Вызываем конструктор родителя для проверки прав админа
        $this->visitModel = new Visit();
    }

    /**
     * Показывает страницу со списком посещений
     */
    public function index() {
        $visits = $this->visitModel->getLatestUniqueVisits();

        $this->renderAdminPage('admin/visits/index', [
            'title' => 'Статистика посещений',
            'visits' => $visits
        ]);
    }
}