<?php
require_once 'config.php';
require_once 'functions.php';

// Check if the user is logged in
if (!is_logged_in()) {
    header("Location: login.php");
    exit();
}

// Get all categories
$categories = get_categories();

// Get locations
$locations = get_locations();

// Get the first category's subcategories
$initial_subcategories = !empty($categories) ? get_subcategories($categories[0]['id']) : [];

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
    $user_id = $_SESSION['user_id'];

    $ad_id = create_ad($user_id, $title, $description, $category_id, $subcategory_id, $location, $condition, $price, $phone1, $phone2);

    if ($ad_id) {
        // Handle photo uploads
        if (!empty($_FILES['photos']['name'][0])) {
            $upload_dir = 'uploads/';
            foreach ($_FILES['photos']['tmp_name'] as $key => $tmp_name) {
                $file_name = $_FILES['photos']['name'][$key];
                $file_path = $upload_dir . uniqid() . '_' . $file_name;
                if (move_uploaded_file($tmp_name, $file_path)) {
                    add_photo($ad_id, $file_path);
                }
            }
        }
        header("Location: view_ad.php?id=" . $ad_id);
        exit();
    } else {
        $error = "Failed to create the ad. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post an Ad - Classified Ads</title>
    <link rel="stylesheet" href="ad.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <header>
        <!-- Add your header content here -->
        <a href="index.php" class="back-to-home">Back to Home</a>
    </header>

    <main>
        <h1>Post an Ad</h1>    <h3 style="font-style: italic; color: pink;">Post your free ad today!</h1>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="" method="POST" enctype="multipart/form-data">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" required></textarea>

            <label for="category">Category:</label>
            <select id="category" name="category" required>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                <?php endforeach; ?>
            </select>

            <label for="subcategory">Subcategory:</label>
            <select id="subcategory" name="subcategory" required>
                <option value="">Select Subcategory</option>
                <?php foreach ($initial_subcategories as $subcategory): ?>
                    <option value="<?php echo $subcategory['id']; ?>"><?php echo htmlspecialchars($subcategory['name']); ?></option>
                <?php endforeach; ?>
            </select>

            <label for="price">Price:</label>
            <input type="number" id="price" name="price" min="0" step="0.01" required>

            <label for="location">Location:</label>
            <select id="location" name="location" required>
                <?php foreach ($locations as $location): ?>
                    <option value="<?php echo htmlspecialchars($location); ?>"><?php echo htmlspecialchars($location); ?></option>
                <?php endforeach; ?>
            </select>

            <div id="condition-group">
                <label for="condition">Condition:</label>
                <select id="condition" name="condition">
                    <option value="new">New</option>
                    <option value="used">Used</option>
                </select>
            </div>

            <label for="phone1">Phone Number 1:</label>
            <input type="tel" id="phone1" name="phone1" required>

            <label for="phone2">Phone Number 2 (optional):</label>
            <input type="tel" id="phone2" name="phone2">

            <label for="photos">Photos:</label>
            <input type="file" id="photos" name="photos[]" accept="image/*" multiple>
            <p id="photo-limit-message">You can upload up to 5 photos.</p>

            <button type="submit">Post Ad</button>
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

        $('#category').change(function() {
            var category_id = $(this).val();
            loadSubcategories(category_id);

            // Hide/show condition field based on category
            var hideConditionCategories = ['SERVICES', 'PETS', 'JOBS', 'REAL ESTATE'];
            var categoryName = $('#category option:selected').text();
            if (hideConditionCategories.includes(categoryName)) {
                $('#condition-group').hide();
            } else {
                $('#condition-group').show();
            }

            // Update photo limit message
            var limitPhotoCategories = ['JOBS', 'SERVICES'];
            if (limitPhotoCategories.includes(categoryName)) {
                $('#photo-limit-message').text('You can upload up to 2 photos for this category.');
            } else {
                $('#photo-limit-message').text('You can upload up to 5 photos.');
            }
        });

        $('#photos').on('change', function() {
            var categoryName = $('#category option:selected').text();
            var limitPhotoCategories = ['JOBS', 'SERVICES'];
            var maxPhotos = limitPhotoCategories.includes(categoryName) ? 2 : 5;
            if (this.files.length > maxPhotos) {
                alert('You can only upload up to ' + maxPhotos + ' photos for this category.');
                this.value = '';
            }
        });

        // Load subcategories for the initially selected category
        var initialCategoryId = $('#category').val();
        loadSubcategories(initialCategoryId);
    });
    </script>
</body>
</html>
