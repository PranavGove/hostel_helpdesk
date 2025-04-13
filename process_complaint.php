<?php
require __DIR__ . '/includes/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user'])) {
    // 1. Validate input
    $complaint = trim($_POST['complaint'] ?? '');
    if (empty($complaint)) {
        die("Please describe your complaint");
    }
    $complaint = sanitize($complaint);

    // 2. Prepare Python command
    $pythonPath = 'C:\\Users\\prana\\AppData\\Local\\Programs\\Python\\Python312\\python.exe';
    $scriptPath = __DIR__ . '\\python\\expert_system.py';

    if (!file_exists($pythonPath)) die("Python executable not found");
    if (!file_exists($scriptPath)) die("Python script not found");

    $command = escapeshellarg($pythonPath) . ' ' . escapeshellarg($scriptPath) . ' ' . escapeshellarg($complaint);
    $solution = shell_exec($command) ?? "Others|System Error|Our staff will contact you soon";

    // 3. Parse the solution
    $parts = explode('|', trim($solution), 3);
    $parts = array_pad($parts, 3, '');
    
    $department = sanitize($parts[0] ?: "Others");
    $sub_problem = sanitize($parts[1] ?: "General Issue");
    $solution_text = str_replace(["\r", "\n"], ' ', $parts[2] ?: "Our staff will contact you soon");
    $solution_text = preg_replace('/\s+/', ' ', $solution_text);

    // 4. Check if form was submitted with solution response
    if (isset($_POST['solution_response'])) {
        // Only save to DB if answer was "No"
        if ($_POST['solution_response'] == 'no') {
            $stmt = $db->prepare("INSERT INTO complaints (user_id, complaint_text, department, solution, is_resolved) 
                                VALUES (?, ?, ?, ?, 0)");
            $stmt->bind_param("isss", $_SESSION['user']['id'], $complaint, $department, $solution_text);
            $stmt->execute();
        }
        
        // Redirect back to dashboard
        header('Location: student_dashboard.php?solved=' . $_POST['solution_response']);
        exit();
    }

    // 5. Display solution and feedback form
    echo '<!DOCTYPE html>
    <html>
    <head>
        <title>Complaint Solution</title>
        <link rel="stylesheet" href="assets/css/style.css">
    </head>
    <body>
        <header>
            <h1>Complaint Solution</h1>
            <a href="student_dashboard.php">Back to Dashboard</a>
        </header>
        
        <div class="solution">
            <h3>Suggested Solution (' . htmlspecialchars($department) . ' - ' . 
             htmlspecialchars($sub_problem) . ')</h3>
            <div class="solution-steps">' . htmlspecialchars($solution_text) . '</div>
            <form method="POST" action="process_complaint.php">
                <input type="hidden" name="complaint" value="' . htmlspecialchars($complaint) . '">
                <div class="feedback">
                    <p>Did this solve your problem?</p>
                    <button type="submit" name="solution_response" value="yes" class="btn">Yes</button>
                    <button type="submit" name="solution_response" value="no" class="btn">No</button>
                </div>
            </form>
        </div>
    </body>
    </html>';
    exit();
}

header('Location: student_dashboard.php');
?>