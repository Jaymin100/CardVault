<?php
session_start();
require_once 'classes/User.php'; // include your class

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = unserialize($_SESSION['user']);
$username = htmlspecialchars($user->getUser());
$user_id = htmlspecialchars($user->getAccountID());
?>

<nav style="background-color: #c9cabd; height: 20px; padding: 10px 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); position: fixed; top: 0; width: 100%; z-index: 1000; border: 2px solid black;">
  <ul style="list-style: none; display: flex; gap: 30px; margin: 0; padding: 0; align-items: center;">
    <li><a href="Create.php" style="text-decoration: none; color: #333;">Create</a></li>
    <li><a href="Search.php" style="text-decoration: none; color: #333;">Search</a></li>
    <li><a href="View.php" style="text-decoration: none; color: #333;">View</a></li>
    <li style="margin-left: auto; font-weight: bold;"><?php echo htmlspecialchars($username); ?></li>
    <!-- Logout Button -->
    <li><a href="Login.php" style="text-decoration: none; color: #333; font-weight: bold; background-color: #457776; color: white; padding: 5px 10px; border-radius: 5px;">Logout</a></li>
  </ul>
</nav>
