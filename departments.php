<?php
session_start(); // Start session

// Check admin login
if (!isset($_SESSION['admin'])) {
    header("Location: login.php"); // Redirect if not logged
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "staff_db");
if ($conn->connect_error) {
    die("Database connection failed");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Departments | Staff System</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI', sans-serif;
}

/* Page background */
body{
    background:#F1F5F9;
}

/* Main container */
.container{
    max-width:1200px;
    margin:30px auto;
    padding:20px;
}

/* Top header */
.header{
    background:linear-gradient(135deg,#1E3A8A,#2563EB);
    padding:25px;
    border-radius:14px;
    color:#fff;
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:30px;
}

/* Header title */
.header h2{
    font-size:22px;
    font-weight:600;
}

/* Dashboard button */
.header a{
    background:#fff;
    color:#1E3A8A;
    padding:10px 18px;
    border-radius:8px;
    text-decoration:none;
    font-size:14px;
    font-weight:500;
}

/* Card grid */
.cards{
    display:grid;
    grid-template-columns:repeat(auto-fill, minmax(260px, 1fr));
    gap:25px;
}

/* Single card */
.card{
    background:#fff;
    border-radius:16px;
    padding:25px;
    box-shadow:0 10px 25px rgba(0,0,0,0.08);
    transition:0.3s;
    position:relative;
    overflow:hidden;
}

/* Card hover */
.card:hover{
    transform:translateY(-6px);
    box-shadow:0 16px 35px rgba(0,0,0,0.12);
}

/* Icon circle */
.card-icon{
    width:55px;
    height:55px;
    border-radius:50%;
    background:#EFF6FF;
    display:flex;
    align-items:center;
    justify-content:center;
    margin-bottom:15px;
}

.card-icon i{
    font-size:24px;
    color:#1E40AF;
}

/* Department name */
.card h3{
    font-size:18px;
    color:#111827;
    margin-bottom:6px;
}

/* Staff count */
.card p{
    font-size:14px;
    color:#6B7280;
}

/* Empty text */
.empty{
    text-align:center;
    font-size:16px;
    color:#6B7280;
    grid-column:1/-1;
}
</style>
</head>

<body>

<div class="container">

    <!-- Page Header -->
    <div class="header">
        <h2><i class="fas fa-building"></i> Departments Overview</h2>
        <a href="dashboard.php">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <!-- Department Cards -->
    <div class="cards">

        <?php
        // Fetch department wise staff count
        $sql = "SELECT department, COUNT(*) AS total FROM staff GROUP BY department";
        $result = $conn->query($sql);

        // Display cards
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "
                <div class='card'>
                    <div class='card-icon'>
                        <i class='fas fa-users'></i>
                    </div>
                    <h3>{$row['department']}</h3>
                    <p>{$row['total']} Staff Members</p>
                </div>
                ";
            }
        } else {
            echo "<div class='empty'>No departments found</div>";
        }
        ?>

    </div>

</div>

</body>
</html>
