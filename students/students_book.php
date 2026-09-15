<?php
session_start();
require_once "../config/database.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: ../auth/login.php");
    exit();
}

$search = "";

if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
}

if ($search != "") {
    $search = mysqli_real_escape_string($conn, $search);

    $query = "SELECT * FROM books
              WHERE book_name LIKE '%$search%'
              OR author LIKE '%$search%'
              OR category LIKE '%$search%'
              ORDER BY id DESC";
} else {
    $query = "SELECT * FROM books ORDER BY id DESC";
}

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Available Books</title>

    <link rel="stylesheet" href="../assets/css/students_book.css">
</head>

<body>

<div class="sidebar">

    <div class="logo">
        <h2>Library</h2>
        <span>Management System</span>
    </div>

    <nav>

        <a href="dashboard.php">
            Dashboard
        </a>

        <a href="books.php" class="active">
            Books
        </a>

        <a href="issued_books.php">
            My Issued Books
        </a>

        <a href="profile.php">
            My Profile
        </a>

        <a href="../auth/logout.php" class="logout">
            Logout
        </a>

    </nav>

</div>


<div class="main">

    <div class="topbar">

        <div>
            <h1>Library Books</h1>
            <p>Browse books available in the library</p>
        </div>

        <div class="user-info">

            <div class="avatar">
                <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
            </div>

            <div>
                <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>
                <small>Student</small>
            </div>

        </div>

    </div>


    <div class="search-box">

        <form method="GET">

            <input
                type="text"
                name="search"
                placeholder="Search by book name, author or category..."
                value="<?php echo htmlspecialchars($search); ?>"
            >

            <button type="submit">Search</button>

            <?php if ($search != "") { ?>

                <a href="books.php">Clear</a>

            <?php } ?>

        </form>

    </div>


    <div class="books-card">

        <div class="card-header">

            <div>
                <h2>Available Books</h2>
                <p>Books currently listed in the library</p>
            </div>

            <span class="book-count">
                <?php echo mysqli_num_rows($result); ?> Books
            </span>

        </div>


        <div class="table-container">

            <table>

                <thead>

                    <tr>
                        <th>SR. No</th>
                        <th>Book Name</th>
                        <th>Author</th>
                        <th>Category</th>
                        <th>Quantity</th>
                        <th>Status</th>
                    </tr>

                </thead>

                <tbody>

                <?php

                if (mysqli_num_rows($result) > 0) {

                    $count = 1;

                    while ($book = mysqli_fetch_assoc($result)) {

                ?>

                    <tr>

                        <td>
                            <?php echo $count++; ?>
                        </td>

                        <td>
                            <strong>
                                <?php echo htmlspecialchars($book['book_name']); ?>
                            </strong>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($book['author']); ?>
                        </td>

                        <td>
                            <span class="category">
                                <?php echo htmlspecialchars($book['category']); ?>
                            </span>
                        </td>

                        <td>
                            <?php echo $book['quantity']; ?>
                        </td>

                        <td>

                            <?php if ($book['quantity'] > 0) { ?>

                                <span class="available">
                                    Available
                                </span>

                            <?php } else { ?>

                                <span class="unavailable">
                                    Not Available
                                </span>

                            <?php } ?>

                        </td>

                    </tr>

                <?php

                    }

                } else {

                ?>

                    <tr>

                        <td colspan="6" class="no-books">
                            No books found.
                        </td>

                    </tr>

                <?php } ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

</body>
</html>