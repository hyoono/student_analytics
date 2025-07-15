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

class StudentAnalyticsService {
    
    /**
     * Grade Analysis - Heavy computation with statistical analysis
     */
    public function analyzeGrades($studentId, $currentGrades, $subjectWeights, $historicalGrades) {
        // Parse input data
        $grades = array_map('floatval', explode(',', $currentGrades));
        $weights = array_map('floatval', explode(',', $subjectWeights));
        $historical = array_map(function($term) {
            return array_map('floatval', explode(',', $term));
        }, explode(';', $historicalGrades));
        
        // Heavy computation: Calculate weighted average
        $weightedSum = 0;
        $totalWeight = 0;
        for ($i = 0; $i < count($grades); $i++) {
            $weight = isset($weights[$i]) ? $weights[$i] : 1;
            $weightedSum += $grades[$i] * $weight;
            $totalWeight += $weight;
        }
        $weightedAverage = $totalWeight > 0 ? $weightedSum / $totalWeight : 0;
        
        // Convert to GPA (4.0 scale)
        $currentGpa = $this->convertToGPA($weightedAverage);
        
        // Grade distribution analysis
        $gradeDistribution = $this->calculateGradeDistribution($grades);
        
        // Performance trend analysis
        $performanceTrend = $this->analyzePerformanceTrend($historical, $grades);
        
        // Generate suggestions based on analysis
        $suggestions = $this->generateGradeSuggestions($grades, $weights, $performanceTrend);
        
        return json_encode([
            'weightedAverage' => round($weightedAverage, 2),
            'currentGpa' => round($currentGpa, 2),
            'gradeDistribution' => $gradeDistribution,
            'performanceTrend' => $performanceTrend,
            'suggestions' => $suggestions
        ]);
    }
    
