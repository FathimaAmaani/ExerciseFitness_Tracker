<?php
// Guard to prevent multiple inclusions
if (!defined('COMMON_PHP_INCLUDED')) {
    define('COMMON_PHP_INCLUDED', true);

    define('POUND_TO_KILO_RATIO', 2.2);
    define('CALORIES_IN_A_KILO', 7700);

    /** Existing Functions (Updated with Rounding) **/

    // Online API Integration
    function KilosToPoundsWebService($weightInKilos) {
        $apiKey = "89a9287fb3msh76620bf81a8ad13p13d89bjsn06508c67ed20"; // Replace with your RapidAPI key
        $url = "https://unit-converter.p.rapidapi.com/convert?from=kg&to=lb&value=" . urlencode($weightInKilos);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "X-RapidAPI-Key: $apiKey",
            "X-RapidAPI-Host: unit-converter.p.rapidapi.com"
        ]);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            curl_close($ch);
            return KilosToPounds($weightInKilos); // Fallback
        }

        curl_close($ch);
        $data = json_decode($response, true);
        return isset($data['result']) ? round(floatval($data['result']), 2) : KilosToPounds($weightInKilos);
    }

    function PoundsToKilosWebService($weightInPounds) {
        $apiKey = "89a9287fb3msh76620bf81a8ad13p13d89bjsn06508c67ed20"; // Replace with your RapidAPI key
        $url = "https://unit-converter.p.rapidapi.com/convert?from=lb&to=kg&value=" . urlencode($weightInPounds);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "X-RapidAPI-Key: $apiKey",
            "X-RapidAPI-Host: unit-converter.p.rapidapi.com"
        ]);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            curl_close($ch);
            return PoundsToKilos($weightInPounds); // Fallback
        }

        curl_close($ch);
        $data = json_decode($response, true);
        return isset($data['result']) ? round(floatval($data['result']), 2) : PoundsToKilos($weightInPounds);
    }

    // Online API for BMI calculation (using Fitness Calculator by API Ninjas as an example)
    function BMICalculatorWebService($weightInKilos, $heightInMeters) {
        $apiKey = "89a9287fb3msh76620bf81a8ad13p13d89bjsn06508c67ed20"; // Replace with your actual RapidAPI key
        $url = "https://fitness-calculator.p.rapidapi.com/bmi?weight=" . urlencode($weightInKilos) . "&height=" . urlencode($heightInMeters * 100); // Height in cm

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "X-RapidAPI-Key: $apiKey",
            "X-RapidAPI-Host: fitness-calculator.p.rapidapi.com"
        ]);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            curl_close($ch);
            return BMICalculator($weightInKilos, $heightInMeters); // Fallback to local calculation
        }

        curl_close($ch);
        $data = json_decode($response, true);
        // Assuming API returns 'bmi' field; adjust based on actual API response
        return isset($data['bmi']) ? round(floatval($data['bmi']), 1) : BMICalculator($weightInKilos, $heightInMeters);
    }

    function BMICalculator($weight, $height) {
        // For weight in KG and height in meters
        if ($height <= 0) return 0;
        return round($weight / ($height * $height), 1);
    }

    function BMICalculatorWeightInPounds($weightInPounds, $heightInMeters) {
        // Convert pounds to kilograms first (1 pound = 0.45359237 kilograms)
        $weightInKg = $weightInPounds * 0.45359237;
        return BMICalculator($weightInKg, $heightInMeters);
    }

    function CaloriesBurnedInCustomTime($weight, $met, $timeInMinutes) {
        // For weight in KG
        return round(($timeInMinutes * ($met * 3.5 * $weight)) / 200, 2);
    }

    function CaloriesBurnedInCustomTimeLb($weightInPounds, $met, $timeInMinutes) {
        // Convert pounds to kilograms first (1 pound = 0.45359237 kilograms)
        $weightInKg = $weightInPounds * 0.45359237;
        return CaloriesBurnedInCustomTime($weightInKg, $met, $timeInMinutes);
    }

    function WeightLostInCustomTime($caloriesBurned) {
        // 1 kg of fat = 7700 calories
        return round($caloriesBurned / 7700, 4);
    }

    function WeightLostInPoundsInCustomTime($met, $weight, $timeInMinutes) {
        $weightInKg = $weight * 0.45359237;
        $weightLostKg = WeightLostInKilosInCustomTime($met, $weightInKg, $timeInMinutes);
        return round($weightLostKg * 2.20462262185, 4);
    }

    function KilosToPounds($kilos) {
        // 1 kg = 2.20462262185 pounds
        return round($kilos * 2.20462262185, 2);
    }

    function WeightLostInKilosInCustomTime($met, $weight, $timeInMinutes) {
        $caloriesBurned = CaloriesBurnedInCustomTime($weight, $met, $timeInMinutes);
        return round($caloriesBurned / CALORIES_IN_A_KILO, 4);
    }

    function PoundsToKilos($pounds) {
        // 1 pound = 0.45359237 kilograms
        return round($pounds * 0.45359237, 2);
    }

    function FeetInchesToMeters($feet, $inches) {
        $totalInches = ($feet * 12) + $inches;
        return round($totalInches * 0.0254, 2);
    }
}
?>