<?php
session_start();
require 'connect.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$user_id = $_SESSION['user_id'];
$success = '';
$error = '';

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_image'])) {
  $file = $_FILES['profile_image'];

  if ($file['error'] === 0) {
    $fileName = basename($file['name']);
    $fileTmp = $file['tmp_name'];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];

    if (in_array($fileExt, $allowed)) {
      $newName = "user_" . $user_id . "_" . time() . "." . $fileExt;
      $destination = "profile_images/" . $newName;

      if (!is_dir("profile_images")) {
        mkdir("profile_images", 0777, true);
      }

      if (move_uploaded_file($fileTmp, $destination)) {
        $update = $conn->prepare("UPDATE users SET profile_pic = ? WHERE id = ?");
        $update->bind_param("si", $newName, $user_id);
        if ($update->execute()) {
          $success = "Profile photo updated successfully!";
        } else {
          $error = "Database update failed.";
        }
      } else {
        $error = "Failed to upload image.";
      }
    } else {
      $error = "Invalid file type. Please upload JPG, JPEG, PNG or WEBP.";
    }
  } else {
    $error = "File error. Please try again.";
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Update Profile Photo</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="stylesheet" href="style.css" />

</head>
<body>

<div class="auth-container">
  <h2>Update Profile Photo</h2>

  <?php if ($success): ?>
    <div class="message"><?= htmlspecialchars($success) ?></div>
  <?php elseif ($error): ?>
    <div class="message error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="POST" action='user_dashboard.php' enctype="multipart/form-data">
    <label>Select a profile image:</label>
    <input type="file" name="profile_image" accept=".jpg,.jpeg,.png,.webp" required>
    <button type="submit">Upload</button>
    <a href="user_dashboard.php" class="back-link">⬅ Back to Dashboard</a>
  </form>
</div>

</body>
</html>