<?php

require_once "../config/database.php";

if (!isset($_GET['id'])) {
    header("Location: student.php");
    exit();
}

$id = $_GET['id'];

$query = "SELECT * FROM students WHERE id=$id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    header("Location: student.php");
    exit();
}

$student = mysqli_fetch_assoc($result);

if (isset($_POST['update'])) {

    $name = $_POST['name'];
    $father_name = $_POST['father_name'];
    $course = $_POST['class_course'];
    $semester = $_POST['semester'];
    $enrollment_no = $_POST['enrollment_no'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $address = $_POST['address'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $category = $_POST['category'];
    $join_date = $_POST['join_date'];

    $update_query = "UPDATE students SET
        name='$name',
        father_name='$father_name',
        class_course='$course',
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

    if (mysqli_query($conn, $update_query)) {
        header("Location: student.php");
        exit();
    } else {
        echo "Update failed!";
    }
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Edit Student</title>

    <link rel="stylesheet" href="../assets/css/student_edit.css">

</head>

<body>

<div class="form-container">

    <h1>Edit Student</h1>

    <form method="POST">

        <label>Name</label>
        <input type="text" name="name"
        value="<?php echo htmlspecialchars($student['name']); ?>" required>

        <label>Father Name</label>
        <input type="text" name="father_name"
        value="<?php echo htmlspecialchars($student['father_name']); ?>" required>

        <label>Class / Course</label>
        <input type="text" name="class_course"
        value="<?php echo htmlspecialchars($student['class_course']); ?>" required>

        <label>Semester</label>
        <input type="text" name="semester"
        value="<?php echo htmlspecialchars($student['semester']); ?>" required>

        <label>Enrollment No</label>
        <input type="text" name="enrollment_no"
        value="<?php echo htmlspecialchars($student['enrollment_no']); ?>" required>

        <label>Email</label>
        <input type="email" name="email"
        value="<?php echo htmlspecialchars($student['email']); ?>" required>

        <label>Mobile</label>
        <input type="text" name="mobile"
        value="<?php echo htmlspecialchars($student['mobile']); ?>" required>

        <label>Address</label>
        <textarea name="address" required><?php echo htmlspecialchars($student['address']); ?></textarea>

        <label>Date of Birth</label>
        <input type="date" name="dob"
        value="<?php echo htmlspecialchars($student['dob']); ?>" required>

        <label>Gender</label>

        <select name="gender" required>

            <option value="Male" <?php if ($student['gender'] == "Male") echo "selected"; ?>>
                Male
            </option>

            <option value="Female" <?php if ($student['gender'] == "Female") echo "selected"; ?>>
                Female
            </option>

            <option value="Other" <?php if ($student['gender'] == "Other") echo "selected"; ?>>
                Other
            </option>

        </select>

        <label>Category</label>

        <select name="category" required>

            <option value="General" <?php if ($student['category'] == "General") echo "selected"; ?>>
                General
            </option>

            <option value="OBC" <?php if ($student['category'] == "OBC") echo "selected"; ?>>
                OBC
            </option>

            <option value="SC" <?php if ($student['category'] == "SC") echo "selected"; ?>>
                SC
            </option>

            <option value="ST" <?php if ($student['category'] == "ST") echo "selected"; ?>>
                ST
            </option>

        </select>

        <label>Join Date</label>

        <input type="date" name="join_date"
        value="<?php echo htmlspecialchars($student['join_date']); ?>" required>

        <div class="buttons">

            <button type="submit" name="update">
                Update Student
            </button>

            <a href="student.php">
                Cancel
            </a>

        </div>

    </form>

</div>

</body>

</html>