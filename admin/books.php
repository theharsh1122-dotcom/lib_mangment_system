<?php

session_start();

require_once "../config/database.php";

$query = "SELECT * FROM books ORDER BY id DESC";
$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Books</title>
</head>
<body>

<h1>Books</h1>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Book Name</th>
        <th>Author</th>
        <th>Category</th>
        <th>Quantity</th>
    </tr>

    <?php while ($book = mysqli_fetch_assoc($result)) { ?>

    <tr>
        <td><?php echo $book['id']; ?></td>
        <td><?php echo $book['book_name']; ?></td>
        <td><?php echo $book['author']; ?></td>
        <td><?php echo $book['category']; ?></td>
        <td><?php echo $book['quantity']; ?></td>
    </tr>

    <?php } ?>

</table>

</body>
</html>