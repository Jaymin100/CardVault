<form action="mainpage.php" method = "post">
  <label for="uname">Username:</label>
  <input type="text" id="uname" name="uname"><br><br>
  <label for="pword">Password:</label>
  <input type="text" id="pword" name="pword"><br><br>
  <input type="submit" value="Submit">
</form>

<?php
if (isset($_GET['uname']) && isset($_GET['pword']))             // Ensures input values are not NULL
{
    $username = $_POST['uname'];                                // Store vars
    $password = $_POST['pword'];
    echo "Username: " . htmlspecialchars($username) . "<br>";   // Prevent cross site scripting
    echo "Password: " . htmlspecialchars($password);
}
?>