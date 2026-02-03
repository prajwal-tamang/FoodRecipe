<?php
/**
 * ========================================
 * ADD RECIPE PAGE
 * ========================================
 * 
 * This page allows logged-in users to add a new recipe.
 * 
 * URL: http://localhost/prajwal-food-recipe/public/add_recipe.php
 */

include '../includes/header.php';

// Require user to be logged in
requireLogin();

// Initialize variables
$error = '';
$recipe_name = '';
$description = '';
$ingredients = '';
$instructions = '';
$cooking_time = '';
$servings = '';
$category = '';
$difficulty = '';

/**
 * PROCESS ADD RECIPE FORM
 */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $recipe_name = trim($_POST['recipe_name']);
    $description = trim($_POST['description']);
    $ingredients = trim($_POST['ingredients']);
    $instructions = trim($_POST['instructions']);
    $cooking_time = (int) $_POST['cooking_time'];
    $servings = (int) $_POST['servings'];
    $category = $_POST['category'];
    $difficulty = $_POST['difficulty'];
    $user_id = $_SESSION['user_id'];

    if (empty($recipe_name) || empty($ingredients) || empty($instructions)) {
        $error = 'Please fill in Recipe Name, Ingredients, and Instructions';
    } elseif (strlen($recipe_name) < 3) {
        $error = 'Recipe name must be at least 3 characters';
    } else {
        // Handle image upload
        $image_name = '';

        if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
            $file_tmp = $_FILES['image']['tmp_name'];
            $file_name = $_FILES['image']['name'];
            $file_size = $_FILES['image']['size'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            $allowed = array('jpg', 'jpeg', 'png', 'gif');

            if (!in_array($file_ext, $allowed)) {
                $error = 'Only JPG, JPEG, PNG, and GIF images are allowed';
            } elseif ($file_size > 5 * 1024 * 1024) {
                $error = 'Image size must be less than 5MB';
            } else {
                $upload_dir = __DIR__ . '/../uploads/';
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                $image_name = uniqid() . '_' . $file_name;
                $upload_path = $upload_dir . $image_name;

                if (!move_uploaded_file($file_tmp, $upload_path)) {
                    $error = 'Failed to upload image. Please try again.';
                    $image_name = '';
                }
            }
        }

        // Insert recipe
        if (empty($error)) {
            $sql = "INSERT INTO recipes 
                    (user_id, recipe_name, description, ingredients, instructions, 
                     cooking_time, servings, category, difficulty, image) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param(
                $stmt,
                "issssiisss",
                $user_id,
                $recipe_name,
                $description,
                $ingredients,
                $instructions,
                $cooking_time,
                $servings,
                $category,
                $difficulty,
                $image_name
            );

            if (mysqli_stmt_execute($stmt)) {
                $new_id = mysqli_insert_id($conn);
                setMessage('Recipe added successfully!', 'success');
                header('Location: view_recipe.php?id=' . $new_id);
                exit;
            } else {
                $error = 'Failed to add recipe. Please try again.';
            }
        }
    }
}
?>

<!-- ============ PAGE TITLE ============ -->
<div class="page-title">
    <h1>➕ Add New Recipe</h1>
    <p>Share your delicious recipe with others</p>
</div>

<!-- ============ ADD RECIPE FORM ============ -->
<div class="card" style="padding: 30px;">

    <?php if ($error): ?>
        <div class="alert alert-danger">
            <?php echo $error; ?>
            <button onclick="this.parentElement.remove()">×</button>
        </div>
    <?php endif; ?>

    <form method="POST" action="" enctype="multipart/form-data">

        <div class="form-group">
            <label for="recipe_name">Recipe Name *</label>
            <input type="text" id="recipe_name" name="recipe_name" value="<?php echo safe($recipe_name); ?>"
                placeholder="e.g., Chocolate Chip Cookies" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" placeholder="A short description of your recipe..."
                style="min-height: 80px;"><?php echo safe($description); ?></textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label for="category">Category</label>
                <select id="category" name="category">
                    <?php foreach (getCategories() as $cat): ?>
                        <option value="<?php echo $cat; ?>" <?php echo $category == $cat ? 'selected' : ''; ?>>
                            <?php echo $cat; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="difficulty">Difficulty</label>
                <select id="difficulty" name="difficulty">
                    <?php foreach (getDifficulties() as $diff): ?>
                        <option value="<?php echo $diff; ?>" <?php echo $difficulty == $diff ? 'selected' : ''; ?>>
                            <?php echo $diff; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="cooking_time">Cooking Time (minutes)</label>
                <input type="number" id="cooking_time" name="cooking_time"
                    value="<?php echo $cooking_time ? $cooking_time : 30; ?>" min="1" max="1000">
            </div>

            <div class="form-group">
                <label for="servings">Servings (people)</label>
                <input type="number" id="servings" name="servings" value="<?php echo $servings ? $servings : 4; ?>"
                    min="1" max="100">
            </div>
        </div>

        <div class="form-group">
            <label for="ingredients">Ingredients *</label>
            <textarea id="ingredients" name="ingredients" placeholder="Enter each ingredient on a new line:
2 cups flour
1 cup sugar
2 eggs
1 tsp vanilla extract" required><?php echo safe($ingredients); ?></textarea>
            <div class="form-hint">Enter each ingredient on a new line</div>
        </div>

        <div class="form-group">
            <label for="instructions">Instructions *</label>
            <textarea id="instructions" name="instructions" placeholder="Enter each step on a new line:
Preheat oven to 350°F (175°C)
Mix flour and sugar in a bowl
Add eggs and vanilla, mix well
Bake for 25 minutes" style="min-height: 200px;" required><?php echo safe($instructions); ?></textarea>
            <div class="form-hint">Enter each step on a new line</div>
        </div>

        <div class="form-group">
            <label for="image">Recipe Image (optional)</label>
            <input type="file" id="image" name="image" accept="image/*">
            <div class="form-hint">Max 5MB. JPG, PNG, or GIF</div>
            <div id="image-preview" style="margin-top:10px; display:none;">
                <label>Preview:</label>
                <div style="width:100%; max-width:360px; border-radius:8px; overflow:hidden; margin-top:8px;">
                    <img id="preview-img" src="#" alt="Image preview" style="width:100%; height:auto; display:block;">
                </div>
            </div>
        </div>

        <div style="display: flex; gap: 15px; margin-top: 20px;">
            <button type="submit" class="btn btn-primary">✨ Add Recipe</button>
            <a href="recipes.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<script>
document.getElementById('image').addEventListener('change', function(e){
  const file = this.files[0];
  const preview = document.getElementById('image-preview');
  const img = document.getElementById('preview-img');
  if (!file) {
    preview.style.display='none';
    return;
  }
  img.src = URL.createObjectURL(file);
  preview.style.display='block';
});
</script>

<?php include '../includes/footer.php'; ?>