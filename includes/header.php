<?php
/**
 * ========================================
 * HEADER - Top part of every page
 * ========================================
 * 
 * This file contains the opening HTML and navigation menu.
 * Include this at the TOP of every page.
 */

require_once __DIR__ . '/../config/db.php';

// Get current user if logged in
$currentUser = getCurrentUser();

// Get current page name for highlighting active menu item
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🍳 Recipe Manager</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>

    <!-- ============ NAVIGATION BAR ============ -->
    <nav class="navbar">
        <div class="container">
            <a href="index.php" class="logo">🍳 Recipe Manager</a>

            <div class="nav-links">
                <a href="index.php" class="<?php echo $currentPage == 'index.php' ? 'active' : ''; ?>">
                    🏠 Home
                </a>
                <a href="recipes.php" class="<?php echo $currentPage == 'recipes.php' ? 'active' : ''; ?>">
                    📖 All Recipes
                </a>

                <?php if (isLoggedIn()): ?>
                    <!-- Show these links only when logged in -->
                    <a href="add_recipe.php" class="<?php echo $currentPage == 'add_recipe.php' ? 'active' : ''; ?>">
                        ➕ Add Recipe
                    </a>
                    <a href="my_recipes.php" class="<?php echo $currentPage == 'my_recipes.php' ? 'active' : ''; ?>">
                        📋 My Recipes
                    </a>
                    <span class="user-info">
                        👤 <?php echo safe($currentUser['username']); ?>
                    </span>
                    <a href="logout.php">🚪 Logout</a>
                <?php else: ?>
                    <!-- Show these links only when logged out -->
                    <a href="login.php" class="<?php echo $currentPage == 'login.php' ? 'active' : ''; ?>">
                        🔑 Login
                    </a>
                    <a href="register.php" class="<?php echo $currentPage == 'register.php' ? 'active' : ''; ?>">
                        📝 Register
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- ============ MAIN CONTENT STARTS ============ -->
    <main class="main-content">
        <div class="container">
            <?php showMessage(); ?>