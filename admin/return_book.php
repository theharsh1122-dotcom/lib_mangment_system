<?php

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

include "../config/database.php";

$success = "";
$error = "";

/* Return Book */

if (isset($_POST['return_book'])) {

    $issued_id = (int)$_POST['issued_id'];
    $return_date = $_POST['return_date'];

    $result = mysqli_query($conn, "
        SELECT *
        FROM issued_books
        WHERE id=$issued_id AND status='Issued'
    ");

    if (mysqli_num_rows($result) == 0) {

        $error = "Issued book not found or already returned!";

    } else {

        $issued = mysqli_fetch_assoc($result);

        $due_date = new DateTime($issued['due_date']);
        $return = new DateTime($return_date);

        $fine = 0;

        if ($return > $due_date) {

            $late_days = $due_date->diff($return)->days;
            $fine = $late_days * 5;

        }

        $query = "
            UPDATE issued_books SET
            return_date='$return_date',
            status='Returned',
            fine=$fine
            WHERE id=$issued_id
        ";

        if (mysqli_query($conn, $query)) {

            mysqli_query(
                $conn,
                "UPDATE books
                 SET quantity = quantity + 1
                 WHERE id=" . $issued['book_id']
            );

            $success = "Book returned successfully!";

        } else {

            $error = "Book return failed!";

        }
    }
}


/* Issued Books */

$issued_result = mysqli_query($conn, "
    SELECT
        issued_books.id,
        issued_books.book_id,
        issued_books.student_id,
        issued_books.issue_date,
        issued_books.due_date,
        issued_books.return_date,
        issued_books.status,
        issued_books.fine,

        books.book_name,
        books.author,

        students.name AS student_name,
        students.enrollment_no

    FROM issued_books

    JOIN books
    ON issued_books.book_id = books.id

    JOIN students
    ON issued_books.student_id = students.id

    ORDER BY issued_books.id DESC
");


/* Currently Issued Books for Return Form */

$active_result = mysqli_query($conn, "
    SELECT
        issued_books.id,
        issued_books.book_id,
        issued_books.student_id,
        issued_books.issue_date,
        issued_books.due_date,

        books.book_name,
        books.author,

        students.name AS student_name,
        students.enrollment_no

    FROM issued_books

    JOIN books
    ON issued_books.book_id = books.id

    JOIN students
    ON issued_books.student_id = students.id

    WHERE issued_books.status='Issued'

    ORDER BY issued_books.id DESC
");

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Return Book</title>

    <link rel="stylesheet" href="../assets/css/admin_return_book.css">

</head>

<body>


<!-- Navbar -->

<div class="navbar">

    <div class="logo">
        📖 <span>RETURN BOOK</span>
    </div>

    <div class="nav-right">

        <a href="dashboard.php">
            🏠 Dashboard
        </a>

        <span class="divider">|</span>

        <span class="user">
            👤 <?php echo $_SESSION['username']; ?>
        </span>

    </div>

</div>


<div class="container">


    <!-- Page Heading -->

    <div class="page-heading">

        <div class="heading-icon">
            📖
        </div>

        <div>

            <h1>Return Book</h1>

            <p>Register and manage returned books</p>

        </div>

    </div>


    <!-- Messages -->

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


    <!-- Return Form -->

    <div class="form-box">

        <h2>Return Book Form</h2>


        <form method="POST">


            <div class="form-grid">


                <!-- Issued Book -->

                <div class="form-group">

                    <label>Book / Issue Record *</label>

                    <select name="issued_id" id="issued_id" required onchange="showBookDetails()">

                        <option value="">
                            Select Book
                        </option>

                        <?php while ($row = mysqli_fetch_assoc($active_result)) { ?>

                            <option
                                value="<?php echo $row['id']; ?>"
                                data-book="<?php echo htmlspecialchars($row['book_name']); ?>"
                                data-author="<?php echo htmlspecialchars($row['author']); ?>"
                                data-student="<?php echo htmlspecialchars($row['student_name']); ?>"
                                data-enrollment="<?php echo htmlspecialchars($row['enrollment_no']); ?>"
                                data-issue="<?php echo $row['issue_date']; ?>"
                                data-due="<?php echo $row['due_date']; ?>"
                            >

                                <?php
                                echo $row['book_name']
                                . " - "
                                . $row['student_name'];
                                ?>

                            </option>

                        <?php } ?>

                    </select>

                </div>


                <!-- Book Title -->

                <div class="form-group">

                    <label>Book Title</label>

                    <input
                        type="text"
                        id="book_name"
                        placeholder="Book title will appear here"
                        readonly
                    >

                </div>


                <!-- Author -->

                <div class="form-group">

                    <label>Author</label>

                    <input
                        type="text"
                        id="author"
                        placeholder="Author name will appear here"
                        readonly
                    >

                </div>


                <!-- Student -->

                <div class="form-group">

                    <label>Issued To (Student)</label>

                    <input
                        type="text"
                        id="student_name"
                        placeholder="Student name will appear here"
                        readonly
                    >

                </div>


                <!-- Enrollment -->

                <div class="form-group">

                    <label>Enrollment No.</label>

                    <input
                        type="text"
                        id="enrollment_no"
                        placeholder="Enrollment number will appear here"
                        readonly
                    >

                </div>


                <!-- Issue Date -->

                <div class="form-group">

                    <label>Issue Date</label>

                    <input
                        type="date"
                        id="issue_date"
                        readonly
                    >

                </div>


                <!-- Due Date -->

                <div class="form-group">

                    <label>Due Date</label>

                    <input
                        type="date"
                        id="due_date"
                        readonly
                    >

                </div>


                <!-- Return Date -->

                <div class="form-group">

                    <label>Return Date *</label>

                    <input
                        type="date"
                        name="return_date"
                        value="<?php echo date('Y-m-d'); ?>"
                        required
                    >

                </div>


                <!-- Fine -->

                <div class="form-group">

                    <label>Fine</label>

                    <input
                        type="text"
                        id="fine"
                        value="₹ 0.00"
                        readonly
                    >

                </div>


            </div>


            <div class="form-buttons">

                <button
                    type="submit"
                    name="return_book"
                    class="return-btn"
                >
                    ↩ Return Book
                </button>

                <button
                    type="reset"
                    class="reset-btn"
                    onclick="resetForm()"
                >
                    ↻ Reset
                </button>

            </div>


        </form>

    </div>


    <!-- Returned Books -->

    <div class="table-box">

        <div class="table-header">

            <h2>Returned Books List</h2>

            <input
                type="text"
                id="search"
                placeholder="🔍 Search..."
                onkeyup="searchTable()"
            >

        </div>


        <div class="table-wrapper">

            <table id="returnTable">

                <thead>

                    <tr>

                        <th>S. No.</th>

                        <th>Book</th>

                        <th>Author</th>

                        <th>Issued To</th>

                        <th>Enrollment No.</th>

                        <th>Issue Date</th>

                        <th>Due Date</th>

                        <th>Return Date</th>

                        <th>Fine</th>

                        <th>Status</th>

                        <th>Action</th>

                    </tr>

                </thead>


                <tbody>

                    <?php

                    $serial = 1;

                    while ($row = mysqli_fetch_assoc($issued_result)) {

                    ?>

                    <tr>

                        <td>
                            <?php echo $serial++; ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row['book_name']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row['author']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row['student_name']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row['enrollment_no']); ?>
                        </td>

                        <td>
                            <?php echo date("d/m/Y", strtotime($row['issue_date'])); ?>
                        </td>

                        <td>
                            <?php echo date("d/m/Y", strtotime($row['due_date'])); ?>
                        </td>

                        <td>

                            <?php

                            if ($row['return_date']) {

                                echo date(
                                    "d/m/Y",
                                    strtotime($row['return_date'])
                                );

                            } else {

                                echo "-";

                            }

                            ?>

                        </td>

                        <td>
                            ₹ <?php echo number_format($row['fine'], 2); ?>
                        </td>

                        <td>

                            <?php if ($row['status'] == 'Returned') { ?>

                                <span class="status returned">
                                    Returned
                                </span>

                            <?php } else { ?>

                                <span class="status issued">
                                    Issued
                                </span>

                            <?php } ?>

                        </td>

                        <td>

                            <div class="action-buttons">

                                <button
                                    class="view-btn"
                                    onclick="viewRecord(
                                        '<?php echo htmlspecialchars($row['book_name'], ENT_QUOTES); ?>',
                                        '<?php echo htmlspecialchars($row['student_name'], ENT_QUOTES); ?>',
                                        '<?php echo $row['status']; ?>'
                                    )"
                                >
                                    👁
                                </button>


                                <button
                                    class="print-btn"
                                    onclick="printRecord(
                                        '<?php echo htmlspecialchars($row['book_name'], ENT_QUOTES); ?>',
                                        '<?php echo htmlspecialchars($row['student_name'], ENT_QUOTES); ?>',
                                        '<?php echo $row['issue_date']; ?>',
                                        '<?php echo $row['due_date']; ?>',
                                        '<?php echo $row['return_date']; ?>',
                                        '<?php echo $row['fine']; ?>',
                                        '<?php echo $row['status']; ?>'
                                    )"
                                >
                                    🖨
                                </button>

                            </div>

                        </td>

                    </tr>

                    <?php } ?>

                </tbody>

            </table>

        </div>

    </div>


