<?php
require 'includes/bootstrap.php';

if (is_logged_in()) {
  // Redirect if already logged in
  redirect_to('user_dashboard.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email']);
  $password = $_POST['password'];

  $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($user = $result->fetch_assoc()) {
 if (password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_email'] = $user['email'];  

      // ✅ Admin check based on email
      redirect_to(is_admin() ? 'admin_dashboard.php' : 'user_dashboard.php');
    } else {
      $error = "❌ Invalid password!";
    }
  } else {
    $error = "❌ No user found with that email!";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Login - Chic Charm Beads</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="style.css" />
</head>
<body>

<div class="auth-container">
  <h2>Login to Your Account</h2>

  <?php if ($error): ?>
    <div class="message"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="POST" action="">
    <label for="email">Email:</label>
    <input type="email" name="email" id="email" required />

    <label for="password">Password:</label>
    <input type="password" name="password" id="password" required />

    <button type="submit">Login</button>
    <p>Don't have an account? <a href="registration.php">Register here</a></p>
    <p><a href="forgot_password.html">Forgot password?</a></p>
  </form>
</div>

</body>
</html>