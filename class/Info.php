<?php
 
 class Info{
    public static function obtener_info_navegador() : string {
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
    
        if (preg_match('/MSIE|Trident/', $userAgent)) {
            // Internet Explorer
            return 'Internet Explorer';
        } elseif (preg_match('/Edge\/([0-9\.]+)/', $userAgent, $matches)) {
            // Microsoft Edge (basado en Chromium)
            return 'Microsoft Edge';
        } elseif (preg_match('/Edg\/([0-9\.]+)/', $userAgent, $matches)) {
            // Microsoft Edge (Chromium)
            return 'Microsoft Edge (Chromium)';
        } elseif (preg_match('/Firefox\/([0-9\.]+)/', $userAgent, $matches)) {
            // Firefox
            return 'Firefox';
        } elseif (preg_match('/Chrome\/([0-9\.]+)/', $userAgent, $matches)) {
            // Google Chrome
            return 'Chrome';
        } elseif (preg_match('/Safari\/([0-9\.]+)/', $userAgent, $matches) && strpos($userAgent, 'Chrome') === false) {
            // Safari
            return 'Safari';
        } elseif (preg_match('/Opera\/([0-9\.]+)/', $userAgent, $matches) || preg_match('/OPR\/([0-9\.]+)/', $userAgent, $matches)) {
            // Opera
            return 'Opera';
        }
    
        return 'Otro';
    }

    public static function obtener_ip() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            // IP desde el cliente
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // IP desde el proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            // IP directa
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    public static function get_hostname() : string{
        $ip = self::obtener_ip();
        return  gethostbyaddr($ip);

    }
   
 }