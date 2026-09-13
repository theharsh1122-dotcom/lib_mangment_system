<?php

session_start();

require_once "../config/database.php";

if (isset($_POST['login'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 0) {

        echo "Username is incorrect!";

    } else {

        $user = mysqli_fetch_assoc($result);

        if ($user['password'] != $password) {

            echo "Password is incorrect!";

        } else {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == 'admin') {
                header("Location: ../admin/dashboard.php");
                exit();
            }

            if ($user['role'] == 'librarian') {
                header("Location: ../librarian/dashboard.php");
                exit();
            }
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Library Management System - Login</title>
</head>
<body>

<h1>Library Management System</h1>

<form method="POST">

    <input type="text" name="username" placeholder="Enter Username" required>

    <br><br>

    <input type="password" name="password" placeholder="Enter Password" required>

    <br><br>

    <button type="submit" name="login">Login</button>

</form>

</body>
</html>