<?php require 'includes/functions.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="form-container">
        <h2>Student Registration</h2>
        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = sanitize($_POST['name']);
            $room = sanitize($_POST['room']);
            $mobile = sanitize($_POST['mobile']);
            $password = sanitize($_POST['password']);
            
            $result = registerUser($name, $room, $mobile, $password);
            if ($result === true) {
                echo "<p class='success'>Registration successful! <a href='login.php'>Login now</a></p>";
            } else {
                echo "<p class='error'>Error: $result</p>";
            }
        }
        ?>
        <form method="POST">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="text" name="room" placeholder="Room Number" required>
            <input type="tel" name="mobile" placeholder="Mobile Number" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Register</button>
        </form>
        <p>Already registered? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>