<?php

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'librarian') {
    header("Location: ../auth/login.php");
    exit();
}

include "../config/database.php";

$query = "SELECT * FROM students ORDER BY id DESC";
$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students - Librarian</title>
    <link rel="stylesheet" href="../assets/css/librarian_student.css">
</head>

<body>

<div class="sidebar">

    <h2>📚 Library</h2>

    <a href="dashboard.php">🏠 Dashboard</a>
    <a href="books.php">📖 Books</a>
    <a href="student.php" class="active">👨‍🎓 Students</a>
    <a href="issued_book.php">📋 Issue Book</a>
    <a href="return_book.php">↩️ Return Book</a>
    <a href="reports.php">📊 Reports</a>

    <a href="../auth/logout.php" class="logout">🚪 Logout</a>

</div>

<div class="main">

    <div class="topbar">
        <div>
            <h1>Students</h1>
            <p>View registered students</p>
        </div>

        <div class="profile">
            <div class="avatar">L</div>
            <span><?php echo $_SESSION['username']; ?></span>
        </div>
    </div>

    <div class="student-box">

        <div class="box-header">
            <h2>Student List</h2>

            <input
                type="text"
                id="searchInput"
                placeholder="Search student..."
                onkeyup="searchStudents()"
            >
        </div>

        <div class="table-container">

            <table id="studentTable">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Father Name</th>
                        <th>Course</th>
                        <th>Semester</th>
                        <th>Enrollment No</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Gender</th>
                        <th>Category</th>
                    </tr>
                </thead>

                <tbody>

                <?php while ($row = mysqli_fetch_assoc($result)) { ?>

                    <tr>

                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['father_name']; ?></td>
                        <td><?php echo $row['course']; ?></td>
                        <td><?php echo $row['semester']; ?></td>
                        <td><?php echo $row['enrollment_no']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['mobile']; ?></td>
                        <td><?php echo $row['gender']; ?></td>
                        <td><?php echo $row['category']; ?></td>

                    </tr>

                <?php } ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<script>

function searchStudents() {

    let input = document.getElementById("searchInput");
    let filter = input.value.toLowerCase();
    let table = document.getElementById("studentTable");
    let rows = table.getElementsByTagName("tr");

    for (let i = 1; i < rows.length; i++) {

        let text = rows[i].textContent.toLowerCase();

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