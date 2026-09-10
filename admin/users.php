<?php

session_start();

include "../config/database.php";

$query = "SELECT * FROM users ORDER BY id DESC";
$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Users</title>
</head>
<body>

<h1>Users</h1>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Username</th>
        <th>Role</th>
    </tr>

    <?php while ($user = mysqli_fetch_assoc($result)) { ?>

    <tr>
        <td><?php echo $user['id']; ?></td>
        <td><?php echo $user['name']; ?></td>
        <td><?php echo $user['email']; ?></td>
        <td><?php echo $user['username']; ?></td>
        <td><?php echo $user['role']; ?></td>
    </tr>

    <?php } ?>

</table>

</body>
</html>