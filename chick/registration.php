<?php
require 'includes/bootstrap.php';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

  $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
  $check->bind_param("s", $email);
  $check->execute();
  $check_result = $check->get_result();

  if ($check_result->num_rows > 0) {
    $message = "⚠️ Email already registered!";
  } else {
    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $password);
    if ($stmt->execute()) {
      $message = "✅ Registered successfully! <a href='login.php'>Login now</a>";
    } else {
      $message = "❌ Registration failed!";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="auth-container">
  <h2>Register</h2>
  <form method="post" action="">
    <label for="name">Full Name</label>
    <input type="text" name="name" required>

    <label for="email">Email</label>
    <input type="email" name="email" required>

    <label for="password">Password</label>
    <input type="password" name="password" required>

    <button type="submit">Register</button>
  </form>
  <p class="message"><?= $message ?></p>
  <p>Already have an account? <a href="login.php">Login here</a></p>
</div>

</body>
</html>