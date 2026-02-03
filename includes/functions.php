<?php
/**
 * ========================================
 * HELPER FUNCTIONS
 * ========================================
 * 
 * This file contains reusable functions used throughout the website.
 * Include this file when you need these functions.
 * 
 * Functions in this file:
 * - safe() = prevent XSS attacks
 * - isLoggedIn() = check if user is logged in
 * - getCurrentUser() = get logged in user's info
 * - requireLogin() = redirect if not logged in
 * - setMessage() = show success/error messages
 * - getAllRecipes() = get all recipes from database
 * - getRecipeById() = get one recipe by ID
 * - getUserRecipes() = get recipes by a specific user
 */

// Make sure we have database connection
global $conn;

/**
 * ========================================
 * SECURITY FUNCTION: Prevent XSS Attacks
 * ========================================
 * 
 * XSS = Cross-Site Scripting
 * This happens when hackers try to inject JavaScript code
 * 
 * Example attack: User enters "<script>alert('hacked')</script>" as their name
 * Without safe(): The script would run and could steal data
 * With safe(): It displays as plain text, harmless
 * 
 * ALWAYS use this when displaying user input!
 */
function safe($string)
{
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * ========================================
 * Check if User is Logged In
 * ========================================
 * 
 * Returns true if user is logged in, false otherwise
 * We check if 'user_id' exists in the session
 */
function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

/**
 * ========================================
 * Get Current Logged In User's Info
 * ========================================
 * 
 * Returns an array with user's id, username, email
 * Returns null if not logged in
 */
function getCurrentUser()
{
    if (!isLoggedIn()) {
        return null;
    }

    global $conn;
    $userId = $_SESSION['user_id'];

    $sql = "SELECT id, username, email FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    return mysqli_fetch_assoc($result);
}

/**
 * ========================================
 * Require User to be Logged In
 * ========================================
 * 
 * If user is not logged in, redirect to login page
 * Use this at the top of pages that need authentication
 */
function requireLogin()
{
    if (!isLoggedIn()) {
        setMessage('Please login to access this page', 'warning');
        header('Location: login.php');
        exit;
    }
}

/**
 * ========================================
 * Flash Messages (Success/Error Messages)
 * ========================================
 * 
 * These functions show temporary messages to users
 * Example: "Recipe added successfully!" or "Login failed"
 */

// Set a message to show on next page
function setMessage($message, $type = 'info')
{
    $_SESSION['message'] = $message;
    $_SESSION['message_type'] = $type;
}

// Show the message and then delete it
function showMessage()
{
    if (isset($_SESSION['message'])) {
        $type = $_SESSION['message_type'] ?? 'info';
        echo '<div class="alert alert-' . $type . '">';
        echo safe($_SESSION['message']);
        echo '<button onclick="this.parentElement.remove()">×</button>';
        echo '</div>';

        // Remove after showing
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
    }
}

/**
 * ========================================
 * GET ALL RECIPES
 * ========================================
 * 
 * Returns an array of all recipes with user info
 * Ordered by newest first
 */
function getAllRecipes()
{
    global $conn;

    $sql = "SELECT recipes.*, users.username 
            FROM recipes 
            JOIN users ON recipes.user_id = users.id 
            ORDER BY recipes.created_at DESC";

    $result = mysqli_query($conn, $sql);

    $recipes = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $recipes[] = $row;
    }

    return $recipes;
}

/**
 * ========================================
 * GET RECIPE BY ID
 * ========================================
 * 
 * Returns a single recipe by its ID
 * Returns null if not found
 */
function getRecipeById($id)
{
    global $conn;

    $sql = "SELECT recipes.*, users.username 
            FROM recipes 
            JOIN users ON recipes.user_id = users.id 
            WHERE recipes.id = ?";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    return mysqli_fetch_assoc($result);
}

/**
 * ========================================
 * GET RECIPES BY USER
 * ========================================
 * 
 * Returns all recipes created by a specific user
 */
function getUserRecipes($userId)
{
    global $conn;

    $sql = "SELECT recipes.*, users.username 
            FROM recipes 
            JOIN users ON recipes.user_id = users.id 
            WHERE recipes.user_id = ?
            ORDER BY recipes.created_at DESC";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $recipes = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $recipes[] = $row;
    }

    return $recipes;
}

/**
 * ========================================
 * FORMAT COOKING TIME
 * ========================================
 * 
 * Converts minutes to readable format
 * Example: 90 -> "1h 30min"
 */
function formatTime($minutes)
{
    if ($minutes < 60) {
        return $minutes . ' min';
    }
    $hours = floor($minutes / 60);
    $mins = $minutes % 60;
    return $hours . 'h ' . ($mins > 0 ? $mins . 'min' : '');
}

/**
 * ========================================
 * GET DIFFICULTY COLOR
 * ========================================
 * 
 * Returns CSS class color for difficulty badge
 */
function getDifficultyColor($difficulty)
{
    switch ($difficulty) {
        case 'Easy':
            return 'success';
        case 'Medium':
            return 'warning';
        case 'Hard':
            return 'danger';
        default:
            return 'secondary';
    }
}

/**
 * ========================================
 * GET CATEGORIES LIST
 * ========================================
 */
function getCategories()
{
    return array('Main Course', 'Appetizer', 'Dessert', 'Beverage', 'Breakfast', 'Snack', 'Other');
}

/**
 * ========================================
 * GET DIFFICULTY LEVELS
 * ========================================
 */
function getDifficulties()
{
    return array('Easy', 'Medium', 'Hard');
}
?>