<?php
/**
 * Simple test script to verify deployment and environment detection
 */

require_once 'config.php';

echo "<h1>Student Analytics - Environment Test</h1>";
echo "<h2>Environment Information</h2>";
echo "<p><strong>Environment:</strong> " . Config::getEnvironment() . "</p>";
echo "<p><strong>Is Azure:</strong> " . (Config::isAzure() ? 'Yes' : 'No') . "</p>";
echo "<p><strong>Base URL:</strong> " . Config::getBaseUrl() . "</p>";
echo "<p><strong>SOAP Server URL:</strong> " . Config::getSoapServerUrl() . "</p>";
echo "<p><strong>SOAP URI:</strong> " . Config::getSoapUri() . "</p>";

echo "<h2>PHP Extensions</h2>";
$requiredExtensions = ['gd', 'soap', 'xml', 'json'];
foreach ($requiredExtensions as $ext) {
    $loaded = extension_loaded($ext);
    echo "<p><strong>{$ext}:</strong> " . ($loaded ? '✓ Loaded' : '✗ Not loaded') . "</p>";
}

echo "<h2>PHP Version</h2>";
echo "<p><strong>Version:</strong> " . PHP_VERSION . "</p>";

echo "<h2>Server Information</h2>";
echo "<p><strong>Server Software:</strong> " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "</p>";
echo "<p><strong>Request Method:</strong> " . ($_SERVER['REQUEST_METHOD'] ?? 'Unknown') . "</p>";
echo "<p><strong>HTTP Host:</strong> " . ($_SERVER['HTTP_HOST'] ?? 'Unknown') . "</p>";

if (Config::isAzure()) {
    echo "<h2>Azure Environment Variables</h2>";
    $azureVars = ['WEBSITE_SITE_NAME', 'WEBSITE_RESOURCE_GROUP', 'APPSETTING_WEBSITE_SITE_NAME'];
    foreach ($azureVars as $var) {
        $value = getenv($var);
        echo "<p><strong>{$var}:</strong> " . ($value ?: 'Not set') . "</p>";
    }
}

echo "<hr>";
echo "<p><a href='index.php'>← Back to Main Application</a></p>";
?>