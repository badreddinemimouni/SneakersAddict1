<?php

require_once __DIR__ . '/../services/SecurityService.php';

abstract class Controller {
    protected $data = [];
    
    protected function render($view, $data = [], $layout = 'layouts/main') {
        // fusion des données avec le data de la classe (c'est dans le cas ou on va mettre des données deja existantes d ebase)
        $this->data = array_merge($this->data, $data);
        
        extract($this->data);
    
        // capture content de la vue
        ob_start();
        $viewFile = __DIR__ . '/../views/' . $view . '.php';
        
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            throw new Exception("Vue non trouvée: " . $view);
        }
        
        $content = ob_get_clean();
        
        $layoutFile = __DIR__ . '/../views/' . $layout . '.php';
        if (file_exists($layoutFile)) {
            
            require $layoutFile;
        } else {
            echo $content; 
        }
    }
    
    protected function redirect($route = null, $params = []) {
        $url = $route ? '?route=' . $route : '/';
        if (!empty($params)) {
            $url .= '&' . http_build_query($params);
        }
        header('Location: ' . $url);
        exit();
    }
    
    protected function checkAuth($role = null) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!SecurityService::isLoggedIn($role)) {
            $this->redirect('login');
        }
    }
    
    protected function isLoggedIn($role = null) {
        return SecurityService::isLoggedIn($role);
    }
    
    protected function isAdmin() {
        return SecurityService::isLoggedIn('admin');
    }
    
    protected function isClient() {
        return SecurityService::isLoggedIn('client');
    }
    
    protected function generateCSRFToken() {
        return SecurityService::generateCSRFToken();
    }
    
    protected function validateCSRFToken($token) {
        return SecurityService::validateCSRFToken($token);
    }
    
    protected function cleanInput($data) {
        return SecurityService::cleanInput($data);
    }
} 