<?php 
require 'includes/config.php';
if (!isset($_SESSION['user']) || $_SESSION['user']['name'] == 'admin') {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <h1>Welcome, <?php echo $_SESSION['user']['name']; ?></h1>
        <a href="logout.php">Logout</a>
    </header>
    
    <div class="form-container">
        <h2>Raise a Complaint</h2>
        <form action="process_complaint.php" method="POST">
            <textarea name="complaint" placeholder="Describe your issue..." required></textarea>
            <button type="submit">Submit</button>
        </form>
        
        <?php if (isset($_GET['solved'])): ?>
            <div class="solution-response">
                <?php if ($_GET['solved'] == 'yes'): ?>
                    <p class="success">We're glad your problem was resolved!</p>
                <?php else: ?>
                    <p class="info">Our technician will contact you soon. Thank you for your patience.</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>