<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config.php';
require_once 'functions.php';

// Test photo ID (replace with an actual photo ID from your database)
$test_photo_id = 1;

echo "Attempting to delete photo with ID: $test_photo_id<br>";

$result = delete_photo($test_photo_id);

if ($result) {
    echo "Photo deleted successfully.";
} else {
    echo "Failed to delete photo.";
}

// Display any MySQL errors
if ($conn->error) {
    echo "<br>MySQL Error: " . $conn->error;
}
