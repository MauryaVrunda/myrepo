<?php
// Helpers for validating user supplied image uploads.

const ALLOWED_IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
const ALLOWED_IMAGE_MIME_TYPES = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
const MAX_IMAGE_BYTES = 5 * 1024 * 1024;

/**
 * Validates an uploaded image and returns a safe, generated filename.
 * Returns null when the upload is not an acceptable image; $error is filled in.
 */
function validated_image_name(array $file, ?string &$error = null): ?string
{
    if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK || !is_uploaded_file($file['tmp_name'])) {
        $error = "Upload failed.";
        return null;
    }

    if ($file['size'] <= 0 || $file['size'] > MAX_IMAGE_BYTES) {
        $error = "Image must be between 1 byte and 5 MB.";
        return null;
    }

    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, ALLOWED_IMAGE_EXTENSIONS, true)) {
        $error = "Invalid file type. Allowed: " . implode(', ', ALLOWED_IMAGE_EXTENSIONS) . ".";
        return null;
    }

    $info = @getimagesize($file['tmp_name']);
    if ($info === false || !in_array($info['mime'], ALLOWED_IMAGE_MIME_TYPES, true)) {
        $error = "File is not a valid image.";
        return null;
    }

    // Filename is generated, never derived from the client supplied name.
    return bin2hex(random_bytes(16)) . '.' . $extension;
}
