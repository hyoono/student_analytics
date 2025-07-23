<?php
/**
 * Configuration helper for Student Analytics
 * Handles environment-specific settings for both XAMPP and Heroku
 */

class Config {
    private static $isHeroku = null;
    
    /**
     * Detect if running on Heroku
     */
    public static function isHeroku() {
        if (self::$isHeroku === null) {
            self::$isHeroku = isset($_ENV['DYNO']) || isset($_SERVER['DYNO']) || 
                             !empty(getenv('DYNO')) || !empty(getenv('PORT'));
        }
        return self::$isHeroku;
    }
    
    /**
     * Get the base URL for the application
     */
    public static function getBaseUrl() {
        if (self::isHeroku()) {
            // On Heroku, use the app URL
            $appName = getenv('HEROKU_APP_NAME');
            if ($appName) {
                return "https://{$appName}.herokuapp.com";
            }
            // Fallback to detect from HTTP_HOST
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
            return "{$protocol}://{$host}";
        } else {
            // Local XAMPP development
            if (isset($_SERVER['HTTP_HOST']) && isset($_SERVER['SCRIPT_NAME'])) {
                $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
                $host = $_SERVER['HTTP_HOST'];
                $path = dirname($_SERVER['SCRIPT_NAME']);
                if ($path === '/' || $path === '\\') {
                    $path = '';
                }
                return "{$protocol}://{$host}{$path}";
            } else {
                // CLI or minimal environment - use localhost with student_analytics path
                return "http://localhost/student_analytics";
            }
        }
    }
    
    /**
     * Get SOAP server URL
     */
    public static function getSoapServerUrl() {
        return self::getBaseUrl() . '/soap_server.php';
    }
    
    /**
     * Get SOAP URI
     */
    public static function getSoapUri() {
        return self::getBaseUrl();
    }
    
    /**
     * Get environment name
     */
    public static function getEnvironment() {
        return self::isHeroku() ? 'heroku' : 'local';
    }
    
    /**
     * Get PHP settings optimized for the environment
     */
    public static function optimizePhpSettings() {
        if (self::isHeroku()) {
            // Heroku optimizations
            ini_set('memory_limit', '512M');
            ini_set('max_execution_time', '30');
            ini_set('max_input_time', '30');
        } else {
            // XAMPP/Local development settings
            ini_set('memory_limit', '256M');
            ini_set('max_execution_time', '60');
            ini_set('max_input_time', '60');
        }
        
        // Common settings for both environments
        ini_set('display_errors', '0');
        ini_set('log_errors', '1');
    }
}

// Initialize environment settings
Config::optimizePhpSettings();
?>