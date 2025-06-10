<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Order.php';

class OrderController extends Controller {
    private $orderModel;
    
    public function __construct() {
        $this->orderModel = new Order();
    }
    
    private function checkOrderAccess() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
               if (!$this->isLoggedIn()) {
            $this->redirect('login');
            return false;
        }
        
        if (!$this->isClient() && !$this->isAdmin()) {
            $this->redirect('main');
            return false;
        }
        
        return true;
    }
    
    public function checkout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        require_once __DIR__ . '/../../config/config.php';
        require_once __DIR__ . '/../services/SecurityService.php';
        
        $this->checkOrderAccess();
        
        if (!isset($_SESSION['panier']) || empty($_SESSION['panier'])) {
            $this->redirect('cart');
        }
        
        $total = 0;
        foreach ($_SESSION['panier'] as $produit) {
            $total += $produit['prix'] * $produit['quantite'];
        }
        
        $csrf_token = SecurityService::generateCSRFToken();
        $errors = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || !SecurityService::validateCSRFToken($_POST['csrf_token'])) {
                $errors[] = "Erreur de sécurité. Veuillez réessayer.";
            } else {
                $nom = SecurityService::cleanInput($_POST['nom'] ?? '');
                $prenom = SecurityService::cleanInput($_POST['prenom'] ?? '');
                $adresse = SecurityService::cleanInput($_POST['adresse'] ?? '');
                $ville = SecurityService::cleanInput($_POST['ville'] ?? '');
                $code_postal = SecurityService::cleanInput($_POST['code_postal'] ?? '');
                $email = SecurityService::cleanInput($_POST['email'] ?? '');
                $telephone = SecurityService::cleanInput($_POST['telephone'] ?? '');
                
                if (empty($nom)) $errors[] = "Le nom est requis";
                if (empty($prenom)) $errors[] = "Le prénom est requis";
                if (empty($adresse)) $errors[] = "L'adresse est requise";
                if (empty($ville)) $errors[] = "La ville est requise";
                if (empty($code_postal)) $errors[] = "Le code postal est requis";
                if (empty($email)) $errors[] = "L'email est requis";
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "L'email n'est pas valide";
                if (empty($telephone)) $errors[] = "Le téléphone est requis";
                
                if (empty($errors)) {
                    $_SESSION['livraison'] = [
                        'nom' => $nom,
                        'prenom' => $prenom,
                        'adresse' => $adresse,
                        'ville' => $ville,
                        'code_postal' => $code_postal,
                        'email' => $email,
                        'telephone' => $telephone
                    ];
                    
                    $this->redirect('payment');
                }
            }
        }
        
        $this->render('orders/checkout', [
            'pageTitle' => 'Checkout - SneakersAddict',
            'panier' => $_SESSION['panier'],
            'total' => $total,
            'csrf_token' => $csrf_token,
            'errors' => $errors
        ]); 
    }
    
    public function payment() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        require_once __DIR__ . '/../../config/config.php';
        require_once __DIR__ . '/../services/SecurityService.php';
        
        $this->checkOrderAccess();
        
        if (!isset($_SESSION['panier']) || empty($_SESSION['panier']) || !isset($_SESSION['livraison'])) {
            $this->redirect('checkout');
        }
        
        $total = 0;
        foreach ($_SESSION['panier'] as $produit) {
            $total += $produit['prix'] * $produit['quantite'];
        }
        
        $csrf_token = SecurityService::generateCSRFToken();
        $errors = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || !SecurityService::validateCSRFToken($_POST['csrf_token'])) {
                $errors[] = "Erreur de sécurité. Veuillez réessayer.";
            } else {
                $card_number = SecurityService::cleanInput($_POST['card_number'] ?? '');
                $card_name = SecurityService::cleanInput($_POST['card_name'] ?? '');
                $card_expiry = SecurityService::cleanInput($_POST['card_expiry'] ?? '');
                $card_cvv = SecurityService::cleanInput($_POST['card_cvv'] ?? '');
                
                if (empty($card_number)) $errors[] = "Le numéro de carte est requis";
                if (empty($card_name)) $errors[] = "Le nom sur la carte est requis";
                if (empty($card_expiry)) $errors[] = "La date d'expiration est requise";
                if (empty($card_cvv)) $errors[] = "Le code de sécurité est requis";
                
                if (!preg_match('/^\d{16}$/', str_replace(' ', '', $card_number))) {
                    $errors[] = "Le numéro de carte doit contenir 16 chiffres";
                }
                
                if (!preg_match('/^\d{3,4}$/', $card_cvv)) {
                    $errors[] = "Le code de sécurité doit contenir 3 ou 4 chiffres";
                }
                
                if (empty($errors)) {
                    $order_number = 'SA-' . date('YmdHis') . '-' . rand(1000, 9999);
                    
                    $_SESSION['order'] = [
                        'number' => $order_number,
                        'date' => date('Y-m-d H:i:s'),
                        'total' => $total,
                        'products' => $_SESSION['panier'],
                        'shipping' => $_SESSION['livraison']
                    ];
                    
                    if (isset($_POST['action']) && $_POST['action'] == 'payer') {
                        $this->orderModel->updateStock($_SESSION['panier']);
                    }
                    
                    $_SESSION['panier'] = array();
                    $this->redirect('confirmation');
                }
            }
        }
        
        $this->render('orders/payment', [
            'pageTitle' => 'Paiement - SneakersAddict',
            'panier' => $_SESSION['panier'],
            'total' => $total,
            'csrf_token' => $csrf_token,
            'errors' => $errors
        ]); 
    }
    
    public function confirmation() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        require_once __DIR__ . '/../../config/config.php';
        require_once __DIR__ . '/../services/SecurityService.php';
        
        $this->checkOrderAccess();
        
        if (!isset($_SESSION['order'])) {
            $this->redirect('products');
        }
        
        $this->render('orders/confirmation', [
            'pageTitle' => 'Confirmation de commande - SneakersAddict',
            'order' => $_SESSION['order']
        ]); 
    }
} 