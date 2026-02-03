<?php
/**
 * ========================================
 * EDIT RECIPE PAGE
 * ========================================
 * 
 * This page allows users to edit their own recipes.
 * 
 * URL: http://localhost/prajwal-food-recipe/public/edit_recipe.php?id=1
 */

include '../includes/header.php';

// Require user to be logged in
requireLogin();

// Get recipe ID from URL
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Fetch the recipe
$recipe = getRecipeById($id);

// Check if recipe exists and belongs to current user
if (!$recipe || $recipe['user_id'] != $_SESSION['user_id']) {
    setMessage('Recipe not found or you do not have permission to edit it', 'danger');
    header('Location: my_recipes.php');
    exit;
}

// Initialize variables with current recipe values
$error = '';
$recipe_name = $recipe['recipe_name'];
$description = $recipe['description'];
$ingredients = $recipe['ingredients'];
$instructions = $recipe['instructions'];
$cooking_time = $recipe['cooking_time'];
$servings = $recipe['servings'];
$category = $recipe['category'];
$difficulty = $recipe['difficulty'];
$current_image = $recipe['image'];

/**
 * PROCESS EDIT RECIPE FORM
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

    if (empty($recipe_name) || empty($ingredients) || empty($instructions)) {
        $error = 'Please fill in Recipe Name, Ingredients, and Instructions';
    } elseif (strlen($recipe_name) < 3) {
        $error = 'Recipe name must be at least 3 characters';
    } else {
        $image_name = $current_image;

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

                if (move_uploaded_file($file_tmp, $upload_path)) {
                    // Delete old image
                    if ($current_image && file_exists($upload_dir . $current_image)) {
                        unlink($upload_dir . $current_image);
                    }
                } else {
                    $error = 'Failed to upload image. Please try again.';
                    $image_name = $current_image;
                }
            }
        }

        // Update recipe
        if (empty($error)) {
            $sql = "UPDATE recipes SET 
                    recipe_name = ?, 
                    description = ?, 
                    ingredients = ?, 
                    instructions = ?, 
                    cooking_time = ?, 
                    servings = ?, 
                    category = ?, 
                    difficulty = ?, 
                    image = ?
                    WHERE id = ? AND user_id = ?";

            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param(
                $stmt,
                "ssssiiissii",
                $recipe_name,
                $description,
                $ingredients,
                $instructions,
                $cooking_time,
                $servings,
                $category,
                $difficulty,
                $image_name,
                $id,
                $_SESSION['user_id']
            );

            if (mysqli_stmt_execute($stmt)) {
                setMessage('Recipe updated successfully!', 'success');
                header('Location: view_recipe.php?id=' . $id);
                exit;
            } else {
                $error = 'Failed to update recipe. Please try again.';
            }
        }
    }
}
?>

<!-- ============ PAGE TITLE ============ -->
<div class="page-title">
    <h1>✏️ Edit Recipe</h1>
    <p>Update your recipe details</p>
</div>

<!-- ============ EDIT RECIPE FORM ============ -->
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
            <input type="text" id="recipe_name" name="recipe_name" value="<?php echo safe($recipe_name); ?>" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description"
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
                <input type="number" id="cooking_time" name="cooking_time" value="<?php echo $cooking_time; ?>" min="1"
                    max="1000">
            </div>

            <div class="form-group">
                <label for="servings">Servings (people)</label>
                <input type="number" id="servings" name="servings" value="<?php echo $servings; ?>" min="1" max="100">
            </div>
        </div>

        <div class="form-group">
            <label for="ingredients">Ingredients *</label>
            <textarea id="ingredients" name="ingredients" required><?php echo safe($ingredients); ?></textarea>
            <div class="form-hint">Enter each ingredient on a new line</div>
        </div>

        <div class="form-group">
            <label for="instructions">Instructions *</label>
            <textarea id="instructions" name="instructions" style="min-height: 200px;"
                required><?php echo safe($instructions); ?></textarea>
            <div class="form-hint">Enter each step on a new line</div>
        </div>

        <?php if ($current_image): ?>
            <div class="form-group">
                <label>Current Image</label>
                <div style="display: flex; align-items: center; gap: 15px;">
                    <img src="../uploads/<?php echo safe($current_image); ?>" alt="Current Image"
                        style="width: 150px; height: 100px; object-fit: cover; border-radius: 5px;">
                    <span style="color: #666;">Upload new image below to replace</span>
                </div>
            </div>
        <?php endif; ?>

        <div class="form-group">
            <label for="image">New Recipe Image (optional)</label>
            <input type="file" id="image" name="image" accept="image/*">
            <div class="form-hint">Max 5MB. JPG, PNG, or GIF. Leave empty to keep current image.</div>
            <div id="image-preview" style="margin-top:10px; display:none;">
                <label>Preview:</label>
                <div style="width:100%; max-width:360px; border-radius:8px; overflow:hidden; margin-top:8px;">
                    <img id="preview-img" src="#" alt="Image preview" style="width:100%; height:auto; display:block;">
                </div>
            </div>
        </div>

        <div style="display: flex; gap: 15px; margin-top: 20px;">
            <button type="submit" class="btn btn-primary">💾 Save Changes</button>
            <a href="view_recipe.php?id=<?php echo $id; ?>" class="btn btn-secondary">Cancel</a>
            <a href="delete_recipe.php?id=<?php echo $id; ?>" class="btn btn-danger"
                onclick="return confirm('Are you sure you want to delete this recipe?');" style="margin-left: auto;">
                🗑️ Delete Recipe
            </a>
        </div>
    </form>
</div>

<script>
const imageInput = document.getElementById('image');
if (imageInput) {
  imageInput.addEventListener('change', function(e){
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
}
</script>

<?php include '../includes/footer.php'; ?>