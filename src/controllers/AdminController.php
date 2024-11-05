<?php
require_once __DIR__ . '/../Models/Product.php';
require_once __DIR__ . '/../Helpers/ValidationHelper.php';
require_once __DIR__ . '/../Helpers/SecurityHelper.php';
require_once __DIR__ . '/../Services/AuthService.php';
require_once __DIR__ . '/../Services/LogService.php';

class AdminController {
    private $productModel;
    
    public function __construct() {
        $this->productModel = new Product();
    }
    
    public function addProduct() {
        if (!$this->validateRequest()) {
            return false;
        }
        
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT);
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
        
        return $this->productModel->addProduct($name, $price, $description);
    }
    
    private function validateRequest() {
        if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
            return false;
        }
        
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            return false;
        }
        
        return true;
    }
}