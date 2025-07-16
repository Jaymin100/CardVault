

<?php
require_once __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();



    $db_server = $_ENV['db_server'];
    $db_user = $_ENV["db_user"];
    $db_pass = $_ENV["db_pass"];
    $db_name = $_ENV["db_name"];

    $connection = "";

    $connection = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

    if ($connection)
    {
      // echo "you are connected!";
    }

    else{
     //   echo "You are not connected.";
    }


?>