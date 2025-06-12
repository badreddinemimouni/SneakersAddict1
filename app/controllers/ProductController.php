<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Product.php';

class ProductController extends Controller {
    private $pdo;
    private $productModel;

    public function __construct() {
        $this->pdo = $this->connectPDO();
        $this->productModel = new Product($this->pdo);
    }

    private function connectPDO() {
        require_once __DIR__ . '/../../config/config.php';
        $pdo = new PDO('mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASSWORD, [
     PDO::MYSQL_ATTR_SSL_CA => true,
    PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }

    public function checkStock() {
        header('Content-Type: application/json');
        
        if (!isset($_GET['product_id']) || !isset($_GET['size'])) {
            echo json_encode(['error' => 'Paramètres manquants']);
            exit;
        }

        $product_id = (int)$_GET['product_id'];
        $size = $_GET['size']; 

        try {
            $sql = "SELECT COALESCE(ss.amount, 0) as stock
                    FROM size sz
                    LEFT JOIN stock_size ss ON sz.id = ss.size_id AND ss.stock_id = ?
                    WHERE sz.size = ?";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$product_id, $size]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $stock = $result ? (int)$result['stock'] : 0;
            
            echo json_encode([
                'available' => $stock > 0,
                'stock' => $stock
            ]);
            
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Erreur de vérification']);
        }
        
        exit;
    }
    
    public function show() {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            $this->redirect('products');
            return;
        }
        
        $product = $this->productModel->getById($id);
        
        
        if (!$product) {
            $this->redirect('products');
            return;
        }
        
        $this->render('products/show', [
            'product' => $product,
            'pageTitle' => $product['nom'] . ' - SneakersAddict'
        ]);
    }
    
    public function index() {
        $products = $this->productModel->getAll();
        
        $this->render('products/index', [
            'products' => $products,
            'pageTitle' => 'Nos produits - SneakersAddict'
        ]);
    }
} 