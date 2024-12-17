<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config.php';
require_once 'functions.php';

if (!is_logged_in()) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $photo_id = isset($_POST['photo_id']) ? intval($_POST['photo_id']) : 0;
    $ad_id = isset($_POST['ad_id']) ? intval($_POST['ad_id']) : 0;

    // Debug output
    error_log("Attempting to delete photo ID: " . $photo_id . " for ad ID: " . $ad_id);

    // Verify that the photo belongs to an ad owned by the current user
    $ad = get_ad($ad_id);
    if ($ad && $ad['user_id'] == $_SESSION['user_id']) {
        $result = delete_photo($photo_id);
        if ($result) {
            $_SESSION['success_message'] = "Photo deleted successfully.";
            error_log("Photo deleted successfully.");
        } else {
            $_SESSION['error_message'] = "Failed to delete the photo.";
            error_log("Failed to delete photo. MySQL Error: " . mysqli_error($conn));
        }
    } else {
        $_SESSION['error_message'] = "You don't have permission to delete this photo.";
        error_log("Permission denied for user ID: " . $_SESSION['user_id']);
    }

    header("Location: edit_ad.php?id=" . $ad_id);
    exit();
} else {
    header("Location: index.php");
    exit();
}
