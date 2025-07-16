<?php
session_start();
include 'csrf.php';

$max_attempts = 5;
$lockout_time = 15*60 ;// 15 mins

if(!isset($_SESSION['login_attempts'])){
$_SESSION['login_attempts'] = 0;
$_SESSION['last_attempt_time'] = time();
}
if($_SESSION['login_attempts'] >= $max_attempts && (time()-$_SESSION['last_attempt_time']) < $lockout_time)






?>

<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="Search.css" />
  <title>Login</title>
</head>
<style>

body {
  background-image: url('../login_background.jpg');
  background-size: cover;
  background-position: center;
}

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
        <?= csrf_input_field() ?>
        <button type="submit" class="button1">Login</button>
      </form>
    </div>
  </div>
  <div style="text-align:center;">
    <p>Click Down here to register!</p><br>
    <button class="button1"><a href="CreateAccount.php" style="text-decoration:none; color:white;">Register</a></button>
</body>
</html>
