
<?php
    $server = "localhost";
    $username = "root";
    $password = "";
    $db = "signup_signin";
    $con = mysqli_connect($server, $username, $password, $db);
    if (!$con)
        die("connection to thies dataase is failed sue to " . mysqli_connect_error());
?>