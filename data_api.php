<?php
// data_api.php
include 'fileio.php';
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

function CalculateAverageCalories($activityData) {
    $total = 0;
    $count = 0;
    foreach ($activityData as $line) {
        $parts = explode(", ", trim($line));
        if (count($parts) == 5) {
            $total += floatval($parts[2]);
            $count++;
        }
    }
    return $count ? round($total / $count, 2) : 0;
}

function CalculateLargestCalories($activityData) {
    $max = 0;
    foreach ($activityData as $line) {
        $parts = explode(", ", trim($line));
        if (count($parts) == 5) {
            $max = max($max, floatval($parts[2]));
        }
    }
    return $max;
}

function CalculateBiggestWeightLossInterval($activityData) {
    $maxDiff = 0;
    for ($i = 1; $i < count($activityData); $i++) {
        $prev = explode(", ", trim($activityData[$i - 1]));
        $curr = explode(", ", trim($activityData[$i]));
        if (count($prev) == 5 && count($curr) == 5) {
            $diff = abs(floatval($prev[3]) - floatval($curr[3]));
            $maxDiff = max($maxDiff, $diff);
        }
    }
    return round($maxDiff, 2);
}

function AddNewActivity($activity, $met) {
    $_SESSION['listOfActivities'][] = ["Activity" => $activity, "METvalue" => floatval($met)];
}
?>