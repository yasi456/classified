<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

if (!is_logged_in()) {
    header("Location: login.php");
    exit();
}

$ad_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$ad = get_ad($ad_id);

if (!$ad || $ad['user_id'] != $_SESSION['user_id']) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (delete_ad($ad_id)) {
        header("Location: my_ads.php?deleted=1");
        exit();
    } else {
        $error_message = "Failed to delete ad. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Ad - Classified Ads</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <!-- Add your header content here -->
    </header>

    <main>
        <h1>Delete Ad</h1>
        <?php if (isset($error_message)): ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <p>Are you sure you want to delete the ad "<?php echo htmlspecialchars($ad['title']); ?>"?</p>
        <form action="" method="POST">
            <input type="submit" value="Yes, Delete Ad" class="delete-btn">
            <a href="my_ads.php" class="cancel-btn">Cancel</a>
        </form>
    </main>

    <footer>
        <!-- Add your footer content here -->
    </footer>
</body>
</html>
