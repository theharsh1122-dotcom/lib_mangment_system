<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

include "../config/database.php";

$success = "";
$error = "";

if (isset($_GET['updated']) && $_GET['updated'] == 1) {
    $success = "Librarian updated successfully!";
}

if (isset($_GET['deleted']) && $_GET['deleted'] == 1) {
    $success = "Librarian deleted successfully!";
}

if (isset($_POST['add_librarian'])) {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    $check = mysqli_query($conn, "SELECT id FROM users WHERE username='$username'");

    if (mysqli_num_rows($check) > 0) {
        $error = "Username already exists!";
    } else {

        $query = "INSERT INTO users (name, email, username, password, role)
                  VALUES ('$name', '$email', '$username', '$password', 'librarian')";

        if (mysqli_query($conn, $query)) {
            $success = "Librarian added successfully!";
        } else {
            $error = "Librarian could not be added!";
        }
    }
}

$result = mysqli_query($conn, "SELECT * FROM users WHERE role='librarian' ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Librarians</title>
    <link rel="stylesheet" href="../assets/css/admin_librarian.css">
</head>

<body>

<div class="container">

    <div class="header">
        <div>
            <h1>Manage Librarians</h1>
            <p>Add and manage library librarians</p>
        </div>

        <a href="dashboard.php" class="back-btn">← Dashboard</a>
    </div>

    <?php if ($success != "") { ?>
        <div class="success"><?php echo $success; ?></div>
    <?php } ?>

    <?php if ($error != "") { ?>
        <div class="error"><?php echo $error; ?></div>
    <?php } ?>

    <div class="form-box">

        <h2>Add New Librarian</h2>

        <form method="POST">

            <div class="form-group">
                <label>Librarian Name</label>
                <input type="text" name="name" placeholder="Enter librarian name" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="Enter email" required>
            </div>

            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Enter username" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Enter password" required>
            </div>

            <button type="submit" name="add_librarian">Add Librarian</button>

        </form>

    </div>

    <div class="list-box">

        <div class="list-header">
            <h2>Librarians List</h2>

            <input type="text" id="searchInput" placeholder="Search librarian...">
        </div>

        <div class="table-container">

            <table id="librarianTable">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                <?php while ($librarian = mysqli_fetch_assoc($result)) { ?>

                    <tr>
                        <td><?php echo $librarian['id']; ?></td>
                        <td><?php echo $librarian['name']; ?></td>
                        <td><?php echo $librarian['email']; ?></td>
                        <td><?php echo $librarian['username']; ?></td>
                        <td>
                            <span class="role">Librarian</span>
                        </td>
                        <td>
                            <a href="edit_librarian.php?id=<?php echo $librarian['id']; ?>" class="edit-btn">Edit</a>

                            <a href="delete_librarian.php?id=<?php echo $librarian['id']; ?>"
                               class="delete-btn"
                               onclick="return confirm('Are you sure you want to delete this librarian?');">
                                Delete
                            </a>
                        </td>
                    </tr>

                <?php } ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<script>

document.getElementById("searchInput").addEventListener("keyup", function () {

    let search = this.value.toLowerCase();
    let rows = document.querySelectorAll("#librarianTable tbody tr");

    rows.forEach(function (row) {

        let text = row.innerText.toLowerCase();

        if (text.includes(search)) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }

    });

});

</script>

</body>
</html>