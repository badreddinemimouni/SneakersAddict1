<?php

require_once __DIR__ . '/Model.php';

class Order extends Model {
    
    public function updateStock($panier) {
        foreach ($panier as $produit) {
            $stmt = $this->query("SELECT id FROM size WHERE size = ?", [$produit['pointure']]);
            $size_result = $stmt->fetch();
            
            if ($size_result) {
                $size_id = $size_result['id'];
                $produit_id = $produit['id'];
                
                $this->query(
                    "UPDATE stock_size SET amount = amount - ? WHERE stock_id = ? AND size_id = ?",
                    [$produit['quantite'], $produit_id, $size_id]
                );
            }
        }
    }
    // ca pourrait servir pour les historiques des commandes pour plus tard (a ameliorer)
    public function create($orderData) {
        $sql = "INSERT INTO orders (order_number, customer_name, customer_email, total_amount, order_date, status) 
                VALUES (?, ?, ?, ?, ?, 'pending')";
        
        $stmt = $this->query($sql, [
            $orderData['number'],
            $orderData['shipping']['prenom'] . ' ' . $orderData['shipping']['nom'],
            $orderData['shipping']['email'],
            $orderData['total'],
            $orderData['date']
        ]);
        
        return $this->pdo->lastInsertId();
    }
    
   
    
    public function getById($id) {
        return $this->fetch("SELECT * FROM orders WHERE id = ?", [$id]);
    }
    
    public function getByOrderNumber($orderNumber) {
        return $this->fetch("SELECT * FROM orders WHERE order_number = ?", [$orderNumber]);
    }
    
    public function getOrderItems($orderId) {
        return $this->fetchAll("SELECT * FROM order_items WHERE order_id = ?", [$orderId]);
    }
} 