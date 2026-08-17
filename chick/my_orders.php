<?php
if (isset($_SESSION['cancel_success'])) {
  echo "<p style='color: green; font-weight: bold;'>" . $_SESSION['cancel_success'] . "</p>";
  unset($_SESSION['cancel_success']);
}
if (isset($_SESSION['cancel_error'])) {
  echo "<p style='color: red; font-weight: bold;'>" . $_SESSION['cancel_error'] . "</p>";
  unset($_SESSION['cancel_error']);
}
?>