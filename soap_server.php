<?php
// Enable CORS for cross-origin requests
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, SOAPAction");
header("Content-Type: text/xml; charset=utf-8");

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

// Include chart generation class
require_once 'charts/ChartGenerator.php';

class StudentAnalyticsService {
    
    /**
     * Grade Analysis - Heavy computation with statistical analysis
     * Updated for Mapua MCL grading system
     */
    public function analyzeGrades($studentId, $currentGrades, $courseUnits, $historicalGrades, $gradeFormat = 'auto') {
        // Parse input data
        $grades = array_map('floatval', explode(',', $currentGrades));
        $units = array_map('floatval', explode(',', $courseUnits));
        $historical = array_map(function($term) {
            return array_map('floatval', explode(',', $term));
        }, explode(';', $historicalGrades));
        
        // Detect grade format if auto
        if ($gradeFormat === 'auto') {
            $gradeFormat = $this->detectGradeFormat($grades);
        }
        
        // Calculate Term Weighted Average (TWA)
        $twa = $this->calculateTWA($grades, $units, $gradeFormat);
        
        // Calculate weighted average using course units
        $weightedSum = 0;
        $totalUnits = 0;
        for ($i = 0; $i < count($grades); $i++) {
            $unit = isset($units[$i]) ? $units[$i] : 3;
            $grade = $grades[$i];
            
            // Convert to consistent format for calculations
            if ($gradeFormat === 'transmuted') {
                $grade = $this->convertTransmutedToRaw($grade);
            }
            
            $weightedSum += $grade * $unit;
            $totalUnits += $unit;
        }
        $weightedAverage = $totalUnits > 0 ? $weightedSum / $totalUnits : 0;
        
        // Grade distribution analysis (using raw grades for consistency)
        $rawGrades = $grades;
        if ($gradeFormat === 'transmuted') {
            $rawGrades = array_map([$this, 'convertTransmutedToRaw'], $grades);
        }
        $gradeDistribution = $this->calculateGradeDistribution($rawGrades);
        
        // Performance trend analysis
        $performanceTrend = $this->analyzePerformanceTrend($historical, $grades);
        
        // Generate suggestions based on analysis
        $suggestions = $this->generateGradeSuggestions($grades, $units, $performanceTrend, $gradeFormat);
        
        return json_encode([
            'twa' => $twa,
            'weightedAverage' => round($weightedAverage, 2),
            'gradeFormat' => $gradeFormat,
            'gradeDistribution' => $gradeDistribution,
            'performanceTrend' => $performanceTrend,
            'suggestions' => $suggestions
        ]);
    }
    
    /**
     * Course Performance Comparison - Complex comparative analysis
     * Updated for Mapua MCL course-based system
     */
    public function compareCourses($studentId, $courseNames, $studentGrades, $classAverages, $creditUnits) {
        $courses = explode(',', $courseNames);
        $grades = array_map('floatval', explode(',', $studentGrades));
        $averages = array_map('floatval', explode(',', $classAverages));
        $units = array_map('floatval', explode(',', $creditUnits));
        
        // Detect grade format
        $gradeFormat = $this->detectGradeFormat($grades);
        
        // Find best and worst performing courses
        $maxGrade = max($grades);
        $minGrade = min($grades);
        $bestCourseIndex = array_search($maxGrade, $grades);
        $worstCourseIndex = array_search($minGrade, $grades);
        
        $bestCourse = $courses[$bestCourseIndex];
        $weakestCourse = $courses[$worstCourseIndex];
        
        // Calculate TWA using course units
        $twa = $this->calculateTWA($grades, $units, $gradeFormat);
        
        // Compare with class averages
        $coursesAboveAverage = [];
        $coursesBelowAverage = [];
        for ($i = 0; $i < count($grades); $i++) {
            if ($grades[$i] > $averages[$i]) {
                $coursesAboveAverage[] = $courses[$i];
            } else {
                $coursesBelowAverage[] = $courses[$i];
            }
        }
        
        // Calculate performance variance
        $mean = array_sum($grades) / count($grades);
        $variance = 0;
        foreach ($grades as $grade) {
            $variance += pow($grade - $mean, 2);
        }
        $performanceVariance = $variance / count($grades);
        
        // Generate recommendations
        $recommendations = $this->generateCourseRecommendations($courses, $grades, $averages);
        
        return json_encode([
            'bestCourse' => $bestCourse,
            'bestGrade' => $maxGrade,
            'weakestCourse' => $weakestCourse,
            'weakestGrade' => $minGrade,
            'twa' => $twa,
            'gradeFormat' => $gradeFormat,
            'coursesAboveAverage' => implode(',', $coursesAboveAverage),
            'coursesBelowAverage' => implode(',', $coursesBelowAverage),
            'performanceVariance' => round($performanceVariance, 2),
            'recommendations' => $recommendations
        ]);
    }
    
