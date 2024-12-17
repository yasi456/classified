<?php
// Database connection functions
function get_conn() {
    global $conn;
    return $conn;
}

// User-related functions
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function get_user_by_id($user_id) {
    global $conn;
    $query = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function get_user_by_email($email) {
    global $conn;
    $email = mysqli_real_escape_string($conn, $email);
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result);
}

function update_user_profile($user_id, $name, $email) {
    global $conn;
    $user_id = mysqli_real_escape_string($conn, $user_id);
    $name = mysqli_real_escape_string($conn, $name);
    $email = mysqli_real_escape_string($conn, $email);
    $query = "UPDATE users SET name = '$name', email = '$email' WHERE id = '$user_id'";
    return mysqli_query($conn, $query);
}

function change_user_password($user_id, $new_password) {
    global $conn;
    $user_id = mysqli_real_escape_string($conn, $user_id);
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $query = "UPDATE users SET password = '$hashed_password' WHERE id = '$user_id'";
    return mysqli_query($conn, $query);
}

// Ad-related functions
function create_ad($user_id, $title, $description, $category_id, $subcategory_id, $location, $condition, $price, $phone1, $phone2) {
    global $conn;
    $user_id = mysqli_real_escape_string($conn, $user_id);
    $title = mysqli_real_escape_string($conn, $title);
    $description = mysqli_real_escape_string($conn, $description);
    $category_id = mysqli_real_escape_string($conn, $category_id);
    $subcategory_id = mysqli_real_escape_string($conn, $subcategory_id);
    $location = mysqli_real_escape_string($conn, $location);
    $condition = mysqli_real_escape_string($conn, $condition);
    $price = mysqli_real_escape_string($conn, $price);
    $phone1 = mysqli_real_escape_string($conn, $phone1);
    $phone2 = mysqli_real_escape_string($conn, $phone2);

    $query = "INSERT INTO ads (user_id, title, description, category_id, subcategory_id, location, `condition`, price, phone1, phone2) 
              VALUES ('$user_id', '$title', '$description', '$category_id', '$subcategory_id', '$location', '$condition', '$price', '$phone1', '$phone2')";
    
    if (mysqli_query($conn, $query)) {
        return mysqli_insert_id($conn);
    } else {
        return false;
    }
}

function get_ad($ad_id) {
    global $conn;
    $ad_id = mysqli_real_escape_string($conn, $ad_id);
    $query = "SELECT ads.*, categories.name AS category_name, subcategories.name AS subcategory_name, users.name AS user_name
              FROM ads 
              JOIN categories ON ads.category_id = categories.id 
              JOIN subcategories ON ads.subcategory_id = subcategories.id 
              JOIN users ON ads.user_id = users.id
              WHERE ads.id = '$ad_id'";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result);
}

