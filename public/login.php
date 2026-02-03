<?php
/**
 * ========================================
 * LOGIN PAGE
 * ========================================
 * 
 * This page allows existing users to log in.
 * 
 * URL: http://localhost/prajwal-food-recipe/public/login.php
 */

require_once '../config/db.php';

// If already logged in, redirect to homepage
if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

// Initialize error variable
$error = '';

/**
 * PROCESS LOGIN FORM
 */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password';
    } else {
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            setMessage('Welcome back, ' . $user['username'] . '!', 'success');
            header('Location: index.php');
            exit;
        } else {
            $error = 'Invalid username or password';
        }
    }
}

include '../includes/header.php';
?>

<!-- ============ LOGIN FORM ============ -->
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h1>🔑 Login</h1>
            <p>Welcome back!</p>
        </div>

        <div class="auth-body">
            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <?php echo $error; ?>
                    <button onclick="this.parentElement.remove()">×</button>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username"
                        value="<?php echo isset($username) ? safe($username) : ''; ?>" required autofocus>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    🔓 Login
                </button>
            </form>
        </div>

        <div class="auth-footer">
            <p>Don't have an account? <a href="register.php" style="color: #e74c3c; font-weight: bold;">Register
                    here</a></p>
        </div>
    </div>

    <!-- Demo Accounts Info -->
    <div style="margin-top: 20px; padding: 20px; background: #d1ecf1; border-radius: 10px;">
        <h4 style="color: #0c5460; margin-bottom: 10px;">📋 Demo Accounts</h4>
        <p style="color: #0c5460; font-size: 14px;">
            <strong>Username:</strong> admin, john, jane<br>
            <strong>Password:</strong> 123456789
        </p>
    </div>
</div>

<?php include '../includes/footer.php'; ?>