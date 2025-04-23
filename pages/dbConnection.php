<?php

    $db_server = "localhost";
    $db_user = "root";
    $db_pass = "mysql";
    $db_name = "CardVault";

    $connection = "";

    $connection = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

    if ($connection)
    {
   //     echo "you are connected!";
    }

    else{
    //    echo "You are not connected.";
    }


?>