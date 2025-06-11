<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/User.php';

class AuthController extends Controller {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
    }
    
    public function login() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        require_once __DIR__ . '/../../config/config.php';
        require_once __DIR__ . '/../services/SecurityService.php';
        
        if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
            $this->redirect('admin_stock');
        }
        
        $_SESSION['admin'] = false;
        $_SESSION['client'] = false;
        $_SESSION['personne'] = true;
        
        $csrf_token = SecurityService::generateCSRFToken();
        
        $error_message = "";
        if(isset($_GET['error'])) {
            switch($_GET['error']) {
                case 'password':
                    $error_message = "Mot de passe incorrect. Veuillez réessayer.";
                    break;
                case 'email':
                    $error_message = "Adresse email non reconnue. Veuillez vérifier ou vous inscrire.";
                    break;
                case 'password_mismatch':
                    $error_message = "Les mots de passe ne correspondent pas. Veuillez réessayer.";
                    break;
                case 'email_exists':
                    $error_message = "Cette adresse email est déjà utilisée. Veuillez en choisir une autre.";
                    break;
                case 'erreur_csrf':
                    $error_message = "Erreur de sécurité. Veuillez réessayer.";
                    break;
                default:
                    $error_message = "Une erreur est survenue. Veuillez réessayer.";
            }
        }
        
        $success_message = "";
        if(isset($_GET['success']) && $_GET['success'] == 'register') {
            $success_message = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
        }
        
        $this->render('auth/login', [
            'csrf_token' => $csrf_token,
            'error_message' => $error_message,
            'success_message' => $success_message,
            'pageTitle' => 'Se connecter - SneakersAddict',
            'css_files' => ['assets/css/login_style.css']
        ]);
    }
    
    public function authenticate() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        require_once __DIR__ . '/../services/SecurityService.php';
        
        $_SESSION['admin'] = false;
        $_SESSION['client'] = false;
        $_SESSION['personne'] = true;
        
        if (isset($_POST['register'])) {
            $this->handleRegister();
            return;
        } elseif (isset($_POST['login'])) {
            $this->handleLogin();
            return;
        }
        
        $this->redirect('login');
    }
    
    private function handleRegister() {
        require_once __DIR__ . '/../services/SecurityService.php';
        
        if (!isset($_POST['csrf_token']) || !SecurityService::validateCSRFToken($_POST['csrf_token'])) {
            $this->redirect('login', ['error' => 'erreur_csrf']);
            exit;
        }
        
        $nom = SecurityService::cleanInput($_POST['nom']);
        $prenom = SecurityService::cleanInput($_POST['prenom']);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $password = $_POST['mdp'];
        $confirm_password = $_POST['confirm_mdp'];
        
        if ($password !== $confirm_password) {
            $this->redirect('login', ['error' => 'password_mismatch']);
            exit;
        }
        
        if ($this->userModel->emailExists($email)) {
            $this->redirect('login', ['error' => 'email_exists']);
            exit;
        }
        
        if ($this->userModel->create($nom, $prenom, $email, $password)) {
            $this->redirect('login', ['success' => 'register']);
        } else {
            $this->redirect('login', ['error' => 'register_failed']);
        }
    }
    
    private function handleLogin() {
        require_once __DIR__ . '/../services/SecurityService.php';
        
        if (!isset($_POST['csrf_token']) || !SecurityService::validateCSRFToken($_POST['csrf_token'])) {
            $this->redirect('login', ['error' => 'erreur_csrf']);
            exit;
        }
        
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $password = $_POST['mdp'];
        
        $user = $this->userModel->findByEmail($email);
        
        if (!$user) {
            $this->redirect('login', ['error' => 'email']);
            exit;
        }
        
        if ($this->userModel->verifyPassword($password, $user['password'])) {
            $_SESSION['personne'] = false;
            
            if ($this->userModel->isAdmin($user)) {
                $_SESSION['admin'] = true;
                $_SESSION['client'] = false;
                $this->redirect('admin_stock');
            } else {
                $_SESSION['admin'] = false;
                $_SESSION['client'] = true;
                $this->redirect('home');
            }
        } else {
            $this->redirect('login', ['error' => 'password']);
        }
    }
    
    public function logout() {
        SecurityService::logoutUser();
    }
} 