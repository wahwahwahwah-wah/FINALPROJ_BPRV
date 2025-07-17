<?php
require_once '../config/db.php';

$username = $password = "";
$username_err = $password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } else {
        $sql = "SELECT id FROM users WHERE username = ?";
        if ($stmt = $db->prepare($sql)) {
            $stmt->bind_param("s", $param_username);
            $param_username = trim($_POST["username"]);

            if ($stmt->execute()) {
                $stmt->store_result();
                if ($stmt->num_rows == 1) {
                    $username_err = "This username is already taken.";
                } else {
                    $username = trim($_POST["username"]);
                }
            } else {
                echo "Oops! Something went wrong.";
            }
            $stmt->close();
        }
    }
    
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";     
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have at least 6 characters.";
    } else {
        $password = trim($_POST["password"]);
    }
    
    if (empty($username_err) && empty($password_err)) {
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
        if ($stmt = $db->prepare($sql)) {
            $stmt->bind_param("ss", $param_username, $param_password);
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT);
            
            if ($stmt->execute()) {
                header("location: login.php");
                exit();
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
    <title>Sign Up</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; background-color: #f4e8f7; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .wrapper { width: 380px; padding: 30px; background: #fff; border-radius: 8px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); text-align: center; }
        h2 { color: #6a0dad; margin-bottom: 20px; }
        p { color: #666; }
        .form-group { margin-bottom: 20px; text-align: left; }
        .form-group label { display: block; margin-bottom: 5px; color: #555; font-weight: 500; }
        .form-group input { width: 100%; padding: 10px; box-sizing: border-box; border: 1px solid #d1aedc; border-radius: 4px; }
        .error { color: #c0392b; font-size: 0.9em; text-align: left; margin-top: 5px; }
        .btn { width: 100%; padding: 12px; background-color: #bf40bf; color: white; border: none; cursor: pointer; border-radius: 5px; font-size: 16px; font-weight: bold; }
        .login-link { margin-top: 20px; }
        .login-link a { color: #6a0dad; text-decoration: none; font-weight: 500; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Create Account</h2>
        <p>Join our pet adoption community!</p>
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
                <input type="submit" class="btn" value="Register">
            </div>
            <p class="login-link">Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>    
</body>
</html>