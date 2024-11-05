<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/Controllers/ProductController.php';
require_once __DIR__ . '/../src/Controllers/CartController.php';

$action = $_GET['action'] ?? 'home';

$productController = new ProductController();
$cartController = new CartController();

switch ($action) {
    case 'home':
        $productController->index();
        break;
    case 'addToCart':
        $cartController->addToCart($_POST['product_id']);
        break;
    case 'viewCart':
        $cartController->viewCart();
        break;
    default:
        header("HTTP/1.0 404 Not Found");
        echo "404 Not Found";
        break;
}

// public/index.php
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->get('/', function ($request, $response, $args) {
    return $response->write('Hello World');
});

$app->run();