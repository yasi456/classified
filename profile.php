<?php
require_once 'config.php';
require_once 'functions.php';

if (!is_logged_in()) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user = get_user_by_id($user_id);
$favorite_ads = get_favorite_ads($user_id);
$user_ads = get_user_ads($user_id);

// Handle removing favorite
if (isset($_POST['remove_favorite'])) {
    $ad_id = intval($_POST['remove_favorite']);
    remove_favorite($user_id, $ad_id);
    header("Location: profile.php");
    exit();
}

// Handle deleting ad
if (isset($_POST['delete_ad'])) {
    $ad_id = intval($_POST['delete_ad']);
    delete_ad($ad_id);
    header("Location: profile.php");
    exit();
}

// Handle profile update
if (isset($_POST['update_profile'])) {
    $new_name = sanitize_input($_POST['new_name']);
    $new_email = sanitize_input($_POST['new_email']);
    $new_password = $_POST['new_password'];

    update_user_profile($user_id, $new_name, $new_email);
    
    if (!empty($new_password)) {
        change_user_password($user_id, $new_password);
    }

    header("Location: profile.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - Classified Ads</title>
    <link rel="stylesheet" href="style.css"><link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
    <header><style type="text/css">
        
a{text-decoration: none;}


    </style>
        <nav>
            <a href="index.php">Home</a>
            <a href="post_ad.php">Post an Ad</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <main>
        <h1>User Profile</h1>

        <section class="user-info">
            <h2>Personal Information</h2>
            <p>Name: <?php echo htmlspecialchars($user['name']); ?></p>
            <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
<p>
  <a class="btn btn-primary" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
    Update Profile
  </a>

</p>
<div class="collapse" id="collapseExample">
  <div class="card card-body">
               <h3>Update Profile</h3>
            <form action="" method="POST">
                <label for="new_name">New Name:</label>
                <input type="text" id="new_name" name="new_name" value="<?php echo htmlspecialchars($user['name']); ?>" required>

                <label for="new_email">New Email:</label>
                <input type="email" id="new_email" name="new_email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

                <label for="new_password">New Password (leave blank to keep current):</label>
                <input type="password" id="new_password" name="new_password">

                <button class="update" type="submit" name="update_profile">Update Profile</button>
            </form>
        </section>
 </div>
</div>
        <section class="my-ads">
            <h2>My Ads</h2>
            <?php if (empty($user_ads)): ?>
                <p>You haven't posted any ads yet.</p>
            <?php else: ?>
                <div class="ad-grid">
                    <?php foreach ($user_ads as $ad): ?>
                        <div class="ad-item">
                            <div class="ad-image">
                                <img src="<?php echo get_ad_first_image($ad['id']); ?>" alt="<?php echo htmlspecialchars($ad['title']); ?>">
                            </div>
                            <div class="ad-details">
                                <h3><?php echo htmlspecialchars($ad['title']); ?></h3>
                                <p class="ad-price">$<?php echo number_format($ad['price'], 2); ?></p>
                                <p class="ad-location"><?php echo htmlspecialchars($ad['location']); ?></p>
                                <p class="ad-category"><?php echo htmlspecialchars($ad['category_name']); ?></p>
                                <p class="ad-date"><?php echo time_ago($ad['created_at']); ?></p>
                                <a href="view_ad.php?id=<?php echo $ad['id']; ?>" class="btn-view-ad">View Ad</a>
                                <a href="edit_ad.php?id=<?php echo $ad['id']; ?>" class="btn-edit-ad ">Edit Ad</a>
                                <form action="" method="POST" style="display: inline;">
                                    <input type="hidden" name="delete_ad" value="<?php echo $ad['id']; ?>">
                                    <button type="submit" class="btn-delete-ad" onclick="return confirm('Are you sure you want to delete this ad?')">Delete Ad</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

        <section class="favorite-ads">
            <h2>Favorite Ads</h2>
            <?php if (empty($favorite_ads)): ?>
                <p>You haven't added any ads to your favorites yet.</p>
            <?php else: ?>
                <div class="ad-grid">
                    <?php foreach ($favorite_ads as $ad): ?>
                        <div class="ad-item">
                            <div class="ad-image">
                                <img src="<?php echo get_ad_first_image($ad['id']); ?>" alt="<?php echo htmlspecialchars($ad['title']); ?>">
                            </div>
                            <div class="ad-details">
                                <h3><?php echo htmlspecialchars($ad['title']); ?></h3>
                                <p class="ad-price">$<?php echo number_format($ad['price'], 2); ?></p>
                                <p class="ad-location"><?php echo htmlspecialchars($ad['location']); ?></p>
                                <p class="ad-category"><?php echo htmlspecialchars($ad['category_name']); ?></p>
                                <p class="ad-date"><?php echo time_ago($ad['created_at']); ?></p>
                                <a href="view_ad.php?id=<?php echo $ad['id']; ?>" class="btn-view-ad">View Ad</a>
                                <form action="" method="POST" style="display: inline;">
                                    <input type="hidden" name="remove_favorite" value="<?php echo $ad['id']; ?>">
                                    <button type="submit" class="btn-remove-favorite">Remove from Favorites</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <footer>
        <!-- Add your footer content here -->
    </footer>
</body>
</html>
