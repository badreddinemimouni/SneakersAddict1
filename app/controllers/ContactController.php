<?php

require_once __DIR__ . '/../core/Controller.php';

class ContactController extends Controller {
    
    public function index() {
        $this->checkAuth();
        
        $csrf_token = $this->generateCSRFToken();
        
        $this->render('contact/index', [
            'csrf_token' => $csrf_token,
            'pageTitle' => 'Contact - SneakersAddict'
        ]);
    }
    
    public function send() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || !$this->validateCSRFToken($_POST['csrf_token'])) {
                $this->redirect('contact', ['error' => 'csrf']);
                return;
            }
            
            $nom = $this->cleanInput($_POST['nom']);
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $message = $this->cleanInput($_POST['message']);
            
            if (empty($nom) || empty($email) || empty($message)) {
                $this->redirect('contact', ['error' => 'empty_fields']);
                return;
            }
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->redirect('contact', ['error' => 'invalid_email']);
                return;
            }
            
            if (!is_dir('messages_contact')) {
                mkdir('messages_contact', 0755, true);
            }
            
            $filename = 'messages_contact/message_' . date('Y-m-d_H-i-s') . '_' . uniqid() . '.txt';
            $content = "Nom: $nom\nEmail: $email\nDate: " . date('Y-m-d H:i:s') . "\nMessage:\n$message";
            
            if (file_put_contents($filename, $content)) {
                $this->redirect('contact', ['success' => '1']);
            } else {
                $this->redirect('contact', ['error' => 'save_failed']);
            }
        }
        
        $this->redirect('contact');
    }
} 