<?php

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

include "../config/database.php";

$message = "";
$message_type = "";

if (isset($_POST['add_student'])) {

    $name = trim($_POST['name']);
    $father_name = trim($_POST['father_name']);
    $course = trim($_POST['course']);
    $semester = trim($_POST['semester']);
    $enrollment_no = trim($_POST['enrollment_no']);
    $email = trim($_POST['email']);
    $mobile = trim($_POST['mobile']);
    $address = trim($_POST['address']);
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $category = $_POST['category'];
    $join_date = $_POST['join_date'];

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (
        $name == "" ||
        $father_name == "" ||
        $course == "" ||
        $semester == "" ||
        $enrollment_no == "" ||
        $email == "" ||
        $mobile == "" ||
        $address == "" ||
        $dob == "" ||
        $gender == "" ||
        $category == "" ||
        $join_date == "" ||
        $username == "" ||
        $password == ""
    ) {

        $message = "Please fill all fields.";
        $message_type = "error";

    } else {

        $name = mysqli_real_escape_string($conn, $name);
        $father_name = mysqli_real_escape_string($conn, $father_name);
        $course = mysqli_real_escape_string($conn, $course);
        $semester = mysqli_real_escape_string($conn, $semester);
        $enrollment_no = mysqli_real_escape_string($conn, $enrollment_no);
        $email = mysqli_real_escape_string($conn, $email);
        $mobile = mysqli_real_escape_string($conn, $mobile);
        $address = mysqli_real_escape_string($conn, $address);
        $dob = mysqli_real_escape_string($conn, $dob);
        $gender = mysqli_real_escape_string($conn, $gender);
        $category = mysqli_real_escape_string($conn, $category);
        $join_date = mysqli_real_escape_string($conn, $join_date);

        $username = mysqli_real_escape_string($conn, $username);
        $password = mysqli_real_escape_string($conn, $password);

        $check_student = mysqli_query(
            $conn,
            "SELECT id FROM students WHERE enrollment_no='$enrollment_no'"
        );

        $check_user = mysqli_query(
            $conn,
            "SELECT id FROM users WHERE username='$username' OR email='$email'"
        );

        if (mysqli_num_rows($check_student) > 0) {

            $message = "Enrollment number already exists.";
            $message_type = "error";

        } elseif (mysqli_num_rows($check_user) > 0) {

            $message = "Username or Email already exists.";
            $message_type = "error";

        } else {

            $user_query = "INSERT INTO users
                (name, email, username, password, role)
                VALUES
                ('$name', '$email', '$username', '$password', 'student')";

            if (mysqli_query($conn, $user_query)) {

                $user_id = mysqli_insert_id($conn);

                $student_query = "INSERT INTO students
                    (user_id, name, father_name, course, semester, enrollment_no, email, mobile, address, dob, gender, category, join_date)
                    VALUES
                    ('$user_id', '$name', '$father_name', '$course', '$semester', '$enrollment_no', '$email', '$mobile', '$address', '$dob', '$gender', '$category', '$join_date')";

                if (mysqli_query($conn, $student_query)) {

                    $message = "Student added successfully.";
                    $message_type = "success";

                } else {

                    mysqli_query(
                        $conn,
                        "DELETE FROM users WHERE id='$user_id'"
                    );

                    $message = "Student creation failed.";
                    $message_type = "error";
                }

            } else {

                $message = "Account creation failed.";
                $message_type = "error";
            }
        }
    }
}

$students = mysqli_query(
    $conn,
    "SELECT * FROM students ORDER BY id DESC"
);

$success = "";

if (isset($_GET['updated']) && $_GET['updated'] == 1) {
    $success = "Student updated successfully!";
}

