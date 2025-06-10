<?php

require_once __DIR__ . '/Model.php';

class Cart extends Model {
    
    public function addProduct($productId, $size, $quantity = 1) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['panier'])) {
            $_SESSION['panier'] = [];
        }
        
        $found = false;
        foreach ($_SESSION['panier'] as &$item) {
            if ($item['id'] == $productId && $item['pointure'] == $size) {
                $item['quantite'] += $quantity;
                $found = true;
                break;
            }
        }
        
        if (!$found) {
            $product = $this->getProductDetails($productId);
            if ($product) {
                $_SESSION['panier'][] = [
                    'id' => $productId,
                    'nom' => $product['nom'],
                    'prix' => $product['prix'],
                    'pointure' => $size,
                    'quantite' => $quantity,
                    'image' => $product['image']
                ];
            }
        }
    }
    
    public function updateQuantity($productId, $size, $quantity) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['panier'])) {
            return false;
        }
        
        foreach ($_SESSION['panier'] as &$item) {
            if ($item['id'] == $productId && $item['pointure'] == $size) {
                if ($quantity <= 0) {
                    $this->removeProduct($productId, $size);
                } else {
                    $item['quantite'] = $quantity;
                }
                return true;
            }
        }
        
        return false;
    }
    
    public function removeProduct($productId, $size) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['panier'])) {
            return false;
        }
        
        foreach ($_SESSION['panier'] as $key => $item) {
            if ($item['id'] == $productId && $item['pointure'] == $size) {
                unset($_SESSION['panier'][$key]);
                $_SESSION['panier'] = array_values($_SESSION['panier']); // Réindexer
                return true;
            }
        }
        
        return false;
    }
    
    public function getItems() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        return $_SESSION['panier'] ?? [];
    }
    
    public function getTotal() {
        $total = 0;
        $items = $this->getItems();
        
        foreach ($items as $item) {
            $total += $item['prix'] * $item['quantite'];
        }
        
        return $total;
    }
    
    public function getItemCount() {
        $count = 0;
        $items = $this->getItems();
        
        foreach ($items as $item) {
            $count += $item['quantite'];
        }
        
        return $count;
    }
    
    public function clearCart() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $_SESSION['panier'] = [];
    }
    
    private function getProductDetails($productId) {
        $sql = "SELECT * FROM stock WHERE id = ?";
        return $this->fetch($sql, [$productId]);
    }
} 