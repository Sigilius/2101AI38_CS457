<?php
session_start();
require_once 'config.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['error'] = "You don't have permission to run SQL queries";
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['query'])) {
    $query = trim($_POST['query']);
    
    try {
        if (stripos($query, 'SELECT') === 0) {
            $stmt = $pdo->query($query);
            $_SESSION['query_result'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $stmt = $pdo->exec($query);
            $_SESSION['message'] = "Query executed successfully. Affected rows: $stmt";
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Query error: " . $e->getMessage();
    }
}

header("Location: dashboard.php");
exit;
?>
