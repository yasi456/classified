<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

if (!is_logged_in()) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$favorites = get_user_favorites($user_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Favorites - Classified Ads</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <!-- Add your header content here -->
    </header>

    <main>
        <h1>My Favorites</h1>
        <?php if (empty($favorites)): ?>
            <p>You haven't added any ads to your favorites yet.</p>
        <?php else: ?>
            <ul class="favorites-list">
                <?php foreach ($favorites as $ad): ?>
                    <li class="favorite-item">
                        <h3><a href="view_ad.php?id=<?php echo $ad['id']; ?>"><?php echo htmlspecialchars($ad['title']); ?></a></h3>
                        <p>Price: <?php echo htmlspecialchars($ad['price']); ?></p>
                        <p>Location: <?php echo htmlspecialchars($ad['location']); ?></p>
                        <p>Category: <?php echo htmlspecialchars($ad['category_name']); ?></p>
                        <form action="remove_favorite.php" method="POST" class="remove-favorite-form">
                            <input type="hidden" name="ad_id" value="<?php echo $ad['id']; ?>">
                            <input type="submit" value="Remove from Favorites" class="remove-favorite-btn">
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </main>

    <footer>
        <!-- Add your footer content here -->
    </footer>
</body>
</html>
