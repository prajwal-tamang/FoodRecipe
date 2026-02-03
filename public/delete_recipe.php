<?php
/**
 * ========================================
 * DELETE RECIPE
 * ========================================
 * 
 * This script deletes a recipe from the database.
 * 
 * URL: http://localhost/prajwal-food-recipe/public/delete_recipe.php?id=1
 */

require_once '../config/db.php';

// Require user to be logged in
requireLogin();

// Get recipe ID from URL
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Fetch the recipe to check ownership
$recipe = getRecipeById($id);

// Check if recipe exists and belongs to current user
if (!$recipe || $recipe['user_id'] != $_SESSION['user_id']) {
    setMessage('Recipe not found or you do not have permission to delete it', 'danger');
    header('Location: my_recipes.php');
    exit;
}

// Delete the recipe from database
$sql = "DELETE FROM recipes WHERE id = ? AND user_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ii", $id, $_SESSION['user_id']);

if (mysqli_stmt_execute($stmt)) {
    // Delete associated image file if exists
    if ($recipe['image']) {
        $image_path = __DIR__ . '/../uploads/' . $recipe['image'];
        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }

    setMessage('Recipe deleted successfully', 'success');
} else {
    setMessage('Failed to delete recipe', 'danger');
}

// Redirect to my recipes page
header('Location: my_recipes.php');
exit;
?>