    /**
     * Predictive Performance Modeling - Machine learning simulation
     * Updated for Mapua MCL system with course hours and credit units
     */
    public function generatePrediction($studentId, $historicalGrades, $attendanceRate, $courseHours, $creditUnits, $gradeFormat = 'auto') {
        $grades = array_map('floatval', explode(',', $historicalGrades));
        $attendance = floatval($attendanceRate);
        $hours = floatval($courseHours);
        $units = floatval($creditUnits);
        
        // Detect grade format if auto
        if ($gradeFormat === 'auto') {
            $gradeFormat = $this->detectGradeFormat($grades);
        }
        
        // Complex predictive algorithm simulation
        $trendFactor = $this->calculateTrendFactor($grades);
        $attendanceFactor = ($attendance / 100) * 0.4; // Increased weight
        $courseHoursFactor = min($hours / 60, 1) * 0.3; // Course hours factor
        $creditUnitsFactor = min($units / 21, 1) * 0.3; // Credit units factor
        
        // Weighted prediction model
        $basePrediction = end($grades);
        $adjustmentFactor = $attendanceFactor + $courseHoursFactor + $creditUnitsFactor;
        
        if ($gradeFormat === 'transmuted') {
            // For transmuted grades, prediction should be in transmuted format
            $predictedGrade = $basePrediction + ($trendFactor * 0.5) + ($adjustmentFactor * 0.5);
            $predictedGrade = max(1.00, min(5.00, $predictedGrade));
        } else {
            // For raw grades, prediction should be in raw format
            $predictedGrade = $basePrediction + ($trendFactor * 10) + ($adjustmentFactor * 15);
            $predictedGrade = max(0, min(100, $predictedGrade));
        }
        
        // Risk assessment
        $riskFactors = [];
        $riskScore = 0;
        
        if ($attendance < 85) {
            $riskFactors[] = "Low attendance";
            $riskScore += 3;
        }
        if ($hours < 30) {
            $riskFactors[] = "Insufficient course hours";
            $riskScore += 2;
        }
        if ($units < 15) {
            $riskFactors[] = "Low credit unit load";
            $riskScore += 1;
        }
        if ($trendFactor < -0.5) {
            $riskFactors[] = "Declining grade trend";
            $riskScore += 4;
        }
        
        $riskLevel = $riskScore >= 6 ? "High" : ($riskScore >= 3 ? "Medium" : "Low");
        $atRisk = $riskScore >= 6;
        
        // Confidence calculation
        $dataQuality = count($grades) >= 5 ? 1 : count($grades) / 5;
        $consistencyFactor = 1 - (array_sum(array_map(function($g) use ($grades) {
            return abs($g - array_sum($grades) / count($grades));
        }, $grades)) / count($grades) / 100);
        $confidenceScore = ($dataQuality * $consistencyFactor) * 100;
        
        // Key factors identification
        $keyFactors = [];
        if ($attendanceFactor > 0.3) $keyFactors[] = "Good attendance";
        if ($courseHoursFactor > 0.2) $keyFactors[] = "Adequate course hours";
        if ($creditUnitsFactor > 0.2) $keyFactors[] = "Optimal credit load";
        if ($trendFactor > 0) $keyFactors[] = "Improving trend";
        
        // Generate recommendations
        $recommendations = $this->generatePredictiveRecommendations($riskFactors, $hours, $units, $attendance);
        
        return json_encode([
            'predictedGrade' => round($predictedGrade, 2),
            'gradeFormat' => $gradeFormat,
            'riskLevel' => $riskLevel,
            'confidenceScore' => round($confidenceScore, 2),
            'trendAnalysis' => $this->describeTrend($trendFactor),
            'keyFactors' => implode(',', $keyFactors),
            'recommendations' => $recommendations,
            'atRisk' => $atRisk
        ]);
    }
    
