<?php
require_once 'config.php';
require_once 'functions.php';

// Check if the ad ID is provided and is valid
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$ad_id = intval($_GET['id']);
$ad = get_ad_details($ad_id);

if (!$ad) {
    header("Location: index.php");
    exit();
}

$is_favorite = is_logged_in() ? is_favorite($_SESSION['user_id'], $ad_id) : false;

// Handle adding/removing favorite
if (is_logged_in() && isset($_POST['toggle_favorite'])) {
    toggle_favorite($_SESSION['user_id'], $ad_id);
    header("Location: view_ad.php?id=" . $ad_id);
    exit();
}

$images = get_ad_images($ad_id);
$related_ads = get_related_ads($ad['category_id'], $ad_id, 4);

// Define categories where the condition should not be displayed
$no_condition_categories = ['Pets', 'Jobs', 'Services'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($ad['title']); ?> - Classified Ads</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


<style type="text/css">.card {
    width: 100%;
    max-width: 300px; /* Fixed width */
    height: 400px; /* Fixed height */
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.card-img-top {
    max-height: 200px; /* Adjust as needed */
    object-fit: cover; /* Ensures image fits well */
}

.card-body {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.card-title, .card-text {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}</style></head>
<body>
    <header>
        <!-- Add your header content here -->
    </header>

    <main class="container my-5">
        <a href="index.php" class="btn btn-secondary mb-4">&larr; Back to Ads</a>
        <div class="ad-actions mb-4">
            <button class="btn btn-primary" onclick="shareAd()">Share</button>
            <?php if (is_logged_in()): ?>
                <form action="" method="POST" style="display: inline;">
                    <input type="hidden" name="toggle_favorite" value="1">
                    <button type="submit" class="btn btn-<?php echo $is_favorite ? 'danger' : 'success'; ?>">
                        <?php echo $is_favorite ? 'Remove from Favorites' : 'Add to Favorites'; ?>
                    </button>
                </form>
            <?php endif; ?>
        </div>
        <section class="ad-details mb-5 rounded bg-light">
            <h2><?php echo htmlspecialchars($ad['title']); ?></h2>
            <p class="ad-price">Rs.<?php echo number_format($ad['price'], 2); ?></p>
            <p class="ad-location"><?php echo htmlspecialchars($ad['location']); ?></p>
            <p class="ad-category">Category: <?php echo htmlspecialchars($ad['category_name']); ?> &gt; <?php echo htmlspecialchars($ad['subcategory_name']); ?></p>
            <p class="ad-date">Posted: <?php echo time_ago($ad['created_at']); ?></p>
            <p class="ad-seller">Seller: <?php echo htmlspecialchars($ad['user_name']); ?></p>

            <!-- Conditionally display the condition based on the category -->
            <?php if (!in_array($ad['category_name'], $no_condition_categories)): ?>
                <p class="ad-condition">Condition: <?php echo htmlspecialchars($ad['condition']); ?></p>
            <?php endif; ?>

            <div class="ad-images mb-4">
                <?php foreach ($images as $image): ?>
                    <img src="<?php echo htmlspecialchars($image['file_path']); ?>" alt="Ad Image" class="img-fluid mb-2">
                <?php endforeach; ?>
            </div>

            <h3>Description</h3>
            <p><?php echo nl2br(htmlspecialchars($ad['description'])); ?></p>
            
            <h3>Contact Information</h3>
            <p>Phone 1: <?php echo htmlspecialchars($ad['phone1']); ?></p>
            <?php if (!empty($ad['phone2'])): ?>
                <p>Phone 2: <?php echo htmlspecialchars($ad['phone2']); ?></p>
            <?php endif; ?>
        </section>

        <section class="related-ads">
            <h3>Related Ads</h3>
            <div class="row">
                <?php foreach ($related_ads as $related_ad): ?>
                    <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
                        <div class="card">
                            <img src="<?php echo htmlspecialchars($related_ad['image']); ?>" class="card-img-top" alt="Related Ad Image">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($related_ad['title']); ?></h5>
                                <p class="card-text">Rs. <?php echo number_format($related_ad['price'], 2); ?></p>
                                <p class="card-text"><?php echo htmlspecialchars($related_ad['category_name']); ?> &gt; <?php echo htmlspecialchars($related_ad['subcategory_name']); ?></p>
                                <a href="view_ad.php?id=<?php echo $related_ad['id']; ?>" class="btn btn-dark">View Ad</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <footer>
        <!-- Add your footer content here -->
    </footer>

    <script>
    function shareAd() {
        if (navigator.share) {
            navigator.share({
                title: '<?php echo addslashes($ad['title']); ?>',
                text: 'Check out this ad: <?php echo addslashes($ad['title']); ?>',
                url: window.location.href
            }).then(() => {
                console.log('Thanks for sharing!');
            }).catch(console.error);
        } else {
            // Fallback for browsers that don't support the Web Share API
            prompt('Copy this link to share:', window.location.href);
        }
    }
    </script>
</body>
</html>
