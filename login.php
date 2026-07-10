<?php
session_start(); // Start session
include 'db/db.php'; // Database connection

// Login form submit check
if (isset($_POST['login'])) {

    // Get and clean username
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));

    // Encrypt password using md5
    $password = md5($_POST['password']);

    // Check admin login details
    $sql = "SELECT * FROM admin WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    // If login success
    if ($result->num_rows > 0) {
        $_SESSION['admin'] = $username; // Set session
        header("Location: dashboard.php"); // Redirect
        exit();
    } else {
        $error = "Invalid username or password!"; // Error message
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Login | Staff System</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- Font Awesome icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
/* Reset default styles */
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI',sans-serif;
}

/* Page background */
body{
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background:linear-gradient(135deg,#eef2ff,#f8fafc);
}

/* Login box */
.login-wrapper{
    background:#fff;
    width:100%;
    max-width:420px;
    padding:40px;
    border-radius:16px;
    box-shadow:0 20px 40px rgba(0,0,0,0.08);
}

/* Header section */
.login-header{
    text-align:center;
    margin-bottom:25px;
}

.login-header i{
    font-size:40px;
    color:#1E3A8A;
}

.login-header h2{
    font-size:22px;
    color:#1f2937;
}

.login-header p{
    font-size:14px;
    color:#6b7280;
}

/* Input group */
.form-group{
    position:relative;
    margin-bottom:18px;
}

/* Left icons */
.form-group i:first-child{
    position:absolute;
    top:50%;
    left:12px;
    transform:translateY(-50%);
    color:#9ca3af;
}

/* Input fields */
.form-group input{
    width:100%;
    padding:12px 40px 12px 40px;
    border:1px solid #d1d5db;
    border-radius:8px;
    font-size:14px;
}

/* Input focus */
.form-group input:focus{
    outline:none;
    border-color:#3B82F6;
}

/* Eye icon */
.toggle-password{
    position:absolute;
    top:50%;
    right:12px;
    transform:translateY(-50%);
    cursor:pointer;
    color:#9ca3af;
}

/* Eye hover */
.toggle-password:hover{
    color:#1E3A8A;
}

/* Forgot password */
.login-options{
    text-align:right;
    margin-bottom:20px;
    font-size:13px;
}

.login-options a{
    color:#3B82F6;
    text-decoration:none;
}

/* Login button */
.btn-login{
    width:100%;
    padding:12px;
    border:none;
    border-radius:8px;
    background:#1E3A8A;
    color:#fff;
    font-size:16px;
    cursor:pointer;
}

/* Button hover */
.btn-login:hover{
    background:#172554;
}

/* Error message */
.error-msg{
    margin-top:15px;
    background:#fee2e2;
    color:#991b1b;
    padding:10px;
    border-radius:6px;
    font-size:14px;
    text-align:center;
}

/* Footer text */
.footer-text{
    text-align:center;
    margin-top:20px;
    font-size:13px;
    color:#9ca3af;
}
</style>
</head>

<body>

<div class="login-wrapper">

    <!-- Login header -->
    <div class="login-header">
        <i class="fas fa-user-shield"></i>
        <h2>Staff Profile System</h2>
        <p>Administrator Login</p>
    </div>

    <!-- Login form -->
    <form method="post">

        <!-- Username field -->
        <div class="form-group">
            <i class="fas fa-user"></i>
            <input type="text" name="username" placeholder="Username" required>
        </div>

        <!-- Password field with eye icon -->
        <div class="form-group">
            <i class="fas fa-lock"></i>
            <input type="password" name="password" id="password" placeholder="Password" required>
            <i class="fas fa-eye toggle-password" id="togglePassword"></i>
        </div>

        <!-- Forgot password -->
        <div class="login-options">
            <a href="forgot_password.php">Forgot Password?</a>
        </div>

        <!-- Submit button -->
        <button type="submit" name="login" class="btn-login">Login</button>
    </form>

    <!-- Error display -->
    <?php if (isset($error)) { ?>
        <div class="error-msg">
            <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
        </div>
    <?php } ?>

    <!-- Footer -->
    <div class="footer-text">
        © <?php echo date("Y"); ?> Staff Management System
    </div>

</div>

<!-- Show/Hide password script -->
<script>
const togglePassword = document.getElementById("togglePassword"); // Eye icon
const passwordField = document.getElementById("password"); // Password input

// Toggle password visibility
togglePassword.addEventListener("click", function () {
    const type = passwordField.type === "password" ? "text" : "password";
    passwordField.type = type;
    this.classList.toggle("fa-eye");
    this.classList.toggle("fa-eye-slash");
});
</script>

</body>
</html>
