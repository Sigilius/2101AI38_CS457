<?php
session_start();
require_once 'config.php';

// Check if user is logged in and has appropriate role
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'editor')) {
    $_SESSION['error'] = "You don't have permission to add students";
    header("Location: dashboard.php");
    exit;
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $roll = $_POST['roll'];
    $name = $_POST['name'];
    $age = $_POST['age'];
    $branch = $_POST['branch'];
    $hometown = $_POST['hometown'];
    
    $stmt = $pdo->prepare("INSERT INTO stud_info (roll, name, age, branch, hometown) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$roll, $name, $age, $branch, $hometown]);
    
    $_SESSION['message'] = "Student added successfully";
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
        }
        .container {
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }
        label {
            display: block;
            font-weight: 600;
            margin-bottom: 5px;
            color: #555;
        }
        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
        }
        .button {
            background: #28a745;
            color: white;
            padding: 12px;
            width: 100%;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }
        .button:hover {
            background: #218838;
        }
        p {
            margin-top: 15px;
            font-size: 14px;
            color: #333;
        }
        a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add New Student</h2>
        <form method="post">
            <div class="form-group">
                <label for="roll">Roll Number</label>
                <input type="number" id="roll" name="roll" required>
            </div>
            
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" required>
            </div>
            
            <div class="form-group">
                <label for="age">Age</label>
                <input type="number" id="age" name="age">
            </div>
            
            <div class="form-group">
                <label for="branch">Branch</label>
                <input type="text" id="branch" name="branch">
            </div>
            
            <div class="form-group">
                <label for="hometown">Hometown</label>
                <input type="text" id="hometown" name="hometown">
            </div>
            
            <button type="submit" class="button">Add Student</button>
        </form>
        <p><a href="dashboard.php">Back to Dashboard</a></p>
    </div>
</body>
</html>
