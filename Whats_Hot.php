<?php include 'navbar.php';?>

<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD"
            crossorigin="anonymous">
</head>
<body>

    <?php
        session_start();
        $name = $_SESSION['name'];
        $email = $_SESSION['email'];

    ?>
    <div style="border-bottom: 1px solid #ccc; padding: 30px; background-color: #f5f5f5;">
      <div style="text-align: center; margin-bottom: 30px;">
        <h1 style="font-size: 3rem; font-weight: bold; color: #444;">Highest-Rated Recipes</h1>
        <p style="font-size: 1.2rem; color: #777; line-height: 1.6;">Welcome to our 'What's Hot' page, where we showcase our most popular recipes as ranked by our users! Each recipe has been given a score from 1 to 5 based on its popularity, with 5 being the highest. This page is the perfect place to find our community's favorite recipes and get inspired for your next meal. Whether you're a seasoned cook or a beginner, we're sure you'll find something delicious here. So, what are you waiting for? Start exploring and discover your new favorite dish today!</p>
      </div>
    </div>

<?php
    include_once 'db.php';
    include_once 'edamamapi.php';
    # Array of hot recipes
    $hot_recipes = array();
    # Retrieve hot recipes from DB
    $conn = connectDB();

    $sql = "SELECT recipeid, recipelabel, recipeimage, AVG(CAST(rating AS FLOAT)) as avg_rating
            FROM rating
            GROUP BY recipeid
            ORDER BY avg_rating DESC;";

    try {
        $result = $conn->query($sql);
    } catch (Exception $e) {
        echo $e; # Remove later
    }

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            array_push($hot_recipes, [$row['recipeid'], $row['recipelabel'], $row['recipeimage'], $row['avg_rating']]);
        }
    }

    closeDB($conn);



    $count = 0;
    $number_of_recipes = 0;
    echo '<div class="container">';
    foreach ($hot_recipes as $recipe) {
      if ($number_of_recipes == 6) {
          break;
      }
        $recipeID = $recipe[0];
        $recipeLabel = $recipe[1];
        $recipeImage = $recipe[2];
        $recipeRating = round($recipe[3], 1);
        $recipe_details = get_recipe_details($recipeID);
        $recipeImage = $recipe_details['recipe']['image'];

        if ($count == 0) {
            echo '<div class="row">';
        }

        echo '
        <div class="col text-center">
            <form action="recipe.php" method="post">
            <input type="hidden" name="recipeid" id="recipeid" value="'.$recipeID.'">
            <img src="'.$recipeImage.'" alt="" style="width:100%; height:auto; object-fit:cover;"><br>
            <button type="submit" name="viewrecipe">'.$recipeLabel.'</button>
            <p>Rating:'.$recipeRating.'</p>
            </form>
        </div>
        ';

        $count = $count + 1;
        if ($count == 3) {
            echo '</div>';
            $count = 0;
        }
        $number_of_recipes = $number_of_recipes + 1;

    }
    echo '</div>';


?>

<?php include 'footer.php';?>

</body>
