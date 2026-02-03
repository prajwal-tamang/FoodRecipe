<?php
/**
 * ========================================
 * VIEW RECIPE PAGE
 * ========================================
 * 
 * This page shows the full details of a single recipe.
 * 
 * URL: http://localhost/prajwal-food-recipe/public/view_recipe.php?id=1
 */

include '../includes/header.php';

// Get recipe ID from URL
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Fetch the recipe
$recipe = getRecipeById($id);

// If recipe not found, redirect
if (!$recipe) {
    setMessage('Recipe not found', 'danger');
    header('Location: recipes.php');
    exit;
}
?>

<!-- ============ RECIPE DETAIL ============ -->
<div class="recipe-detail">

    <div class="recipe-header">
        <a href="recipes.php" style="color: rgba(255,255,255,0.8);">← Back to Recipes</a>
        <h1 style="margin-top: 15px;"><?php echo safe($recipe['recipe_name']); ?></h1>
        <div style="margin-top: 15px; display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">
            <span class="badge badge-secondary" style="font-size: 14px;">
                <?php echo safe($recipe['category']); ?>
            </span>
            <span class="badge badge-<?php echo getDifficultyColor($recipe['difficulty']); ?>" style="font-size: 14px;">
                <?php echo safe($recipe['difficulty']); ?>
            </span>
            <span style="opacity: 0.9;">
                👤 By <?php echo safe($recipe['username']); ?>
            </span>
            <span style="opacity: 0.8; font-size: 14px;">
                📅 <?php echo date('M d, Y', strtotime($recipe['created_at'])); ?>
            </span>
        </div>

        <?php if (isLoggedIn() && $_SESSION['user_id'] == $recipe['user_id']): ?>
            <div style="margin-top: 20px;">
                <a href="edit_recipe.php?id=<?php echo $recipe['id']; ?>" class="btn btn-secondary">
                    ✏️ Edit Recipe
                </a>
                <a href="delete_recipe.php?id=<?php echo $recipe['id']; ?>" class="btn btn-danger"
                    onclick="return confirm('Are you sure you want to delete this recipe?');">
                    🗑️ Delete Recipe
                </a>
            </div>
        <?php endif; ?>
    </div>

    <div class="recipe-body">
        <div>
            <?php if ($recipe['description']): ?>
                <div class="recipe-section">
                    <h2>📝 Description</h2>
                    <p style="font-size: 16px; color: #555; line-height: 1.8;">
                        <?php echo nl2br(safe($recipe['description'])); ?>
                    </p>
                </div>
            <?php endif; ?>

            <div class="recipe-section">
                <h2>🥕 Ingredients</h2>
                <ul class="ingredient-list">
                    <?php
                    $ingredients = explode("\n", $recipe['ingredients']);
                    foreach ($ingredients as $ingredient):
                        $ingredient = trim($ingredient);
                        if ($ingredient != ''):
                            ?>
                            <li>✓ <?php echo safe($ingredient); ?></li>
                        <?php
                        endif;
                    endforeach;
                    ?>
                </ul>
            </div>

            <div class="recipe-section">
                <h2>📋 Instructions</h2>
                <ol class="instruction-list">
                    <?php
                    $instructions = explode("\n", $recipe['instructions']);
                    $step = 0;
                    foreach ($instructions as $instruction):
                        $instruction = trim($instruction);
                        if ($instruction != ''):
                            $step++;
                            ?>
                            <li data-step="<?php echo $step; ?>">
                                <?php echo safe($instruction); ?>
                            </li>
                        <?php
                        endif;
                    endforeach;
                    ?>
                </ol>
            </div>
        </div>

        <div>
            <?php if ($recipe['image']): ?>
                <div style="margin-bottom: 25px; border-radius: 10px; overflow: hidden;">
                    <img src="../uploads/<?php echo safe($recipe['image']); ?>" alt="Recipe Image"
                        style="width: 100%; height: auto;">
                </div>
            <?php endif; ?>

            <div class="info-box">
                <h3>📊 Recipe Info</h3>
                <div class="info-item">
                    <span>⏱️ Cooking Time</span>
                    <strong><?php echo formatTime($recipe['cooking_time']); ?></strong>
                </div>
                <div class="info-item">
                    <span>👥 Servings</span>
                    <strong><?php echo $recipe['servings'] ? $recipe['servings'] . ' people' : 'Not specified'; ?></strong>
                </div>
                <div class="info-item">
                    <span>📊 Difficulty</span>
                    <span class="badge badge-<?php echo getDifficultyColor($recipe['difficulty']); ?>">
                        <?php echo safe($recipe['difficulty']); ?>
                    </span>
                </div>
                <div class="info-item">
                    <span>📁 Category</span>
                    <strong><?php echo safe($recipe['category']); ?></strong>
                </div>
            </div>

            <div style="margin-top: 20px; text-align: center;">
                <a href="recipes.php" class="btn btn-secondary" style="width: 100%;">
                    ← Back to All Recipes
                </a>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>