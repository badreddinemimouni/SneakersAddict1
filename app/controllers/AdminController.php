<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../services/SecurityService.php';

class AdminController extends Controller {
    private $productModel;
    private $userModel;
    private $pdo;
    
    public function __construct() {
        $this->productModel = new Product();
        $this->userModel = new User();
        $this->connectPDO();
    }
    
    private function connectPDO() {
        try {
            require_once __DIR__ . '/../../config/config.php';
            $this->pdo = new PDO('mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASSWORD, [
     PDO::MYSQL_ATTR_SSL_CA => true,
    PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            die("Erreur de connexion PDO : " . $e->getMessage());
        }
    }
    
    public function stock() {
        // Vérification session + headers no-cache
        SecurityService::setNoCacheHeaders();
        if (!$this->isAdmin()) {
            $this->redirect('login');
            return;
        }
        
        $message = '';
        $messageType = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || !SecurityService::validateCSRFToken($_POST['csrf_token'])) {
                $message = "Erreur de sécurité. Veuillez réessayer.";
                $messageType = "error";
            } else {
                try {
                    switch ($_POST['action']) {
                        case 'update_stock':
                            $this->updateStock($_POST);
                            $message = "Stock mis à jour avec succès !";
                            $messageType = "success";
                            break;
                            
                        case 'add_product':
                            $this->addProduct($_POST, $_FILES);
                            $message = "Nouveau produit ajouté avec succès !";
                            $messageType = "success";
                            break;
                            
                        case 'delete_product':
                            $this->deleteProduct($_POST['produit_id']);
                            $message = "Produit supprimé avec succès !";
                            $messageType = "success";
                            break;
                    }
                } catch (Exception $e) {
                    $message = "Erreur : " . $e->getMessage();
                    $messageType = "error";
                }
            }
        }
        
        if (isset($_GET['message']) && isset($_GET['type'])) {
            $message = SecurityService::cleanInput($_GET['message']);
            $messageType = SecurityService::cleanInput($_GET['type']);
        }
        
        $produits = $this->productModel->getAll();
        $pointures = $this->getAllSizes();
        
        foreach ($produits as &$produit) {
            $produit['stocks'] = $this->getProductStocks($produit['id']);
        }
        
        $csrf_token = SecurityService::generateCSRFToken();
        
