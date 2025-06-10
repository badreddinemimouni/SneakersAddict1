<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

session_start();

require_once 'app/core/Router.php';
require_once 'app/core/Controller.php';

$router = new Router();

$router->get('home', 'HomeController', 'index');
$router->get('main', 'HomeController', 'index'); 

$router->get('login', 'AuthController', 'login');
$router->post('login', 'AuthController', 'authenticate');
$router->get('info', 'AuthController', 'authenticate');
$router->post('info', 'AuthController', 'authenticate');
$router->get('logout', 'AuthController', 'logout');
$router->get('deconnexion', 'AuthController', 'logout'); 

$router->get('products', 'ProductController', 'index');
$router->get('Produits', 'ProductController', 'index'); 
$router->get('product', 'ProductController', 'show');
$router->get('Chaussure', 'ProductController', 'show'); 
$router->get('check_stock', 'ProductController', 'checkStock');

$router->get('cart', 'CartController', 'index');
$router->post('cart', 'CartController', 'update');
$router->get('Panier', 'CartController', 'index'); 
$router->post('Panier', 'CartController', 'update'); 

$router->get('contact', 'ContactController', 'index');
$router->post('contact', 'ContactController', 'send');
$router->get('Contact', 'ContactController', 'index'); 
$router->post('contact_process', 'ContactController', 'send');
$router->post('traitement_contact', 'ContactController', 'send'); 

$router->get('checkout', 'OrderController', 'checkout');
$router->post('checkout', 'OrderController', 'checkout');
$router->get('payment', 'OrderController', 'payment');
$router->post('payment', 'OrderController', 'payment');
$router->get('confirmation', 'OrderController', 'confirmation');

$router->get('admin_stock', 'AdminController', 'stock');
$router->post('admin_stock', 'AdminController', 'stock');
$router->get('admin/stock', 'AdminController', 'stock'); 
$router->post('admin/stock', 'AdminController', 'stock'); 

$router->get('admin_users', 'AdminController', 'users');
$router->post('admin_users', 'AdminController', 'users');
$router->get('admin/admin_users', 'AdminController', 'users'); 
$router->post('admin/admin_users', 'AdminController', 'users'); 

$router->get('admin_get_stock', 'AdminController', 'getStock');
$router->get('admin/get_stock', 'AdminController', 'getStock'); 

$router->dispatch();
?>