<?php

/**
 * An API function that will convert weight from kilograms to pounds
 * @param float $weightInKilos Weight in kilograms
 * @return float Weight in pounds rounded to 2 decimal places
 * RETURNS Weight in pounds rounded to 2 decimal places
 */
function KilosToPounds($weightInKilos) {
    return round($weightInKilos * 2.2, 2);
}

/**
 * An API function that will calculate Body Mass Index (BMI) using weight and height
 * @param float $weightInKilos Weight in kilograms
 * @param float $height Height in meters
 * @return float BMI value rounded to 2 decimal places
 * RETURNS BMI value rounded to 2 decimal places
 */
function BMICalculator($weightInKilos, $height) {
    return round($weightInKilos / ($height * $height), 2);
}

/**
 * An API function that will calculate calories burned during 60 minutes of activity
 * @param float $METvalue Metabolic Equivalent of Task value for the activity
 * @param float $weightInKilos Weight of person in kilograms
 * @return float Total calories burned in 60 minutes
 * RETURNS the total calories burned in 60 minutes rounded to 2 decimal places
 */

function CaloriesBurnedIn60Minutes($METvalue, $weightInKilos) {
    return $METvalue * $weightInKilos;
}

/**
 * An API function that will calculate calories burned during 30 minutes of activity
 * @param float $METvalue Metabolic Equivalent of Task value for the activity
 * @param float $weightInKilos Weight of person in kilograms
 * @return float Total calories burned in 30 minutes
 * RETURNS the total calories burned in 30 minutes rounded to 2 decimal places
 */
function CaloriesBurnedIn30Minutes($METvalue, $weightInKilos) {
    return CaloriesBurnedIn60Minutes($METvalue, $weightInKilos) / 2;
}

/**
 * An API function that will calculate potential weight loss in kilograms for 30 minutes of activity
 * @param float $METvalue Metabolic Equivalent of Task value for the activity
 * @param float $weightInKilos Weight of person in kilograms
 * @return float Weight loss in kilograms rounded to 2 decimal places
 * RETURNS the weight loss in kilograms rounded to 2 decimal places
*/
function WeightLostInKilosIn30Minutes($METvalue, $weightInKilos) {
    $caloriesBurned = CaloriesBurnedIn30Minutes($METvalue, $weightInKilos);
    return round($caloriesBurned / 7700, 2); // 7700 calories per kg
}

/**API Extension Code for Learners:
    INSERT API CODE 
    Add functions to enhance functionality as per project scenario
*/

/**Calories Burned (15 minutes, custom time)
============================================
Ext_API_01: An API function that will calculate calories burned during 15 minutes of activity

Ext_API_02: An API function that will calculate calories burned for custom durations

function CaloriesBurnedInCustomTime($METvalue, $weightInKilos, $minutes) {
    return $METvalue * $weightInKilos * ($minutes / 60);
}
**/

/**weight lost in kilos (15 minutes, 60 minutes, custom time)
============================================
Ext_API_03: An API function that will calculate potential weight loss in kilograms for 15 minutes of activity

Ext_API_04: An API function that will calculate potential weight loss in kilograms for 60 minutes of activity

Ext_API_05: An API function that will calculate potential weight loss in kilograms for custom minutes of activity


/**weight lost in pounds (15 minutes, 30 minutes, 60 minutes, custom time)
============================================
Ext_API_06: An API function that will calculate potential weight loss in pounds for 15 minutes of activity

Ext_API_07: An API function that will calculate potential weight loss in pounds for 30 minutes of activity

Ext_API_08: An API function that will calculate potential weight loss in pounds for 60 minutes of activity

Ext_API_09: An API function that will calculate potential weight loss in pounds for custom minutes of activity


/**Calories loss for a weight in pounds (15 minutes, 30 minutes, 60 minutes, custom time)
============================================
Ext_API_10: An API function that will calculate calories lost in pounds during 15 minutes of activity

Ext_API_11: An API function that will calculate calories lost in pounds during 30 minutes of activity

Ext_API_12: An API function that will calculate calories lost in pounds during 60 minutes of activity

Ext_API_13: An API function that will calculate calories lost in pounds during custom minutes of activity


/**BMI calculator that uses a starting weight in pounds
============================================
Ext_API_14: An API function that will calculate Body Mass Index (BMI) using weight and height in pounds


/** average calories burned over all activities 
============================================
Ext_API_15: An API function that will calculate average calories burned over all activities


/**  largest amount of calories burned 
============================================
Ext_API_16: An API function that will calculate largest amount of calories burned


/**  biggest weight loss interval between activities.
============================================
Ext_API_17: An API function that will calculate biggest weight loss interval between activities


/**Online API Extension Services:
    INSERT API CODE 
    Add functions to enhance functionality as per project scenario


/**  kilos to pounds 
============================================
Ext_API_18: An API function that will calculate kilos to pounds using online service API 
// Online API integration (hypothetical example)
function kilosToPoundsWebService($weightInKilos) {
    // Replace with real API call, e.g., using curl
    // $response = file_get_contents("https://api.example.com/convert?from=kg&to=lbs&value=$weightInKilos");
    // $data = json_decode($response, true);
    // return $data['value'];
    return kilosToPounds($weightInKilos); // Simulated
}

/**  pounds to kilos 
============================================
Ext_API_19: An API function that will calculate pounds to kilos using online service API 

/**  BMI calculations.  
============================================
Ext_API_20: An API function that will calculate BMI calculations using online service API 
function bmiCalculatorWebService($weightInKilos, $height) {
    // Simulated API call
    return bmiCalculator($weightInKilos, $height);
}
*/

?>