<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Analytics SOAP Client</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .result-card {
            margin-top: 20px;
            padding: 20px;
            border-radius: 10px;
            background: #f8f9fa;
        }
        .nav-pills .nav-link.active {
            background-color: #6f42c1;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-5">Student Performance Analytics - SOAP Client</h1>
        
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pills-grade-tab" data-bs-toggle="pill" data-bs-target="#pills-grade" type="button" role="tab">Grade Analysis</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-comparison-tab" data-bs-toggle="pill" data-bs-target="#pills-comparison" type="button" role="tab">Subject Comparison</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-prediction-tab" data-bs-toggle="pill" data-bs-target="#pills-prediction" type="button" role="tab">Predictive Modeling</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-scholarship-tab" data-bs-toggle="pill" data-bs-target="#pills-scholarship" type="button" role="tab">Scholarship Eligibility</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-charts-tab" data-bs-toggle="pill" data-bs-target="#pills-charts" type="button" role="tab">Visual Analytics</button>
            </li>
        </ul>
        
        <div class="tab-content" id="pills-tabContent">
            <!-- Grade Analysis Tab -->
            <div class="tab-pane fade show active" id="pills-grade" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h4>Grade Analysis</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <input type="hidden" name="action" value="grade_analysis">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Student ID</label>
                                        <input type="text" class="form-control" name="student_id" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Current Grades (comma-separated)</label>
                                        <input type="text" class="form-control" name="current_grades" placeholder="85,92,78,88" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Subject Weights (comma-separated)</label>
                                        <input type="text" class="form-control" name="subject_weights" placeholder="0.3,0.25,0.25,0.2" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Historical Grades (semicolon-separated terms)</label>
                                        <input type="text" class="form-control" name="historical_grades" placeholder="80,85,75,82;83,88,77,85" required>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Analyze Grades</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Subject Comparison Tab -->
            <div class="tab-pane fade" id="pills-comparison" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h4>Subject Performance Comparison</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <input type="hidden" name="action" value="subject_comparison">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Student ID</label>
                                        <input type="text" class="form-control" name="student_id" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Subject Names (comma-separated)</label>
                                        <input type="text" class="form-control" name="subject_names" placeholder="Math,Physics,Chemistry,Biology" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Subject Grades</label>
                                        <input type="text" class="form-control" name="subject_grades" placeholder="85,92,78,88" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Class Averages</label>
                                        <input type="text" class="form-control" name="class_averages" placeholder="82,89,75,85" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Credit Hours</label>
                                        <input type="text" class="form-control" name="credit_hours" placeholder="3,4,3,3" required>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Compare Performance</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Predictive Modeling Tab -->
            <div class="tab-pane fade" id="pills-prediction" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h4>Predictive Performance Modeling</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <input type="hidden" name="action" value="predictive_modeling">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Student ID</label>
                                        <input type="text" class="form-control" name="student_id" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Historical Grades</label>
                                        <input type="text" class="form-control" name="historical_grades" placeholder="85,87,82,89,91" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">Attendance Rate (%)</label>
                                        <input type="number" step="0.1" class="form-control" name="attendance_rate" placeholder="95.5" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">Participation Score (1-10)</label>
                                        <input type="number" step="0.1" class="form-control" name="participation_score" placeholder="8.5" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">Study Hours/Week</label>
                                        <input type="number" class="form-control" name="study_hours" placeholder="25" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">Extracurricular Hours/Week</label>
                                        <input type="number" class="form-control" name="extracurricular_hours" placeholder="5" required>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Generate Prediction</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Scholarship Eligibility Tab -->
            <div class="tab-pane fade" id="pills-scholarship" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h4>Scholarship Eligibility</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <input type="hidden" name="action" value="scholarship_eligibility">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Student ID</label>
                                        <input type="text" class="form-control" name="student_id" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">GPA (0.0-4.0)</label>
                                        <input type="number" step="0.01" class="form-control" name="gpa" placeholder="3.75" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Income Level</label>
                                        <select class="form-control" name="income_level" required>
                                            <option value="Low">Low</option>
                                            <option value="Middle" selected>Middle</option>
                                            <option value="High">High</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Community Service Hours</label>
                                        <input type="number" class="form-control" name="community_service_hours" placeholder="120" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Extracurriculars</label>
                                        <input type="text" class="form-control" name="extracurriculars" placeholder="Basketball,Debate,Student Council">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Honors and Awards</label>
                                        <input type="text" class="form-control" name="honors" placeholder="Dean's List,Academic Excellence">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Leadership Positions</label>
                                        <input type="text" class="form-control" name="leadership_positions" placeholder="Class President,Team Captain">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Check Eligibility</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Visual Analytics Tab -->
            <div class="tab-pane fade" id="pills-charts" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h4>Visual Analytics - Chart Generation</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Student Performance Charts</h5>
                                <form method="POST" action="" class="mb-3">
                                    <input type="hidden" name="action" value="grades_trend_chart">
                                    <div class="mb-3">
                                        <label class="form-label">Student ID</label>
                                        <input type="text" class="form-control" name="student_id" required>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Width (400-1200)</label>
                                                <input type="number" class="form-control" name="width" value="800" min="400" max="1200">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Height (300-800)</label>
                                                <input type="number" class="form-control" name="height" value="600" min="300" max="800">
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm">Generate Grades Trend Chart</button>
                                </form>
                                
                                <form method="POST" action="" class="mb-3">
                                    <input type="hidden" name="action" value="subject_comparison_chart">
                                    <div class="mb-3">
                                        <label class="form-label">Student ID</label>
                                        <input type="text" class="form-control" name="student_id" required>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Width</label>
                                                <input type="number" class="form-control" name="width" value="800" min="400" max="1200">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Height</label>
                                                <input type="number" class="form-control" name="height" value="600" min="300" max="800">
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-success btn-sm">Generate Subject Comparison Chart</button>
                                </form>
                                
                                <form method="POST" action="" class="mb-3">
                                    <input type="hidden" name="action" value="gpa_progress_chart">
                                    <div class="mb-3">
                                        <label class="form-label">Student ID</label>
                                        <input type="text" class="form-control" name="student_id" required>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Width</label>
                                                <input type="number" class="form-control" name="width" value="800" min="400" max="1200">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Height</label>
                                                <input type="number" class="form-control" name="height" value="600" min="300" max="800">
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-warning btn-sm">Generate GPA Progress Chart</button>
                                </form>
                            </div>
                            
                            <div class="col-md-6">
                                <h5>Class Analytics Charts</h5>
                                <form method="POST" action="" class="mb-3">
                                    <input type="hidden" name="action" value="performance_distribution_chart">
                                    <div class="mb-3">
                                        <label class="form-label">Class ID</label>
                                        <input type="text" class="form-control" name="class_id" required>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Width</label>
                                                <input type="number" class="form-control" name="width" value="800" min="400" max="1200">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Height</label>
                                                <input type="number" class="form-control" name="height" value="600" min="300" max="800">
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-info btn-sm">Generate Performance Distribution Chart</button>
                                </form>
                                
                                <form method="POST" action="" class="mb-3">
                                    <input type="hidden" name="action" value="class_average_chart">
                                    <div class="mb-3">
                                        <label class="form-label">Class ID</label>
                                        <input type="text" class="form-control" name="class_id" required>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Width</label>
                                                <input type="number" class="form-control" name="width" value="800" min="400" max="1200">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Height</label>
                                                <input type="number" class="form-control" name="height" value="600" min="300" max="800">
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-secondary btn-sm">Generate Class Average Chart</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $client = new SoapClient(null, array(
                    'location' => 'http://localhost/student_analytics/soap_server.php',
                    'uri' => 'http://localhost/student_analytics',
                    'trace' => 1
                ));
                
                $action = $_POST['action'];
                $result = null;
                
                switch ($action) {
                    case 'grade_analysis':
                        $result = $client->analyzeGrades(
                            $_POST['student_id'],
                            $_POST['current_grades'],
                            $_POST['subject_weights'],
                            $_POST['historical_grades']
                        );
                        break;
                        
                    case 'subject_comparison':
                        $result = $client->compareSubjects(
                            $_POST['student_id'],
                            $_POST['subject_names'],
                            $_POST['subject_grades'],
                            $_POST['class_averages'],
                            $_POST['credit_hours']
                        );
                        break;
                        
                    case 'predictive_modeling':
                        $result = $client->generatePrediction(
                            $_POST['student_id'],
                            $_POST['historical_grades'],
                            $_POST['attendance_rate'],
                            $_POST['participation_score'],
                            $_POST['study_hours'],
                            $_POST['extracurricular_hours']
                        );
                        break;
                        
                    case 'scholarship_eligibility':
                        $result = $client->checkEligibility(
                            $_POST['student_id'],
                            $_POST['gpa'],
                            $_POST['extracurriculars'],
                            $_POST['income_level'],
                            $_POST['honors'],
                            $_POST['community_service_hours'],
                            $_POST['leadership_positions']
                        );
                        break;
                        
                    case 'grades_trend_chart':
                        $result = $client->generateGradesTrendChart(
                            $_POST['student_id'],
                            $_POST['width'],
                            $_POST['height']
                        );
                        break;
                        
                    case 'subject_comparison_chart':
                        $result = $client->generateSubjectComparisonChart(
                            $_POST['student_id'],
                            $_POST['width'],
                            $_POST['height']
                        );
                        break;
                        
                    case 'gpa_progress_chart':
                        $result = $client->generateGPAProgressChart(
                            $_POST['student_id'],
                            $_POST['width'],
                            $_POST['height']
                        );
                        break;
                        
                    case 'performance_distribution_chart':
                        $result = $client->generatePerformanceDistributionChart(
                            $_POST['class_id'],
                            $_POST['width'],
                            $_POST['height']
                        );
                        break;
                        
                    case 'class_average_chart':
                        $result = $client->generateClassAverageChart(
                            $_POST['class_id'],
                            $_POST['width'],
                            $_POST['height']
                        );
                        break;
                }
                
                if ($result) {
                    $data = json_decode($result, true);
                    echo '<div class="result-card">';
                    echo '<h5>Results for ' . ucfirst(str_replace('_', ' ', $action)) . '</h5>';
                    
                    // Check if this is a chart result
                    if (isset($data['chartType']) && isset($data['imageData'])) {
                        if ($data['success']) {
                            echo '<div class="text-center">';
                            echo '<img src="data:image/png;base64,' . $data['imageData'] . '" class="img-fluid border" alt="Generated Chart" style="max-width: 100%; height: auto;">';
                            echo '</div>';
                            echo '<div class="row mt-3">';
                            foreach ($data as $key => $value) {
                                if ($key !== 'imageData') {
                                    $label = ucwords(str_replace('_', ' ', $key));
                                    echo '<div class="col-md-6 mb-2">';
                                    echo "<strong>$label:</strong> $value";
                                    echo '</div>';
                                }
                            }
                            echo '</div>';
                        } else {
                            echo '<div class="alert alert-danger">';
                            echo '<strong>Chart Generation Failed:</strong> ' . $data['error'];
                            echo '</div>';
                        }
                    } else {
                        // Regular non-chart result
                        echo '<div class="row">';
                        foreach ($data as $key => $value) {
                            $label = ucwords(str_replace('_', ' ', $key));
                            echo '<div class="col-md-6 mb-2">';
                            echo "<strong>$label:</strong> $value";
                            echo '</div>';
                        }
                        echo '</div>';
                    }
                    echo '</div>';
                }
                
            } catch (Exception $e) {
                echo '<div class="alert alert-danger mt-3">Error: ' . $e->getMessage() . '</div>';
            }
        }
        ?>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>