<?php
session_start();
if(!isset($_SESSION['admin'])) header("Location: ../login.php");

include '../db/db.php';

$search = $_GET['search'] ?? '';
$where='';
if(!empty($search)){
    $safe = $conn->real_escape_string($search);
    $where = "WHERE name LIKE '%$safe%' OR email LIKE '%$safe%' OR contact LIKE '%$safe%' OR designation LIKE '%$safe%' OR department LIKE '%$safe%'";
}

$result = $conn->query("SELECT * FROM staff $where ORDER BY staff_id DESC");

$uploadDir = "../assets/uploads/profile_pics/"; // folder path
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Staff Directory</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
body { margin:0; font-family:'Segoe UI',sans-serif; background:#f0f2f7; }
.navbar { background:#1E40AF; color:#fff; padding:15px 30px; display:flex; justify-content:space-between; align-items:center; }
.navbar a { color:#fff; margin-left:20px; text-decoration:none; font-weight:500; }
.navbar a:hover { text-decoration:underline; }

.container { max-width:1300px; margin:30px auto; padding:0 15px; }
.top-bar { display:flex; justify-content:space-between; flex-wrap:wrap; margin-bottom:25px; }
.top-bar input { padding:10px; width:260px; border-radius:6px; border:1px solid #ccc; }
.top-bar button, .add-btn { padding:10px 16px; background:#0f2a44; color:#fff; border:none; border-radius:6px; cursor:pointer; text-decoration:none; }
.add-btn:hover, .top-bar button:hover { background:#081b2c; }

.staff-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(320px,1fr)); gap:25px; }
.card { background:#fff; border-radius:14px; padding:20px; box-shadow:0 10px 25px rgba(0,0,0,0.08); transition:0.3s; }
.card:hover { transform:translateY(-5px); }
.photo { width:90px; height:90px; border-radius:50%; object-fit:cover; border:3px solid #0f2a44; }
.section-title { font-size:13px; font-weight:600; color:#0f2a44; margin-top:15px; }
.label { font-size:12px; color:#777; margin-top:8px; }
.value { font-size:14px; font-weight:500; margin-bottom:5px; }
.actions { margin-top:15px; }
.actions a { padding:8px 12px; border-radius:6px; color:#fff; text-decoration:none; font-size:13px; margin-right:6px; }
.edit { background:#2ecc71; } .edit:hover { background:#27ae60; }
.delete { background:#e74c3c; } .delete:hover { background:#c0392b; }
</style>
</head>
<body>

<div class="navbar">
<h3>Staff Management</h3>
<div><a href="../dashboard.php">Dashboard</a></div>
</div>

<div class="container">
<div class="top-bar">
<form method="GET">
<input type="text" name="search" placeholder="Search staff..." value="<?= htmlspecialchars($search) ?>">
<button type="submit"><i class="fa fa-search"></i></button>
</form>
<a href="staff_add.php" class="add-btn"><i class="fa fa-user-plus"></i> Add Staff</a>
</div>

<div class="staff-grid">
<?php
if($result->num_rows==0){ echo "<p>No staff found.</p>"; }
else {
    while($row=$result->fetch_assoc()):
        $filename = trim($row['profile_pic'] ?? '');
        $imgPath = (!empty($filename) && file_exists($uploadDir.$filename)) ? $uploadDir.$filename : $uploadDir."default.png";

?>
<div class="card">
<img src="<?= htmlspecialchars($imgPath) ?>" class="photo" alt="Profile">

<div class="section-title">Personal Information</div>
<div class="label">Full Name</div>
<div class="value"><?= htmlspecialchars($row['name']) ?></div>
<div class="label">Email</div>
<div class="value"><?= htmlspecialchars($row['email']) ?></div>
<div class="label">Contact</div>
<div class="value"><?= $row['contact'] ?: '-' ?></div>

<div class="section-title">Professional Details</div>
<div class="label">Designation</div>
<div class="value"><?= $row['designation'] ?: '-' ?></div>
<div class="label">Department</div>
<div class="value"><?= $row['department'] ?: '-' ?></div>

<div class="actions">
<a href="staff_edit.php?id=<?= $row['staff_id'] ?>" class="edit"><i class="fa fa-edit"></i></a>
<a href="staff_delete.php?id=<?= $row['staff_id'] ?>" class="delete" onclick="return confirm('Delete staff?')"><i class="fa fa-trash"></i></a>
</div>
</div>
<?php endwhile; } ?>
</div>
</div>
</body>
</html>