    /**
     * Scholarship Eligibility - TWA-based decision system for Mapua MCL
     * Updated to use TWA instead of GPA and simplified criteria
     */
    public function checkScholarshipEligibility($studentId, $twa, $creditUnits, $completedUnits) {
        $studentTWA = floatval($twa);
        $currentUnits = floatval($creditUnits);
        $completedUnits = floatval($completedUnits);
        
        // Validate TWA range (1.00-5.00, where 1.00 is highest)
        if ($studentTWA < 1.00 || $studentTWA > 5.00) {
            return json_encode([
                'eligibilityStatus' => 'Invalid TWA',
                'error' => 'TWA must be between 1.00 and 5.00'
            ]);
        }
        
        // Scoring system based on TWA and academic load
        $twaScore = 0;
        $academicLoadScore = 0;
        $progressScore = 0;
        
        // TWA scoring (70 points max) - Lower TWA = Higher score
        if ($studentTWA <= 1.25) $twaScore = 70;
        else if ($studentTWA <= 1.50) $twaScore = 60;
        else if ($studentTWA <= 1.75) $twaScore = 50;
        else if ($studentTWA <= 2.00) $twaScore = 40;
        else if ($studentTWA <= 2.25) $twaScore = 30;
        else if ($studentTWA <= 2.50) $twaScore = 20;
        else if ($studentTWA <= 2.75) $twaScore = 10;
        else if ($studentTWA <= 3.00) $twaScore = 5;
        else $twaScore = 0; // Failed grades
        
        // Academic load scoring (20 points max)
        if ($currentUnits >= 18) $academicLoadScore = 20;
        else if ($currentUnits >= 15) $academicLoadScore = 15;
        else if ($currentUnits >= 12) $academicLoadScore = 10;
        else $academicLoadScore = 5;
        
        // Progress scoring (10 points max)
        if ($completedUnits >= 100) $progressScore = 10;
        else if ($completedUnits >= 75) $progressScore = 8;
        else if ($completedUnits >= 50) $progressScore = 6;
        else if ($completedUnits >= 25) $progressScore = 4;
        else $progressScore = 2;
        
        $overallScore = $twaScore + $academicLoadScore + $progressScore;
        
        // Determine eligibility status and scholarship categories
        $eligibilityStatus = "Not Eligible";
        $eligibleScholarships = [];
        
        if ($overallScore >= 80 && $studentTWA <= 1.50) {
            $eligibilityStatus = "Eligible";
            $eligibleScholarships = ["Academic Excellence Scholarship", "Dean's List Award"];
            if ($studentTWA <= 1.25) {
                $eligibleScholarships[] = "President's List Scholarship";
            }
        } else if ($overallScore >= 60 && $studentTWA <= 2.00) {
            $eligibilityStatus = "Conditional";
            $eligibleScholarships = ["Merit Scholarship", "Academic Achievement Award"];
        } else if ($overallScore >= 40 && $studentTWA <= 2.50) {
            $eligibilityStatus = "Conditional";
            $eligibleScholarships = ["Academic Improvement Grant"];
        }
        
        // Generate recommendations
        $recommendations = $this->generateScholarshipRecommendations($overallScore, $twaScore, $academicLoadScore, $progressScore, $studentTWA);
        
        return json_encode([
            'eligibilityStatus' => $eligibilityStatus,
            'overallScore' => round($overallScore, 2),
            'twa' => $studentTWA,
            'twaScore' => round($twaScore, 2),
            'academicLoadScore' => round($academicLoadScore, 2),
            'progressScore' => round($progressScore, 2),
            'eligibleScholarships' => implode(',', $eligibleScholarships),
            'recommendations' => $recommendations
        ]);
    }
    
