<?php

class ChartGenerator {
    private $width;
    private $height;
    private $image;
    private $colors;

    public function __construct($width = 800, $height = 600) {
        $this->width = $width;
        $this->height = $height;
        $this->image = imagecreate($width, $height);
        $this->initializeColors();
    }

    private function initializeColors() {
        $this->colors = [
            'white' => imagecolorallocate($this->image, 255, 255, 255),
            'black' => imagecolorallocate($this->image, 0, 0, 0),
            'blue' => imagecolorallocate($this->image, 52, 152, 219),
            'red' => imagecolorallocate($this->image, 231, 76, 60),
            'green' => imagecolorallocate($this->image, 46, 204, 113),
            'orange' => imagecolorallocate($this->image, 243, 156, 18),
            'purple' => imagecolorallocate($this->image, 155, 89, 182),
            'gray' => imagecolorallocate($this->image, 149, 165, 166),
            'light_gray' => imagecolorallocate($this->image, 236, 240, 241),
            'dark_gray' => imagecolorallocate($this->image, 52, 73, 94)
        ];
    }

    public function generateLineChart($data, $title, $xLabel, $yLabel) {
        // Fill background
        imagefill($this->image, 0, 0, $this->colors['white']);
        
        // Chart area dimensions
        $margin = 80;
        $chartWidth = $this->width - 2 * $margin;
        $chartHeight = $this->height - 2 * $margin;
        
        // Draw title
        $titleFont = 5;
        $titleWidth = strlen($title) * imagefontwidth($titleFont);
        imagestring($this->image, $titleFont, ($this->width - $titleWidth) / 2, 20, $title, $this->colors['black']);
        
        // Draw chart border
        imagerectangle($this->image, $margin, $margin, $this->width - $margin, $this->height - $margin, $this->colors['black']);
        
        if (empty($data)) {
            $this->drawErrorMessage("No data available");
            return $this->getBase64Image();
        }
        
        // Find min and max values
        $values = array_values($data);
        $minValue = min($values);
        $maxValue = max($values);
        $valueRange = $maxValue - $minValue;
        
        if ($valueRange == 0) $valueRange = 1;
        
        // Draw grid lines
        $this->drawGrid($margin, $chartWidth, $chartHeight, $minValue, $maxValue);
        
        // Draw axes labels
        imagestring($this->image, 3, 20, $this->height / 2, $yLabel, $this->colors['black']);
        $xLabelWidth = strlen($xLabel) * imagefontwidth(3);
        imagestring($this->image, 3, ($this->width - $xLabelWidth) / 2, $this->height - 30, $xLabel, $this->colors['black']);
        
        // Draw data points and lines
        $points = [];
        $keys = array_keys($data);
        $stepX = $chartWidth / (count($data) - 1);
        
        for ($i = 0; $i < count($data); $i++) {
            $x = $margin + $i * $stepX;
            $y = $margin + $chartHeight - (($values[$i] - $minValue) / $valueRange) * $chartHeight;
            $points[] = ['x' => $x, 'y' => $y, 'value' => $values[$i], 'label' => $keys[$i]];
            
            // Draw point
            imagefilledellipse($this->image, $x, $y, 8, 8, $this->colors['blue']);
            
            // Draw value label
            imagestring($this->image, 2, $x - 15, $y - 25, round($values[$i], 1), $this->colors['black']);
            
            // Draw x-axis label
            $labelWidth = strlen($keys[$i]) * imagefontwidth(2);
            imagestring($this->image, 2, $x - $labelWidth/2, $this->height - $margin + 10, $keys[$i], $this->colors['black']);
        }
        
        // Draw connecting lines
        for ($i = 0; $i < count($points) - 1; $i++) {
            imageline($this->image, $points[$i]['x'], $points[$i]['y'], 
                     $points[$i+1]['x'], $points[$i+1]['y'], $this->colors['blue']);
        }
        
        return $this->getBase64Image();
    }

