<?php
// scripts/attach_sample_images.php
// Run this locally: php scripts/attach_sample_images.php

require_once __DIR__ . '/../config/db.php';

$map = [
    'Spaghetti Carbonara' => 'sample1.svg',
    'Chocolate Chip Cookies' => 'sample2.svg',
    'Caesar Salad' => 'sample3.svg',
];

foreach ($map as $name => $image) {
    $stmt = mysqli_prepare($conn, "UPDATE recipes SET image = ? WHERE recipe_name = ?");
    mysqli_stmt_bind_param($stmt, 'ss', $image, $name);
    if (mysqli_stmt_execute($stmt)) {
        echo "Updated: $name -> $image\n";
    } else {
        echo "Failed: $name (" . mysqli_error($conn) . ")\n";
    }
    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
echo "Done. Visit /public/recipes.php to see sample images.\n";