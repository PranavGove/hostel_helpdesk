<?php 
require 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = sanitize($_POST['name']);
    $password = sanitize($_POST['password']);
    
    if (loginUser($name, $password)) {
        header($name == 'admin' ? 'Location: admin_dashboard.php' : 'Location: student_dashboard.php');
        exit();
    } else {
        $error = "Invalid credentials";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="form-container">
        <h2>Login</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <input type="text" name="name" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <p>New user? <a href="register.php">Register here</a></p>
    </div>
</body>
</html>