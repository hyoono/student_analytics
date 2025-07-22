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
        <h1 class="text-center mb-5">Student Performance Analytics - Mapua MCL SOAP Service</h1>
        
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pills-grade-tab" data-bs-toggle="pill" data-bs-target="#pills-grade" type="button" role="tab">Grade Analysis</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-comparison-tab" data-bs-toggle="pill" data-bs-target="#pills-comparison" type="button" role="tab">Course Comparison</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-prediction-tab" data-bs-toggle="pill" data-bs-target="#pills-prediction" type="button" role="tab">Predictive Modeling</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-scholarship-tab" data-bs-toggle="pill" data-bs-target="#pills-scholarship" type="button" role="tab">Scholarship Eligibility</button>
            </li>

        </ul>
        
        <div class="tab-content" id="pills-tabContent">
            <!-- Grade Analysis Tab -->
            <div class="tab-pane fade show active" id="pills-grade" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h4>Grade Analysis - Mapua MCL System</h4>
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
                                        <input type="text" class="form-control" name="current_grades" placeholder="1.25,1.50,2.00,1.75 or 90,85,92,88" required>
                                        <small class="form-text text-muted">Use transmuted grades (1.00-5.00) or raw grades (0-100)</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Course Units (comma-separated)</label>
                                        <input type="text" class="form-control" name="course_units" placeholder="3,4,3,3" required>
                                        <small class="form-text text-muted">Credit units for each course</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Historical Grades (semicolon-separated terms)</label>
                                        <input type="text" class="form-control" name="historical_grades" placeholder="1.50,1.75,2.00,1.25;1.25,1.50,2.25,1.75" required>
                                        <small class="form-text text-muted">Previous term grades separated by semicolons</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Grade Format</label>
                                        <select class="form-control" name="grade_format">
                                            <option value="auto">Auto-detect</option>
                                            <option value="transmuted">Transmuted (1.00-5.00)</option>
                                            <option value="raw">Raw (0-100)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Analyze Grades</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Course Comparison Tab -->
            <div class="tab-pane fade" id="pills-comparison" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h4>Course Performance Comparison</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <input type="hidden" name="action" value="course_comparison">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Student ID</label>
                                        <input type="text" class="form-control" name="student_id" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Course Names (comma-separated)</label>
                                        <input type="text" class="form-control" name="course_names" placeholder="Math,Physics,Chemistry,Biology" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Student Grades</label>
                                        <input type="text" class="form-control" name="student_grades" placeholder="1.25,1.50,2.00,1.75" required>
                                        <small class="form-text text-muted">Transmuted or raw grades</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Class Averages</label>
                                        <input type="text" class="form-control" name="class_averages" placeholder="1.75,2.00,2.25,1.50" required>
                                        <small class="form-text text-muted">Same format as student grades</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Credit Units</label>
                                        <input type="text" class="form-control" name="credit_units" placeholder="3,4,3,3" required>
                                        <small class="form-text text-muted">Course credit units</small>
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
                                        <input type="text" class="form-control" name="historical_grades" placeholder="1.50,1.75,2.00,1.25,1.50" required>
                                        <small class="form-text text-muted">Previous grades (transmuted or raw)</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Attendance Rate (%)</label>
                                        <input type="number" step="0.01" class="form-control" name="attendance_rate" placeholder="95.50" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Course Hours/Week</label>
                                        <input type="number" step="0.01" class="form-control" name="course_hours" placeholder="40.00" required>
                                        <small class="form-text text-muted">Total lecture + lab hours</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Credit Units</label>
                                        <input type="number" step="0.5" class="form-control" name="credit_units" placeholder="18" required>
                                        <small class="form-text text-muted">Current semester units</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Grade Format</label>
                                        <select class="form-control" name="grade_format">
                                            <option value="auto">Auto-detect</option>
                                            <option value="transmuted">Transmuted (1.00-5.00)</option>
                                            <option value="raw">Raw (0-100)</option>
                                        </select>
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
                        <h4>Scholarship Eligibility - Mapua MCL System</h4>
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
                                        <label class="form-label">TWA (Term Weighted Average)</label>
                                        <input type="number" step="0.01" class="form-control" name="twa" placeholder="1.75" min="1.00" max="5.00" required>
                                        <small class="form-text text-muted">Range: 1.00-5.00 (1.00 = highest)</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Current Credit Units</label>
                                        <input type="number" step="0.5" class="form-control" name="credit_units" placeholder="18" required>
                                        <small class="form-text text-muted">Current semester units</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Completed Units</label>
                                        <input type="number" step="0.5" class="form-control" name="completed_units" placeholder="75" required>
                                        <small class="form-text text-muted">Total units completed</small>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Check Eligibility</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
        
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // First try to get the analysis results
                $client = new SoapClient(null, array(
                    'location' => 'http://localhost/student_analytics/soap_server.php',
                    'uri' => 'http://localhost/student_analytics',
                    'trace' => 1,
                    'connection_timeout' => 30
                ));
                
                $action = $_POST['action'];
                $result = null;
                
                switch ($action) {
                    case 'grade_analysis':
                        $result = $client->analyzeGrades(
                            $_POST['student_id'],
                            $_POST['current_grades'],
                            $_POST['course_units'],
                            $_POST['historical_grades'],
                            $_POST['grade_format'] ?? 'auto'
                        );
                        break;
                        
                    case 'course_comparison':
                        $result = $client->compareCourses(
                            $_POST['student_id'],
                            $_POST['course_names'],
                            $_POST['student_grades'],
                            $_POST['class_averages'],
                            $_POST['credit_units']
                        );
                        break;
                        
                    case 'predictive_modeling':
                        $result = $client->generatePrediction(
                            $_POST['student_id'],
                            $_POST['historical_grades'],
                            $_POST['attendance_rate'],
                            $_POST['course_hours'] ?? '0',
                            $_POST['credit_units'] ?? '0',
                            $_POST['grade_format'] ?? 'auto'
                        );
                        break;
                        
                    case 'scholarship_eligibility':
                        $result = $client->checkScholarshipEligibility(
                            $_POST['student_id'],
                            $_POST['twa'],
                            $_POST['credit_units'],
                            $_POST['completed_units']
                        );
                        break;

                }
                
                if ($result) {
                    $data = json_decode($result, true);
                    echo '<div class="result-card">';
                    echo '<h5>Results for ' . ucfirst(str_replace('_', ' ', $action)) . '</h5>';
                    
                    // Display analysis results
                    echo '<div class="row">';
                    foreach ($data as $key => $value) {
                        $label = ucwords(str_replace('_', ' ', $key));
                        echo '<div class="col-md-6 mb-2">';
                        echo "<strong>$label:</strong> $value";
                        echo '</div>';
                    }
                    echo '</div>';
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