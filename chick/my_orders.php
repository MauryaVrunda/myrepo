<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}

$cancel_result = $_SESSION['cancel_result'] ?? null;
unset($_SESSION['cancel_result']);

if ($cancel_result) {
  $color = $cancel_result['status'] === 'success' ? 'green' : 'red';
  echo "<p style='color: $color; font-weight: bold;'>" . htmlspecialchars($cancel_result['msg']) . "</p>";
}
?>