function get_ad_details($ad_id) {
    global $conn;
    $query = "SELECT ads.*, categories.name AS category_name, subcategories.name AS subcategory_name, users.name AS user_name
              FROM ads
              JOIN categories ON ads.category_id = categories.id
              JOIN subcategories ON ads.subcategory_id = subcategories.id
              JOIN users ON ads.user_id = users.id
              WHERE ads.id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $ad_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function get_user_ads($user_id) {
    global $conn;
    $user_id = mysqli_real_escape_string($conn, $user_id);
    $query = "SELECT ads.*, categories.name AS category_name 
              FROM ads 
              JOIN categories ON ads.category_id = categories.id 
              WHERE ads.user_id = '$user_id' 
              ORDER BY ads.created_at DESC";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function update_ad($ad_id, $title, $description, $category_id, $subcategory_id, $location, $condition, $price, $phone1, $phone2) {
    global $conn;
    $ad_id = mysqli_real_escape_string($conn, $ad_id);
    $title = mysqli_real_escape_string($conn, $title);
    $description = mysqli_real_escape_string($conn, $description);
    $category_id = mysqli_real_escape_string($conn, $category_id);
    $subcategory_id = mysqli_real_escape_string($conn, $subcategory_id);
    $location = mysqli_real_escape_string($conn, $location);
    $condition = mysqli_real_escape_string($conn, $condition);
    $price = mysqli_real_escape_string($conn, $price);
    $phone1 = mysqli_real_escape_string($conn, $phone1);
    $phone2 = mysqli_real_escape_string($conn, $phone2);

    $query = "UPDATE ads 
              SET title = '$title', description = '$description', category_id = '$category_id', 
                  subcategory_id = '$subcategory_id', location = '$location', `condition` = '$condition', 
                  price = '$price', phone1 = '$phone1', phone2 = '$phone2', updated_at = CURRENT_TIMESTAMP 
              WHERE id = '$ad_id'";
    
    return mysqli_query($conn, $query);
}

function delete_ad($ad_id) {
    global $conn;
    $ad_id = mysqli_real_escape_string($conn, $ad_id);
    $photo_query = "DELETE FROM photos WHERE ad_id = '$ad_id'";
    mysqli_query($conn, $photo_query);
    $ad_query = "DELETE FROM ads WHERE id = '$ad_id'";
    return mysqli_query($conn, $ad_query);
}

function get_ad_images($ad_id) {
    global $conn;
    $query = "SELECT file_path FROM photos WHERE ad_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $ad_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

function get_ad_photos($ad_id) {
    global $conn;
    $ad_id = mysqli_real_escape_string($conn, $ad_id);
    $query = "SELECT file_path FROM photos WHERE ad_id = '$ad_id'";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function add_photo($ad_id, $file_path) {
    global $conn;
    $ad_id = mysqli_real_escape_string($conn, $ad_id);
    $file_path = mysqli_real_escape_string($conn, $file_path);
    $query = "INSERT INTO photos (ad_id, file_path) VALUES ('$ad_id', '$file_path')";
    return mysqli_query($conn, $query);
}

function get_ad_first_image($ad_id) {
    global $conn;
    $ad_id = intval($ad_id);
    $query = "SELECT file_path FROM photos WHERE ad_id = $ad_id LIMIT 1";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row ? $row['file_path'] : 'default_ad_image.jpg';
}

function get_ad_views($ad_id) {
    global $conn;
    $ad_id = mysqli_real_escape_string($conn, $ad_id);
    $query = "SELECT views FROM ads WHERE id = '$ad_id'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['views'];
}

function increment_ad_views($ad_id) {
    global $conn;
    $ad_id = mysqli_real_escape_string($conn, $ad_id);
    $query = "UPDATE ads SET views = views + 1 WHERE id = '$ad_id'";
    return mysqli_query($conn, $query);
}

// Category and subcategory functions
function get_categories() {
    global $conn;
    $query = "SELECT * FROM categories ORDER BY name";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function get_subcategories($category_id) {
    global $conn;
    $category_id = mysqli_real_escape_string($conn, $category_id);
    $query = "SELECT * FROM subcategories WHERE category_id = '$category_id' ORDER BY name";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function get_category_name($category_id) {
    global $conn;
    $category_id = intval($category_id);
    $query = "SELECT name FROM categories WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $category = $result->fetch_assoc();
    return $category ? $category['name'] : '';
}

// Location functions
function get_locations() {
    // You can replace this with a database query if you store locations in the database
    return [
        'New York', 'Los Angeles', 'Chicago', 'Houston', 'Phoenix',
        'Philadelphia', 'San Antonio', 'San Diego', 'Dallas', 'San Jose'
    ];
}

// Search and filter functions
function get_filtered_ads($filter_date, $filter_location, $filter_category, $filter_subcategory, $search_query, $limit = 20, $offset = 0) {
    global $conn;
    
    $query = "SELECT ads.*, categories.name AS category_name 
              FROM ads 
              JOIN categories ON ads.category_id = categories.id 
              WHERE 1=1";
    
    if ($filter_location) {
        $query .= " AND ads.location = '" . mysqli_real_escape_string($conn, $filter_location) . "'";
    }
    
    if ($filter_category) {
        $query .= " AND ads.category_id = " . intval($filter_category);
    }
    
    if ($filter_subcategory) {
        $query .= " AND ads.subcategory_id = " . intval($filter_subcategory);
    }
    
    if ($search_query) {
        $search_query = mysqli_real_escape_string($conn, $search_query);
        $query .= " AND (ads.title LIKE '%$search_query%' OR ads.description LIKE '%$search_query%')";
    }
    
    $query .= " ORDER BY ads.created_at " . ($filter_date == 'new' ? 'DESC' : 'ASC');
    $query .= " LIMIT " . intval($limit) . " OFFSET " . intval($offset);
    
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function count_filtered_ads($filter_date, $filter_location, $filter_category, $filter_subcategory, $search_query) {
    global $conn;
    
    $query = "SELECT COUNT(*) as total FROM ads 
              JOIN categories ON ads.category_id = categories.id 
              WHERE 1=1";
    
    if ($filter_location) {
        $query .= " AND ads.location = '" . mysqli_real_escape_string($conn, $filter_location) . "'";
    }
    
    if ($filter_category) {
        $query .= " AND ads.category_id = " . intval($filter_category);
    }
    
    if ($filter_subcategory) {
        $query .= " AND ads.subcategory_id = " . intval($filter_subcategory);
    }
    
    if ($search_query) {
        $search_query = mysqli_real_escape_string($conn, $search_query);
        $query .= " AND (ads.title LIKE '%$search_query%' OR ads.description LIKE '%$search_query%')";
    }
    
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['total'];
}

function get_recent_ads($limit = 10) {
    global $conn;
    
    $limit = (int)$limit;
    $query = "SELECT ads.*, categories.name AS category_name 
              FROM ads 
              JOIN categories ON ads.category_id = categories.id 
              ORDER BY ads.created_at DESC 
              LIMIT $limit";
    
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function get_related_ads($category_id, $exclude_ad_id, $limit = 4) {
    global $conn;
    
    $query = "SELECT a.id, a.title, a.price, a.category_id, a.subcategory_id, i.file_path AS image, c.name AS category_name, sc.name AS subcategory_name
              FROM ads a
              LEFT JOIN photos i ON a.id = i.ad_id
              LEFT JOIN categories c ON a.category_id = c.id
              LEFT JOIN subcategories sc ON a.subcategory_id = sc.id
              WHERE a.category_id = ? AND a.id != ?
              GROUP BY a.id
              ORDER BY a.created_at DESC
              LIMIT ?";
              
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error);
        return false;
    }
    
    $stmt->bind_param("iii", $category_id, $exclude_ad_id, $limit);
    $stmt->execute();

   $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Favorite-related functions
function is_favorite($user_id, $ad_id) {
    global $conn;
    $query = "SELECT * FROM favorites WHERE user_id = ? AND ad_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $ad_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}

function toggle_favorite($user_id, $ad_id) {
    global $conn;
    if (is_favorite($user_id, $ad_id)) {
        remove_favorite($user_id, $ad_id);
    } else {
        add_favorite($user_id, $ad_id);
    }
}

function add_favorite($user_id, $ad_id) {
    global $conn;
    $query = "INSERT INTO favorites (user_id, ad_id) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $ad_id);
    $stmt->execute();
}

function remove_favorite($user_id, $ad_id) {
    global $conn;
    $query = "DELETE FROM favorites WHERE user_id = ? AND ad_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $ad_id);
    $stmt->execute();
}

function get_favorite_ads($user_id) {
    global $conn;
    $query = "SELECT ads.*, categories.name AS category_name 
              FROM favorites 
              JOIN ads ON favorites.ad_id = ads.id 
              JOIN categories ON ads.category_id = categories.id 
              WHERE favorites.user_id = ?
              ORDER BY favorites.created_at DESC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Message-related functions
function get_user_messages($user_id) {
    global $conn;
    
    $user_id = mysqli_real_escape_string($conn, $user_id);
    $query = "SELECT messages.*, users.name AS sender_name 
              FROM messages 
              JOIN users ON messages.sender_id = users.id 
              WHERE messages.receiver_id = '$user_id' 
              ORDER BY messages.created_at DESC";
    
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function send_message($sender_id, $receiver_id, $ad_id, $message) {
    global $conn;
    
    $sender_id = mysqli_real_escape_string($conn, $sender_id);
    $receiver_id = mysqli_real_escape_string($conn, $receiver_id);
    $ad_id = mysqli_real_escape_string($conn, $ad_id);
    $message = mysqli_real_escape_string($conn, $message);

    $query = "INSERT INTO messages (sender_id, receiver_id, ad_id, message) 
              VALUES ('$sender_id', '$receiver_id', '$ad_id', '$message')";
    return mysqli_query($conn, $query);
}

// Utility functions
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function time_ago($datetime, $timezone = 'Asia/Colombo') {
    $now = new DateTime('now', new DateTimeZone($timezone));  // Current time with timezone
    $ago = new DateTime($datetime, new DateTimeZone($timezone)); // Posted time with timezone
    $diff = $now->diff($ago);

    // Formatting the difference as human-readable time ago
    if ($diff->y > 0) {
        return $diff->y . ' year' . ($diff->y > 1 ? 's' : '') . ' ago';
    } elseif ($diff->m > 0) {
        return $diff->m . ' month' . ($diff->m > 1 ? 's' : '') . ' ago';
    } elseif ($diff->d > 0) {
        if ($diff->d >= 7) {
            $weeks = floor($diff->d / 7);
            return $weeks . ' week' . ($weeks > 1 ? 's' : '') . ' ago';
        }
        return $diff->d . ' day' . ($diff->d > 1 ? 's' : '') . ' ago';
    } elseif ($diff->h > 0) {
        return $diff->h . ' hour' . ($diff->h > 1 ? 's' : '') . ' ago';
    } elseif ($diff->i > 0) {
        return $diff->i . ' minute' . ($diff->i > 1 ? 's' : '') . ' ago';
    } else {
        return 'Just now';
    }
}

function delete_photo($photo_id) {
    global $conn;
    
    // Get the file path
    $query = "SELECT file_path FROM photos WHERE id = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error);
        return false;
    }
    $stmt->bind_param("i", $photo_id);
    if (!$stmt->execute()) {
        error_log("Execute failed: " . $stmt->error);
        return false;
    }
    $result = $stmt->get_result();
    $photo = $result->fetch_assoc();

    // Delete the file if it exists
    if ($photo && file_exists($photo['file_path'])) {
        if (!unlink($photo['file_path'])) {
            error_log("Failed to delete file: " . $photo['file_path']);
        }
    }

    // Delete the database record
    $query = "DELETE FROM photos WHERE id = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error);
        return false;
    }
    $stmt->bind_param("i", $photo_id);
    if (!$stmt->execute()) {
        error_log("Execute failed: " . $stmt->error);
        return false;
    }

    return true;
}
