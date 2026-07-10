<?php
session_start();

// Security check
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "staff_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check ID - Removed (int) casting to allow string IDs (like STF001)
if (!isset($_GET['id'])) {
    header("Location: staff_list.php");
    exit();
}

$id = $_GET['id']; 

// Use "s" instead of "i" if your staff_id contains letters
$stmt = $conn->prepare("DELETE FROM staff WHERE staff_id = ?");
$stmt->bind_param("s", $id); // "s" means string, "i" means integer

if ($stmt->execute()) {
    // Check if any row was actually deleted
    if ($stmt->affected_rows > 0) {
        header("Location: staff_list.php?msg=deleted");
        exit();
    } else {
        echo "No record found with that ID. (ID requested: " . htmlspecialchars($id) . ")";
    }
} else {
    echo "Query Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>