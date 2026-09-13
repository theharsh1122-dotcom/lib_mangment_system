<?php

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

include "../config/database.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: books.php");
    exit();
}

$id = (int) $_GET['id'];

$result = mysqli_query($conn, "SELECT * FROM books WHERE id=$id");

if (mysqli_num_rows($result) == 0) {
    header("Location: books.php");
    exit();
}

$book = mysqli_fetch_assoc($result);

$message = "";
$message_type = "";

if (isset($_POST['update_book'])) {

    $book_name = trim($_POST['book_name']);
    $author = trim($_POST['author']);
    $category = trim($_POST['category']);
    $quantity = (int) $_POST['quantity'];

    if ($book_name == "" || $author == "" || $category == "" || $quantity <= 0) {

        $message = "Please fill all fields correctly.";
        $message_type = "error";

    } else {

        $book_name = mysqli_real_escape_string($conn, $book_name);
        $author = mysqli_real_escape_string($conn, $author);
        $category = mysqli_real_escape_string($conn, $category);

        $query = "UPDATE books SET
                    book_name='$book_name',
                    author='$author',
                    category='$category',
                    quantity='$quantity'
                  WHERE id=$id";

        if (mysqli_query($conn, $query)) {

            header("Location: books.php?updated=1");
            exit();

        } else {

            $message = "Unable to update book. Please try again.";
            $message_type = "error";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Book - Library Management System</title>

    <link rel="stylesheet" href="../assets/css/edit_book.css">

</head>

<body>

<div class="sidebar">

    <div class="logo">
        <span>📚</span>
        <h2>Library</h2>
    </div>

    <div class="menu">

        <a href="dashboard.php">
            <span>📊</span>
            Dashboard
        </a>

        <a href="books.php" class="active">
            <span>📖</span>
            Manage Books
        </a>

        <a href="#">
            <span>👨‍🎓</span>
            Manage Students
        </a>

        <a href="#">
            <span>👨‍💼</span>
            Manage Librarians
        </a>

        <a href="#">
            <span>📋</span>
            Issued Books
        </a>

        <a href="#">
            <span>📊</span>
            Reports
        </a>

    </div>

    <a href="../auth/logout.php" class="logout">
        <span>🚪</span>
        Logout
    </a>

</div>


<div class="main">

    <div class="topbar">

        <div>

            <h1>Edit Book</h1>

            <p>
                Update the information of your library book
            </p>

        </div>


        <div class="profile">

            <div class="avatar">
                <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
            </div>

            <div>

                <strong>
                    <?php echo htmlspecialchars($_SESSION['username']); ?>
                </strong>

                <small>Administrator</small>

            </div>

        </div>

    </div>


    <div class="page-content">

        <?php if ($message != "") { ?>

            <div class="message <?php echo $message_type; ?>">
                <?php echo $message; ?>
            </div>

        <?php } ?>


        <div class="edit-card">

            <div class="card-header">

                <div class="header-info">

                    <div class="book-icon">
                        📖
                    </div>

                    <div>

                        <h2>Book Information</h2>

                        <p>
                            Make changes to the book details below
                        </p>

                    </div>

                </div>

                <span class="book-id">
                    ID #<?php echo $book['id']; ?>
                </span>

            </div>


            <form method="POST">

                <div class="form-grid">

                    <div class="form-group">

                        <label for="book_name">
                            Book Name
                        </label>

                        <input
                            type="text"
                            id="book_name"
                            name="book_name"
                            value="<?php echo htmlspecialchars($book['book_name']); ?>"
                            placeholder="Enter book name"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label for="author">
                            Author
                        </label>

                        <input
                            type="text"
                            id="author"
                            name="author"
                            value="<?php echo htmlspecialchars($book['author']); ?>"
                            placeholder="Enter author name"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label for="category">
                            Category
                        </label>

                        <input
                            type="text"
                            id="category"
                            name="category"
                            value="<?php echo htmlspecialchars($book['category']); ?>"
                            placeholder="Enter book category"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label for="quantity">
                            Quantity
                        </label>

                        <input
                            type="number"
                            id="quantity"
                            name="quantity"
                            value="<?php echo $book['quantity']; ?>"
                            min="1"
                            placeholder="Enter quantity"
                            required
                        >

                    </div>

                </div>


                <div class="form-footer">

                    <a href="books.php" class="back-btn">
                        ← Back to Books
                    </a>

                    <div class="form-actions">

                        <button
                            type="reset"
                            class="reset-btn"
                        >
                            Reset
                        </button>

                        <button
                            type="submit"
                            name="update_book"
                            class="update-btn"
                        >
                            ✓ Update Book
                        </button>

                    </div>

                </div>

            </form>

        </div>


        <div class="info-box">

            <div class="info-icon">
                💡
            </div>

            <div>

                <h3>Update Book Details</h3>

                <p>
                    Make sure the book information is accurate before
                    saving your changes.
                </p>

            </div>

        </div>

    </div>

</div>

</body>
</html>