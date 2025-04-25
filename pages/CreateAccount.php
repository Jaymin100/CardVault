<html>
    <head>
        <title>Create Account</title>
    </head>
    <link rel="stylesheet" href="Search.css">
    <body>
        <form action="CreateAccount.php" method = "post">
        <label for="uname">Username (8 - 16 characters):</label>
        <input type="text" id="uname" name="uname"><br><br>
        <label for="pword">Password (8 - 32 characters):</label>
        <input type="password" id="pword" name="pword"><br><br>
        <label for="pword_re">Password (Re-enter):</label>
        <input type="password" id="pword_re" name = "pword_re"><br><br>
        <input type="submit" value="Submit">
        </form>
    </body>

    <?php
        $db_server = "localhost";
        $db_user = "root";
        $db_pass = "mysql";
        $db_name = "CardVault";
        $conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

        $valid_uname;
        $valid_pword;
        if(isset($_POST['uname']) && isset($_POST['pword'])             // Check for NULL values
            && isset($_POST['pword_re']))                               //
        {
            $prop_uname = $_POST['uname'];                              // Proposed username
            $prop_pword = $_POST['pword'];                              // Proposed password
            $prop_pword_re = $_POST['pword_re'];                        // Re-entered password  

            $is_valid_uname = is_valid_uname($prop_uname);                                // Validate username meets parameters
            $is_valid_pword = is_valid_pword($prop_pword, $prop_pword_re);   // Validate password meets parameters
            $is_in_database = is_in_database($prop_uname);
            
            if($is_valid_uname && $is_valid_pword && !$is_in_database)
            {
                insert_info($prop_uname, $prop_pword);
            }

        }
        function insert_info($uname_in, $pword_in)
        {
            global $conn;
            $query = "INSERT INTO user (username, user_password)
                VALUES ('$uname_in', '$pword_in')";
            $result = $conn->query($query);

             if(!$result) die("Fatal Error");

             echo "Successfully signed up!";
        }

        function is_in_database($uname_in): bool                         // Checks whether the username is already taken                        
        {
            global $conn;                                                 // We will need our conneciton variable and the boolean
            $uname_in = $conn->real_escape_string($uname_in);   
            $query = "SELECT 1 FROM user 
                        where username = '$uname_in' LIMIT 1";          // Query to get all usernames 
            $result = $conn->query($query);                      // Query database and store results

            if(!$result) die("Fatal error");                            // If the database didn't return anything, DIE! >:)

            $r = $result->num_rows;

            if($r > 0)
                echo "Username already taken";

            return $result->num_rows > 0;
        }
        
        function is_valid_uname($uname_in): bool
        {
            if(strlen($uname_in) < 8)                           // Proposed username is less than 8 characters
            {
                echo "Error: not enough characters";
                return false;
            }
            else if(strlen($uname_in) > 16)                     // Proposed username is greater than 16 characters
            {                                       
                echo "Error: too many characters";
                return false;
            }
            else
                return true;                                            // Username is valid
        }

        function is_valid_pword($pword_in, $pword_re_in): bool
        {
            if(strlen($pword_in) < 8)                           // Proposed password is less than 8 characters
            { 
                echo "Error: not enough characters";
                return false;
            }
            else if(strlen($pword_in) > 32)  
            {                                                           // Proposed password is greater than 32 characters.
                echo "Error: too many characters";
                return false;
            }
            else if($pword_in != $pword_re_in)
                return false;

            else
                return true;
        }

    ?>

</html>