<?php
// data_api.php

include 'common.php';

function GetMetValueForActivity($search) {
    global $ListofActivities;
    foreach ($_SESSION['listOfActivities'] as $activity) {
        if (strtolower($activity['Activity']) == strtolower($search)) {
            return $activity['METvalue'];
        }
    }
    return 0;
}


if (!defined('DATA_API_PHP_INCLUDED')) {
    define('DATA_API_PHP_INCLUDED', true);

    // Add a new activity to the session list
    function AddNewActivity($activityName, $metValue) {
        $_SESSION['listOfActivities'][] = array(
            'Activity' => $activityName,
            'METvalue' => $metValue
        );
    }

    // Calculate average calories burned from activity data
    function CalculateAverageCalories($activityData) {
        if (empty($activityData)) {
            return 0;
        }
        $totalCalories = 0;
        $count = 0;
        foreach ($activityData as $line) {
            $parts = explode(", ", trim($line));
            if (count($parts) >= 5) { // Ensure valid record
                $totalCalories += floatval($parts[2]); // caloriesBurned is 3rd field
                $count++;
            }
        }
        return $count > 0 ? round($totalCalories / $count, 2) : 0;
    }

    // Calculate the largest calories burned in a single activity
    function CalculateLargestCalories($activityData) {
        if (empty($activityData)) {
            return 0;
        }
        $maxCalories = 0;
        foreach ($activityData as $line) {
            $parts = explode(", ", trim($line));
            if (count($parts) >= 5) {
                $calories = floatval($parts[2]);
                $maxCalories = max($maxCalories, $calories);
            }
        }
        return round($maxCalories, 2);
    }

    // Calculate the biggest weight loss interval (largest single weight loss)
    function CalculateBiggestWeightLossInterval($activityData) {
        if (empty($activityData)) {
            return 0;
        }
        $biggestWeightLoss = 0;
        foreach ($activityData as $line) {
            $parts = explode(", ", trim($line));
            if (count($parts) == 5) {
                $weightLost = floatval($parts[3]);
                $recordUnits = trim($parts[4]);
                // Convert weight loss to KG for consistent comparison
                $weightLostKg = ($recordUnits == "KG") ? $weightLost : PoundsToKilos($weightLost);
                if ($weightLostKg > $biggestWeightLoss) {
                    $biggestWeightLoss = $weightLostKg;
                }
            }
        }
        return $biggestWeightLoss; // Return value in KG
    }
}

?>