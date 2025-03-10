<?php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];

// Fetch student data
$stmt = $pdo->query("SELECT * FROM stud_info");
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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
            color: white;
            flex-direction: column;
        }
        .container {
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 1000px;
            text-align: center;
            color: #333;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .header h2 {
            font-size: 24px;
        }
        .button {
            background: #28a745;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        .button:hover {
            background: #218838;
        }
        .button-red {
            background: #dc3545;
        }
        .button-red:hover {
            background: #c82333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .query-box {
            margin-top: 20px;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 8px;
            text-align: left;
        }
        textarea {
            width: 100%;
            height: 100px;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Welcome, <?= htmlspecialchars($username) ?> (<?= htmlspecialchars($role) ?>)</h2>
            <div>
                <?php if ($role === 'admin'): ?>
                    <a href="manage_users.php" class="button">Manage Users</a>
                <?php endif; ?>
                <a href="logout.php" class="button button-red">Logout</a>
            </div>
        </div>
        
        <h3>Student Information</h3>
        <table>
            <thead>
                <tr>
                    <th>Roll</th>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Branch</th>
                    <th>Hometown</th>
                    <?php if ($role === 'admin' || $role === 'editor'): ?>
                        <th>Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?= htmlspecialchars($student['roll']) ?></td>
                        <td><?= htmlspecialchars($student['name']) ?></td>
                        <td><?= htmlspecialchars($student['age']) ?></td>
                        <td><?= htmlspecialchars($student['branch']) ?></td>
                        <td><?= htmlspecialchars($student['hometown']) ?></td>
                        <?php if ($role === 'admin' || $role === 'editor'): ?>
                            <td>
                                <a href="edit_student.php?roll=<?= $student['roll'] ?>" class="button">Edit</a>
                                <?php if ($role === 'admin'): ?>
                                    <a href="delete_student.php?roll=<?= $student['roll'] ?>" class="button button-red" onclick="return confirm('Are you sure?')">Delete</a>
                                <?php endif; ?>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <?php if ($role === 'admin' || $role === 'editor'): ?>
            <p><a href="add_student.php" class="button">Add New Student</a></p>
        <?php endif; ?>
        
        <?php if ($role === 'admin'): ?>
            <div class="query-box">
                <h3>Run SQL Query (Admin Only)</h3>
                <form method="post" action="run_query.php">
                    <textarea name="query" placeholder="Enter your SQL query here"></textarea>
                    <button type="submit" class="button">Run Query</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