        $this->render('admin/stock', [
            'produits' => $produits,
            'pointures' => $pointures,
            'message' => $message,
            'messageType' => $messageType,
            'csrf_token' => $csrf_token
        ]);
    }
    
    public function users() {
        // Vérification session + headers no-cache
        SecurityService::setNoCacheHeaders();
        if (!$this->isAdmin()) {
            $this->redirect('login');
            return;
        }
        
        $message = '';
        $messageType = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Debug - à enlever après test
            error_log("AdminController::users() POST reçu : " . print_r($_POST, true));
            
            if (!isset($_POST['csrf_token']) || !SecurityService::validateCSRFToken($_POST['csrf_token'])) {
                $message = "Erreur de sécurité. Veuillez réessayer.";
                $messageType = "error";
                error_log("Erreur CSRF dans users()");
            } else {
                try {
                    switch ($_POST['action']) {
                        case 'add_user':
                            error_log("Action add_user déclenchée");
                            $this->addUser($_POST);
                            $message = "Utilisateur ajouté avec succès !";
                            $messageType = "success";
                            break;
                            
                        case 'delete_user':
                            error_log("Action delete_user déclenchée pour user_id: " . $_POST['user_id']);
                            $this->userModel->deleteUser($_POST['user_id']);
                            $message = "Utilisateur supprimé avec succès !";
                            $messageType = "success";
                            break;
                            
                        case 'toggle_role':
                            error_log("Action toggle_role déclenchée pour user_id: " . $_POST['user_id']);
                            $this->userModel->toggleUserRole($_POST['user_id']);
                            $message = "Rôle utilisateur modifié avec succès !";
                            $messageType = "success";
                            error_log("Rôle modifié avec succès");
                            break;
                            
                        default:
                            error_log("Action inconnue: " . ($_POST['action'] ?? 'non définie'));
                            break;
                    }
                } catch (Exception $e) {
                    $message = "Erreur : " . $e->getMessage();
                    $messageType = "error";
                    error_log("Erreur dans users(): " . $e->getMessage());
                }
            }
        }
        
        $users = $this->userModel->getAllUsers();


       
        
        $csrf_token = SecurityService::generateCSRFToken();
        
        $this->render('admin/users', [
            'users' => $users,
            'message' => $message,
            'messageType' => $messageType,
            'csrf_token' => $csrf_token
        ]);
    }
    
    public function getStock() {
        header('Content-Type: application/json');
        
        if (!isset($_GET['produit_id']) || !isset($_GET['pointure_id'])) {
            echo json_encode(['success' => false, 'message' => 'Paramètres manquants']);
            exit();
        }
        
        $produit_id = (int)$_GET['produit_id'];
        $pointure_id = (int)$_GET['pointure_id'];
        
        try {
            $stock = $this->getStockAmount($produit_id, $pointure_id);
            echo json_encode(['success' => true, 'amount' => $stock]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        
        exit();
    }
    
    private function updateStock($data) {
        error_log("AdminController::updateStock() appelé avec données : " . print_r($data, true));
        
        $produit_id = (int)$data['produit_id'];
        $pointure_id = (int)$data['pointure_id'];
        $quantite = (int)$data['quantite'];
        $prix = (float)$data['prix'];
        
        error_log("Parsed data: produit_id=$produit_id, pointure_id=$pointure_id, quantite=$quantite, prix=$prix");
        
        $stock_size = $this->checkStockSize($produit_id, $pointure_id);
        error_log("Existing stock_size: " . print_r($stock_size, true));
        
        if ($stock_size) {
            $this->updateStockSize($stock_size['id'], $quantite);
            error_log("Stock mis à jour pour l'ID: " . $stock_size['id']);
        } else {
            $this->createStockSize($produit_id, $pointure_id, $quantite);
            error_log("Nouveau stock créé pour produit=$produit_id, pointure=$pointure_id");
        }
        
        $this->productModel->updatePrice($produit_id, $prix);
        error_log("Prix mis à jour pour le produit $produit_id");
    }
    
    private function addProduct($data, $files) {
        $nom = SecurityService::cleanInput($data['nom']);
        $prix = (float)$data['prix'];
        $couleur = SecurityService::cleanInput($data['couleur']);
        $image_path = null;
        
        if (isset($files['image_file']) && $files['image_file']['error'] == 0) {
            $image_path = $this->uploadImage($files['image_file']);
        }
        
        $this->productModel->create($nom, $prix, $couleur, $image_path);
    }
    
    private function deleteProduct($produit_id) {
        $produit = $this->productModel->getById($produit_id);
        
        if (!$produit) {
            throw new Exception("Produit introuvable.");
        }
        
        $this->deleteStockSizes($produit_id);
        
        $this->productModel->delete($produit_id);
        
        if ($produit['image'] && file_exists(__DIR__ . '/../../' . $produit['image']) && $produit['image'] != 'assets/images/default.webp') {
            unlink(__DIR__ . '/../../' . $produit['image']);
        }
    }
    
    private function uploadImage($file) {
        if ($file['size'] > 2000000) {
            throw new Exception("L'image est trop volumineuse. Taille maximale: 2 Mo.");
        }
        
        $allowed_types = ['image/jpeg', 'image/png', 'image/webp'];
        $file_info = getimagesize($file['tmp_name']);
        
        if (!$file_info || !in_array($file_info['mime'], $allowed_types)) {
            throw new Exception("Le format de l'image n'est pas accepté. Utilisez JPG, PNG ou WEBP.");
        }
        
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $new_filename = uniqid('sneaker_') . '.' . $extension;
        $upload_path = __DIR__ . '/../../assets/images/' . $new_filename;
        
        if (!move_uploaded_file($file['tmp_name'], $upload_path)) {
            throw new Exception("Erreur lors du téléchargement de l'image.");
        }
        
        return 'assets/images/' . $new_filename;
    }
    
    private function getAllSizes() {
        $stmt = $this->pdo->query("SELECT * FROM size ORDER BY size");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    private function getStockAmount($produit_id, $pointure_id) {
        $stmt = $this->pdo->prepare("SELECT amount FROM stock_size WHERE stock_id = ? AND size_id = ?");
        $stmt->execute([$produit_id, $pointure_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['amount'] : 0;
    }
    
    private function checkStockSize($produit_id, $pointure_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM stock_size WHERE stock_id = ? AND size_id = ?");
        $stmt->execute([$produit_id, $pointure_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    private function updateStockSize($id, $quantite) {
        $stmt = $this->pdo->prepare("UPDATE stock_size SET amount = ? WHERE id = ?");
        $stmt->execute([$quantite, $id]);
    }
    
    private function createStockSize($produit_id, $pointure_id, $quantite) {
        $stmt = $this->pdo->prepare("INSERT INTO stock_size (stock_id, size_id, amount) VALUES (?, ?, ?)");
        $stmt->execute([$produit_id, $pointure_id, $quantite]);
    }
    
    private function deleteStockSizes($produit_id) {
        $stmt = $this->pdo->prepare("DELETE FROM stock_size WHERE stock_id = ?");
        $stmt->execute([$produit_id]);
    }
    
    private function getProductStocks($produit_id) {
        $stmt = $this->pdo->prepare("
            SELECT s.size, ss.amount 
            FROM stock_size ss 
            JOIN size s ON ss.size_id = s.id 
            WHERE ss.stock_id = ? 
            ORDER BY s.size
        ");
        $stmt->execute([$produit_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    private function addUser($data) {
        $prenom = SecurityService::cleanInput($data['prenom']);
        $nom = SecurityService::cleanInput($data['nom']);
        $email = filter_var($data['email'], FILTER_VALIDATE_EMAIL);
        $password = $data['password'];
        $confirm_password = $data['confirm_password'];
        $grade = SecurityService::cleanInput($data['grade']);
        
        if ($password !== $confirm_password) {
            throw new Exception("Les mots de passe ne correspondent pas.");
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("L'adresse email n'est pas valide.");
        }
        
        if ($this->userModel->emailExists($email)) {
            throw new Exception("Cette adresse email est déjà utilisée.");
        }
        
        if (strlen($password) < 4) {
            throw new Exception("Le mot de passe doit contenir au moins 4 caractères.");
        }
        
        if (!in_array($grade, ['client', 'admin'])) {
            throw new Exception("Rôle invalide.");
        }
        
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO user_site (prenom, nom, email, password, Grade) VALUES (?, ?, ?, ?, ?)");
        $result = $stmt->execute([$prenom, $nom, $email, $hashedPassword, $grade]);
        
        if (!$result) {
            throw new Exception("Erreur lors de la création de l'utilisateur.");
        }
    }
} 