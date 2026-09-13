<?php

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'librarian') {
    header("Location: ../auth/login.php");
    exit();
}

include "../config/database.php";

$success = "";
$error = "";

if (isset($_POST['add_book'])) {

    $book_name = $_POST['book_name'];
    $author = $_POST['author'];
    $category = $_POST['category'];
    $quantity = (int)$_POST['quantity'];

    if ($quantity < 1) {

        $error = "Quantity must be at least 1!";

    } else {

        $query = "INSERT INTO books (book_name, author, category, quantity)
                  VALUES ('$book_name', '$author', '$category', $quantity)";

        if (mysqli_query($conn, $query)) {
            $success = "Book added successfully!";
        } else {
            $error = "Book could not be added!";
        }
    }
}

$result = mysqli_query($conn, "SELECT * FROM books ORDER BY id DESC");

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Manage Books - Librarian</title>

    <link rel="stylesheet" href="../assets/css/librarian_books.css">

</head>

<body>


<div class="sidebar">

    <h2>📚 Library</h2>

    <a href="dashboard.php">🏠 Dashboard</a>

    <a href="books.php" class="active">📖 Manage Books</a>

    <a href="student.php">👨‍🎓 Manage Students</a>

    <a href="issued_book.php">📋 Issue Book</a>

    <a href="return_book.php">↩️ Return Book</a>

    <a href="reports.php">📊 Reports</a>

    <a href="../auth/logout.php" class="logout">🚪 Logout</a>

</div>


<div class="main">


    <div class="topbar">

        <div>

            <h1>Manage Books</h1>

            <p>
                Add and manage library books
            </p>

        </div>

        <div class="profile">

            <div class="avatar">
                L
            </div>

            <span>
                <?php echo htmlspecialchars($_SESSION['username']); ?>
            </span>

        </div>

    </div>


    <?php if ($success != "") { ?>

        <div class="success-message">
            <?php echo $success; ?>
        </div>

    <?php } ?>


    <?php if ($error != "") { ?>

        <div class="error-message">
            <?php echo $error; ?>
        </div>

    <?php } ?>


    <div class="form-box">

        <h2>➕ Add New Book</h2>

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
                        placeholder="Enter category"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>Quantity</label>

                    <input
                        type="number"
                        name="quantity"
                        min="1"
                        placeholder="Enter quantity"
                        required
                    >

                </div>

            </div>


            <button
                type="submit"
                name="add_book"
                class="add-btn"
            >
                ➕ Add Book
            </button>

        </form>

    </div>


    <div class="table-box">

        <div class="table-header">

            <div>

                <h2>📚 All Books</h2>

                <p>
                    Total Books:
                    <strong>
                        <?php echo mysqli_num_rows($result); ?>
                    </strong>
                </p>

            </div>

            <input
                type="text"
                id="search"
                placeholder="🔍 Search books..."
                onkeyup="searchBooks()"
            >

        </div>


        <div class="table-wrapper">

            <table id="booksTable">

                <thead>

                    <tr>

                        <th>ID</th>

                        <th>Book Name</th>

                        <th>Author</th>

                        <th>Category</th>

                        <th>Quantity</th>

                        <th>Available</th>

                    </tr>

                </thead>


                <tbody>

                <?php while ($book = mysqli_fetch_assoc($result)) { ?>

                    <tr>

                        <td>
                            <?php echo $book['id']; ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($book['book_name']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($book['author']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($book['category']); ?>
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

                <?php } ?>

                </tbody>

            </table>

        </div>

    </div>


</div>


<script>

function searchBooks() {

    let input =
        document.getElementById("search").value.toLowerCase();

    let rows =
        document
        .getElementById("booksTable")
        .getElementsByTagName("tbody")[0]
        .getElementsByTagName("tr");

    for (let i = 0; i < rows.length; i++) {

        let text =
            rows[i].innerText.toLowerCase();

        if (text.includes(input)) {

            rows[i].style.display = "";

        } else {

            rows[i].style.display = "none";

        }
    }
}

</script>


</body>

</html>