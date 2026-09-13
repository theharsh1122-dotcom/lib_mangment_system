<?php

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

include "../config/database.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: student.php");
    exit();
}

$id = (int)$_GET['id'];

$result = mysqli_query($conn, "SELECT * FROM students WHERE id=$id");

if (mysqli_num_rows($result) == 0) {
    header("Location: student.php");
    exit();
}

$student = mysqli_fetch_assoc($result);

if (isset($_POST['update_student'])) {

    $name = $_POST['name'];
    $father_name = $_POST['father_name'];
    $course = $_POST['course'];
    $semester = $_POST['semester'];
    $enrollment_no = $_POST['enrollment_no'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $address = $_POST['address'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $category = $_POST['category'];
    $join_date = $_POST['join_date'];

    $query = "UPDATE students SET
        name='$name',
        father_name='$father_name',
        course='$course',
        semester='$semester',
        enrollment_no='$enrollment_no',
        email='$email',
        mobile='$mobile',
        address='$address',
        dob='$dob',
        gender='$gender',
        category='$category',
        join_date='$join_date'
        WHERE id=$id";

    if (mysqli_query($conn, $query)) {
        header("Location: student.php?updated=1");
        exit();
    } else {
        $error = "Student update failed!";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Student</title>
    <link rel="stylesheet" href="../assets/css/edit_student.css">
</head>

<body>

<div class="container">

    <h1>Edit Student</h1>

    <?php if (isset($error)) { ?>
        <p class="error"><?php echo $error; ?></p>
    <?php } ?>

    <form method="POST">

        <input type="text" name="name"
               value="<?php echo $student['name']; ?>"
               placeholder="Student Name" required>

        <input type="text" name="father_name"
               value="<?php echo $student['father_name']; ?>"
               placeholder="Father Name" required>

        <input type="text" name="course"
               value="<?php echo $student['course']; ?>"
               placeholder="Course" required>

        <input type="text" name="semester"
               value="<?php echo $student['semester']; ?>"
               placeholder="Semester" required>

        <input type="text" name="enrollment_no"
               value="<?php echo $student['enrollment_no']; ?>"
               placeholder="Enrollment Number" required>

        <input type="email" name="email"
               value="<?php echo $student['email']; ?>"
               placeholder="Email" required>

        <input type="text" name="mobile"
               value="<?php echo $student['mobile']; ?>"
               placeholder="Mobile" required>

        <textarea name="address"
                  placeholder="Address"
                  required><?php echo $student['address']; ?></textarea>

        <label>Date of Birth</label>
        <input type="date" name="dob"
               value="<?php echo $student['dob']; ?>"
               required>

        <select name="gender" required>
            <option value="">Select Gender</option>
            <option value="Male" <?php if ($student['gender'] == 'Male') echo 'selected'; ?>>Male</option>
            <option value="Female" <?php if ($student['gender'] == 'Female') echo 'selected'; ?>>Female</option>
            <option value="Other" <?php if ($student['gender'] == 'Other') echo 'selected'; ?>>Other</option>
        </select>

        <input type="text" name="category"
               value="<?php echo $student['category']; ?>"
               placeholder="Category" required>

        <label>Join Date</label>
        <input type="date" name="join_date"
               value="<?php echo $student['join_date']; ?>"
               required>

        <button type="submit" name="update_student">
            Update Student
        </button>

    </form>

    <a href="student.php" class="back-btn">← Back to Students</a>

</div>

</body>
</html>