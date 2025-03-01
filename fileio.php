<?php

/**
 * Fitness Activity Tracking System - File I/O Operations
 * API module for reading and writing data to and from the text file 
 * 
 * This file handles all file input/output operations for the fitness tracking system.
 * It includes functions for:
 * - Loading default user data
 * - Inserting new user measurements
 * - Adding new activity records
 * - Loading existing activity records
 * 
 * File format: ExerciseData.txt
 * - Line 1: Headers for user data
 * - Line 2: User measurements (height, weight, BMI, units)
 * - Line 3: Headers for activity records
 * - Line 4+: Activity records
 */

// Function to load default user data from ExerciseData.txt
function LoadDefaultData() {
    $file = __DIR__ . '/ExerciseData.txt';
    if (!file_exists($file)) {
        return [];
    }
    $lines = file($file, FILE_IGNORE_NEW_LINES);
    if (count($lines) < 2) {
        return [];
    }
    return explode(", ", $lines[1]);
}

// Function to insert new user data into ExerciseData.txt
function InsertNewUserData($height, $weight, $bmi, $units) {
    $file = __DIR__ . '/ExerciseData.txt';
    if (!file_exists($file)) {
        return false;
    }
    $lines = file($file, FILE_IGNORE_NEW_LINES);
    if (count($lines) < 2) {
        return false;
    }
    $lines[1] = "$height, $weight, $bmi, $units";
    if (file_put_contents($file, implode("\n", $lines)) !== false) {
        return true;
    } else {
        return false;
    }
}

// Function to add a new activity record to ExerciseData.txt
function AddNewActivityRecord($activity, $duration, $caloriesBurned, $weightLost, $units) {
    $file = __DIR__ . '/ExerciseData.txt';
    $record = "$activity, $duration, $caloriesBurned, $weightLost, $units\n";
    
    if (!file_exists($file)) {
        // Create new file with headers and extra line break
        $headers = "ACTIVITY, DURATION, CALORIES_BURNED, WEIGHT_LOST, UNITS\n\n";
        file_put_contents($file, $headers);
    } else {
        // Check if file exists but doesn't have the extra line break
        $contents = file_get_contents($file);
        if (strpos($contents, "\n\n") === false) {
            // Add extra line break after headers
            file_put_contents($file, "\n", FILE_APPEND);
        }
    }
    
    if (is_writable($file)) {
        if (file_put_contents($file, $record, FILE_APPEND) !== false) {
            return true;
        } else {
            error_log("Failed to write to $file");
            return false;
        }
    } else {
        error_log("$file is not writable - check permissions");
        return false;
    }
}


// Function to load activity records from ExerciseData.txt
function LoadActivityRecords() {
    $file = __DIR__ . '/ExerciseData.txt';
    if (!file_exists($file)) {
        return [];
    }
    $lines = file($file, FILE_IGNORE_NEW_LINES);
    if (count($lines) < 4) {
        return [];
    }
    // Line 3 is activity headers, so activity records start from line 4
    $activityRecords = array_slice($lines, 3);
    // Remove any empty lines
    $activityRecords = array_filter($activityRecords, function($line) {
        return trim($line) != '';
    });
    return $activityRecords;
}

?>