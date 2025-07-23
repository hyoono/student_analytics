# Student Analytics - SOAP Server

A PHP-based Student Performance Analytics SOAP Server with Chart Generation capabilities, designed to work on both XAMPP (local development) and Heroku (production deployment).

## Features

- **Grade Analysis**: Term Weighted Average (TWA) calculation with Mapua MCL grading system support
- **Course Comparison**: Performance comparison across multiple courses
- **Predictive Modeling**: Grade prediction based on historical data and attendance
- **Scholarship Eligibility**: Academic scholarship assessment for Mapua MCL requirements
- **Chart Generation**: Visual analytics with GD library-based chart generation
- **Environment-Aware**: Automatically detects and configures for XAMPP or Heroku environments

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

## Heroku Deployment

### Prerequisites
- Heroku CLI installed
- Git repository

### Deploy to Heroku
1. Create a new Heroku app:
   ```bash
   heroku create your-app-name
   ```

2. Set the Heroku app name (optional, for better URL detection):
   ```bash
   heroku config:set HEROKU_APP_NAME=your-app-name
   ```

3. Deploy the application:
   ```bash
   git push heroku main
   ```

4. Access your deployed application:
   - Main Interface: `https://your-app-name.herokuapp.com/`
   - Environment Test: `https://your-app-name.herokuapp.com/test_env.php`
   - SOAP Server: `https://your-app-name.herokuapp.com/soap_server.php`

### Heroku Configuration Files

- **Procfile**: Specifies how to run the app on Heroku
- **heroku_apache.conf**: Apache configuration for Heroku
- **composer.json**: PHP dependencies and required extensions

## Environment Detection

The application automatically detects the environment:

- **XAMPP/Local**: Uses `http://localhost/student_analytics/` URLs
- **Heroku**: Uses Heroku app URLs with HTTPS

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
├── Procfile              # Heroku process configuration
├── heroku_apache.conf    # Heroku Apache settings
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

## Support

For issues or questions, please check the environment test page first to verify your setup, then contact the development team.