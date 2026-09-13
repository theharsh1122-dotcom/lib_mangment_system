<?php

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'librarian') {
    header("Location: ../auth/login.php");
    exit();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Librarian Dashboard</title>
</head>
<body>

<h1>Welcome Librarian</h1>

<p>Welcome, <?php echo $_SESSION['username']; ?></p>

<a href="#">Manage Books</a><br><br>
<a href="#">Manage Students</a><br><br>
<a href="#">Issue Book</a><br><br>
<a href="#">Return Book</a><br><br>
<a href="#">Reports</a><br><br>
<a href="../auth/logout.php">Logout</a>

</body>
</html>