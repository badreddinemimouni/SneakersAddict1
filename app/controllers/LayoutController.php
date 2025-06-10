<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../services/UrlService.php';
require_once __DIR__ . '/../services/CartService.php';
require_once __DIR__ . '/../services/AuthService.php';

class LayoutController extends Controller {
    
    
    public static function getHeaderData() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $panier = CartService::getCartData();
        $auth = AuthService::getAuthData();
        $navigation = AuthService::getNavigationData();
        
        return [
            'panier' => $panier,
            'auth' => $auth,
            'navigation' => $navigation
        ];
    }
    
    
    public static function getFooterData() {
        return [
            'current_year' => date('Y'),
            'site_name' => 'SneakersAddict'
        ];
    }
} 