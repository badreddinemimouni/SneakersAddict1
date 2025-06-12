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
    
    
} 