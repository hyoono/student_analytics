# Student Analytics - SOAP Server

A PHP-based Student Performance Analytics SOAP Server with Chart Generation capabilities, designed to work on both XAMPP (local development) and Azure App Service (production deployment).

## Features

- **Grade Analysis**: Term Weighted Average (TWA) calculation with Mapua MCL grading system support
- **Course Comparison**: Performance comparison across multiple courses
- **Predictive Modeling**: Grade prediction based on historical data and attendance
- **Scholarship Eligibility**: Academic scholarship assessment for Mapua MCL requirements
- **Chart Generation**: Visual analytics with GD library-based chart generation
- **Environment-Aware**: Automatically detects and configures for XAMPP or Azure environments

## Local Development (XAMPP)

### Prerequisites
- XAMPP with PHP 7.4 or higher
- Required PHP extensions: gd, soap, xml, json (included in XAMPP)

### Setup
1. Clone the repository to your XAMPP `htdocs` directory:
   ```bash
   git clone https://github.com/hyoono/student_analytics.git
   cd student_analytics
   ```

2. Start XAMPP Apache server

3. Access the application:
   - Main Interface: `http://localhost/student_analytics/`
   - Environment Test: `http://localhost/student_analytics/test_env.php`
   - SOAP Server: `http://localhost/student_analytics/soap_server.php`

## Azure App Service Deployment

### Prerequisites
- Azure CLI installed
- Azure subscription with App Service plan
- Git repository

### Deploy to Azure App Service

#### Method 1: Using Azure CLI
1. Login to Azure:
   ```bash
   az login
   ```

2. Create a resource group (if not exists):
   ```bash
   az group create --name myResourceGroup --location "East US"
   ```

3. Create an App Service plan:
   ```bash
   az appservice plan create --name myAppServicePlan --resource-group myResourceGroup --sku FREE
   ```

4. Create a web app:
   ```bash
   az webapp create --resource-group myResourceGroup --plan myAppServicePlan --name your-app-name --runtime "PHP|8.0"
   ```

5. Configure deployment from Git:
   ```bash
   az webapp deployment source config --name your-app-name --resource-group myResourceGroup --repo-url https://github.com/hyoono/student_analytics.git --branch main --manual-integration
   ```

#### Method 2: Using Azure Portal
1. Go to Azure Portal (https://portal.azure.com)
2. Create a new App Service
3. Choose PHP 8.0+ runtime stack
4. In Deployment Center, connect to your GitHub repository
5. Select the main branch and save

#### Method 3: Using VS Code Azure Extension
1. Install Azure App Service extension for VS Code
2. Sign in to Azure
3. Right-click on your project folder
4. Select "Deploy to Web App"
5. Choose your subscription and create/select an App Service

### Configuration

#### Required App Settings (Optional)
Set these in Azure Portal > App Service > Configuration > Application settings:
- `WEBSITE_SITE_NAME`: Your app name (usually auto-detected)

#### PHP Extensions
The following extensions are automatically available in Azure App Service:
- gd
- soap
- xml
- json

### Access Your Deployed Application
- Main Interface: `https://your-app-name.azurewebsites.net/`
- Environment Test: `https://your-app-name.azurewebsites.net/test_env.php`
- SOAP Server: `https://your-app-name.azurewebsites.net/soap_server.php`

### Azure Configuration Files

- **web.config**: IIS configuration for Azure App Service
- **azure_apache.conf**: Apache configuration (if using Apache on Azure)
- **composer.json**: PHP dependencies and required extensions

## Environment Detection

The application automatically detects the environment:

- **XAMPP/Local**: Uses `http://localhost/student_analytics/` URLs
- **Azure App Service**: Uses Azure app URLs with HTTPS (`https://your-app.azurewebsites.net/`)

## API Usage

### SOAP Methods

1. **analyzeGrades**: Grade analysis with TWA calculation
2. **compareCourses**: Course performance comparison
3. **generatePrediction**: Predictive performance modeling
4. **checkScholarshipEligibility**: Academic scholarship eligibility check
5. **generateGradeAnalysisWithChart**: Grade analysis with visual chart
6. **generateCourseComparisonWithAnalysis**: Course comparison with chart
7. **generatePredictionWithChart**: Prediction with trend visualization

### Example SOAP Client (PHP)
```php
<?php
require_once 'config.php';

$client = new SoapClient(null, array(
    'location' => Config::getSoapServerUrl(),
    'uri' => Config::getSoapUri(),
    'trace' => 1
));

// Analyze grades
$result = $client->analyzeGrades(
    'STUDENT001',
    '1.25,1.50,2.00,1.75',
    '3,4,3,3',
    '1.50,1.75,2.00,1.25;1.25,1.50,2.25,1.75',
    'transmuted'
);

echo $result;
?>
```

## File Structure

```
student_analytics/
├── index.php              # Main client interface
├── soap_server.php        # SOAP server implementation
├── config.php            # Environment configuration
├── test_env.php          # Environment test page
├── charts/
│   └── ChartGenerator.php # Chart generation class
├── web.config            # Azure App Service configuration
├── azure_apache.conf     # Azure Apache settings (if needed)
├── .htaccess            # Apache configuration for XAMPP
├── composer.json        # PHP dependencies
└── README.md           # This file
```

## Troubleshooting

### SOAP Connection Issues
- Verify the SOAP server URL is accessible
- Check that required PHP extensions are loaded
- Ensure firewall/security settings allow SOAP requests

### Chart Generation Issues
- Verify GD extension is installed and enabled
- Check memory limits if generating large charts
- Ensure temporary directory permissions are correct

### Environment Detection Issues
- Check the environment test page: `/test_env.php`
- Verify environment variables are set correctly
- Ensure HTTP_HOST and SCRIPT_NAME are available

### Azure-Specific Issues
- Check Azure App Service logs in Azure Portal
- Verify PHP version compatibility (use PHP 8.0+)
- Ensure all required extensions are enabled in Azure
- Check web.config syntax if deployment fails

## Performance Optimization

### Azure App Service
- Automatic scaling based on demand
- Built-in load balancing
- CDN integration available
- Application Insights for monitoring

### Local Development
- Uses optimized PHP settings for development
- Detailed error reporting enabled
- Extended execution time limits

## Support

For issues or questions, please check the environment test page first to verify your setup, then contact the development team.

## Migration from Heroku

If you're migrating from a Heroku deployment:
1. The application will automatically detect Azure environment
2. No code changes required in your application logic
3. SOAP endpoints remain the same
4. All functionality is preserved