<?php
/**
 * ========================================
 * HOMEPAGE
 * ========================================
 * 
 * This is the main landing page of the website.
 * It shows a welcome message and the latest recipes.
 * 
 * URL: http://localhost/prajwal-food-recipe/public/index.php
 */

include '../includes/header.php';

// Get the 6 most recent recipes
$recipes = getAllRecipes();
$latestRecipes = array_slice($recipes, 0, 6);
?>

<!-- ============ HERO SECTION ============ -->
<div class="hero">
    <h1>🍳 Welcome to Recipe Manager</h1>
    <p>Discover, create, and share delicious recipes!</p>

    <!-- Simple Search Form -->
    <form action="recipes.php" method="GET" class="hero-search">
        <input type="text" name="search" placeholder="Search for recipes...">
        <button type="submit" class="btn btn-secondary" style="border-radius: 25px; padding: 15px 25px;">
            🔍 Search
        </button>
    </form>

    <div class="hero-buttons">
        <a href="recipes.php" class="btn btn-secondary">📖 Browse Recipes</a>
        <?php if (!isLoggedIn()): ?>
            <a href="register.php" class="btn" style="background: white; color: #e74c3c;">📝 Sign Up Free</a>
        <?php else: ?>
            <a href="add_recipe.php" class="btn" style="background: white; color: #e74c3c;">➕ Add Recipe</a>
        <?php endif; ?>
    </div>
</div>

<!-- ============ LATEST RECIPES ============ -->
<div class="page-title">
    <h1>🕐 Latest Recipes</h1>
    <p>Check out our newest additions</p>
</div>

<?php if (empty($latestRecipes)): ?>
    <div class="empty-state">
        <div class="icon">📖</div>
        <h3>No Recipes Yet</h3>
        <p>Be the first to add a recipe!</p>
        <?php if (isLoggedIn()): ?>
            <a href="add_recipe.php" class="btn btn-primary">➕ Add Recipe</a>
        <?php else: ?>
            <a href="register.php" class="btn btn-primary">📝 Sign Up to Add Recipes</a>
        <?php endif; ?>
    </div>
<?php else: ?>
    <div class="recipe-grid">
        <?php foreach ($latestRecipes as $recipe): ?>
            <div class="card">
                <div class="card-image">
                    <?php if ($recipe['image']): ?>
                        <img src="../uploads/<?php echo safe($recipe['image']); ?>" alt="Recipe Image">
                    <?php else: ?>
                        🍽️
                    <?php endif; ?>
                </div>
                <div class="card-body">
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
                        👤 By <?php echo safe($recipe['username']); ?>
                    </div>
                    <div class="recipe-actions">
                        <a href="view_recipe.php?id=<?php echo $recipe['id']; ?>" class="btn btn-primary btn-small">
                            👁️ View Recipe
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div style="text-align: center; margin-top: 30px;">
        <a href="recipes.php" class="btn btn-primary">View All Recipes →</a>
    </div>
<?php endif; ?>

<!-- ============ FEATURES SECTION ============ -->
<div class="features-grid">
    <div class="card feature-card">
        <div class="icon">📝</div>
        <h3>Create Recipes</h3>
        <p>Add your own recipes with ingredients and instructions</p>
    </div>
    <div class="card feature-card">
        <div class="icon">🔍</div>
        <h3>Search & Filter</h3>
        <p>Find recipes by name, ingredient, or category</p>
    </div>
    <div class="card feature-card">
        <div class="icon">🔒</div>
        <h3>Secure Login</h3>
        <p>Your recipes are protected with secure authentication</p>
    </div>
</div>

<?php include '../includes/footer.php'; ?>