<?php
session_start();
if(!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "staff_db");
if($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// FIX 1: Removed intval() to allow string IDs like STF001
if(!isset($_GET['id'])) die("No staff selected.");
$staff_id = $_GET['id']; 

// Handle form submission
if(isset($_POST['update'])){
    $updates = [];
    $params = [];
    $types = '';

    // Handle profile picture
    $uploadDir = "../assets/uploads/profile_pics/";
    if(isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0){
        $fileTmp = $_FILES['profile_pic']['tmp_name'];
        $fileName = time() . '_' . basename($_FILES['profile_pic']['name']);
        if(move_uploaded_file($fileTmp, $uploadDir . $fileName)){
            $updates[] = "profile_pic=?";
            $params[] = $fileName;
            $types .= 's';
        }
    }

    foreach($_POST as $col => $value){
        // FIX 2: Skip 'update' button and 'profile_pic' (handled above) and 'staff_id'
        if(in_array($col, ['update', 'staff_id', 'profile_pic'])) continue;
        
        $updates[] = "$col=?";
        $params[] = $value;
        $types .= 's';
    }

    if(!empty($updates)){
        // FIX 3: Changed WHERE staff_id=? to use "s" (string) instead of "i"
        $stmt = $conn->prepare("UPDATE staff SET ".implode(', ', $updates)." WHERE staff_id=?");
        $params[] = $staff_id;
        $types .= 's'; // Changed from 'i' to 's'
        
        $stmt->bind_param($types, ...$params);
        if($stmt->execute()){
            header("Location: staff_list.php?msg=updated"); 
            exit;
        } else {
            $error = "Update failed: " . $stmt->error;
        }
    } else {
        $error = "No data to update.";
    }
}

// Fetch staff details - FIX 4: Changed "i" to "s" for the fetch query
$stmt = $conn->prepare("SELECT * FROM staff WHERE staff_id=?");
$stmt->bind_param("s", $staff_id);
$stmt->execute();
$result = $stmt->get_result();
if($result->num_rows === 0) die("Staff not found in database.");
$staff = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Staff Profile</title>
    <style>
        :root {
            --primary-blue: #1a3673;
            --hover-blue: #142a5a;
        }
        body { font-family: 'Segoe UI', Arial; background: #f4f7f9; padding: 40px; color: #333; }
        .container { 
            max-width: 550px; 
            margin: auto; 
            background: #fff; 
            padding: 30px; 
            border-radius: 12px; 
            box-shadow: 0 8px 24px rgba(0,0,0,0.1); 
        }
        h2 { text-align: center; color: var(--primary-blue); margin-top: 0; }
        .profile-preview { text-align: center; margin-bottom: 20px; }
        img.photo { width: 100px; height: 100px; object-fit: cover; border-radius: 50%; border: 3px solid #eee; }
        
        label { font-weight: 600; margin-top: 15px; display: block; font-size: 14px; color: #555; }
        input { 
            width: 100%; padding: 12px; margin-top: 5px; 
            border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box;
            transition: border 0.3s;
        }
        input:focus { border-color: var(--primary-blue); outline: none; }
        
        .btn-group { margin-top: 25px; }
        button { 
            width: 100%; padding: 14px; background: var(--primary-blue); 
            color: white; border: none; border-radius: 6px; 
            font-weight: 600; cursor: pointer; transition: 0.3s; 
        }
        button:hover { background: var(--hover-blue); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .back-link { display: block; text-align: center; margin-top: 15px; color: #666; text-decoration: none; font-size: 14px; }
        .error { background: #fdeaea; color: #d9534f; padding: 10px; border-radius: 6px; margin-bottom: 15px; text-align: center; }
    </style>
</head>
<body>

<div class="container">
    <h2>Edit Staff Profile</h2>
    
    <?php if(isset($error)) echo "<div class='error'>$error</div>"; ?>

    <div class="profile-preview">
        <?php 
            $val = $staff['profile_pic'];
            $imgPath = (!empty($val) && file_exists("../assets/uploads/profile_pics/".$val)) ? "../assets/uploads/profile_pics/".$val : "../assets/uploads/profile_pics/default.png";
        ?>
        <img src="<?php echo $imgPath; ?>" class="photo">
    </div>

    <form method="post" enctype="multipart/form-data">
        <?php
        foreach($staff as $col => $val){
            if($col == 'staff_id') continue;
            
            if($col == 'profile_pic'){
                echo "<label>Update Photo</label>";
                echo "<input type='file' name='profile_pic' accept='image/*'>";
            } else {
                $type = "text";
                if(strpos($col, 'email') !== false) $type = "email";
                if(strpos($col, 'date') !== false) $type = "date";
                
                $label = ucwords(str_replace('_', ' ', $col));
                echo "<label>$label</label>";
                echo "<input type='$type' name='$col' value='".htmlspecialchars($val, ENT_QUOTES)."' required>";
            }
        }
        ?>
        <div class="btn-group">
            <button type="submit" name="update">Update Staff Member</button>
            <a href="staff_list.php" class="back-link">Cancel and Go Back</a>
        </div>
    </form>
</div>

</body>
</html>