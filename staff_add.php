<?php
include '../includes/auth.php';
include '../includes/header.php';
include '../db/db.php';

if (isset($_POST['add'])) {
    // 1. Sanitize input data
    $staff_id     = mysqli_real_escape_string($conn, $_POST['staff_id']);
    $name         = mysqli_real_escape_string($conn, $_POST['name']);
    $designation  = mysqli_real_escape_string($conn, $_POST['designation']);
    $department   = mysqli_real_escape_string($conn, $_POST['department']);
    $contact      = mysqli_real_escape_string($conn, $_POST['contact']);
    $email        = mysqli_real_escape_string($conn, $_POST['email']);
    $date_of_join = mysqli_real_escape_string($conn, $_POST['date_of_join']);

    $uploadDir = "../assets/uploads/profile_pics/";
    $profile_pic = "default.png"; 

    // Create directory if it doesn't exist
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // 3. Handle File Upload (Fixed $_FILES syntax)
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
        $fileTmp  = $_FILES['profile_pic']['tmp_name'];
        $fileName = time() . '_' . basename($_FILES['profile_pic']['name']);
        $filePath = $uploadDir . $fileName;

        if (move_uploaded_file($fileTmp, $filePath)) {
            $profile_pic = $fileName;
        }
    }

    // 4. Insert into Database
    $sql = "INSERT INTO staff (staff_id, name, designation, department, contact, email, date_of_join, profile_pic)
            VALUES ('$staff_id', '$name', '$designation', '$department', '$contact', '$email', '$date_of_join', '$profile_pic')";

    if ($conn->query($sql)) {
        echo "<script>alert('Staff added successfully');window.location='staff_list.php';</script>";
    } else {
        echo "<div class='error-msg'>Database error: " . $conn->error . "</div>";
    }
}
?>

<style>
    /* CSS Part to make the form look better */
    .form-container {
        max-width: 500px;
        margin: 30px auto;
        padding: 20px;
        background: #f9f9f9;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        font-family: Arial, sans-serif;
    }
    .form-container h2 {
        text-align: center;
        color: #333;
    }
    .form-group {
        margin-bottom: 15px;
    }
    .form-group label {
        display: block;
        font-weight: bold;
        margin-bottom: 5px;
        color: #555;
    }
    .form-group input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-sizing: border-box; /* Ensures padding doesn't affect width */
    }
    .submit-btn {
        width: 100%;
        padding: 12px;
        background-color: #193165;
        border: none;
        color: white;
        font-size: 16px;
        font-weight: bold;
        border-radius: 4px;
        cursor: pointer;
        transition: background 0.3s;
    }
    .submit-btn:hover {
        background-color: #193165;
    }
    .error-msg {
        color: red;
        text-align: center;
        margin-top: 10px;
    }
</style>

<div class="form-container">
    <h2>Add New Staff</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Staff ID:</label>
            <input type="text" name="staff_id" required>
        </div>

        <div class="form-group">
            <label>Full Name:</label>
            <input type="text" name="name" required>
        </div>

        <div class="form-group">
            <label>Designation:</label>
            <input type="text" name="designation" required>
        </div>

        <div class="form-group">
            <label>Department:</label>
            <input type="text" name="department" required>
        </div>

        <div class="form-group">
            <label>Contact Number:</label>
            <input type="text" name="contact" required>
        </div>

        <div class="form-group">
            <label>Email Address:</label>
            <input type="email" name="email" required>
        </div>

        <div class="form-group">
            <label>Date of Join:</label>
            <input type="date" name="date_of_join">
        </div>

        <div class="form-group">
            <label>Profile Picture:</label>
            <input type="file" name="profile_pic" accept="image/*">
        </div>

        <button type="submit" name="add" class="submit-btn">Add Staff Member</button>
    </form>
</div>