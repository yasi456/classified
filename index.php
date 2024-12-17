<?php
require_once 'config.php';
require_once 'functions.php';

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$categories = get_categories();
$locations = get_locations();

$filter_date = isset($_GET['filter_date']) ? $_GET['filter_date'] : 'new';
$filter_location = isset($_GET['filter_location']) ? $_GET['filter_location'] : '';
$filter_category = isset($_GET['filter_category']) ? intval($_GET['filter_category']) : 0;
$filter_subcategory = isset($_GET['filter_subcategory']) ? intval($_GET['filter_subcategory']) : 0;
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

// Pagination
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$ads_per_page = 20;
$offset = ($page - 1) * $ads_per_page;

$total_ads = count_filtered_ads($filter_date, $filter_location, $filter_category, $filter_subcategory, $search_query);
$total_pages = ceil($total_ads / $ads_per_page);

$ads = get_filtered_ads($filter_date, $filter_location, $filter_category, $filter_subcategory, $search_query, $ads_per_page, $offset);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classified Ads</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <header>
        <h1>Classified Ads</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <?php if (is_logged_in()): ?>
                    <li><a href="post_ad.php">Post Ad</a></li>
                    <li><a href="profile.php">Profile</a></li>
                    <li><a href="logout.php">Logout</a></li>
                    <li class="welcome-message">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main>
        <section class="filters">
            <h2>Filters</h2>
            <form action="" method="GET">
                <div class="filter-group">
                    <label for="filter_date">Date:</label>
                    <select name="filter_date" id="filter_date">
                        <option value="new" <?php echo $filter_date == 'new' ? 'selected' : ''; ?>>Newest First</option>
                        <option value="old" <?php echo $filter_date == 'old' ? 'selected' : ''; ?>>Oldest First</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="filter_location">Location:</label>
                    <select name="filter_location" id="filter_location">
                        <option value="">All Locations</option>
                        <?php foreach ($locations as $location): ?>
                            <option value="<?php echo $location; ?>" <?php echo $filter_location == $location ? 'selected' : ''; ?>><?php echo htmlspecialchars($location); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="filter_category">Category:</label>
                    <select name="filter_category" id="filter_category">
                        <option value="0">All Categories</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category['id']; ?>" <?php echo $filter_category == $category['id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($category['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="filter_subcategory">Subcategory:</label>
                    <select name="filter_subcategory" id="filter_subcategory">
                        <option value="0">All Subcategories</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="search">Search:</label>
                    <input type="text" name="search" id="search" value="<?php echo htmlspecialchars($search_query); ?>" placeholder="Search ads...">
                </div>
                <button type="submit" class="btn-primary">Apply Filters</button>
                <a href="index.php" class="btn-secondary">Reset Filters</a>
            </form>
        </section>

        <section class="ads">
            <h2>Ads</h2>
            <?php if (empty($ads)): ?>
                <p>No ads available at the moment.</p>
            <?php else: ?>
                <div class="ad-grid">
                    <?php foreach ($ads as $ad): ?>
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
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
<!-- Pagination -->
<div class="pagination">
    <?php
    // Remove 'page' from the existing query parameters
    $query_params = $_GET;
    unset($query_params['page']);
    $query_string = http_build_query($query_params);
    ?>
    
    <?php if ($page > 1): ?>
        <a href="?page=<?php echo $page - 1; ?><?php echo $query_string ? '&' . $query_string : ''; ?>" class="btn-secondary">Previous</a>
    <?php endif; ?>

    <?php
    $start_page = max(1, $page - 2);
    $end_page = min($total_pages, $page + 2);
    
    for ($i = $start_page; $i <= $end_page; $i++):
    ?>
        <?php if ($i == $page): ?>
            <span class="current-page"><?php echo $i; ?></span>
        <?php else: ?>
            <a href="?page=<?php echo $i; ?><?php echo $query_string ? '&' . $query_string : ''; ?>"><?php echo $i; ?></a>
        <?php endif; ?>
    <?php endfor; ?>

    <?php if ($page < $total_pages): ?>
        <a href="?page=<?php echo $page + 1; ?><?php echo $query_string ? '&' . $query_string : ''; ?>" class="btn-secondary">Next</a>
    <?php endif; ?>
</div>
            <?php endif; ?>
        </section>
    </main>

    <footer>
        <p>&copy; 2023 Classified Ads. All rights reserved.</p>
    </footer>

    <script>
    $(document).ready(function() {
        $('#filter_category').change(function() {
            var category_id = $(this).val();
            $.ajax({
                url: 'get_subcategories.php',
                method: 'POST',
                data: {category_id: category_id},
                dataType: 'json',
                success: function(data) {
                    var options = '<option value="0">All Subcategories</option>';
                    for (var i = 0; i < data.length; i++) {
                        options += '<option value="' + data[i].id + '">' + data[i].name + '</option>';
                    }
                    $('#filter_subcategory').html(options);
                }
            });
        });
    });
    </script>
</body>
</html>