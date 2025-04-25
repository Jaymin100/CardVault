<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="Search.css" />
  <title>Login</title>
</head>
<style>

 .button1{
            font-size: 1rem;
            background-color: #457776;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
 }
</style>

  
<body>
  <h1 style="text-align:center;">Welcome to Card Vault</h1>
  <div class="notecard-wrapper">
    <div class="notecard" style="width: 300px; text-align: center; border:3px #457776 solid;">
      <h1 class="notecard-title">Login</h1>
      <form action="mainpage.php" method="post" style="display: flex; flex-direction: column; gap: 15px;">
        <div>
          <label for="uname" class="notecard-author">Username:</label><br>
          <input type="text" id="uname" name="uname" required>
        </div>
        <div>
          <label for="pword" class="notecard-author">Password:</label><br>
          <input type="password" id="pword" name="pword" required>
        </div>
        <button type="submit">Login</button>
      </form>
    </div>
  </div>
  <div style="text-align:center;">
    <p>Don't have an account?</p><br>
    <button class="button1""><a href="index.php" style="text-decoration:none; color:white;">Back to Home</a></button>
</body>
</html>
