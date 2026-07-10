<?php
session_start();

// Show all errors for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if admin is logged in
if(!isset($_SESSION['admin'])){
    header("Location: ../login.php");
    exit();
}

// Connect to database
$conn = new mysqli("localhost","root","","staff_db");
if($conn->connect_error){
    die("Database connection failed: " . $conn->connect_error);
}

// Handle attendance form submission
if(isset($_POST['submit'])){
    $date = $_POST['date'];
    $statuses = $_POST['status']; // array of staff_id => status

    foreach($statuses as $staff_id => $status){
        // Sanitize inputs to prevent SQL injection and errors
        $staff_id = $conn->real_escape_string($staff_id);
        $status = $conn->real_escape_string($status);
        $date = $conn->real_escape_string($date);

        // Check if attendance already exists for this staff on this specific date
        $check_query = "SELECT * FROM attendance WHERE staff_id='$staff_id' AND date='$date'";
        $check = $conn->query($check_query);
        
        if($check && $check->num_rows > 0){
            // Update existing record
            $conn->query("UPDATE attendance SET status='$status' WHERE staff_id='$staff_id' AND date='$date'");
        } else {
            // Insert new record
            $conn->query("INSERT INTO attendance (staff_id, date, status) VALUES ('$staff_id', '$date', '$status')");
        }
    }
    $msg = "Attendance saved successfully for $date!";
}

// Get staff list
$staff_result = $conn->query("SELECT * FROM staff ORDER BY name ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance | Staff Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family:'Segoe UI',sans-serif; margin:0; background:#E5F1FF; }
        .navbar { display:flex; justify-content:space-between; align-items:center; background:#1E40AF; padding:15px 30px; color:#fff; border-radius:0 0 12px 12px; box-shadow:0 4px 12px rgba(0,0,0,0.15);}
        .navbar h2{margin:0; font-size:22px;}
        .navbar-links a{color:#fff; text-decoration:none; margin-left:15px; transition:0.3s;}
        .navbar-links a:hover{color:#93C5FD; border-bottom:2px solid #93C5FD; padding-bottom:2px;}
        .container{max-width:1000px; margin:30px auto; padding:25px; background:#fff; border-radius:12px; box-shadow:0 8px 20px rgba(0,0,0,0.1);}
        h2{color:#1E40AF; border-bottom: 2px solid #E5F1FF; padding-bottom: 10px; margin-top: 30px;}
        table{width:100%; border-collapse:collapse; margin-top:20px;}
        th, td{padding:12px; text-align:center; border:1px solid #ddd;}
        th{background:#1E40AF; color:#fff;}
        .status-select{padding:8px; border-radius:5px; border: 1px solid #ccc; width: 100%;}
        .submit-btn{padding:12px 24px; background:#3B82F6; color:#fff; border:none; border-radius:6px; cursor:pointer; margin-top:20px; font-weight: bold; font-size: 16px;}
        .submit-btn:hover{background:#2563EB;}
        .msg{background: #D1FAE5; color: #065F46; padding: 15px; border-radius: 8px; border: 1px solid #A7F3D0; font-weight: bold;}
        input[type="date"] { padding: 8px; border-radius: 5px; border: 1px solid #ccc; margin-left: 10px; }
    </style>
</head>
<body>

<div class="navbar">
    <h2><i class="fas fa-calendar-check"></i> Attendance System</h2>
    <div class="navbar-links">
        <a href="../dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
        <a href="staff_list.php"><i class="fas fa-address-book"></i> Staff List</a>
        <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
</div>

<div class="container">
    <h2>Mark Attendance</h2>

    <?php if(isset($msg)) echo "<div class='msg'><i class='fas fa-check-circle'></i> $msg</div>"; ?>

    <form method="POST" action="">
        <div style="margin-bottom: 20px;">
            <label for="date"><strong>Select Attendance Date:</strong></label>
            <input type="date" name="date" id="date" required value="<?php echo date('Y-m-d'); ?>">
        </div>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Staff Name</th>
                    <th>Designation</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if($staff_result && $staff_result->num_rows > 0) {
                    $i=1;
                    $today = date('Y-m-d');
                    while($staff = $staff_result->fetch_assoc()){
                        // Check if record already exists for THIS staff on TODAY'S date to show current status
                        $att_res = $conn->query("SELECT status FROM attendance WHERE staff_id='".$staff['staff_id']."' AND date='$today'");
                        
                        // Fix: Check if $att_res is not false before using num_rows
                        $status = ($att_res && $att_res->num_rows > 0) ? $att_res->fetch_assoc()['status'] : 'Present';

                        echo "<tr>
                                <td>{$i}</td>
                                <td><strong>{$staff['name']}</strong></td>
                                <td>{$staff['designation']}</td>
                                <td>
                                    <select name='status[{$staff['staff_id']}]' class='status-select'>
                                        <option value='Present' ".($status=='Present'?'selected':'').">Present</option>
                                        <option value='Absent' ".($status=='Absent'?'selected':'').">Absent</option>
                                        <option value='Leave' ".($status=='Leave'?'selected':'').">Leave</option>
                                    </select>
                                </td>
                              </tr>";
                        $i++;
                    }
                } else {
                    echo "<tr><td colspan='4'>No staff members found.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <button type="submit" name="submit" class="submit-btn"><i class="fas fa-save"></i> Save All Records</button>
    </form>

    <h2>Attendance Summary (Last 10 Days)</h2>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Total Staff</th>
                <th>Present</th>
                <th>Absent</th>
                <th>Leave</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $dates_result = $conn->query("SELECT DISTINCT date FROM attendance ORDER BY date DESC LIMIT 10");
            if($dates_result && $dates_result->num_rows > 0) {
                while($row = $dates_result->fetch_assoc()){
                    $summary_date = $row['date'];
                    
                    // Count statuses for this date
                    $total = $conn->query("SELECT COUNT(*) as t FROM attendance WHERE date='$summary_date'")->fetch_assoc()['t'];
                    $present = $conn->query("SELECT COUNT(*) as t FROM attendance WHERE date='$summary_date' AND status='Present'")->fetch_assoc()['t'];
                    $absent = $conn->query("SELECT COUNT(*) as t FROM attendance WHERE date='$summary_date' AND status='Absent'")->fetch_assoc()['t'];
                    $leave = $conn->query("SELECT COUNT(*) as t FROM attendance WHERE date='$summary_date' AND status='Leave'")->fetch_assoc()['t'];

                    echo "<tr>
                            <td>".date('d M, Y', strtotime($summary_date))."</td>
                            <td>$total</td>
                            <td style='color:green; font-weight:bold;'>$present</td>
                            <td style='color:red;'>$absent</td>
                            <td style='color:orange;'>$leave</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No records found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php $conn->close(); ?>
</body>
</html>