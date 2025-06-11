<?php

require_once __DIR__ . '/Model.php';

class User extends Model {
    
    public function findByEmail($email) {
        return $this->fetch("SELECT * FROM user_site WHERE email = ?", [$email]);
    }
    
    public function findById($id) {
        return $this->fetch("SELECT * FROM user_site WHERE user_id = ?", [$id]);
    }
    
    public function create($nom, $prenom, $email, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->query("INSERT INTO user_site (nom, prenom, email, password, Grade) VALUES (?, ?, ?, ?, 'client')", 
                           [$nom, $prenom, $email, $hashedPassword]);
        return $stmt->rowCount() > 0;
    }
    
    public function verifyPassword($password, $hashedPassword) {
        return password_verify($password, $hashedPassword);
    }
    
    public function emailExists($email) {
        $user = $this->findByEmail($email);
        return $user !== false;
    }
    
    public function isAdmin($user) {
        return $user['Grade'] === 'admin';
    }
    
    public function isClient($user) {
        return $user['Grade'] === 'client';
    }
    
    public function getAllUsers() {
        return $this->fetchAll("SELECT user_id as id, nom, prenom, email, Grade as role FROM user_site ORDER BY user_id DESC");
    }
    
    public function deleteUser($userId) {
        return $this->query("DELETE FROM user_site WHERE user_id = ?", [$userId]);
    }
    
    public function toggleUserRole($userId) {
        $user = $this->findById($userId);
        if (!$user) {
            throw new Exception("Utilisateur introuvable.");
        }
        
        $newRole = $user['Grade'] === 'admin' ? 'client' : 'admin';
        return $this->query("UPDATE user_site SET Grade = ? WHERE user_id = ?", [$newRole, $userId]);
    }
    
    // public function updateLastLogin($userId) {
    //     return true;
    // }
    
    public function getUserStats() {
        return [
            'total' => $this->fetch("SELECT COUNT(*) as count FROM user_site")['count'],
            'admins' => $this->fetch("SELECT COUNT(*) as count FROM user_site WHERE Grade = 'admin'")['count'],
            'clients' => $this->fetch("SELECT COUNT(*) as count FROM user_site WHERE Grade = 'client'")['count']
        ];
    }
} 