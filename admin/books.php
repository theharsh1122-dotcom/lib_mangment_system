<?php

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

include "../config/database.php";

$message = "";
$message_type = "";

if (isset($_POST['add_book'])) {

    $book_name = trim($_POST['book_name']);
    $author = trim($_POST['author']);
    $category = trim($_POST['category']);
    $quantity = (int) $_POST['quantity'];

    if ($book_name == "" || $author == "" || $category == "" || $quantity <= 0) {

        $message = "Please fill all fields correctly.";
        $message_type = "error";

    } else {

        $query = "INSERT INTO books (book_name, author, category, quantity)
                  VALUES ('$book_name', '$author', '$category', '$quantity')";

        if (mysqli_query($conn, $query)) {

            $message = "Book added successfully.";
            $message_type = "success";

        } else {

            $message = "Something went wrong. Please try again.";
            $message_type = "error";
        }
    }
}

$books = mysqli_query($conn, "SELECT * FROM books ORDER BY id DESC");

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Manage Books - Library Management System</title>

    <link rel="stylesheet" href="../assets/css/admin_books.css">

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
            <h1>Manage Books</h1>

            <p>
                Add, manage and organize library books
            </p>
        </div>

        <div class="profile">

            <div class="avatar">
                <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
            </div>

            <div>
                <strong><?php echo $_SESSION['username']; ?></strong>
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


        <div class="form-card">

            <div class="card-heading">

                <div>
                    <h2>Add New Book</h2>
                    <p>Enter the book details below</p>
                </div>

                <div class="book-icon">
                    📚
                </div>

            </div>


            <form method="POST">

                <div class="form-grid">

                    <div class="form-group">

                        <label>Book Name</label>

                        <input
                            type="text"
                            name="book_name"
                            placeholder="Enter book name"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label>Author</label>

                        <input
                            type="text"
                            name="author"
                            placeholder="Enter author name"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label>Category</label>

                        <input
                            type="text"
                            name="category"
                            placeholder="e.g. Programming"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label>Quantity</label>

                        <input
                            type="number"
                            name="quantity"
                            placeholder="Enter quantity"
                            min="1"
                            required
                        >

                    </div>

                </div>


                <div class="form-actions">

                    <button type="reset" class="reset-btn">
                        Clear
                    </button>

                    <button type="submit" name="add_book" class="add-btn">
                        + Add Book
                    </button>

                </div>

            </form>

        </div>


        <div class="books-card">

            <div class="books-header">

                <div>
                    <h2>All Books</h2>

                    <p>
                        Manage all books available in the library
                    </p>
                </div>

                <div class="search-box">

                    <input
                        type="text"
                        id="bookSearch"
                        placeholder="Search books..."
                        onkeyup="searchBooks()"
                    >

                </div>

            </div>


            <div class="table-wrapper">

                <table id="booksTable">

                    <thead>

                        <tr>
                            <th>#</th>
                            <th>Book Name</th>
                            <th>Author</th>
                            <th>Category</th>
                            <th>Quantity</th>
                            <th>Action</th>
                        </tr>

                    </thead>


                    <tbody>

                    <?php

                    $count = 1;

                    if (mysqli_num_rows($books) > 0) {

                        while ($book = mysqli_fetch_assoc($books)) {

                    ?>

                        <tr>

                            <td>
                                <?php echo $count++; ?>
                            </td>

                            <td>

                                <div class="book-name">

                                    <div class="book-avatar">
                                        📖
                                    </div>

                                    <strong>
                                        <?php echo htmlspecialchars($book['book_name']); ?>
                                    </strong>

                                </div>

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

                                <span class="quantity">
                                    <?php echo $book['quantity']; ?>
                                </span>

                            </td>

                            <td>

                                <div class="actions">

                                    <a
                                        href="edit_book.php?id=<?php echo $book['id']; ?>"
                                        class="edit-btn"
                                    >
                                        Edit
                                    </a>

                                    <a
                                        href="delete_book.php?id=<?php echo $book['id']; ?>"
                                        class="delete-btn"
                                        onclick="return confirm('Are you sure you want to delete this book?');"
                                    >
                                        Delete
                                    </a>

                                </div>

                            </td>

                        </tr>

                    <?php

                        }

                    } else {

                    ?>

                        <tr>

                            <td colspan="6" class="no-books">
                                No books found. Add your first book above.
                            </td>

                        </tr>

                    <?php } ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>


<script>

function searchBooks() {

    let input = document.getElementById("bookSearch");
    let filter = input.value.toLowerCase();

    let table = document.getElementById("booksTable");
    let rows = table.getElementsByTagName("tr");

    for (let i = 1; i < rows.length; i++) {

        let text = rows[i].innerText.toLowerCase();

        if (text.includes(filter)) {
            rows[i].style.display = "";
        } else {
            rows[i].style.display = "none";
        }

    }

}

</script>

</body>
</html>