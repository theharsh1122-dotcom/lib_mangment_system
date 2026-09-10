<?php

session_start();

require_once "../config/database.php";

$search = "";

if (isset($_GET['search'])) {
    $search = $_GET['search'];
}

$query = "SELECT * FROM students 
          WHERE name LIKE '%$search%'
          OR father_name LIKE '%$search%'
          OR enrollment_no LIKE '%$search%'
          OR email LIKE '%$search%'
          ORDER BY id DESC";

$result = mysqli_query($conn, $query);

$count_query = "SELECT COUNT(*) AS total FROM students";
$count_result = mysqli_query($conn, $count_query);
$count_data = mysqli_fetch_assoc($count_result);

$total_students = $count_data['total'];

?>

<!DOCTYPE html>
<html>

<head>

    <title>Students</title>

    <link rel="stylesheet" href="../assets/css/student.css">

</head>

<body>

<div class="top-section">

    <div>
        <h1>Students</h1>
        <p>Manage all registered students</p>
    </div>

    <div class="total-box">
        <span>Total Students</span>
        <strong><?php echo $total_students; ?></strong>
    </div>

</div>


<div class="search-section">

    <form method="GET">

        <input
            type="text"
            name="search"
            placeholder="Search student..."
            value="<?php echo htmlspecialchars($search); ?>"
        >

        <button type="submit">Search</button>

        <a href="student.php">Reset</a>

    </form>

</div>


<div class="table-container">

<table>

    <tr>

        <th>ID</th>
        <th>Name</th>
        <th>Father Name</th>
        <th>Class / Course</th>
        <th>Semester</th>
        <th>Enrollment No</th>
        <th>Email</th>
        <th>Mobile</th>
        <th>Gender</th>
        <th>Category</th>
        <th>Join Date</th>
        <th>Action</th>

    </tr>


    <?php while ($student = mysqli_fetch_assoc($result)) { ?>

    <tr>

        <td><?php echo $student['id']; ?></td>

        <td><?php echo htmlspecialchars($student['name']); ?></td>

        <td><?php echo htmlspecialchars($student['father_name']); ?></td>

        <td><?php echo htmlspecialchars($student['class_course']); ?></td>

        <td><?php echo htmlspecialchars($student['semester']); ?></td>

        <td><?php echo htmlspecialchars($student['enrollment_no']); ?></td>

        <td><?php echo htmlspecialchars($student['email']); ?></td>

        <td><?php echo htmlspecialchars($student['mobile']); ?></td>

        <td><?php echo htmlspecialchars($student['gender']); ?></td>

        <td><?php echo htmlspecialchars($student['category']); ?></td>

        <td><?php echo htmlspecialchars($student['join_date']); ?></td>

        <td class="actions">

            <a href="student_edit.php?id=<?php echo $student['id']; ?>" class="edit">
                Edit
            </a>

            <a
                href="student_delete.php?id=<?php echo $student['id']; ?>"
                class="delete"
                onclick="return confirm('Are you sure you want to delete this student?');"
            >
                Delete
            </a>

        </td>

    </tr>

    <?php } ?>

</table>

</div>

</body>

</html>