</div>


<script>


function showBookDetails() {

    let select = document.getElementById("issued_id");

    let option = select.options[select.selectedIndex];


    if (!option.value) {

        resetForm();

        return;

    }


    document.getElementById("book_name").value =
        option.getAttribute("data-book");

    document.getElementById("author").value =
        option.getAttribute("data-author");

    document.getElementById("student_name").value =
        option.getAttribute("data-student");

    document.getElementById("enrollment_no").value =
        option.getAttribute("data-enrollment");

    document.getElementById("issue_date").value =
        option.getAttribute("data-issue");

    document.getElementById("due_date").value =
        option.getAttribute("data-due");


    calculateFine();

}


function calculateFine() {

    let due = document.getElementById("due_date").value;

    let returnDate = document.querySelector(
        'input[name="return_date"]'
    ).value;


    if (!due || !returnDate) {

        document.getElementById("fine").value = "₹ 0.00";

        return;

    }


    let dueDate = new Date(due);

    let returnDateObj = new Date(returnDate);


    if (returnDateObj > dueDate) {

        let difference =
            returnDateObj - dueDate;

        let days =
            Math.ceil(
                difference /
                (1000 * 60 * 60 * 24)
            );

        let fine = days * 5;

        document.getElementById("fine").value =
            "₹ " + fine.toFixed(2);

    } else {

        document.getElementById("fine").value =
            "₹ 0.00";

    }

}


