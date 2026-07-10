<?php
session_start();

// Check if admin is logged in
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

// Connect to the database
$conn = new mysqli("localhost", "root", "", "staff_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard | Staff Management</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
/* Base */
body {
    font-family: 'Segoe UI', sans-serif;
    margin: 0;
    background: #E5F1FF; /* light professional blue background */
}

/* Navbar */
.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #1E40AF; /* login page button blue */
    padding: 15px 30px;
    color: #fff;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    border-radius: 0 0 12px 12px;
}

.navbar h2 {
    margin: 0;
    font-size: 22px;
    font-weight: 600;
}

.navbar-links a {
    color: #fff;
    text-decoration: none;
    margin-left: 20px;
    font-weight: 500;
    transition: 0.3s;
}

.navbar-links a:hover {
    color: #93C5FD; /* lighter hover blue */
    border-bottom: 2px solid #93C5FD;
    padding-bottom: 3px;
}

/* Container */
.container {
    max-width: 1200px;
    margin: 30px auto;
    padding: 25px;
}

/* Welcome */
.welcome {
    text-align: center;
    margin-bottom: 30px;
}

.welcome h2 {
    font-size: 26px;
    color: #1E40AF; /* dark blue for headings */
    margin-bottom: 8px;
}

.welcome p {
    font-size: 16px;
    color: #1D4ED8; /* medium blue text */
}

/* Cards */
.card-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
}

.card {
    flex: 1 1 250px;
    background: #ffffff;
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    transition: transform 0.3s, box-shadow 0.3s;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 25px rgba(0,0,0,0.15);
}

.card i {
    font-size: 40px;
    color: #3B82F6; /* same login page button blue */
    margin-bottom: 10px;
}

.card h3 {
    font-size: 20px;
    margin-bottom: 8px;
    color: #1E3A8A;
}

.card p {
    font-size: 14px;
    color: #1E40AF;
}

.add-btn {
    display: inline-block;
    padding: 10px 16px;
    border-radius: 6px;
    background: #3B82F6; /* login page button blue */
    color: #fff;
    text-decoration: none;
    margin-top: 10px;
    transition: 0.3s;
}

.add-btn:hover {
    background: #2563EB; /* slightly darker blue */
}

/* Responsive */
@media(max-width:768px){
    .navbar h2{
        font-size: 18px;
    }
    .navbar-links a{
        font-size: 14px;
        margin-left: 12px;
    }
    .card-container{
        flex-direction: column;
        align-items:center;
    }
    .card{
        width:80%;
    }
}
</style>
</head>
<body>

<!-- Navbar -->
<div class="navbar">
    <h2><i class="fas fa-users-cog"></i> Staff Manager</h2>
    <div class="navbar-links">
        <a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
        <a href="staff/staff_list.php"><i class="fas fa-address-book"></i> Staff List</a>
        <a href="staff/staff_add.php"><i class="fas fa-user-plus"></i> Add Staff</a>
        
        <a href="departments.php"><i class="fas fa-building"></i> Departments</a>
        <a href="staff/attendance.php"><i class="fas fa-calendar-check"></i> Attendance</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>

    </div>
</div>

<div class="container">
    <!-- Welcome Message -->
    <div class="welcome">
        <h2>Welcome, <?php echo $_SESSION['admin']; ?>!</h2>
        <p>This is your Staff Management Dashboard.</p>
    </div>

    <!-- Dashboard Cards -->
    <div class="card-container">
    <div class="card">
        <i class="fas fa-users"></i>
        <h3>All Staff</h3>
        <?php
        $staff_count = $conn->query("SELECT COUNT(*) as total FROM staff")->fetch_assoc()['total'];
        echo "<p>Total Staff: $staff_count</p>";
        ?>
    </div>

    <div class="card">
        <i class="fas fa-user-plus"></i>
        <h3>Add Staff</h3>
        <p>Click below to add a new staff member.</p>
        <a href="staff/staff_add.php" class="add-btn">Add Staff</a>
    </div>

    <div class="card">
        <i class="fas fa-address-book"></i>
        <h3>Staff Directory</h3>
        <p>View all staff records.</p>
        <a href="staff/staff_list.php" class="add-btn">View Staff</a>
    </div>

    <!-- NEW CARD: Departments -->
    <div class="card">
        <i class="fas fa-building"></i>
        <h3>Departments</h3>
        <?php
        $dept_count = $conn->query("SELECT COUNT(*) as total FROM staff GROUP BY department")->num_rows;
        echo "<p>Total Departments: $dept_count</p>";
        ?>
        <a href="departments.php"class="add-btn">View Departments</a>
    </div>

    <!-- NEW CARD: Attendance -->
    <div class="card">
        <i class="fas fa-calendar-check"></i>
        <h3>Attendance</h3>
        <?php
        // Count attendance records
        $attendance_count = $conn->query("SELECT COUNT(*) as total FROM attendance")->fetch_assoc()['total'];
        echo "<p>Total Records: $attendance_count</p>";
        ?>
        <a href="staff/attendance.php" class="add-btn"><i class="fas fa-eye"></i> View Attendance</a>
    </div>

<?php $conn->close(); ?>
</body>
</html>
