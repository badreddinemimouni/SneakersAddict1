<?php

class UrlService {
    
   
    public static function route($route, $params = []) {
        $url = '?route=' . $route;
        
        if (!empty($params)) {
            $url .= '&' . http_build_query($params);
        }
        
        return $url;
    }
    
} 