    /**
     * Generate Grades Trend Chart - Line chart showing grade progression over time
     */
    public function generateGradesTrendChart($studentId, $width = 800, $height = 600) {
        // Validate dimensions
        if ($width < 400 || $width > 1200 || $height < 300 || $height > 800) {
            return $this->createErrorResponse("Invalid chart dimensions. Width must be 400-1200, Height must be 300-800");
        }
        
        try {
            // Simulate grade data over time (in real implementation, this would come from database)
            $gradeData = $this->getGradesTrendData($studentId);
            
            if (empty($gradeData)) {
                return $this->createErrorResponse("Insufficient data for grades trend chart");
            }
            
            $chart = new ChartGenerator($width, $height);
            $base64Image = $chart->generateLineChart($gradeData, "Grade Progression Over Time - Student $studentId", "Assessment Period", "Grade");
            
            return json_encode([
                'success' => true,
                'chartType' => 'grades_trend',
                'imageData' => $base64Image,
                'studentId' => $studentId,
                'dataPoints' => count($gradeData)
            ]);
        } catch (Exception $e) {
            return $this->createErrorResponse("Error generating grades trend chart: " . $e->getMessage());
        }
    }
    
    /**
     * Generate Subject Comparison Chart - Bar chart comparing performance across subjects
     */
    public function generateSubjectComparisonChart($studentId, $width = 800, $height = 600) {
        // Validate dimensions
        if ($width < 400 || $width > 1200 || $height < 300 || $height > 800) {
            return $this->createErrorResponse("Invalid chart dimensions. Width must be 400-1200, Height must be 300-800");
        }
        
        try {
            // Simulate subject performance data
            $subjectData = $this->getSubjectComparisonData($studentId);
            
            if (empty($subjectData)) {
                return $this->createErrorResponse("Insufficient data for subject comparison chart");
            }
            
            $chart = new ChartGenerator($width, $height);
            $base64Image = $chart->generateBarChart($subjectData, "Subject Performance Comparison - Student $studentId", "Subjects", "Grade");
            
            return json_encode([
                'success' => true,
                'chartType' => 'subject_comparison',
                'imageData' => $base64Image,
                'studentId' => $studentId,
                'subjects' => count($subjectData)
            ]);
        } catch (Exception $e) {
            return $this->createErrorResponse("Error generating subject comparison chart: " . $e->getMessage());
        }
    }
    
    /**
     * Generate GPA Progress Chart - Line chart showing GPA/TWA changes over terms
     * Updated to support both GPA and TWA data
     */
    public function generateGPAProgressChart($studentId, $width = 800, $height = 600) {
        // Validate dimensions
        if ($width < 400 || $width > 1200 || $height < 300 || $height > 800) {
            return $this->createErrorResponse("Invalid chart dimensions. Width must be 400-1200, Height must be 300-800");
        }
        
        try {
            // Simulate TWA progress data (updated for Mapua MCL system)
            $twaData = $this->getTWAProgressData($studentId);
            
            if (empty($twaData)) {
                return $this->createErrorResponse("Insufficient data for TWA progress chart");
            }
            
            $chart = new ChartGenerator($width, $height);
            $base64Image = $chart->generateLineChart($twaData, "TWA Progress Over Terms - Student $studentId", "Term", "TWA");
            
            return json_encode([
                'success' => true,
                'chartType' => 'twa_progress',
                'imageData' => $base64Image,
                'studentId' => $studentId,
                'terms' => count($twaData)
            ]);
        } catch (Exception $e) {
            return $this->createErrorResponse("Error generating TWA progress chart: " . $e->getMessage());
        }
    }
    
