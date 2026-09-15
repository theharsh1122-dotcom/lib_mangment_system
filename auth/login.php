<?php

session_start();

require_once "../config/database.php";

$error = "";

if (isset($_POST['login'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 0) {

        $error = "Username is incorrect!";

    } else {

        $user = mysqli_fetch_assoc($result);

        if ($user['password'] != $password) {

            $error = "Password is incorrect!";

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

            if ($user['role'] == 'student') {
                header("Location: ../students/dashboard.php");
                exit();
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Library Management System - Login</title>

    <link rel="stylesheet" href="../assets/css/login.css">

</head>

<body>

<div class="login-container">

    <div class="login-icon">📚</div>

    <h1>Library Management System</h1>

    <p>Login to your account</p>

    <?php if ($error != "") { ?>

        <div class="error-message">
            <?php echo $error; ?>
        </div>

    <?php } ?>

    <form method="POST">

        <div class="input-group">

            <input
                type="text"
                name="username"
                placeholder="Enter Username"
                required
            >

        </div>

        <div class="input-group">

            <input
                type="password"
                name="password"
                placeholder="Enter Password"
                required
            >

        </div>

        <button type="submit" name="login">
            Login
        </button>

    </form>

</div>

</body>

</html>