    public function generateBarChart($data, $title, $xLabel, $yLabel) {
        // Fill background
        imagefill($this->image, 0, 0, $this->colors['white']);
        
        // Chart area dimensions
        $margin = 80;
        $chartWidth = $this->width - 2 * $margin;
        $chartHeight = $this->height - 2 * $margin;
        
        // Draw title
        $titleFont = 5;
        $titleWidth = strlen($title) * imagefontwidth($titleFont);
        imagestring($this->image, $titleFont, ($this->width - $titleWidth) / 2, 20, $title, $this->colors['black']);
        
        // Draw chart border
        imagerectangle($this->image, $margin, $margin, $this->width - $margin, $this->height - $margin, $this->colors['black']);
        
        if (empty($data)) {
            $this->drawErrorMessage("No data available");
            return $this->getBase64Image();
        }
        
        // Find max value for scaling
        $values = array_values($data);
        $maxValue = max($values);
        if ($maxValue == 0) $maxValue = 1;
        
        // Draw grid lines
        $this->drawGrid($margin, $chartWidth, $chartHeight, 0, $maxValue);
        
        // Draw axes labels
        imagestring($this->image, 3, 20, $this->height / 2, $yLabel, $this->colors['black']);
        $xLabelWidth = strlen($xLabel) * imagefontwidth(3);
        imagestring($this->image, 3, ($this->width - $xLabelWidth) / 2, $this->height - 30, $xLabel, $this->colors['black']);
        
        // Draw bars
        $keys = array_keys($data);
        $barWidth = $chartWidth / count($data) * 0.7;
        $barSpacing = $chartWidth / count($data) * 0.3;
        $colors = [$this->colors['blue'], $this->colors['green'], $this->colors['orange'], $this->colors['purple'], $this->colors['red']];
        
        for ($i = 0; $i < count($data); $i++) {
            $barHeight = ($values[$i] / $maxValue) * $chartHeight;
            $x1 = $margin + $i * ($barWidth + $barSpacing) + $barSpacing/2;
            $y1 = $margin + $chartHeight;
            $x2 = $x1 + $barWidth;
            $y2 = $y1 - $barHeight;
            
            // Draw bar
            $color = $colors[$i % count($colors)];
            imagefilledrectangle($this->image, $x1, $y2, $x2, $y1, $color);
            imagerectangle($this->image, $x1, $y2, $x2, $y1, $this->colors['black']);
            
            // Draw value label
            imagestring($this->image, 2, $x1 + $barWidth/2 - 15, $y2 - 20, round($values[$i], 1), $this->colors['black']);
            
            // Draw x-axis label
            $labelWidth = strlen($keys[$i]) * imagefontwidth(2);
            imagestring($this->image, 2, $x1 + $barWidth/2 - $labelWidth/2, $this->height - $margin + 10, $keys[$i], $this->colors['black']);
        }
        
        return $this->getBase64Image();
    }

    public function generatePieChart($data, $title) {
        // Fill background
        imagefill($this->image, 0, 0, $this->colors['white']);
        
        // Draw title
        $titleFont = 5;
        $titleWidth = strlen($title) * imagefontwidth($titleFont);
        imagestring($this->image, $titleFont, ($this->width - $titleWidth) / 2, 20, $title, $this->colors['black']);
        
        if (empty($data)) {
            $this->drawErrorMessage("No data available");
            return $this->getBase64Image();
        }
        
        // Calculate pie center and radius
        $centerX = $this->width / 2;
        $centerY = $this->height / 2 + 20;
        $radius = min($this->width, $this->height) / 3;
        
        // Calculate total value
        $total = array_sum(array_values($data));
        if ($total == 0) {
            $this->drawErrorMessage("No valid data for pie chart");
            return $this->getBase64Image();
        }
        
        // Draw pie slices
        $startAngle = 0;
        $colors = [$this->colors['blue'], $this->colors['green'], $this->colors['orange'], $this->colors['purple'], $this->colors['red']];
        $legendY = 100;
        $legendX = $this->width - 200;
        
        $i = 0;
        foreach ($data as $label => $value) {
            $sliceAngle = ($value / $total) * 360;
            $endAngle = $startAngle + $sliceAngle;
            
            $color = $colors[$i % count($colors)];
            imagefilledarc($this->image, $centerX, $centerY, $radius * 2, $radius * 2, 
                          $startAngle, $endAngle, $color, IMG_ARC_PIE);
            imagearc($this->image, $centerX, $centerY, $radius * 2, $radius * 2, 
                    $startAngle, $endAngle, $this->colors['black']);
            
            // Draw legend
            imagefilledrectangle($this->image, $legendX, $legendY + $i * 25, $legendX + 15, $legendY + $i * 25 + 15, $color);
            imagerectangle($this->image, $legendX, $legendY + $i * 25, $legendX + 15, $legendY + $i * 25 + 15, $this->colors['black']);
            $percentage = round(($value / $total) * 100, 1);
            imagestring($this->image, 3, $legendX + 20, $legendY + $i * 25, "$label ($percentage%)", $this->colors['black']);
            
            $startAngle = $endAngle;
            $i++;
        }
        
        return $this->getBase64Image();
    }

    private function drawGrid($margin, $chartWidth, $chartHeight, $minValue, $maxValue) {
        // Draw horizontal grid lines
        $gridLines = 5;
        for ($i = 0; $i <= $gridLines; $i++) {
            $y = $margin + ($i * $chartHeight / $gridLines);
            imageline($this->image, $margin, $y, $margin + $chartWidth, $y, $this->colors['light_gray']);
            
            // Draw y-axis labels
            $value = $maxValue - ($i * ($maxValue - $minValue) / $gridLines);
            imagestring($this->image, 2, $margin - 40, $y - 8, round($value, 1), $this->colors['black']);
        }
        
        // Draw vertical grid lines
        $verticalLines = 4;
        for ($i = 0; $i <= $verticalLines; $i++) {
            $x = $margin + ($i * $chartWidth / $verticalLines);
            imageline($this->image, $x, $margin, $x, $margin + $chartHeight, $this->colors['light_gray']);
        }
    }

    private function drawErrorMessage($message) {
        $font = 4;
        $textWidth = strlen($message) * imagefontwidth($font);
        $textHeight = imagefontheight($font);
        $x = ($this->width - $textWidth) / 2;
        $y = ($this->height - $textHeight) / 2;
        imagestring($this->image, $font, $x, $y, $message, $this->colors['red']);
    }

    private function getBase64Image() {
        ob_start();
        imagepng($this->image);
        $imageData = ob_get_contents();
        ob_end_clean();
        imagedestroy($this->image);
        
        return base64_encode($imageData);
    }
}
?>