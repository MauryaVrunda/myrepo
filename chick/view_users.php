<?php
require 'auth.php';
require 'connect.php';

require_admin();

// ✅ Fetch all users except the admin
$stmt = $conn->prepare("SELECT * FROM users WHERE email != ? ORDER BY id DESC");
$admin_email = ADMIN_EMAIL;
$stmt->bind_param("s", $admin_email);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>View Users</title>
  <link rel="stylesheet" href="styles/admin.css">
  <style>
    .container {
      max-width: 1000px;
      margin: 30px auto;
      background: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.08);
    }

    h1 {
      text-align: center;
      color: #333;
      margin-bottom: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 15px;
    }

    table th, table td {
      border: 1px solid #ddd;
      padding: 12px;
      text-align: center;
    }

    table th {
      background-color: #f2f2f2;
    }

    .action-link {
      color: red;
      text-decoration: none;
    }

    .action-link:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Registered Users</h1>

    <?php if ($result->num_rows > 0): ?>
      <table>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Registered On</th>
          <!-- Optional -->
          <!-- <th>Actions</th> -->
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= date("d M Y", strtotime($row['created_at'] ?? $row['registration_date'] ?? '')) ?></td>
            <!-- <td><a href="delete_user.php?id=<?= $row['id'] ?>" class="action-link" onclick="return confirm('Delete this user?')">Delete</a></td> -->
          </tr>
        <?php endwhile; ?>
      </table>
    <?php else: ?>
      <p>No users found.</p>
    <?php endif; ?>
  </div>
</body>
</html>