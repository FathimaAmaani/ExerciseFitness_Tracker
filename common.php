<?php
// common.php
define('POUND_TO_KILO_RATIO', 2.2);
define('CALORIES_IN_A_KILO', 7700);

/** Existing Functions (Updated with Rounding) **/
function KilosToPounds($weightInKilos) {
    return round($weightInKilos * POUND_TO_KILO_RATIO, 2);
}

function PoundsToKilos($weightInPounds) {
    return round($weightInPounds / POUND_TO_KILO_RATIO, 2);
}

function BMICalculator($weightInKilos, $height) {
    return round($weightInKilos / ($height * $height), 2);
}

function CaloriesBurnedIn60Minutes($METvalue, $weightInKilos) {
    return round($METvalue * $weightInKilos, 2);
}

function CaloriesBurnedIn30Minutes($METvalue, $weightInKilos) {
    return round(CaloriesBurnedIn60Minutes($METvalue, $weightInKilos) / 2, 2);
}

function WeightLostInKilosIn30Minutes($METvalue, $weightInKilos) {
    $caloriesBurned = CaloriesBurnedIn30Minutes($METvalue, $weightInKilos);
    return round($caloriesBurned / CALORIES_IN_A_KILO, 4); // Higher precision for small values
}

/** Extended Functions **/
// Calories burned in 15 minutes
function CaloriesBurnedIn15Minutes($METvalue, $weightInKilos) {
    return round(CaloriesBurnedIn30Minutes($METvalue, $weightInKilos) / 2, 2);
}

// Calories burned in custom time
function CaloriesBurnedInCustomTime($METvalue, $weightInKilos, $minutes) {
    $hours = $minutes / 60;
    return round($METvalue * $weightInKilos * $hours, 2);
}

// Weight lost in kilos for 15 minutes
function WeightLostInKilosIn15Minutes($METvalue, $weightInKilos) {
    $calories = CaloriesBurnedIn15Minutes($METvalue, $weightInKilos);
    return round($calories / CALORIES_IN_A_KILO, 4);
}

// Weight lost in kilos for 60 minutes
function WeightLostInKilosIn60Minutes($METvalue, $weightInKilos) {
    $calories = CaloriesBurnedIn60Minutes($METvalue, $weightInKilos);
    return round($calories / CALORIES_IN_A_KILO, 4);
}

// Weight lost in kilos for custom time
function WeightLostInKilosInCustomTime($METvalue, $weightInKilos, $minutes) {
    $calories = CaloriesBurnedInCustomTime($METvalue, $weightInKilos, $minutes);
    return round($calories / CALORIES_IN_A_KILO, 4);
}

// Weight lost in pounds
function WeightLostInPoundsIn15Minutes($METvalue, $weightInPounds) {
    $weightInKilos = PoundsToKilos($weightInPounds);
    return KilosToPounds(WeightLostInKilosIn15Minutes($METvalue, $weightInKilos));
}

function WeightLostInPoundsIn30Minutes($METvalue, $weightInPounds) {
    $weightInKilos = PoundsToKilos($weightInPounds);
    return KilosToPounds(WeightLostInKilosIn30Minutes($METvalue, $weightInKilos));
}

function WeightLostInPoundsIn60Minutes($METvalue, $weightInPounds) {
    $weightInKilos = PoundsToKilos($weightInPounds);
    return KilosToPounds(WeightLostInKilosIn60Minutes($METvalue, $weightInKilos));
}

function WeightLostInPoundsInCustomTime($METvalue, $weightInPounds, $minutes) {
    $weightInKilos = PoundsToKilos($weightInPounds);
    return KilosToPounds(WeightLostInKilosInCustomTime($METvalue, $weightInKilos, $minutes));
}

// Calories burned for weight in pounds
function CaloriesBurnedIn15MinutesLb($METvalue, $weightInPounds) {
    $weightInKilos = PoundsToKilos($weightInPounds);
    return CaloriesBurnedIn15Minutes($METvalue, $weightInKilos);
}

function CaloriesBurnedIn30MinutesLb($METvalue, $weightInPounds) {
    $weightInKilos = PoundsToKilos($weightInPounds);
    return CaloriesBurnedIn30Minutes($METvalue, $weightInKilos);
}

function CaloriesBurnedIn60MinutesLb($METvalue, $weightInPounds) {
    $weightInKilos = PoundsToKilos($weightInPounds);
    return CaloriesBurnedIn60Minutes($METvalue, $weightInKilos);
}

function CaloriesBurnedInCustomTimeLb($METvalue, $weightInPounds, $minutes) {
    $weightInKilos = PoundsToKilos($weightInPounds);
    return CaloriesBurnedInCustomTime($METvalue, $weightInKilos, $minutes);
}

// BMI calculator for pounds
function BMICalculatorWeightInPounds($weightInPounds, $height) {
    $weightInKilos = PoundsToKilos($weightInPounds);
    return BMICalculator($weightInKilos, $height);
}

// Online API Integration
function KilosToPoundsWebService($weightInKilos) {
    $apiKey = 'YOUR_API_KEY'; // Replace with your RapidAPI key
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
    $apiKey = 'YOUR_API_KEY'; // Replace with your RapidAPI key
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

function BMICalculatorWebService($weightInKilos, $height) {
    // Simulated for now; replace with a real BMI API if available
    return BMICalculator($weightInKilos, $height);
}
?>