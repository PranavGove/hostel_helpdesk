<?php 
require 'includes/config.php';
if (!isset($_SESSION['user']) || $_SESSION['user']['name'] != 'admin') {
    header('Location: login.php');
    exit();
}

// Handle complaint deletion
if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    $stmt = $db->prepare("DELETE FROM complaints WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    header("Location: admin_dashboard.php?dept=" . urlencode($_GET['dept'] ?? ''));
    exit();
}

$departments = ["Electrical", "Plumbing", "IT", "Furniture", "Housekeeping", "Others"];
$current_dept = isset($_GET['dept']) ? sanitize($_GET['dept']) : $departments[0];

// Get complaints for selected department
$stmt = $db->prepare("SELECT c.*, u.name, u.room_number, u.mobile 
                      FROM complaints c JOIN users u ON c.user_id = u.id 
                      WHERE c.department = ?");
$stmt->bind_param("s", $current_dept);
$stmt->execute();
$complaints = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <h1>Admin Dashboard</h1>
        <a href="logout.php">Logout</a>
    </header>
    
    <div class="admin-container">
        <nav class="dept-nav">
            <?php foreach ($departments as $dept): ?>
                <a href="?dept=<?php echo urlencode($dept); ?>" 
                   class="<?php echo $dept == $current_dept ? 'active' : ''; ?>">
                   <?php echo $dept; ?>
                </a>
            <?php endforeach; ?>
        </nav>
        
        <div class="complaints-list">
            <h2><?php echo $current_dept; ?> Complaints</h2>
            <?php if (empty($complaints)): ?>
                <p class="info">No complaints in this department.</p>
            <?php else: ?>
                <table>
                    <tr>
                        <th>Room</th>
                        <th>Name</th>
                        <th>Complaint</th>
                        <th>Solution</th>
                        <th>Mobile</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                    <?php foreach ($complaints as $c): ?>
                    <tr>
                        <td><?php echo $c['room_number']; ?></td>
                        <td><?php echo $c['name']; ?></td>
                        <td><?php echo $c['complaint_text']; ?></td>
                        <td><?php echo $c['solution']; ?></td>
                        <td><?php echo $c['mobile']; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($c['created_at'])); ?></td>
                        <td>
                            <a href="?dept=<?php echo urlencode($current_dept); ?>&delete_id=<?php echo $c['id']; ?>" 
                               class="delete-btn"
                               onclick="return confirm('Are you sure you want to delete this complaint?')">
                               Delete
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>