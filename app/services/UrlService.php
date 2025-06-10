<?php

class UrlService {
    
   
    public static function route($route, $params = []) {
        $url = '?route=' . $route;
        
        if (!empty($params)) {
            $url .= '&' . http_build_query($params);
        }
        
        return $url;
    }
    
    
    public static function absolute($route, $params = []) {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'];
        $basePath = dirname($_SERVER['SCRIPT_NAME']);
        
        $url = $protocol . $host . $basePath . '/' . self::route($route, $params);
        
        return $url;
    }
    
    
    public static function redirect($route, $params = []) {
        $url = self::route($route, $params);
        header('Location: ' . $url);
        exit();
    }
} 