document.querySelector(
    'input[name="return_date"]'
).addEventListener(
    "change",
    calculateFine
);


function resetForm() {

    document.getElementById("book_name").value = "";
    document.getElementById("author").value = "";
    document.getElementById("student_name").value = "";
    document.getElementById("enrollment_no").value = "";
    document.getElementById("issue_date").value = "";
    document.getElementById("due_date").value = "";
    document.getElementById("fine").value = "₹ 0.00";

}


function searchTable() {

    let input =
        document.getElementById("search")
        .value
        .toLowerCase();

    let rows =
        document
        .getElementById("returnTable")
        .getElementsByTagName("tbody")[0]
        .getElementsByTagName("tr");


    for (let i = 0; i < rows.length; i++) {

        let text =
            rows[i].innerText.toLowerCase();

        rows[i].style.display =
            text.includes(input)
            ? ""
            : "none";

    }

}


function viewRecord(book, student, status) {

    alert(
        "Book: " + book +
        "\nStudent: " + student +
        "\nStatus: " + status
    );

}


function printRecord(
    book,
    student,
    issue,
    due,
    returnDate,
    fine,
    status
) {

    let printWindow =
        window.open("", "", "width=800,height=600");


    printWindow.document.write(`

        <html>

        <head>

            <title>Return Book Receipt</title>

            <style>

                body {
                    font-family: Arial, sans-serif;
                    padding: 40px;
                }

                h1 {
                    text-align: center;
                    color: #164e9b;
                }

                .box {
                    border: 1px solid #ddd;
                    padding: 25px;
                    margin-top: 30px;
                }

                table {
                    width: 100%;
                    border-collapse: collapse;
                }

                td {
                    padding: 12px;
                    border-bottom: 1px solid #ddd;
                }

                td:first-child {
                    font-weight: bold;
                    width: 35%;
                }

                .footer {
                    text-align: center;
                    margin-top: 40px;
                }

            </style>

        </head>

        <body>

            <h1>Library Management System</h1>

            <h2 style="text-align:center;">
                Book Return Receipt
            </h2>

            <div class="box">

                <table>

                    <tr>
                        <td>Book</td>
                        <td>${book}</td>
                    </tr>

                    <tr>
                        <td>Student</td>
                        <td>${student}</td>
                    </tr>

                    <tr>
                        <td>Issue Date</td>
                        <td>${issue}</td>
                    </tr>

                    <tr>
                        <td>Due Date</td>
                        <td>${due}</td>
                    </tr>

                    <tr>
                        <td>Return Date</td>
                        <td>${returnDate || "-"}</td>
                    </tr>

                    <tr>
                        <td>Fine</td>
                        <td>₹ ${fine}</td>
                    </tr>

                    <tr>
                        <td>Status</td>
                        <td>${status}</td>
                    </tr>

                </table>

            </div>

            <div class="footer">

                <p>Library Management System</p>

            </div>

        </body>

        </html>

    `);


    printWindow.document.close();

    printWindow.focus();

    printWindow.print();

    printWindow.close();

}


</script>


</body>

</html>