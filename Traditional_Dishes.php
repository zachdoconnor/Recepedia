<?php include 'navbar.php';?>

<div class="traditional_dishes">

<div class="container-fluid search-container">
  <div class="container">
    <form action="" method="GET">
      <div class="input-group mb-3">
        <input type="text" name="ingredient" id="ingredient" class="form-control search-input" placeholder="Search here e.g. chicken" aria-label="Search input">
        <div class="input-group-append">
          <button id="cuisine-button" class="btn search-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Cuisine</button>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="cuisine-button">
            <li><input class="form-check-input" type="radio" name="cuisine" value="American"> American</li>
            <li><input class="form-check-input" type="radio" name="cuisine" value="Asian"> Asian</li>
            <li><input class="form-check-input" type="radio" name="cuisine" value="Caribbean"> Caribbean</li>
            <li><input class="form-check-input" type="radio" name="cuisine" value="French"> French</li>
            <li><input class="form-check-input" type="radio" name="cuisine" value="Indian"> Indian</li>
            <li><input class="form-check-input" type="radio" name="cuisine" value="Italian"> Italian</li>
            <li><input class="form-check-input" type="radio" name="cuisine" value="Japanese"> Japanese</li>
            <li><input class="form-check-input" type="radio" name="cuisine" value="Kosher"> Kosher</li>
            <li><input class="form-check-input" type="radio" name="cuisine" value="Mediterranean"> Mediterranean</li>
          </ul>
        </div>
        <div class="input-group-append">
          <button id="meal-button" class="btn search-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Mealtype</button>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="meal-button">
            <li><input class="form-check-input" type="radio" name="meal" value="Breakfast"> Breakfast</li>
            <li><input class="form-check-input" type="radio" name="meal" value="Lunch"> Lunch</li>
            <li><input class="form-check-input" type="radio" name="meal" value="Dinner"> Dinner</li>
          </ul>
        </div>
        <div class="input-group-append">
          <button class="btn search-btn" type="submit" name="search">Search</button>
        </div>
      </div>
    </form>
  </div>
</div>

</div>
  
<div class "dishes" style="background-color: #f5f5f5;">

<?php
// session_start();
include_once 'edamamapi.php';
$meal = $cuisine = array();
$ingredients = array('Egg', 'Bacon');
$max_pages = 8;

if (isset($_GET['search'])) {
    if (isset($_GET['ingredient'])) {
        $ingredients = array($_GET['ingredient']);
    }
    if (isset($_GET['meal'])) {
        $meal = array($_GET['meal']);
    }
    if (isset($_GET['cuisine'])) {
        $cuisine = array($_GET['cuisine']);
    }
}


echo '<div style="width: 100%; text-align: center; padding: 8px; font-size: 1.5em;">';
echo 'Here are all the recipes that contain the following: <br>';
echo 'ingredients: '.ucwords(implode(", ", $ingredients),",").'<br>';
if (count($meal) > 0) {
echo 'Meal Types: '.implode(", ", $meal).'<br>';
}
if (count($cuisine) > 0) {
echo 'Cuisine: '.implode(", ", $cuisine).'<br>';
}
echo '</div>';

$page = isset($_GET['page']) ? $_GET['page'] : 1;

$search_results = search_recipes($ingredients, $page, $meal, $cuisine, $max_pages);

$recipes = $search_results['recipes'];
$total_pages = $search_results['total_pages'];

// Display recipes here...
// Display the recipes
$count = 0;
echo '<div class="container" style="border: 1px solid #f5f5f5; background-color: #f5f5f5;">';
foreach ($recipes as $recipe) {
    $recipeID = extract_recipe_id($recipe['uri']);
    $recipeLabel = $recipe['label'];
    $recipeImage = $recipe['image'];

    if ($count == 0) {
        echo '<div class="row">';
    }

    echo '
    <div class="col-3 text-center">
        <form action="recipe.php" method="post">
        <input type="hidden" name="recipeid" id="recipeid" value="'.$recipeID.'">
        <img src="'.$recipeImage.'" alt="" style="width:100%; height:auto; object-fit:cover;"><br>
        <button type="submit" name="viewrecipe">'.$recipeLabel.'</button>
        </form>
    </div>
    ';

    $count = $count + 1;
    if ($count == 4) {
        echo '</div>';
        $count = 0;
    }

}
// Display pagination links
echo '<div class="text-center" style="border-top: 1px solid black; width:100%; padding:16px; ">';
for ($i = 1; $i <= $max_pages; $i++) {
    if ($i == $page) {
        // Current page, do not link
        echo '<span class="current" style="border: 1px solid black; background-color:#f5f5f5; border-radius:4px; margin-right:4px; padding:4px; text-decoration:none;">Page ' . $i . '</span>';
    } else {
        // Other pages, link to the page
        echo '<a style="border: 1px solid blue; border-radius:4px; background-color:cyan; margin-right:8px; padding:4px; text-decoration:none;" href="Traditional_Dishes.php?' . http_build_query(array_merge($_GET, array('ingredient' => implode(", ", $ingredients), 'cuisine' => $cuisine, 'meal' => $meal, 'page' => $i))) . '">Page ' . $i . '</a>';

    }
}
echo '</div>';
echo '</div>';
?>
  
</div>

<?php include 'footer.php';?>

<script>
    $(document).ready(function() {
        $('input[name="cuisine"]').click(function() {
            var cuisineText = $(this).val();
            $('#cuisine-button').text(cuisineText);
        });

        $('input[name="meal"]').click(function() {
            var mealText = $(this).val();
            $('#meal-button').text(mealText);
        });
    });
</script>

</body>
</html>
