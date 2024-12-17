<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

if (!is_logged_in()) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ad_id'])) {
    $user_id = $_SESSION['user_id'];
    $ad_id = intval($_POST['ad_id']);

    if (add_to_favorites($user_id, $ad_id)) {
        header("Location: view_ad.php?id=" . $ad_id . "&favorited=1");
        exit();
    } else {
        header("Location: view_ad.php?id=" . $ad_id . "&error=1");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
