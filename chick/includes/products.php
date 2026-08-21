<?php
// Product lookup and image handling shared by the shop and admin pages.

if (!defined('PRODUCT_IMAGE_DIR')) {
    define('PRODUCT_IMAGE_DIR', 'images/');
}

function find_product(mysqli $conn, int $product_id): ?array
{
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();

    return $product ?: null;
}

function product_image_path(string $imageName): string
{
    return PRODUCT_IMAGE_DIR . $imageName;
}

// Stores an uploaded product image and returns its generated file name, or null on failure.
function store_product_image(array $file): ?string
{
    if (empty($file['name']) || empty($file['size'])) {
        return null;
    }

    $imageName = time() . "_" . basename($file['name']);
    if (!move_uploaded_file($file['tmp_name'], product_image_path($imageName))) {
        return null;
    }

    return $imageName;
}

function delete_product_image(string $imageName): void
{
    $path = product_image_path($imageName);
    if ($imageName !== '' && file_exists($path)) {
        unlink($path);
    }
}
