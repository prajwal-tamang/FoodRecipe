-- - A database called "np03cs4a240143"

-- Create the database
CREATE DATABASE IF NOT EXISTS np03cs4a240143;
USE np03cs4a240143;

-- ========================================
-- USERS TABLE
-- ========================================
-- Stores user accounts for login system
-- 
-- id = unique number for each user
-- username = their chosen username
-- email = their email address
-- password = encrypted password (never stored as plain text!)
-- created_at = when they registered

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ========================================
-- RECIPES TABLE
-- ========================================
-- Stores all the recipes
--
-- id = unique number for each recipe
-- user_id = which user created this recipe
-- recipe_name = name of the dish
-- description = short description
-- ingredients = list of ingredients needed
-- instructions = step-by-step cooking instructions
-- category = type of dish (Main Course, Dessert, etc.)
-- cooking_time = how long to cook (in minutes)
-- servings = how many people it serves
-- difficulty = Easy, Medium, or Hard
-- image = filename of uploaded image (optional)
-- created_at = when recipe was added

CREATE TABLE IF NOT EXISTS recipes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    recipe_name VARCHAR(200) NOT NULL,
    description TEXT,
    ingredients TEXT NOT NULL,
    instructions TEXT NOT NULL,
    category VARCHAR(50) DEFAULT 'Other',
    cooking_time INT DEFAULT 30,
    servings INT DEFAULT 4,
    difficulty VARCHAR(20) DEFAULT 'Medium',
    image VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ========================================
-- SAMPLE DATA
-- ========================================
-- Adding some test users and recipes

-- Add sample users
-- Password for all users is: 123456789
-- (The long string is the encrypted version)

INSERT INTO users (username, email, password) VALUES
('admin', 'admin@example.com', '$2y$10$CgEd5atDtY3JN0lcvxrVY.5WYRViXjzAUkFAm8tXAVyshWvXe3bjG'),
('john', 'john@example.com', '$2y$10$CgEd5atDtY3JN0lcvxrVY.5WYRViXjzAUkFAm8tXAVyshWvXe3bjG'),
('jane', 'jane@example.com', '$2y$10$CgEd5atDtY3JN0lcvxrVY.5WYRViXjzAUkFAm8tXAVyshWvXe3bjG');

-- Add sample recipes

INSERT INTO recipes (user_id, recipe_name, description, ingredients, instructions, category, cooking_time, servings, difficulty) VALUES

(1, 'Spaghetti Carbonara', 
'A classic Italian pasta dish with creamy egg sauce and crispy bacon.',
'400g spaghetti
200g bacon or pancetta
4 eggs
100g parmesan cheese
2 cloves garlic
Salt and pepper
Olive oil', 
'Boil water and cook spaghetti according to package
Cut bacon into small pieces
Fry bacon in a pan until crispy
Beat eggs with grated parmesan
Drain pasta and add to bacon pan
Remove from heat and add egg mixture
Toss quickly to create creamy sauce
Serve with extra parmesan and pepper',
'Main Course', 25, 4, 'Medium'),

(1, 'Chocolate Chip Cookies', 
'Soft and chewy cookies loaded with chocolate chips.',
'2 cups flour
1 cup butter (softened)
1 cup sugar
2 eggs
1 tsp vanilla
1 tsp baking soda
2 cups chocolate chips
Pinch of salt', 
'Preheat oven to 350°F (175°C)
Mix butter and sugar until fluffy
Add eggs and vanilla
Mix in flour, baking soda, and salt
Fold in chocolate chips
Drop spoonfuls onto baking sheet
Bake for 10-12 minutes
Let cool before eating',
'Dessert', 25, 24, 'Easy'),

(2, 'Caesar Salad', 
'Fresh romaine lettuce with creamy Caesar dressing and croutons.',
'1 head romaine lettuce
Caesar dressing
Croutons
Parmesan cheese
Lemon juice
Black pepper', 
'Wash and chop romaine lettuce
Make or use store-bought Caesar dressing
Toss lettuce with dressing
Add croutons on top
Sprinkle with parmesan
Add fresh black pepper
Serve immediately',
'Appetizer', 15, 4, 'Easy'),

(2, 'Beef Tacos', 
'Seasoned ground beef in crispy taco shells with fresh toppings.',
'500g ground beef
Taco seasoning
8 taco shells
Lettuce, shredded
Tomatoes, diced
Cheese, shredded
Sour cream
Salsa', 
'Brown ground beef in a pan
Add taco seasoning and water
Simmer until sauce thickens
Warm taco shells in oven
Fill shells with beef
Add toppings as desired
Serve with salsa and sour cream',
'Main Course', 20, 4, 'Easy'),

(3, 'Banana Smoothie', 
'A healthy and refreshing banana smoothie.',
'2 ripe bananas
1 cup milk
1/2 cup yogurt
1 tbsp honey
Ice cubes', 
'Peel and slice bananas
Add all ingredients to blender
Blend until smooth
Add more milk if too thick
Pour into glasses
Serve immediately',
'Beverage', 5, 2, 'Easy'),

(3, 'Grilled Cheese Sandwich', 
'Classic comfort food with melted cheese between toasted bread.',
'4 slices bread
4 slices cheese
2 tbsp butter
Optional: ham, tomato', 
'Butter one side of each bread slice
Place cheese between unbuttered sides
Heat pan over medium heat
Place sandwich buttered-side down
Cook until golden brown
Flip and cook other side
Cut in half and serve',
'Main Course', 10, 2, 'Easy');
