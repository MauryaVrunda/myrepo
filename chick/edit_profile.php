<?php
require 'auth.php';
require 'connect.php';
require 'uploads.php';

require_login();

$user_id = $_SESSION['user_id'];
$success = '';
$error = '';

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_image'])) {
  $file = $_FILES['profile_image'];

  $uploadError = null;
  $newName = validated_image_name($file, $uploadError);

  if ($newName !== null) {
      $destination = "profile_images/" . $newName;

      if (!is_dir("profile_images")) {
        mkdir("profile_images", 0755, true);
      }

      if (move_uploaded_file($file['tmp_name'], $destination)) {
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
    $error = $uploadError ?? "File error. Please try again.";
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