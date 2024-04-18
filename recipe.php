<?php
include_once 'db.php';
session_start();
if (isset($_POST['rate'])) {
    # Get the recipe ID
    $recipeID = $_SESSION['recipeID'];
    # Check for label
    if (isset($_POST['recipelabel'])) {
        $recipelabel = $_POST['recipelabel'];
    }
    # Check for image
    if (isset($_POST['recipeimage'])) {
        $recipeimage = $_POST['recipeimage'];
    }
    # Check for rating
    if (isset($_POST['rating'])) {
        $rating = $_POST['rating']/2;
    }
    # Check for comment
    $comment = NULL;
    if (isset($_POST['comment'])) {
        $comment = $_POST['comment'];
    }
    # Get the user email
    $userID = $_SESSION['email'];
    $conn = connectDB();
    $sql = "INSERT INTO rating (recipeid, userid, rating, comment, recipelabel, recipeimage)
            VALUES ('$recipeID', '$userID', '$rating', '$comment', '$recipelabel', '$recipeimage')";

    try {
        $conn->query($sql);
      } catch (Exception $e) {
      echo $e;
  }

  closeDB($conn);
}

?>
<?php include 'navbar.php';?>

<div class="container text-center">
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST["viewrecipe"])) {
        $_SESSION['recipeID'] = $_POST["recipeid"];
    }
    if (isset($_SESSION["recipeID"])) {
        $recipeID = $_SESSION['recipeID'];
        # Get overall rating
        $conn = connectDB();
        $sql = "SELECT avg(rating) AS overall FROM rating WHERE recipeid='$recipeID'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $overall = round($row['overall'], 1);
            }
        }
        # Close DB
        closeDB($conn);
        $appId = "c7ece9da";
        $appKey = "3fc0677b16cdf9289af68f9e44b73925";
        $type = "public";
        $url = "https://api.edamam.com/api/recipes/v2/$recipeID?type=$type&app_id=$appId&app_key=$appKey";
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
        ));
        $resp = curl_exec($curl);
        curl_close($curl);
        $result = json_decode($resp, true);
        $uri = $result['recipe']['uri'];
        $image = $result['recipe']['image'];
        $label = $result['recipe']['label'];
        $source = $result['recipe']['source'];
        $serve = $result['recipe']['yield'];
        $time = $result['recipe']['totalTime'];
        $cuisines = $result['recipe']['cuisineType'];
        $meals = $result['recipe']['mealType'];
        if ($result['recipe']['dishType']) {
            $dishes = $result['recipe']['dishType'];
        }
        $diets = $result['recipe']['dietLabels'];
        $healthlabels = $result['recipe']['healthLabels'];
        $ingredients = $result['recipe']['ingredientLines'];
        $nutrients = $result['recipe']['totalNutrients'];
        $instructions = $result['recipe']['url'];
        echo "<br>";
        echo "<div class='container'>";
        echo "<p class='h1' style='text-align:center;'>$label</p>";
        echo "<div class='row'>";
        echo "<div class='col-6'>";
            echo "<img src='$image' alt='Recipe Image' class='img-fluid img-thumbnail'>";
        echo "</div>";
        echo "<div class='col-6 text-start'>";
        echo "<div class='mb-4'><p class='d-inline fw-semibold'>Cuisine: </p>";
        foreach ($cuisines as $cuisine) {
            echo "<p class='d-inline text-capitalize fst-italic'>$cuisine</p>";
        }
        echo "</div>";
        echo "<div class='mb-4'><p class='d-inline fw-semibold'>Meal: </p>";
        foreach ($meals as $meal) {
            echo "<p class='d-inline text-capitalize fst-italic'>$meal</p>";
        }
        echo "</div>";
        echo "<div class='mb-4'><p class='d-inline fw-semibold'>Dish: </p>";
        if ($dishes) {
            foreach ($dishes as $dish) {
                echo "<p class='d-inline text-capitalize fst-italic'>$dish</p>";
            }
        }
        echo "</div>";
        echo "<div class='mb-4'><p class='d-inline fw-semibold'>Diet: </p>";
        foreach ($diets as $diet) {
            echo "<p class='d-inline text-capitalize fst-italic'>$diet</p>";
        }
        echo "</div>";
        echo "<div class='mb-4'><p class='d-inline fw-semibold'>Serves:</p>
            <p class='d-inline text-capitalize fst-italic'> $serve</p>";
        echo "</div>";
        echo "<div class='mb-4'><p class='d-inline fw-semibold'>Source:</p>
            <p class='d-inline text-capitalize fst-italic'> $source</p>";
        echo "</div>";
        echo "<div class='mb-0'><p class='d-inline fw-semibold'>Rating:</p>
            <p class='d-inline text-capitalize fst-italic'> $overall</p>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
        echo "<div class='container mt-2 pt-2'>";
        # Rating form
        echo "<p class='h5'>Rating</p>";
        echo "<form action='' method='post'>
                <input type='hidden' name='recipelabel' id='recipelabel' value='$label'>
                <input type='hidden' name='recipeimage' id='recipeimage' value='$image'>
                0<input type='range' id='rating' name='rating'
                min='0' max='10' step='2' value='0' style='width:50%;'>5<br>
                <input type='text' name='comment' placeholder='Leave comment (optional)' style='width:40%; margin-bottom: 4px;'><br>
                <input type='submit' name='rate' value='Rate'>
                </form>";
                echo "<br>";
                echo "<br>";
        echo "<p class='h5'>Allergen & Health Advice</p>";
        echo "<div class='w-75 m-auto'>";
        echo "<div class='container mb-2 py-4 px-5 border rounded'>";
            foreach ($healthlabels as $health) {
                echo "<p class='d-inline text-capitalize fst-italic'>$health</p>, ";
            }
        echo "</div>";
        echo "</div>";
        echo "</div>";
        echo "<div class='container mt-2 pt-2 border rounded'>";
        echo "<div class='row'>";
        # Ingredients
        echo "<div class='col-6 border-end'>";
            echo "<p class='h4 pb-2 border-bottom border-4'>Ingredients</p>";
            foreach ($ingredients as $ingr) {
                echo "<p>$ingr</p>";
            }
            echo "<p class='h4 mt-4 pb-2 border-bottom border-4'>Instructions</p>";
            echo "<a class='btn btn-secondary' href='$instructions' role='button' target='_blank'>Find Instructions Here</a>";
            # Past ratings
            $conn = connectDB();
                $sql = "SELECT rating, comment FROM rating WHERE recipeid='$recipeID'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                  echo "<br>";
                      echo "<p class='h4 mt-4 pb-2 border-bottom border-4'>Ratings</p>";
                  echo "<table style='border-collapse: collapse; width: 100%; margin-bottom: 1rem;'>
              <tr>
                  <th style='border: 1px solid #ddd; padding: 8px; text-align: left; background-color: #f2f2f2; font-weight: bold;'>Rating</th>
                  <th style='border: 1px solid #ddd; padding: 8px; text-align: left; background-color: #f2f2f2; font-weight: bold;'>Comment</th>
              </tr>";
                // output data of each row
                $rowCounter = 0;
                while($row = $result->fetch_assoc()) {
                  $rowCounter++;
                  $backgroundColor = ($rowCounter % 2 === 0) ? '#f2f2f2' : '#ffffff';
                  echo "<tr style='background-color: " . $backgroundColor . ";'>
                  <td style='border: 1px solid #ddd; padding: 8px; text-align: left;'>".$row['rating']."</td>
                  <td style='border: 1px solid #ddd; padding: 8px; text-align: left;'>".$row['comment']."</td>
                  </tr>";
                  }
            echo "</table>";
          } else {
            echo "<br>";
                echo "<p class='h4 mt-4 pb-2 border-bottom border-4'>Ratings</p>";
            echo "There are no current ratings for this recipe. If you have made this recipe before please rate. We would love to hear your feeback. In order to rate a recipe, you must create an account.";
              echo "<br>";
                echo "<br>";
            echo "<p class='h5'>What did you think of this recipe on a scale from 1-5?</p>";
            echo "<form action='' method='post'>
                    <input type='hidden' name='recipelabel' id='recipelabel' value='$label'>
                    <input type='hidden' name='recipeimage' id='recipeimage' value='$image'>
                    0<input type='range' id='rating' name='rating'
                    min='0' max='10' step='2' value='0' style='width:50%;'>5<br>
                    <input type='text' name='comment' placeholder='Leave comment (optional)' style='width:40%; margin-bottom: 4px;'><br>
                    <input type='submit' name='rate' value='Rate'>
                    </form>";
          }
          closeDB($conn);
        echo "</div>";
        # Nutrition
        echo "<div class='col-6'>";
            echo "<p class='h4 pb-2 border-bottom border-4'>Nutrition Values</p>";
            echo "<table class='table table-striped w-75 m-auto'>
                    <thead>
                        <tr>
                        <th scope='col'>Nutrient</th>
                        <th scope='col'>Quantity</th>
                        <th scope='col'>Unit</th>
                        </tr>
                    </thead>
                    <tbody>";
                    foreach($nutrients as $nutrient) {
                    echo "<tr>
                            <td>".$nutrient['label']."</td>
                            <td>".round($nutrient['quantity'],2)."</td>
                            <td>".$nutrient['unit']."</td>
                        </tr>";
                    }
            echo "</tbody>
            </table>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
    }
}
?>
</body>
