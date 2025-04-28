<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Create Account</title>
  <link rel="stylesheet" href="Search.css" />
  <style>
    .button1 {
      font-size: 1rem;
      background-color: #457776;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      padding: 10px;
    }
    input[type="text"], input[type="password"] {
      width: 90%;
      padding: 8px;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
  </style>
</head>

<body>
  <h1 style="text-align:center;">Create Your Account</h1>
  <div class="notecard-wrapper">
    <div class="notecard" style="width: 300px; text-align: center; border:3px #457776 solid;">
      <h1 class="notecard-title">Register</h1>
      <form action="CreateAccountHandler.php" method="post" style="display: flex; flex-direction: column; gap: 15px;">
        <div>
          <label for="uname" class="notecard-author">Username (8-16 characters):</label><br>
          <input type="text" id="uname" name="uname" required>
        </div>
        <div>
          <label for="pword" class="notecard-author">Password (8-32 characters):</label><br>
          <input type="password" id="pword" name="pword" required>
        </div>
        <div>
          <label for="pword_re" class="notecard-author">Re-enter Password:</label><br>
          <input type="password" id="pword_re" name="pword_re" required>
        </div>
        <button type="submit" class="button1">Register</button>
      </form>
    </div>
  </div>
