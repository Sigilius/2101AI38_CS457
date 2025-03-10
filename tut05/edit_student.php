<?php
session_start();
require_once 'config.php';

// Check if user is logged in and has appropriate role
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'editor')) {
    $_SESSION['error'] = "You don't have permission to edit students";
    header("Location: dashboard.php");
    exit;
}

$roll = $_GET['roll'];
$stmt = $pdo->prepare("SELECT * FROM stud_info WHERE roll = ?");
$stmt->execute([$roll]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $branch = $_POST['branch'];
    $hometown = $_POST['hometown'];
    
    $stmt = $pdo->prepare("UPDATE stud_info SET name = ?, age = ?, branch = ?, hometown = ? WHERE roll = ?");
    $stmt->execute([$name, $age, $branch, $hometown, $roll]);
    
    $_SESSION['message'] = "Student updated successfully";
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Student</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
        .container { max-width: 500px; margin: 0 auto; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input { width: 100%; padding: 8px; }
        .button { background-color: #4CAF50; color: white; padding: 10px 15px; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Student</h2>
        <form method="post">
            <div class="form-group">
                <label>Roll Number</label>
                <input type="number" value="<?= $student['roll'] ?>" readonly>
            </div>
            
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="<?= $student['name'] ?>" required>
            </div>
            
            <div class="form-group">
                <label for="age">Age</label>
                <input type="number" id="age" name="age" value="<?= $student['age'] ?>">
            </div>
            
            <div class="form-group">
                <label for="branch">Branch</label>
                <input type="text" id="branch" name="branch" value="<?= $student['branch'] ?>">
            </div>
            
            <div class="form-group">
                <label for="hometown">Hometown</label>
                <input type="text" id="hometown" name="hometown" value="<?= $student['hometown'] ?>">
            </div>
            
            <button type="submit" class="button">Update Student</button>
        </form>
        <p><a href="dashboard.php">Back to Dashboard</a></p>
    </div>
</body>
</html>
