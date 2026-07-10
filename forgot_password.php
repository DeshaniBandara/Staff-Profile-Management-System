<?php
session_start();
include 'db/db.php';

$msg = "";
$error = "";

if (isset($_POST['reset'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $new_password = md5($_POST['new_password']);

    $check = $conn->query("SELECT * FROM admin WHERE username='$username'");

    if ($check->num_rows > 0) {
        $conn->query("UPDATE admin SET password='$new_password' WHERE username='$username'");
        $msg = "Password reset successfully. Please login.";
    } else {
        $error = "Username not found!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Forgot Password</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI', sans-serif;
}

body{
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background:linear-gradient(135deg,#eef2ff,#f8fafc);
}

.card{
    background:#fff;
    width:100%;
    max-width:420px;
    padding:40px;
    border-radius:16px;
    box-shadow:0 20px 40px rgba(0,0,0,0.08);
}

.header{
    text-align:center;
    margin-bottom:25px;
}

.header i{
    font-size:38px;
    color:#1E3A8A;
    margin-bottom:10px;
}

.header h2{
    font-size:22px;
    color:#1f2937;
}

.header p{
    font-size:14px;
    color:#6b7280;
}

.input-group{
    position:relative;
    margin-bottom:18px;
}

.input-group i{
    position:absolute;
    top:50%;
    left:12px;
    transform:translateY(-50%);
    color:#9ca3af;
}

.input-group input{
    width:100%;
    padding:12px 12px 12px 40px;
    border:1px solid #d1d5db;
    border-radius:8px;
    font-size:14px;
}

.input-group input:focus{
    outline:none;
    border-color:#3B82F6;
}

.btn{
    width:100%;
    padding:12px;
    border:none;
    border-radius:8px;
    background:#1E3A8A;
    color:#fff;
    font-size:16px;
    cursor:pointer;
    transition:.3s;
}

.btn:hover{
    background:#172554;
}

.msg{
    margin-top:15px;
    padding:10px;
    border-radius:6px;
    font-size:14px;
    text-align:center;
}

.success{
    background:#dcfce7;
    color:#166534;
}

.error{
    background:#fee2e2;
    color:#991b1b;
}

.back{
    text-align:center;
    margin-top:18px;
}

.back a{
    color:#3B82F6;
    font-size:14px;
    text-decoration:none;
}

.back a:hover{
    text-decoration:underline;
}
</style>
</head>

<body>

<div class="card">
    <div class="header">
        <i class="fas fa-key"></i>
        <h2>Forgot Password</h2>
        <p>Reset your administrator password</p>
    </div>

    <form method="post">
        <div class="input-group">
            <i class="fas fa-user"></i>
            <input type="text" name="username" placeholder="Admin Username" required>
        </div>

        <div class="input-group">
            <i class="fas fa-lock"></i>
            <input type="password" name="new_password" placeholder="New Password" required>
        </div>

        <button type="submit" name="reset" class="btn">Reset Password</button>
    </form>

    <?php if($msg): ?>
        <div class="msg success"><?php echo $msg; ?></div>
    <?php endif; ?>

    <?php if($error): ?>
        <div class="msg error"><?php echo $error; ?></div>
    <?php endif; ?>

    <div class="back">
        <a href="login.php"><i class="fas fa-arrow-left"></i> Back to Login</a>
    </div>
</div>

</body>
</html>
