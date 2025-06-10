<?php

class CartService {
    
    
    public static function getCartData() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $nombre_articles = 0;
        $total_panier = 0.0;
        $panier_items = [];
        
        if (isset($_SESSION['panier']) && !empty($_SESSION['panier'])) {
            foreach ($_SESSION['panier'] as $id_produit => $produit) {
                $nombre_articles += $produit['quantite'];
                $total_panier += $produit['prix'] * $produit['quantite'];
                $panier_items[$id_produit] = $produit;
            }
        }
        
        return [
            'nombre_articles' => $nombre_articles,
            'total_panier' => $total_panier,
            'items' => $panier_items,
            'is_empty' => empty($_SESSION['panier'])
        ];
    }
    
    
    public static function addProduct($id_produit, $produit_data) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['panier'])) {
            $_SESSION['panier'] = [];
        }
        
        if (isset($_SESSION['panier'][$id_produit])) {
            $_SESSION['panier'][$id_produit]['quantite'] += $produit_data['quantite'];
        } else {
            $_SESSION['panier'][$id_produit] = $produit_data;
        }
    }
    
    
    public static function removeProduct($id_produit) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (isset($_SESSION['panier'][$id_produit])) {
            unset($_SESSION['panier'][$id_produit]);
        }
    }
    
    
    public static function updateQuantity($id_produit, $nouvelle_quantite) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (isset($_SESSION['panier'][$id_produit])) {
            if ($nouvelle_quantite <= 0) {
                self::removeProduct($id_produit);
            } else {
                $_SESSION['panier'][$id_produit]['quantite'] = $nouvelle_quantite;
            }
        }
    }
    
    
    public static function clearCart() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $_SESSION['panier'] = [];
    }
    
    
    public static function isEmpty() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        return empty($_SESSION['panier']);
    }
} 