    /**
     * Generate Performance Distribution Chart - Pie chart showing grade distribution in a class
     */
    public function generatePerformanceDistributionChart($classId, $width = 800, $height = 600) {
        // Validate dimensions
        if ($width < 400 || $width > 1200 || $height < 300 || $height > 800) {
            return $this->createErrorResponse("Invalid chart dimensions. Width must be 400-1200, Height must be 300-800");
        }
        
        try {
            // Simulate class performance distribution data
            $distributionData = $this->getPerformanceDistributionData($classId);
            
            if (empty($distributionData)) {
                return $this->createErrorResponse("Insufficient data for performance distribution chart");
            }
            
            $chart = new ChartGenerator($width, $height);
            $base64Image = $chart->generatePieChart($distributionData, "Grade Distribution - Class $classId");
            
            return json_encode([
                'success' => true,
                'chartType' => 'performance_distribution',
                'imageData' => $base64Image,
                'classId' => $classId,
                'totalStudents' => array_sum(array_values($distributionData))
            ]);
        } catch (Exception $e) {
            return $this->createErrorResponse("Error generating performance distribution chart: " . $e->getMessage());
        }
    }
    
    /**
     * Generate Class Average Chart - Bar chart comparing class averages by subject
     */
    public function generateClassAverageChart($classId, $width = 800, $height = 600) {
        // Validate dimensions
        if ($width < 400 || $width > 1200 || $height < 300 || $height > 800) {
            return $this->createErrorResponse("Invalid chart dimensions. Width must be 400-1200, Height must be 300-800");
        }
        
        try {
            // Simulate class average data by subject
            $classAverageData = $this->getClassAverageData($classId);
            
            if (empty($classAverageData)) {
                return $this->createErrorResponse("Insufficient data for class average chart");
            }
            
            $chart = new ChartGenerator($width, $height);
            $base64Image = $chart->generateBarChart($classAverageData, "Class Average by Subject - Class $classId", "Subjects", "Average Grade");
            
            return json_encode([
                'success' => true,
                'chartType' => 'class_average',
                'imageData' => $base64Image,
                'classId' => $classId,
                'subjects' => count($classAverageData)
            ]);
        } catch (Exception $e) {
            return $this->createErrorResponse("Error generating class average chart: " . $e->getMessage());
        }
    }
    
    // Chart data generation methods (simulating database queries)
    private function getGradesTrendData($studentId) {
        // Simulate grade trend data - in real implementation, this would query the database
        return [
            'Week 1' => 75 + ($studentId % 10),
            'Week 2' => 78 + ($studentId % 8),
            'Week 3' => 82 + ($studentId % 6),
            'Week 4' => 85 + ($studentId % 5),
            'Week 5' => 88 + ($studentId % 4),
            'Week 6' => 90 + ($studentId % 3),
            'Week 7' => 87 + ($studentId % 4),
            'Week 8' => 91 + ($studentId % 3)
        ];
    }
    
    private function getSubjectComparisonData($studentId) {
        // Simulate course performance data using Mapua MCL transmuted grades
        $baseGrade = 1.50 + ($studentId % 10) * 0.05;
        return [
            'Mathematics' => round($baseGrade + rand(-3, 3) * 0.25, 2),
            'Physics' => round($baseGrade + rand(-2, 4) * 0.25, 2),
            'Chemistry' => round($baseGrade + rand(-4, 2) * 0.25, 2),
            'Biology' => round($baseGrade + rand(-1, 3) * 0.25, 2),
            'Literature' => round($baseGrade + rand(-2, 2) * 0.25, 2)
        ];
    }
    
    private function getGPAProgressData($studentId) {
        // Simulate TWA progress over terms for Mapua MCL system
        return $this->getTWAProgressData($studentId);
    }
    
