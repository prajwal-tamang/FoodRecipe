<?php
/**
 * ========================================
 * REGISTRATION PAGE
 * ========================================
 * 
 * This page allows new users to create an account.
 * 
 * URL: http://localhost/prajwal-food-recipe/public/register.php
 */

require_once '../config/db.php';

// If already logged in, redirect to homepage
if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

// Initialize variables
$error = '';
$username = '';
$email = '';

/**
 * PROCESS REGISTRATION FORM
 */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($username) || empty($email) || empty($password)) {
        $error = 'Please fill in all fields';
    } elseif (strlen($username) < 3) {
        $error = 'Username must be at least 3 characters';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match';
    } else {
        // Check if username already exists
        $sql = "SELECT id FROM users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $error = 'Username already taken. Please choose another.';
        } else {
            // Check if email already exists
            $sql = "SELECT id FROM users WHERE email = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) > 0) {
                $error = 'Email already registered. Please use another or login.';
            } else {
                // Hash the password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Insert new user
                $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashed_password);

                if (mysqli_stmt_execute($stmt)) {
                    setMessage('Registration successful! Please login.', 'success');
                    header('Location: login.php');
                    exit;
                } else {
                    $error = 'Registration failed. Please try again.';
                }
            }
        }
    }
}

include '../includes/header.php';
?>

<!-- ============ REGISTRATION FORM ============ -->
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h1>📝 Register</h1>
            <p>Create a new account</p>
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
                    <input type="text" id="username" name="username" value="<?php echo safe($username); ?>" required
                        autofocus>
                    <div class="form-hint">At least 3 characters</div>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo safe($email); ?>" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                    <div class="form-hint">At least 6 characters</div>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    ✨ Create Account
                </button>
            </form>
        </div>

        <div class="auth-footer">
            <p>Already have an account? <a href="login.php" style="color: #e74c3c; font-weight: bold;">Login here</a>
            </p>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>