<?php
session_start();
require_once 'config.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['error'] = "You don't have permission to delete students";
    header("Location: dashboard.php");
    exit;
}

$roll = $_GET['roll'];
$stmt = $pdo->prepare("DELETE FROM stud_info WHERE roll = ?");
$stmt->execute([$roll]);

$_SESSION['message'] = "Student deleted successfully";
header("Location: dashboard.php");
exit;
?>