    private function getTWAProgressData($studentId) {
        // Simulate TWA progress over terms (1.00-5.00 scale)
        $baseTWA = 1.50 + ($studentId % 10) * 0.05;
        return [
            'Fall 2022' => round($baseTWA + 0.25, 2),
            'Spring 2023' => round($baseTWA + 0.15, 2),
            'Summer 2023' => round($baseTWA + 0.20, 2),
            'Fall 2023' => round($baseTWA - 0.10, 2),
            'Spring 2024' => round($baseTWA - 0.15, 2)
        ];
    }
    
    private function getPerformanceDistributionData($classId) {
        // Simulate grade distribution for a class
        $totalStudents = 25 + ($classId % 15);
        return [
            'A (90-100)' => floor($totalStudents * 0.2),
            'B (80-89)' => floor($totalStudents * 0.35),
            'C (70-79)' => floor($totalStudents * 0.25),
            'D (60-69)' => floor($totalStudents * 0.15),
            'F (0-59)' => floor($totalStudents * 0.05)
        ];
    }
    
    private function getClassAverageData($classId) {
        // Simulate class averages by subject
        $baseAverage = 75 + ($classId % 15);
        return [
            'Mathematics' => $baseAverage + rand(0, 10),
            'Physics' => $baseAverage + rand(-5, 8),
            'Chemistry' => $baseAverage + rand(-3, 12),
            'Biology' => $baseAverage + rand(2, 8),
            'Literature' => $baseAverage + rand(-2, 6),
            'History' => $baseAverage + rand(1, 9)
        ];
    }
    
    private function createErrorResponse($message) {
        return json_encode([
            'success' => false,
            'error' => $message,
            'chartType' => 'error'
        ]);
    }
    
    // Helper methods for complex calculations
    private function convertToGPA($percentage) {
        if ($percentage >= 97) return 4.0;
        if ($percentage >= 93) return 3.7;
        if ($percentage >= 90) return 3.3;
        if ($percentage >= 87) return 3.0;
        if ($percentage >= 83) return 2.7;
        if ($percentage >= 80) return 2.3;
        if ($percentage >= 77) return 2.0;
        if ($percentage >= 73) return 1.7;
        if ($percentage >= 70) return 1.3;
        if ($percentage >= 67) return 1.0;
        return 0.0;
    }
    
    /**
     * Convert raw percentage grade to Mapua MCL transmuted grade
     * @param float $rawGrade Raw percentage grade (0-100)
     * @return float Mapua MCL transmuted grade (1.00-5.00)
     */
    private function convertRawToTransmuted($rawGrade) {
        $rawGrade = floatval($rawGrade);
        
        if ($rawGrade >= 96) return 1.00;
        if ($rawGrade >= 93) return 1.25;
        if ($rawGrade >= 90) return 1.50;
        if ($rawGrade >= 87) return 1.75;
        if ($rawGrade >= 84) return 2.00;
        if ($rawGrade >= 81) return 2.25;
        if ($rawGrade >= 78) return 2.50;
        if ($rawGrade >= 75) return 2.75;
        if ($rawGrade >= 70) return 3.00;
        return 5.00; // Failed
    }
    
    /**
     * Convert Mapua MCL transmuted grade to raw percentage
     * @param float $transmutedGrade Mapua MCL transmuted grade (1.00-5.00)
     * @return float Raw percentage grade (0-100)
     */
    private function convertTransmutedToRaw($transmutedGrade) {
        $transmutedGrade = floatval($transmutedGrade);
        
        if ($transmutedGrade == 1.00) return 98;
        if ($transmutedGrade == 1.25) return 94;
        if ($transmutedGrade == 1.50) return 91;
        if ($transmutedGrade == 1.75) return 88;
        if ($transmutedGrade == 2.00) return 85;
        if ($transmutedGrade == 2.25) return 82;
        if ($transmutedGrade == 2.50) return 79;
        if ($transmutedGrade == 2.75) return 76;
        if ($transmutedGrade == 3.00) return 72;
        return 0; // Failed
    }
    
