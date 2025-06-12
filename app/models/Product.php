<?php

require_once __DIR__ . '/Model.php';

class Product extends Model {
    
    public function getAll() {
        $sql = "SELECT s.*, 
                       COALESCE(SUM(ss.amount), 0) as stock
                FROM stock s 
                LEFT JOIN stock_size ss ON s.id = ss.stock_id 
                GROUP BY s.id 
                ORDER BY s.nom";
        return $this->fetchAll($sql);
    }
    
    public function getById($id) {
        $sql = "SELECT s.*, 
                       COALESCE(SUM(ss.amount), 0) as stock
                FROM stock s 
                LEFT JOIN stock_size ss ON s.id = ss.stock_id 
                WHERE s.id = ?
                GROUP BY s.id";
                // var_dump($this->fetch($sql, [$id]));
        return $this->fetch($sql, [$id]);
    }
    

    
    public function create($nom, $prix, $couleur, $image = null) {
        $sql = "INSERT INTO stock (nom, prix, couleur, image) VALUES (?, ?, ?, ?)";
        return $this->query($sql, [$nom, $prix, $couleur, $image]);
    }
    
    public function update($id, $nom, $prix, $couleur, $image = null) {
        $sql = "UPDATE stock SET nom = ?, prix = ?, couleur = ?, image = ? WHERE id = ?";
        return $this->query($sql, [$nom, $prix, $couleur, $image, $id]);
    }
    
    public function updatePrice($id, $prix) {
        return $this->query("UPDATE stock SET prix = ? WHERE id = ?", [$prix, $id]);
    }
    
    public function delete($id) {
        return $this->query("DELETE FROM stock WHERE id = ?", [$id]);
    }
    

} 