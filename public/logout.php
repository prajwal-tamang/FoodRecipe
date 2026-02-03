<?php
/**
 * ========================================
 * LOGOUT
 * ========================================
 * 
 * This simple script logs out the user.
 * 
 * URL: http://localhost/prajwal-food-recipe/public/logout.php
 */

require_once '../config/db.php';

// Destroy the session (log out)
session_destroy();

// Redirect to homepage
header('Location: index.php');
exit;
?>