if (isset($_GET['deleted']) && $_GET['deleted'] == 1) {
    $success = "Student deleted successfully!";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Manage Students - Library Management System</title>

    <link rel="stylesheet" href="../assets/css/admin_student.css">

</head>

<body>

<?php if ($success != "") { ?>

    <div class="success-message">
        <?php echo $success; ?>
    </div>

<?php } ?>

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

        <a href="books.php">

            <span>📖</span>

            Manage Books

        </a>

        <a href="student.php" class="active">

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

            <h1>Manage Students</h1>

            <p>
                Add, manage and organize library students
            </p>

        </div>

        <div class="profile">

            <div class="avatar">

                <?php
                echo strtoupper(
                    substr($_SESSION['username'], 0, 1)
                );
                ?>

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

        <div class="form-card">

            <div class="card-heading">

                <div>

                    <h2>Add New Student</h2>

                    <p>
                        Enter the student details below
                    </p>

                </div>

                <div class="student-icon">

                    👨‍🎓

                </div>

            </div>

            <form method="POST">

                <div class="form-grid">

                    <div class="form-group">

                        <label>Student Name</label>

                        <input
                            type="text"
                            name="name"
                            placeholder="Enter student name"
                            required
                        >

                    </div>

                    <div class="form-group">

                        <label>Father Name</label>

                        <input
                            type="text"
                            name="father_name"
                            placeholder="Enter father name"
                            required
                        >

                    </div>

                    <div class="form-group">

                        <label>Course</label>

                        <input
                            type="text"
                            name="course"
                            placeholder="e.g. BCA"
                            required
                        >

                    </div>

                    <div class="form-group">

                        <label>Semester</label>

                        <select name="semester" required>

                            <option value="">
                                Select Semester
                            </option>

                            <option value="1st Semester">
                                1st Semester
                            </option>

                            <option value="2nd Semester">
                                2nd Semester
                            </option>

                            <option value="3rd Semester">
                                3rd Semester
                            </option>

                            <option value="4th Semester">
                                4th Semester
                            </option>

                            <option value="5th Semester">
                                5th Semester
                            </option>

                            <option value="6th Semester">
                                6th Semester
                            </option>

                            <option value="7th Semester">
                                7th Semester
                            </option>

                            <option value="8th Semester">
                                8th Semester
                            </option>

                        </select>

                    </div>

                    <div class="form-group">

                        <label>Enrollment No.</label>

                        <input
                            type="text"
                            name="enrollment_no"
                            placeholder="Enter enrollment number"
                            required
                        >

                    </div>

                    <div class="form-group">

                        <label>Email</label>

                        <input
                            type="email"
                            name="email"
                            placeholder="Enter email address"
                            required
                        >

                    </div>

                    <div class="form-group">

                        <label>Mobile</label>

                        <input
                            type="text"
                            name="mobile"
                            placeholder="Enter mobile number"
                            maxlength="15"
                            required
                        >

                    </div>

                    <div class="form-group">

                        <label>Date of Birth</label>

                        <input
                            type="date"
                            name="dob"
                            required
                        >

                    </div>

                    <div class="form-group">

                        <label>Gender</label>

                        <select name="gender" required>

                            <option value="">
                                Select Gender
                            </option>

                            <option value="Male">
                                Male
                            </option>

                            <option value="Female">
                                Female
                            </option>

                            <option value="Other">
                                Other
                            </option>

                        </select>

                    </div>

                    <div class="form-group">

                        <label>Category</label>

                        <select name="category" required>

                            <option value="">
                                Select Category
                            </option>

                            <option value="General">
                                General
                            </option>

                            <option value="OBC">
                                OBC
                            </option>

                            <option value="SC">
                                SC
                            </option>

                            <option value="ST">
                                ST
                            </option>

                            <option value="EWS">
                                EWS
                            </option>

                        </select>

                    </div>

                    <div class="form-group">

                        <label>Join Date</label>

                        <input
                            type="date"
                            name="join_date"
                            value="<?php echo date('Y-m-d'); ?>"
                            required
                        >

                    </div>

                    <div class="form-group">

                        <label>Username</label>

                        <input
                            type="text"
                            name="username"
                            placeholder="Create username"
                            required
                        >

                    </div>

                    <div class="form-group">

                        <label>Password</label>

                        <input
                            type="password"
                            name="password"
                            placeholder="Create password"
                            required
                        >

                    </div>

                    <div class="form-group full-width">

                        <label>Address</label>

                        <textarea
                            name="address"
                            rows="3"
                            placeholder="Enter complete address"
                            required
                        ></textarea>

                    </div>

                </div>

                <div class="form-actions">

                    <button
                        type="reset"
                        class="reset-btn"
                    >
                        Clear
                    </button>

                    <button
                        type="submit"
                        name="add_student"
                        class="add-btn"
                    >
                        + Add Student
                    </button>

                </div>

            </form>

        </div>

        <div class="students-card">

            <div class="students-header">

                <div>

                    <h2>All Students</h2>

                    <p>
                        Manage all registered library students
                    </p>

                </div>

                <div class="search-box">

                    <input
                        type="text"
                        id="studentSearch"
                        placeholder="Search students..."
                        onkeyup="searchStudents()"
                    >

                </div>

            </div>

            <div class="table-wrapper">

                <table id="studentsTable">

                    <thead>

                        <tr>

                            <th>#</th>

                            <th>Student</th>

                            <th>Enrollment No.</th>

                            <th>Course</th>

                            <th>Semester</th>

                            <th>Email</th>

                            <th>Mobile</th>

                            <th>Category</th>

                            <th>Action</th>

                        </tr>

                    </thead>

                    <tbody>

                    <?php

                    $count = 1;

                    if (mysqli_num_rows($students) > 0) {

                        while ($student = mysqli_fetch_assoc($students)) {

                    ?>

                        <tr>

                            <td>

                                <?php echo $count++; ?>

                            </td>

                            <td>

                                <div class="student-name">

                                    <div class="student-avatar">

                                        <?php
                                        echo strtoupper(
                                            substr($student['name'], 0, 1)
                                        );
                                        ?>

                                    </div>

                                    <div>

                                        <strong>

                                            <?php
                                            echo htmlspecialchars(
                                                $student['name']
                                            );
                                            ?>

                                        </strong>

                                        <small>

                                            <?php
                                            echo htmlspecialchars(
                                                $student['father_name']
                                            );
                                            ?>

                                        </small>

                                    </div>

                                </div>

                            </td>

                            <td>

                                <span class="enrollment">

                                    <?php
                                    echo htmlspecialchars(
                                        $student['enrollment_no']
                                    );
                                    ?>

                                </span>

                            </td>

                            <td>

                                <?php
                                echo htmlspecialchars(
                                    $student['course']
                                );
                                ?>

                            </td>

                            <td>

                                <span class="semester">

                                    <?php
                                    echo htmlspecialchars(
                                        $student['semester']
                                    );
                                    ?>

                                </span>

                            </td>

                            <td>

                                <?php
                                echo htmlspecialchars(
                                    $student['email']
                                );
                                ?>

                            </td>

                            <td>

                                <?php
                                echo htmlspecialchars(
                                    $student['mobile']
                                );
                                ?>

                            </td>

                            <td>

                                <span class="category">

                                    <?php
                                    echo htmlspecialchars(
                                        $student['category']
                                    );
                                    ?>

                                </span>

                            </td>

                            <td>

                                <div class="actions">

                                    <a
                                        href="edit_student.php?id=<?php echo $student['id']; ?>"
                                        class="edit-btn"
                                    >
                                        Edit
                                    </a>

                                    <a
                                        href="delete_student.php?id=<?php echo $student['id']; ?>"
                                        class="delete-btn"
                                        onclick="return confirm('Are you sure you want to delete this student?');"
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

                            <td
                                colspan="9"
                                class="no-students"
                            >

                                No students found.
                                Add your first student above.

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

function searchStudents() {

    let input = document.getElementById("studentSearch");

    let filter = input.value.toLowerCase();

    let table = document.getElementById("studentsTable");

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