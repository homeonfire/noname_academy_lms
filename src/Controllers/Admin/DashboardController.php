<?php
// src/Controllers/Admin/DashboardController.php

class AdminDashboardController extends AdminController {

    public function index() {
        $this->render('admin/dashboard/index', ['title' => 'Панель администратора']);
    }
}