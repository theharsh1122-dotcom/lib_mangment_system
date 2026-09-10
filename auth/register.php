<?php 

require_once "../config/database.php"; 

if (isset($_POST['register'])) { 

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

    $query = "INSERT INTO students 
    (name, father_name, class_course, semester, enrollment_no, email, mobile, address, dob, gender, category, join_date)
    VALUES 
    ('$name', '$father_name', '$course', '$semester', '$enrollment_no', '$email', '$mobile', '$address', '$dob', '$gender', '$category', '$join_date')"; 

    if (mysqli_query($conn, $query)) { 
        header("Location: ../admin/student.php");
        exit();
    } else { 
        echo "Registration failed!"; 
    } 
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Registration</title>
    <link rel="stylesheet" href="../assets/css/register.css">
</head>
<body>

<div class="register-container">

    <h1>Student Registration</h1>
    <p>Register a new student in the library system</p>

 <form method="POST">

    <div>
        <label>Student Name</label>
        <input type="text" name="name" placeholder="Enter student name" required>
    </div>

    <div>
        <label>Father Name</label>
        <input type="text" name="father_name" placeholder="Enter father name" required>
    </div>

    <select name="class_course" required>
    <option value="">Select Class / Course</option>

    <optgroup label="School">
        <option value="Class Nursery">Nursery</option>
        <option value="Class LKG">LKG</option>
        <option value="Class UKG">UKG</option>
        <option value="Class 1">Class 1</option>
        <option value="Class 2">Class 2</option>
        <option value="Class 3">Class 3</option>
        <option value="Class 4">Class 4</option>
        <option value="Class 5">Class 5</option>
        <option value="Class 6">Class 6</option>
        <option value="Class 7">Class 7</option>
        <option value="Class 8">Class 8</option>
        <option value="Class 9">Class 9</option>
        <option value="Class 10">Class 10</option>
        <option value="Class 11">Class 11</option>
        <option value="Class 12">Class 12</option>
    </optgroup>

    <optgroup label="Undergraduate Courses">
        <option value="BCA">BCA</option>
        <option value="BBA">BBA</option>
        <option value="B.Com">B.Com</option>
        <option value="BA">BA</option>
        <option value="B.Sc">B.Sc</option>
        <option value="B.Tech">B.Tech</option>
        <option value="B.E">B.E</option>
        <option value="B.Pharm">B.Pharm</option>
        <option value="B.Sc Nursing">B.Sc Nursing</option>
        <option value="B.Ed">B.Ed</option>
        <option value="LLB">LLB</option>
    </optgroup>

    <optgroup label="Postgraduate Courses">
        <option value="MCA">MCA</option>
        <option value="MBA">MBA</option>
        <option value="M.Com">M.Com</option>
        <option value="MA">MA</option>
        <option value="M.Sc">M.Sc</option>
        <option value="M.Tech">M.Tech</option>
        <option value="M.E">M.E</option>
        <option value="M.Pharm">M.Pharm</option>
        <option value="M.Ed">M.Ed</option>
        <option value="LLM">LLM</option>
    </optgroup>

    <optgroup label="Diploma / Other">
        <option value="Polytechnic">Polytechnic</option>
        <option value="ITI">ITI</option>
        <option value="DCA">DCA</option>
        <option value="PGDCA">PGDCA</option>
        <option value="ADCA">ADCA</option>
        <option value="Diploma in Engineering">Diploma in Engineering</option>
        <option value="Nursing">Nursing</option>
        <option value="Other">Other</option>
    </optgroup>
</select>

    <div>
        <label>Semester</label>
        <input type="text" name="semester" placeholder="Enter semester" required>
    </div>

    <div>
        <label>Enrollment No</label>
        <input type="text" name="enrollment_no" placeholder="Enter enrollment number">
    </div>

    <div>
        <label>Email</label>
        <input type="email" name="email" placeholder="Enter email address" required>
    </div>

    <div>
        <label>Mobile Number</label>
        <input type="text" name="mobile" placeholder="Enter mobile number" required>
    </div>

    <div>
        <label>Date of Birth</label>
        <input type="date" name="dob" required>
    </div>

    <div>
        <label>Gender</label>
        <select name="gender" required>
            <option value="">Select gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select>
    </div>

    <div>
        <label>Category</label>
        <input type="text" name="category" placeholder="Enter category" required>
    </div>

    <div>
        <label>Join Date</label>
        <input type="date" name="join_date" required>
    </div>

    <div class="full-width">
        <label>Address</label>
        <textarea name="address" placeholder="Enter complete address" required></textarea>
    </div>

    <button type="submit" name="register">Register Student</button>

</form>

</div>

</body>
</html>