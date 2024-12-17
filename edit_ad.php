<?php
require_once 'config.php';
require_once 'functions.php';

// Check if the user is logged in
if (!is_logged_in()) {
    header("Location: login.php");
    exit();
}

// Get ad ID from the query string
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$ad_id = intval($_GET['id']);
$ad = get_ad_details($ad_id);

if (!$ad || $ad['user_id'] != $_SESSION['user_id']) {
    header("Location: index.php");  
    exit();
}

$images = get_ad_images($ad_id);
$categories = get_categories();
$locations = get_locations();

// Get subcategories based on the current category
$initial_subcategories = !empty($ad['category_id']) ? get_subcategories($ad['category_id']) : [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = sanitize_input($_POST['title']);
    $description = sanitize_input($_POST['description']);
    $category_id = intval($_POST['category']);
    $subcategory_id = intval($_POST['subcategory']);
    $price = floatval($_POST['price']);
    $location = sanitize_input($_POST['location']);
    $condition = isset($_POST['condition']) ? sanitize_input($_POST['condition']) : null;
    $phone1 = sanitize_input($_POST['phone1']);
    $phone2 = sanitize_input($_POST['phone2']);

    // Server-side validation for number of photos
    $limit_photo_categories = ['JOBS', 'SERVICES'];
    $max_photos = in_array(get_category_name($category_id), $limit_photo_categories) ? 2 : 5;

    $current_photo_count = count($images);
    $new_photo_count = count($_FILES['photos']['name']);
    $delete_photo_count = isset($_POST['delete_images']) ? count($_POST['delete_images']) : 0;
    $total_photos = $current_photo_count + $new_photo_count - $delete_photo_count;

    if ($total_photos > $max_photos) {
        $error = "You can only have up to " . $max_photos . " photos for this category.";
    } else {
        // Update the ad
        update_ad($ad_id, $title, $description, $category_id, $subcategory_id, $location, $condition, $price, $phone1, $phone2);

        // Handle photo uploads
        if (!empty($_FILES['photos']['name'][0])) {
            $upload_dir = 'uploads/';
            foreach ($_FILES['photos']['tmp_name'] as $key => $tmp_name) {
                $file_name = $_FILES['photos']['name'][$key];
                $file_path = $upload_dir . uniqid() . '_' . $file_name;
                
                // Check for upload errors
                if ($_FILES['photos']['error'][$key] !== UPLOAD_ERR_OK) {
                    $error = "Error uploading file: " . $file_name;
                    break;
                }
                
                // Check file type
                $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
                if (!in_array($_FILES['photos']['type'][$key], $allowed_types)) {
                    $error = "Invalid file type for: " . $file_name . ". Only JPEG, PNG, and GIF are allowed.";
                    break;
                }
                
                if (move_uploaded_file($tmp_name, $file_path)) {
                    add_photo($ad_id, $file_path);
                } else {
                    $error = "Failed to move uploaded file: " . $file_name;
                    break;
                }
            }
        }

        // Handle photo deletions
        if (isset($_POST['delete_images'])) {
            foreach ($_POST['delete_images'] as $image_id) {
                delete_photo($image_id);
            }
        }

        if (!isset($error)) {
            header("Location: view_ad.php?id=" . $ad_id);
            exit();
        }
    }
}

// Refresh images after potential deletions
$images = get_ad_images($ad_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Ad - Classified Ads</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <header>
        <a href="index.php" class="back-to-home">Back to Home</a>
    </header>

    <main>
        <h1>Edit Ad</h1>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <!-- ... (rest of the form fields remain the same) ... -->

<div class="existing-photos">
    <h3>Existing Photos</h3>
    <?php 
    if (empty($images)) {
        echo "<p>No existing photos found.</p>";
    } else {
        foreach ($images as $image): 
    ?>
        <div class="photo-item">
            <img src="<?php echo htmlspecialchars($image['file_path']); ?>" alt="Ad Image" width="150">
            <label>
                <input type="checkbox" name="delete_images[]" value="<?php echo $image['id']; ?>">
                Delete
            </label>
        </div>
    <?php 
        endforeach;
    }
    ?>
</div>

            <a href="view_ad.php?id=<?php echo $ad_id; ?>" class="btn-cancel">Cancel</a>
            <button type="submit">Update Ad</button>
        </form>
    </main>

    <footer>
        <!-- Add your footer content here -->
    </footer>

    <script>
    $(document).ready(function() {
        function loadSubcategories(category_id) {
            $.ajax({
                url: 'get_subcategories.php',
                method: 'POST',
                data: {category_id: category_id},
                dataType: 'json',
                success: function(data) {
                    var options = '<option value="">Select Subcategory</option>';
                    for (var i = 0; i < data.length; i++) {
                        options += '<option value="' + data[i].id + '">' + data[i].name + '</option>';
                    }
                    $('#subcategory').html(options);
                }
            });
        }

        function updateConditionAndPhotoLimit(categoryName) {
            var hideConditionCategories = ['SERVICES', 'PETS', 'JOBS', 'REAL ESTATE'];
            var limitPhotoCategories = ['JOBS', 'SERVICES'];

            if (hideConditionCategories.includes(categoryName)) {
                $('#condition-group').hide();
            } else {
                $('#condition-group').show();
            }

            if (limitPhotoCategories.includes(categoryName)) {
                $('#photo-limit-message').text('You can upload up to 2 photos for this category.');
            } else {
                $('#photo-limit-message').text('You can upload up to 5 photos.');
            }
        }

        $('#category').change(function() {
            var category_id = $(this).val();
            var categoryName = $('#category option:selected').text();
            loadSubcategories(category_id);
            updateConditionAndPhotoLimit(categoryName);
        });

        // Initial setup
        var initialCategoryId = $('#category').val();
        var initialCategoryName = $('#category option:selected').text();
        loadSubcategories(initialCategoryId);
        updateConditionAndPhotoLimit(initialCategoryName);
    });
    </script>
</body>