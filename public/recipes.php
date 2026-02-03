<?php
/**
 * ========================================
 * ALL RECIPES PAGE
 * ========================================
 * 
 * This page shows all recipes with search and filter options.
 * 
 * URL: http://localhost/prajwal-food-recipe/public/recipes.php
 */

include '../includes/header.php';

// Get search/filter parameters from URL
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';
$difficulty = isset($_GET['difficulty']) ? $_GET['difficulty'] : '';

// Get all recipes
$allRecipes = getAllRecipes();

// Filter recipes based on search criteria
$filteredRecipes = array();

foreach ($allRecipes as $recipe) {
    $matchesSearch = true;
    $matchesCategory = true;
    $matchesDifficulty = true;

    if ($search != '') {
        $searchLower = strtolower($search);
        $nameMatch = strpos(strtolower($recipe['recipe_name']), $searchLower) !== false;
        $ingredientsMatch = strpos(strtolower($recipe['ingredients']), $searchLower) !== false;
        $matchesSearch = $nameMatch || $ingredientsMatch;
    }

    if ($category != '') {
        $matchesCategory = ($recipe['category'] == $category);
    }

    if ($difficulty != '') {
        $matchesDifficulty = ($recipe['difficulty'] == $difficulty);
    }

    if ($matchesSearch && $matchesCategory && $matchesDifficulty) {
        $filteredRecipes[] = $recipe;
    }
}
?>

<!-- ============ PAGE TITLE ============ -->
<div class="page-title">
    <h1>📖 All Recipes</h1>
    <p>Browse our collection of <?php echo count($filteredRecipes); ?> recipes</p>
</div>

<!-- ============ SEARCH & FILTER BOX ============ -->
<div class="search-box">
    <form action="recipes.php" method="GET" class="search-form">
        <input type="text" name="search" placeholder="🔍 Search recipes or ingredients..."
            value="<?php echo safe($search); ?>">

        <select name="category">
            <option value="">📁 All Categories</option>
            <?php foreach (getCategories() as $cat): ?>
                <option value="<?php echo $cat; ?>" <?php echo $category == $cat ? 'selected' : ''; ?>>
                    <?php echo $cat; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <select name="difficulty">
            <option value="">📊 All Difficulties</option>
            <?php foreach (getDifficulties() as $diff): ?>
                <option value="<?php echo $diff; ?>" <?php echo $difficulty == $diff ? 'selected' : ''; ?>>
                    <?php echo $diff; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit" class="btn btn-primary">🔍 Search</button>

        <?php if ($search || $category || $difficulty): ?>
            <a href="recipes.php" class="btn btn-secondary">✖ Clear</a>
        <?php endif; ?>
    </form>
</div>

<!-- ============ RECIPE GRID ============ -->
<?php if (empty($filteredRecipes)): ?>
    <div class="empty-state">
        <div class="icon">🔍</div>
        <h3>No Recipes Found</h3>
        <p>Try a different search term or filter</p>
        <a href="recipes.php" class="btn btn-primary">View All Recipes</a>
    </div>
<?php else: ?>
    <div class="recipe-grid">
        <?php foreach ($filteredRecipes as $recipe): ?>
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
                        👤 By <?php echo safe($recipe['username']); ?>
                    </div>
                    <div class="recipe-actions">
                        <a href="view_recipe.php?id=<?php echo $recipe['id']; ?>" class="btn btn-primary btn-small">
                            👁️ View
                        </a>
                        <?php if (isLoggedIn() && $_SESSION['user_id'] == $recipe['user_id']): ?>
                            <a href="edit_recipe.php?id=<?php echo $recipe['id']; ?>" class="btn btn-secondary btn-small">
                                ✏️ Edit
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>