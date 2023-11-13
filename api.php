<?php
session_start();
require_once "conn.php";

// Sign Up API
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signup'])) {
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $username_search = "SELECT * FROM `signup_signin` WHERE `username`= '$username'";
    
    $query = mysqli_query($con, $username_search);
    $username_count = mysqli_num_rows($query);

    if ($username_count) {
        $_SESSION['msg'] = "$username already exists";
        http_response_code(400); // 400 Bad Request
        echo json_encode(['error' => 'User already exists']);
        exit();
    } else {
        $sql = "INSERT INTO `signup_signin` (`username`, `password`) VALUES ('$username', '$password')";

        if ($con->query($sql)) {
            $_SESSION['msg'] = "Signup successful";
            http_response_code(201); // 201 Created
            echo json_encode(['message' => 'Signup successful', 'username' => $username]);
            exit();
        } else {
            $_SESSION['msg'] = "Error in signup";
            http_response_code(500); // 500 Internal Server Error
            echo json_encode(['error' => 'Error in signup']);
            exit();
        }
    }
    $con->close();
}

// Login API
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $pass = mysqli_real_escape_string($con, $_POST['password']);
    $username_search = "SELECT * FROM `signup_signin` WHERE `username`= '$username'";
    
    $query = mysqli_query($con, $username_search);

    if ($query) {
        $username_count = mysqli_num_rows($query);

        if ($username_count) {
            $username_pass = mysqli_fetch_array($query);
            $db_pass = $username_pass['password'];

            if ($pass == $db_pass) {
                $_SESSION['msg'] = "Login successful";
                http_response_code(200);
                echo json_encode(['message' => 'Login successful']);

                // Redirect to index.php
                $_SESSION['username'] = $username;
                header("Location: index.php");
                exit();
            } else {
                $_SESSION['msg'] = "Incorrect password";
                http_response_code(401);
                echo json_encode(['error' => 'Invalid credentials as password wrong']);
                exit();
            }
        } else {
            $_SESSION['msg'] = "No username found";
            http_response_code(401);
            echo json_encode(['error' => 'Invalid credentials and no user found']);
            exit();
        }
    } else {
        $_SESSION['msg'] = "Error in database query";
        http_response_code(500);
        echo json_encode(['error' => 'Error in database query']);
        exit();
    }
    $con->close();
}

// Logout API
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_destroy();
    echo json_encode(['message' => 'Logout successful']);
    header("Location: login.php");
    exit();
}

// Generate Random ID API
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generateRandomID'])) {
    $randomID = md5(uniqid(rand(), true));
    echo json_encode(['randomID' => $randomID]);
    exit();
}

?>
