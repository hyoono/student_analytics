<?php
/**
 * Configuration helper for Student Analytics
 * Handles environment-specific settings for both XAMPP and Azure App Service
 */

class Config {
    private static $isAzure = null;
    
    /**
     * Detect if running on Azure App Service
     */
    public static function isAzure() {
        if (self::$isAzure === null) {
            // Azure App Service environment detection
            self::$isAzure = isset($_ENV['WEBSITE_SITE_NAME']) || isset($_SERVER['WEBSITE_SITE_NAME']) || 
                             !empty(getenv('WEBSITE_SITE_NAME')) || 
                             isset($_ENV['APPSETTING_WEBSITE_SITE_NAME']) || isset($_SERVER['APPSETTING_WEBSITE_SITE_NAME']) ||
                             strpos($_SERVER['HTTP_HOST'] ?? '', '.azurewebsites.net') !== false;
        }
        return self::$isAzure;
    }
    
    /**
     * Get the base URL for the application
     */
    public static function getBaseUrl() {
        if (self::isAzure()) {
            // On Azure App Service, use the app URL
            $siteName = getenv('WEBSITE_SITE_NAME') ?: getenv('APPSETTING_WEBSITE_SITE_NAME');
            if ($siteName) {
                return "https://{$siteName}.azurewebsites.net";
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
        return self::isAzure() ? 'azure' : 'local';
    }
    
    /**
     * Get PHP settings optimized for the environment
     */
    public static function optimizePhpSettings() {
        if (self::isAzure()) {
            // Azure App Service optimizations
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