<?php
require_once 'config.php';
require_once 'functions.php';

if (isset($_POST['category_id'])) {
    $category_id = intval($_POST['category_id']);
    $subcategories = get_subcategories($category_id);
    echo json_encode($subcategories);
} else {
    echo json_encode([]);
}
