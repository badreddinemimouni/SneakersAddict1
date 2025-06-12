<?php

class SecurityService {
    
    public static function cleanInput($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        return $data;
    }


    public static function isLoggedIn($role = null) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if ($role === 'admin') {
            return isset($_SESSION['admin']) && $_SESSION['admin'] === true;
        } elseif ($role === 'client') {
            return isset($_SESSION['client']) && $_SESSION['client'] === true;
        } else {
            return isset($_SESSION['personne']) && $_SESSION['personne'] === false;
        }
    }
    
    public static function isAdmin() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        return self::isLoggedIn() && isset($_SESSION['admin']) && $_SESSION['admin'] === true;
    }
    
    public static function isClient() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        return self::isLoggedIn() && isset($_SESSION['client']) && $_SESSION['client'] === true;
    }
    
    
        
  

    public static function generateCSRFToken() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
               if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); 
        }
        
        return $_SESSION['csrf_token'];
    }

    public static function validateCSRFToken($token) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
               if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
            return false;
        }
        
        return true;
    }

    public static function logoutUser() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        self::setNoCacheHeaders();
        
        $_SESSION = [];
        
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        
        session_destroy();
        
        header('Location: ?route=login&message=' . urlencode('Vous avez été déconnecté(e) avec succès'));
        exit();
    }

    public static function checkRouteAccess($route = null) {
        if ($route === null) {
            $route = $_GET['route'] ?? 'home';
        }
        
        $publicRoutes = ['home', 'login'];
        
        $loginRequiredRoutes = ['products', 'contact', 'cart', 'checkout', 'payment', 'confirmation'];
        
        $adminRoutes = ['admin_stock', 'admin_users','admin/stock', 'admin'];
        
        if (in_array($route, $adminRoutes) && !self::isAdmin()) {
            header('Location: ?route=login');
            exit();
        }
        
        if (in_array($route, $loginRequiredRoutes) && !self::isLoggedIn()) {
            header('Location: ?route=login');
            exit();
        }
        
        if (in_array($route, $adminRoutes) || in_array($route, $loginRequiredRoutes)) {
            self::setNoCacheHeaders();
        }
        
        return true;
    }
    
    public static function setNoCacheHeaders() {
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); 
    }
} 