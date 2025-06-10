<?php

require_once __DIR__ . '/../core/Controller.php';

class HomeController extends Controller {
    
    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $data = [
            'pageTitle' => 'SneakersAddict - Accueil',
            'isLoggedIn' => $this->isLoggedIn(),
            'isAdmin' => $this->isAdmin(),
            'isClient' => $this->isClient()
        ];
        
        $this->render('home/index', $data);
    }
} 