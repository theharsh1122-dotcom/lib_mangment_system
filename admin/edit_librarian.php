<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

include "../config/database.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: librarians.php");
    exit();
}

$id = (int)$_GET['id'];

$result = mysqli_query($conn, "SELECT * FROM users WHERE id=$id AND role='librarian'");

if (mysqli_num_rows($result) == 0) {
    header("Location: librarians.php");
    exit();
}

$librarian = mysqli_fetch_assoc($result);

if (isset($_POST['update_librarian'])) {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $username = $_POST['username'];

    $query = "UPDATE users SET
              name='$name',
              email='$email',
              username='$username'
              WHERE id=$id AND role='librarian'";

    if (mysqli_query($conn, $query)) {
        header("Location: librarians.php?updated=1");
        exit();
    } else {
        $error = "Librarian update failed!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Librarian</title>
    <link rel="stylesheet" href="../assets/css/edit_librarian.css">
</head>

<body>

<div class="container">

    <h1>Edit Librarian</h1>

    <?php if (isset($error)) { ?>
        <p class="error"><?php echo $error; ?></p>
    <?php } ?>

    <form method="POST">

        <label>Librarian Name</label>
        <input type="text" name="name"
               value="<?php echo $librarian['name']; ?>" required>

        <label>Email</label>
        <input type="email" name="email"
               value="<?php echo $librarian['email']; ?>" required>

        <label>Username</label>
        <input type="text" name="username"
               value="<?php echo $librarian['username']; ?>" required>

        <button type="submit" name="update_librarian">
            Update Librarian
        </button>

    </form>

    <a href="librarian.php" class="back-btn">← Back to Librarians</a>

</div>

</body>
</html>