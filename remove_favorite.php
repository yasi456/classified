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

    if (remove_from_favorites($user_id, $ad_id)) {
        header("Location: favorites.php?removed=1");
        exit();
    } else {
        header("Location: favorites.php?error=1");
        exit();
    }
} else {
    header("Location: favorites.php");
    exit();
}
