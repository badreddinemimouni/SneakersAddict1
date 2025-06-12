<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Cart.php';

class CartController extends Controller {
    
    private $cartModel;
    
    public function __construct() {
        $this->cartModel = new Cart();
    }
    
    public function index() {
        $this->checkAuth();
        
        $panier = isset($_SESSION['panier']) ? $_SESSION['panier'] : [];
        $total = 0;
        
        foreach ($panier as $item) {
            $total += $item['prix'] * $item['quantite'];
        }
        
        $this->render('cart/index', [
            'panier' => $panier,
            'total' => $total,
            'pageTitle' => 'Mon Panier - SneakersAddict'
        ]); 
    }
    
    public function remove() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (isset($_GET['id'])) {
            $produit_id = $_GET['id'];
            if (isset($_SESSION['panier'][$produit_id])) {
                unset($_SESSION['panier'][$produit_id]);
            }
            $this->redirect('cart');
            return;
        }
        
        if (isset($_POST['delete_product_cart']) && isset($_POST['produit_id'])) {
            $produit_id = $_POST['produit_id'];
            
            if (isset($_SESSION['panier'][$produit_id])) {
                unset($_SESSION['panier'][$produit_id]);
            }
        }
        
        $this->redirect('cart');
    }
    
    public function update() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        
        if (isset($_POST['action']) && $_POST['action'] === 'add') {
            if (isset($_POST['product_id']) && isset($_POST['size']) && isset($_POST['quantity'])) {
                $product_id = (int)$_POST['product_id'];
                $size = $_POST['size'];
                $quantity = (int)$_POST['quantity'];
                
                // error_log("Ajout au panier : product_id=$product_id, size=$size, quantity=$quantity");
                
                if ($product_id > 0 && !empty($size) && $quantity > 0) {
                    $this->cartModel->addProduct($product_id, $size, $quantity);
                    error_log("Produit ajouté au panier avec succès");
                } else {
                    error_log("Paramètres invalides pour ajout panier");
                }
            } else {
                error_log("Paramètres manquants pour ajout panier");
            }
            $this->redirect('cart');
            return;
        }
        
        if (isset($_POST['edit']) && isset($_POST['produit_id']) && isset($_POST['nouvelle_quantite'])) {
            $produit_id = $_POST['produit_id'];
            $nouvelle_quantite = (int)$_POST['nouvelle_quantite'];
            
            if (isset($_SESSION['panier'][$produit_id]) && $nouvelle_quantite > 0) {
                $_SESSION['panier'][$produit_id]['quantite'] = $nouvelle_quantite;
            }
        }
        
        if (isset($_POST['delete_product_cart']) && isset($_POST['produit_id'])) {
            $this->remove();
            return;
        }
        
        if (isset($_POST['vider'])) {
            $_SESSION['panier'] = [];
        }
        
        $this->redirect('cart');
    }
} 