    /**
     * Detect grade format (raw or transmuted)
     * @param array $grades Array of grades
     * @return string 'raw' or 'transmuted'
     */
    private function detectGradeFormat($grades) {
        $transmutedCount = 0;
        $total = count($grades);
        
        foreach ($grades as $grade) {
            $grade = floatval($grade);
            // If grade is in transmuted range and matches valid transmuted values
            if ($grade >= 1.00 && $grade <= 5.00) {
                $validTransmuted = [1.00, 1.25, 1.50, 1.75, 2.00, 2.25, 2.50, 2.75, 3.00, 5.00];
                if (in_array($grade, $validTransmuted)) {
                    $transmutedCount++;
                }
            }
        }
        
        // If majority are transmuted format, return transmuted
        return ($transmutedCount > $total / 2) ? 'transmuted' : 'raw';
    }
    
    /**
     * Validate transmuted grade
     * @param float $grade Grade to validate
     * @return bool True if valid transmuted grade
     */
    private function isValidTransmutedGrade($grade) {
        $validGrades = [1.00, 1.25, 1.50, 1.75, 2.00, 2.25, 2.50, 2.75, 3.00, 5.00];
        return in_array(floatval($grade), $validGrades);
    }
    
    /**
     * Calculate Term Weighted Average (TWA)
     * @param array $grades Array of grades
     * @param array $units Array of course units
     * @param string $gradeFormat 'raw', 'transmuted', or 'auto'
     * @return float TWA value (1.00-5.00 scale)
     */
    private function calculateTWA($grades, $units, $gradeFormat = 'auto') {
        if (empty($grades) || empty($units)) return 5.00;
        
        if ($gradeFormat === 'auto') {
            $gradeFormat = $this->detectGradeFormat($grades);
        }
        
        $totalWeightedGrades = 0;
        $totalUnits = 0;
        
        for ($i = 0; $i < count($grades); $i++) {
            $grade = floatval($grades[$i]);
            $unit = isset($units[$i]) ? floatval($units[$i]) : 3; // Default 3 units
            
            // Convert to transmuted if needed
            if ($gradeFormat === 'raw') {
                $transmutedGrade = $this->convertRawToTransmuted($grade);
            } else {
                $transmutedGrade = $grade;
            }
            
            $totalWeightedGrades += $transmutedGrade * $unit;
            $totalUnits += $unit;
        }
        
        return $totalUnits > 0 ? round($totalWeightedGrades / $totalUnits, 2) : 5.00;
    }
    
    private function calculateGradeDistribution($grades) {
        $distribution = ['A' => 0, 'B' => 0, 'C' => 0, 'D' => 0, 'F' => 0];
        foreach ($grades as $grade) {
            if ($grade >= 90) $distribution['A']++;
            else if ($grade >= 80) $distribution['B']++;
            else if ($grade >= 70) $distribution['C']++;
            else if ($grade >= 60) $distribution['D']++;
            else $distribution['F']++;
        }
        return implode(', ', array_map(function($letter, $count) {
            return "$letter: $count";
        }, array_keys($distribution), $distribution));
    }
    
    private function analyzePerformanceTrend($historical, $current) {
        if (empty($historical)) return "No historical data available";
        
        $allGrades = array_merge($historical[0] ?? [], $current);
        $trends = [];
        
        for ($i = 1; $i < count($allGrades); $i++) {
            $trends[] = $allGrades[$i] - $allGrades[$i-1];
        }
        
        $avgTrend = array_sum($trends) / count($trends);
        
        if ($avgTrend > 2) return "Strong upward trend";
        if ($avgTrend > 0.5) return "Improving";
        if ($avgTrend > -0.5) return "Stable";
        if ($avgTrend > -2) return "Declining";
        return "Concerning downward trend";
    }
    