    /**
     * Subject Performance Comparison - Complex comparative analysis
     */
    public function compareSubjects($studentId, $subjectNames, $subjectGrades, $classAverages, $creditHours) {
        $subjects = explode(',', $subjectNames);
        $grades = array_map('floatval', explode(',', $subjectGrades));
        $averages = array_map('floatval', explode(',', $classAverages));
        $credits = array_map('intval', explode(',', $creditHours));
        
        // Find best and worst performing subjects
        $maxGrade = max($grades);
        $minGrade = min($grades);
        $bestSubjectIndex = array_search($maxGrade, $grades);
        $worstSubjectIndex = array_search($minGrade, $grades);
        
        $bestSubject = $subjects[$bestSubjectIndex];
        $weakestSubject = $subjects[$worstSubjectIndex];
        
        // Calculate overall GPA weighted by credit hours
        $totalGradePoints = 0;
        $totalCredits = 0;
        for ($i = 0; $i < count($grades); $i++) {
            $gpa = $this->convertToGPA($grades[$i]);
            $credit = isset($credits[$i]) ? $credits[$i] : 3;
            $totalGradePoints += $gpa * $credit;
            $totalCredits += $credit;
        }
        $overallGpa = $totalCredits > 0 ? $totalGradePoints / $totalCredits : 0;
        
        // Compare with class averages
        $subjectsAboveAverage = [];
        $subjectsBelowAverage = [];
        for ($i = 0; $i < count($grades); $i++) {
            if ($grades[$i] > $averages[$i]) {
                $subjectsAboveAverage[] = $subjects[$i];
            } else {
                $subjectsBelowAverage[] = $subjects[$i];
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
        $recommendations = $this->generateSubjectRecommendations($subjects, $grades, $averages);
        
        return json_encode([
            'bestSubject' => $bestSubject,
            'bestGrade' => $maxGrade,
            'weakestSubject' => $weakestSubject,
            'weakestGrade' => $minGrade,
            'overallGpa' => round($overallGpa, 2),
            'subjectsAboveAverage' => implode(',', $subjectsAboveAverage),
            'subjectsBelowAverage' => implode(',', $subjectsBelowAverage),
            'performanceVariance' => round($performanceVariance, 2),
            'recommendations' => $recommendations
        ]);
    }
    
    /**
     * Predictive Performance Modeling - Machine learning simulation
     */
    public function generatePrediction($studentId, $historicalGrades, $attendanceRate, $participationScore, $studyHoursPerWeek, $extracurricularHours) {
        $grades = array_map('floatval', explode(',', $historicalGrades));
        $attendance = floatval($attendanceRate);
        $participation = floatval($participationScore);
        $studyHours = intval($studyHoursPerWeek);
        $extraHours = intval($extracurricularHours);
        
        // Complex predictive algorithm simulation
        $trendFactor = $this->calculateTrendFactor($grades);
        $attendanceFactor = ($attendance / 100) * 0.3;
        $participationFactor = ($participation / 10) * 0.2;
        $studyFactor = min($studyHours / 40, 1) * 0.3;
        $balanceFactor = ($extraHours > 0 && $extraHours < 15) ? 0.2 : 0.1;
        
        // Weighted prediction model
        $basePrediction = end($grades);
        $adjustmentFactor = $attendanceFactor + $participationFactor + $studyFactor + $balanceFactor;
        $predictedGrade = $basePrediction + ($trendFactor * 10) + ($adjustmentFactor * 15);
        $predictedGrade = max(0, min(100, $predictedGrade));
        
        // Risk assessment
        $riskFactors = [];
        $riskScore = 0;
        
        if ($attendance < 85) {
            $riskFactors[] = "Low attendance";
            $riskScore += 3;
        }
        if ($participation < 6) {
            $riskFactors[] = "Low participation";
            $riskScore += 2;
        }
        if ($studyHours < 15) {
            $riskFactors[] = "Insufficient study time";
            $riskScore += 2;
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
        if ($attendanceFactor > 0.2) $keyFactors[] = "Good attendance";
        if ($participationFactor > 0.15) $keyFactors[] = "Active participation";
        if ($studyFactor > 0.2) $keyFactors[] = "Adequate study time";
        if ($trendFactor > 0) $keyFactors[] = "Improving trend";
        
        // Generate recommendations
        $recommendations = $this->generatePredictiveRecommendations($riskFactors, $studyHours, $participation, $attendance);
        
        return json_encode([
            'predictedGrade' => round($predictedGrade, 2),
            'riskLevel' => $riskLevel,
            'confidenceScore' => round($confidenceScore, 2),
            'trendAnalysis' => $this->describeTrend($trendFactor),
            'keyFactors' => implode(',', $keyFactors),
            'recommendations' => $recommendations,
            'atRisk' => $atRisk
        ]);
    }
    
    /**
     * Scholarship Eligibility - Rule-based decision system
     */
    public function checkEligibility($studentId, $gpa, $extracurriculars, $incomeLevel, $honors, $communityServiceHours, $leadershipPositions) {
        $studentGpa = floatval($gpa);
        $extraList = array_filter(explode(',', $extracurriculars));
        $income = trim($incomeLevel);
        $honorsList = array_filter(explode(',', $honors));
        $serviceHours = intval($communityServiceHours);
        $leadershipList = array_filter(explode(',', $leadershipPositions));
        
        // Scoring system (out of 100)
        $gpaScore = 0;
        $extracurricularScore = 0;
        $serviceScore = 0;
        $leadershipScore = 0;
        $needBasedBonus = 0;
        
        // GPA scoring (30 points max)
        if ($studentGpa >= 3.8) $gpaScore = 30;
        else if ($studentGpa >= 3.5) $gpaScore = 25;
        else if ($studentGpa >= 3.2) $gpaScore = 20;
        else if ($studentGpa >= 3.0) $gpaScore = 15;
        else if ($studentGpa >= 2.8) $gpaScore = 10;
        else $gpaScore = 5;
        
        // Extracurricular scoring (25 points max)
        $extracurricularScore = min(count($extraList) * 5, 25);
        
        // Community service scoring (20 points max)
        if ($serviceHours >= 150) $serviceScore = 20;
        else if ($serviceHours >= 100) $serviceScore = 15;
        else if ($serviceHours >= 50) $serviceScore = 10;
        else if ($serviceHours >= 25) $serviceScore = 5;
        
        // Leadership scoring (15 points max)
        $leadershipScore = min(count($leadershipList) * 5, 15);
        
        // Need-based bonus (10 points max)
        if ($income === "Low") $needBasedBonus = 10;
        else if ($income === "Middle") $needBasedBonus = 5;
        
        // Honors bonus
        $honorsBonus = min(count($honorsList) * 2, 10);
        
        $overallScore = $gpaScore + $extracurricularScore + $serviceScore + $leadershipScore + $needBasedBonus + $honorsBonus;
        
        // Determine eligibility status
        $eligibilityStatus = "Not Eligible";
        $eligibleScholarships = [];
        
        if ($overallScore >= 80) {
            $eligibilityStatus = "Eligible";
            $eligibleScholarships = ["Merit Scholarship", "Leadership Award", "Community Service Grant"];
            if ($income === "Low") $eligibleScholarships[] = "Need-Based Grant";
        } else if ($overallScore >= 60) {
            $eligibilityStatus = "Conditional";
            $eligibleScholarships = ["Partial Merit Scholarship"];
            if ($income === "Low") $eligibleScholarships[] = "Need-Based Assistance";
        }
        
        // Generate recommendations
        $recommendations = $this->generateScholarshipRecommendations($overallScore, $gpaScore, $extracurricularScore, $serviceScore, $leadershipScore);
        
        return json_encode([
            'eligibilityStatus' => $eligibilityStatus,
            'overallScore' => round($overallScore, 2),
            'gpaScore' => round($gpaScore, 2),
            'extracurricularScore' => round($extracurricularScore, 2),
            'serviceScore' => round($serviceScore, 2),
            'leadershipScore' => round($leadershipScore, 2),
            'needBasedBonus' => round($needBasedBonus, 2),
            'eligibleScholarships' => implode(',', $eligibleScholarships),
            'recommendations' => $recommendations
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
        
        $allGrades = array_merge(...$historical, $current);
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
    
    private function generateGradeSuggestions($grades, $weights, $trend) {
        $suggestions = [];
        
        if (min($grades) < 70) {
            $suggestions[] = "Focus on improving lowest performing subject";
        }
        
        if (strpos($trend, "declining") !== false || strpos($trend, "downward") !== false) {
            $suggestions[] = "Implement consistent study schedule";
            $suggestions[] = "Seek additional tutoring support";
        }
        
        if (array_sum($grades) / count($grades) < 80) {
            $suggestions[] = "Consider forming study groups";
        }
        
        return implode("; ", $suggestions);
    }
    
    private function generateSubjectRecommendations($subjects, $grades, $averages) {
        $recommendations = [];
        
        for ($i = 0; $i < count($grades); $i++) {
            if ($grades[$i] < $averages[$i] - 5) {
                $recommendations[] = "Focus additional study time on " . $subjects[$i];
            }
        }
        
        if (empty($recommendations)) {
            $recommendations[] = "Maintain current performance levels";
        }
        
        return implode("; ", $recommendations);
    }
    
    private function generatePredictiveRecommendations($riskFactors, $studyHours, $participation, $attendance) {
        $recommendations = [];
        
        if (in_array("Low attendance", $riskFactors)) {
            $recommendations[] = "Improve class attendance to above 90%";
        }
        
        if (in_array("Low participation", $riskFactors)) {
            $recommendations[] = "Increase class participation and engagement";
        }
        
        if (in_array("Insufficient study time", $riskFactors)) {
            $recommendations[] = "Increase weekly study hours to at least 20";
        }
        
        if (in_array("Declining grade trend", $riskFactors)) {
            $recommendations[] = "Seek academic counseling immediately";
        }
        
        if (empty($recommendations)) {
            $recommendations[] = "Continue current positive academic habits";
        }
        
        return implode("; ", $recommendations);
    }
    
    private function generateScholarshipRecommendations($overall, $gpa, $extra, $service, $leadership) {
        $recommendations = [];
        
        if ($gpa < 25) {
            $recommendations[] = "Focus on improving GPA above 3.5";
        }
        
        if ($extra < 15) {
            $recommendations[] = "Join additional extracurricular activities";
        }
        
        if ($service < 10) {
            $recommendations[] = "Increase community service involvement";
        }
        
        if ($leadership < 10) {
            $recommendations[] = "Seek leadership opportunities in organizations";
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