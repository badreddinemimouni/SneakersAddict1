<?php

class AuthService {
    
    
    public static function getAuthData() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $isLoggedIn = isset($_SESSION['admin']) && $_SESSION['admin'] === true || 
                     isset($_SESSION['client']) && $_SESSION['client'] === true;
        $isAdmin = isset($_SESSION['admin']) && $_SESSION['admin'] === true && 
                  isset($_SESSION['client']) && $_SESSION['client'] === false;
        $isClient = isset($_SESSION['client']) && $_SESSION['client'] === true;
        $isPersonne = isset($_SESSION['personne']) && $_SESSION['personne'] === false;
        
        return [
            'is_logged_in' => $isLoggedIn,
            'is_admin' => $isAdmin,
            'is_client' => $isClient,
            'is_personne' => $isPersonne
        ];
    }
    
    
    public static function isLoggedIn() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        return isset($_SESSION['admin']) && $_SESSION['admin'] === true || 
               isset($_SESSION['client']) && $_SESSION['client'] === true;
    }
    
   
    public static function isAdmin() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        return isset($_SESSION['admin']) && $_SESSION['admin'] === true && 
               isset($_SESSION['client']) && $_SESSION['client'] === false;
    }
    
    
    public static function isClient() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        return isset($_SESSION['client']) && $_SESSION['client'] === true;
    }
    
   
    public static function isPersonne() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        return isset($_SESSION['personne']) && $_SESSION['personne'] === false;
    }
    
   
    public static function getNavigationData() {
        $auth = self::getAuthData();
        
        return [
            'show_products' => $auth['is_personne'],
            'show_admin_links' => $auth['is_admin']
        ];
    }
} 