    private function calculateTrendFactor($grades) {
        if (count($grades) < 2) return 0;
        
        $x = range(1, count($grades));
        $y = $grades;
        
        $n = count($grades);
        $sumX = array_sum($x);
        $sumY = array_sum($y);
        $sumXY = 0;
        $sumX2 = 0;
        
        for ($i = 0; $i < $n; $i++) {
            $sumXY += $x[$i] * $y[$i];
            $sumX2 += $x[$i] * $x[$i];
        }
        
        $slope = ($n * $sumXY - $sumX * $sumY) / ($n * $sumX2 - $sumX * $sumX);
        return $slope / 10; // Normalize
    }
    
    private function describeTrend($trendFactor) {
        if ($trendFactor > 0.5) return "Strong improvement trajectory";
        if ($trendFactor > 0.1) return "Gradual improvement";
        if ($trendFactor > -0.1) return "Stable performance";
        if ($trendFactor > -0.5) return "Slight decline";
        return "Significant decline";
    }
    
    private function generateGradeSuggestions($grades, $units, $trend, $gradeFormat) {
        $suggestions = [];
        
        // Convert grades to raw for analysis if needed
        $rawGrades = $grades;
        if ($gradeFormat === 'transmuted') {
            $rawGrades = array_map([$this, 'convertTransmutedToRaw'], $grades);
        }
        
        if (min($rawGrades) < 70) {
            $suggestions[] = "Focus on improving lowest performing course";
        }
        
        if (strpos($trend, "declining") !== false || strpos($trend, "downward") !== false) {
            $suggestions[] = "Implement consistent study schedule";
            $suggestions[] = "Seek additional tutoring support";
        }
        
        if (array_sum($rawGrades) / count($rawGrades) < 80) {
            $suggestions[] = "Consider forming study groups";
        }
        
        return implode("; ", $suggestions);
    }
    
    private function generateCourseRecommendations($courses, $grades, $averages) {
        $recommendations = [];
        
        for ($i = 0; $i < count($grades); $i++) {
            if ($grades[$i] < $averages[$i] - 5) {
                $recommendations[] = "Focus additional study time on " . $courses[$i];
            }
        }
        
        if (empty($recommendations)) {
            $recommendations[] = "Maintain current performance levels";
        }
        
        return implode("; ", $recommendations);
    }
    
    private function generatePredictiveRecommendations($riskFactors, $courseHours, $creditUnits, $attendance) {
        $recommendations = [];
        
        if (in_array("Low attendance", $riskFactors)) {
            $recommendations[] = "Improve class attendance to above 90%";
        }
        
        if (in_array("Insufficient course hours", $riskFactors)) {
            $recommendations[] = "Increase course engagement and study time";
        }
        
        if (in_array("Low credit unit load", $riskFactors)) {
            $recommendations[] = "Consider taking additional courses if academically prepared";
        }
        
        if (in_array("Declining grade trend", $riskFactors)) {
            $recommendations[] = "Seek academic counseling immediately";
        }
        
        if (empty($recommendations)) {
            $recommendations[] = "Continue current positive academic habits";
        }
        
        return implode("; ", $recommendations);
    }
    
    private function generateScholarshipRecommendations($overall, $twa, $academicLoad, $progress, $currentTWA) {
        $recommendations = [];
        
        if ($twa < 40) {
            $recommendations[] = "Focus on improving TWA to 2.00 or below";
        }
        
        if ($academicLoad < 15) {
            $recommendations[] = "Consider increasing credit unit load";
        }
        
        if ($progress < 6) {
            $recommendations[] = "Maintain steady progress toward degree completion";
        }
        
        if ($currentTWA > 2.50) {
            $recommendations[] = "Critical: Improve grades to maintain academic standing";
        }
        
        if ($overall >= 80) {
            $recommendations[] = "Apply for multiple scholarship opportunities";
        }
        
        return implode("; ", $recommendations);
    }
}

// Create SOAP server
$server = new SoapServer(null, array(
    'uri' => 'http://localhost/student_analytics',
    'location' => 'http://localhost/student_analytics/soap_server.php'
));

$server->setClass('StudentAnalyticsService');

// Handle the request
try {
    $server->handle();
} catch (Exception $e) {
    echo "SOAP Fault: " . $e->getMessage();
}
?>