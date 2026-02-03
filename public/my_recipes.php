<?php
/**
 * ========================================
 * MY RECIPES PAGE
 * ========================================
 * 
 * This page shows all recipes created by the logged-in user.
 * 
 * URL: http://localhost/prajwal-food-recipe/public/my_recipes.php
 */

include '../includes/header.php';

// Require user to be logged in
requireLogin();

// Get recipes created by current user
$myRecipes = getUserRecipes($_SESSION['user_id']);
?>

<!-- ============ PAGE TITLE ============ -->
<div class="page-title">
    <h1>📋 My Recipes</h1>
    <p>You have <?php echo count($myRecipes); ?> recipe(s)</p>
</div>

<div style="text-align: right; margin-bottom: 20px;">
    <a href="add_recipe.php" class="btn btn-primary">➕ Add New Recipe</a>
</div>

<?php if (empty($myRecipes)): ?>
    <div class="empty-state">
        <div class="icon">📖</div>
        <h3>No Recipes Yet</h3>
        <p>You haven't added any recipes yet. Start sharing your favorite recipes!</p>
        <a href="add_recipe.php" class="btn btn-primary">➕ Add Your First Recipe</a>
    </div>
<?php else: ?>
    <div class="recipe-grid">
        <?php foreach ($myRecipes as $recipe): ?>
            <div class="card">
                <div class="card-image">
                    <?php if ($recipe['image']): ?>
                        <img src="../uploads/<?php echo safe($recipe['image']); ?>" alt="Recipe Image">
                    <?php else: ?>
                        <img src="../assets/img/placeholder.svg" alt="No image available">
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <span class="badge badge-secondary" style="margin-bottom: 10px;">
                        <?php echo safe($recipe['category']); ?>
                    </span>
                    <h3 class="card-title">
                        <a href="view_recipe.php?id=<?php echo $recipe['id']; ?>">
                            <?php echo safe($recipe['recipe_name']); ?>
                        </a>
                    </h3>
                    <div class="recipe-meta">
                        <span>⏱️ <?php echo formatTime($recipe['cooking_time']); ?></span>
                        <span class="badge badge-<?php echo getDifficultyColor($recipe['difficulty']); ?>">
                            <?php echo safe($recipe['difficulty']); ?>
                        </span>
                    </div>
                    <div class="recipe-author">
                        📅 Added <?php echo date('M d, Y', strtotime($recipe['created_at'])); ?>
                    </div>
                    <div class="recipe-actions">
                        <a href="view_recipe.php?id=<?php echo $recipe['id']; ?>" class="btn btn-primary btn-small">
                            👁️ View
                        </a>
                        <a href="edit_recipe.php?id=<?php echo $recipe['id']; ?>" class="btn btn-secondary btn-small">
                            ✏️ Edit
                        </a>
                        <a href="delete_recipe.php?id=<?php echo $recipe['id']; ?>" class="btn btn-danger btn-small"
                            onclick="return confirm('Are you sure you want to delete this recipe?');">
                            🗑️
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>