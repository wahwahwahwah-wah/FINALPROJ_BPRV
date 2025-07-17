<?php
session_start();
 
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: ../dashboard.php");
    exit;
}
 
require_once '../config/db.php';
 
$username = $password = "";
$username_err = $password_err = $login_err = "";
 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter username.";
    } else {
        $username = trim($_POST["username"]);
    }
    
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }
    
    if (empty($username_err) && empty($password_err)) {
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        if ($stmt = $db->prepare($sql)) {
            $stmt->bind_param("s", $param_username);
            $param_username = $username;
            
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                if ($result->num_rows == 1) {
                    $row = $result->fetch_assoc();
                    $hashed_password = $row['password'];
                    if (password_verify($password, $hashed_password)) {
                        session_start();
                        $_SESSION["loggedin"] = true;
                        $_SESSION["id"] = $row['id'];
                        $_SESSION["username"] = $row['username'];                           
                        header("location: ../dashboard.php");
                    } else {
                        $login_err = "Invalid username or password.";
                    }
                } else {
                    $login_err = "Invalid username or password.";
                }
            } else {
                echo "Oops! Something went wrong.";
            }
            $stmt->close();
        }
    }
    $db->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; background-color: #f4e8f7; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .wrapper { width: 380px; padding: 30px; background: #fff; border-radius: 8px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); text-align: center; }
        h2 { color: #6a0dad; margin-bottom: 20px; }
        p { color: #666; }
        .alert-danger { padding: 10px; background-color: #fce4ec; color: #c0392b; border: 1px solid #f8bbd0; border-radius: 4px; margin-bottom: 15px; }
        .form-group { margin-bottom: 20px; text-align: left; }
        .form-group label { display: block; margin-bottom: 5px; color: #555; font-weight: 500; }
        .form-group input { width: 100%; padding: 10px; box-sizing: border-box; border: 1px solid #d1aedc; border-radius: 4px; }
        .error { color: #c0392b; font-size: 0.9em; text-align: left; margin-top: 5px; }
        .btn { width: 100%; padding: 12px; background-color: #bf40bf; color: white; border: none; cursor: pointer; border-radius: 5px; font-size: 16px; font-weight: bold; }
        .signup-link { margin-top: 20px; }
        .signup-link a { color: #6a0dad; text-decoration: none; font-weight: 500; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Welcome Back!</h2>
        <p>Please log in to continue.</p>

        <?php if (!empty($login_err)) { echo '<div class="alert-danger">' . $login_err . '</div>'; } ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" value="<?php echo $username; ?>">
                <span class="error"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password">
                <span class="error"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn" value="Login">
            </div>
            <p class="signup-link">Don't have an account? <a href="register.php">Sign up now</a>.</p>
        </form>
    </div>
</body>
</html>