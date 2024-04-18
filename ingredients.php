<?php
    include 'db.php';
    include 'navbar.php';
    include 'edamamapi.php';

    if (isset($_SESSION['email'])) {
        $email = $_SESSION['email'];
    } else {
        header('Location: ' . filter_var('https://cgi.luddy.indiana.edu/~team31/home.php', FILTER_SANITIZE_URL));
    }

    $error = "none";

    if (isset($_POST['add_ingredient'])) {
        if (isset($_POST['ingredient'])) {
            $ingredient = $_POST['ingredient'];


            $sql = "INSERT INTO User_Ingredients VALUES ('$ingredient', '$email')";

            $conn = connectDB();

            try {
                $conn->query($sql);
            } catch (Exception $e) {
                // echo "<div style='background-color:red;color:white;text-align:center;padding:4px;'><p>Error adding ingredient</p></div>"; // Replace
                $error = "block";
            }

            closeDB($conn);
        }
    }
    $recipes = [];
    $ingredients = [];
    $meal = [];
    $cuisine = [];
    $max_pages = 8;
    if (isset($_GET['search'])) {
        if(!empty($_GET['checkbox'])) {
            $ingredients = $_GET['checkbox'];
            $page = isset($_GET['page']) ? $_GET['page'] : 1;

            $search_results = search_recipes($ingredients, $page, $meal, $cuisine, $max_pages);

            $recipes = $search_results['recipes'];
            $total_pages = $search_results['total_pages'];
        }
    }
  ?>

<div>

  <div class="row" style="width: 100%; margin:0px;">
      <div class="col-2" style="border-right: 1px solid black; text-align:center; padding:0px;">
          <div style="border-bottom: 1px solid #ccc; background-color: #5bc0de; color: White;">
              <h3>Your Pantry</h3>
          </div>
          <div class="ingredients_table">
              <style>
                  table {
                      width: 100%;
                      border-collapse: collapse;
                      border: 2px solid #ccc;
                  }

                  th, td {
                      border: 1px solid #ccc;
                      padding: 8px;
                  }

              </style>
              <?php
                  $conn = connectDB();

                  $sql = "SELECT name from User_Ingredients WHERE userid='$email'";
                  $result = $conn->query($sql);

                  if ($result->num_rows > 0) {
                  echo "<form action='' method='get'>
                  <table><tr><th>Select</th><th>Ingredient</th><th>Action</th></tr>";
                  // output data of each row
                  $count = 0;
                  while($row = $result->fetch_assoc()) {
                      echo "
                      <tr><td><input type='checkbox' name='checkbox[]' value='".$row['name']."'></td>
                      <td>".$row['name']."</td>
                              <td>
                                  <form action='deleteingredient.php' method='post'>
                                      <input type='hidden' name='ingredient' value='".$row['name']."'>
                                      <input type='submit' value='Delete'>
                                  </form>
                              </td>

                          </tr>";
                  }
                  echo "</table>
                      </form>";
                  } else {
                  echo "Pantry is empty";
                  }
                  closeDB($conn);
              ?>
        <p>Please select the check boxes of the ingredients you would like to search by. Then, select the button below.</p>
        <input type='submit' name='search' value='Find Recipes' style='width:90%;background-color:#5bc0de; margin-top: 10px;'>
        </form>
        </div>

        </div>
        <!-- Begin right side of page -->
        <div class="col-10" style="padding:0; text-align:center; ">
            <div class ="insert" style="border-bottom:1px solid #ccc; width:100%;background-color: white;">
                <form action="" method="post">
                    <h3>What's In Your Pantry?</h3>

                    Please enter your ingredients (one at a time):<br>
                    <input type="text" name= "ingredient" required><br>

                    <input type="submit" name="add_ingredient" value ="Add Ingredients" ><br>
                </form>
            </div>

            <!--The recipes-->
            <div>
                <?php
                echo "<div style='text-align:center; width:100%; background-color:#5bc0de;padding:4px; '>";
                $str_ingr = "";
                echo '<div style="width: 100%; text-align: center; padding: 8px; font-size: 1.5em;">';
                echo 'Here are all the recipes that contain the following: <br>';
                echo 'Ingredients: '.ucwords(implode(", ", $ingredients),",").'<br>';
                if (count($meal) > 0) {
                echo 'Meal Types: '.implode(", ", $meal).'<br>';
                }
                if (count($cuisine) > 0) {
                echo 'Cuisine: '.implode(", ", $cuisine).'<br>';
                }
                echo '</div>';
                echo "<h3><i>$str_ingr</i></h3>";
                echo "</div>";
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
                echo '<div class="text-center" style="border-top: 1px solid black; width:100%; padding:16px; ">';
                for ($i = 1; $i <= $max_pages; $i++) {
                    if ($i == $page) {
                        // Current page, do not link
                        echo '<span class="current" style="border: 1px solid black; background-color:#f5f5f5; border-radius:4px; margin-right:4px; padding:4px; text-decoration:none;">Page ' . $i . '</span>';
                    } else {
                        // Other pages, link to the page
                        echo '<a style="border: 1px solid black; border-radius:4px; background-color:#f5f5f5; margin-right:8px; padding:4px; text-decoration:none;" href="ingredients.php?' . http_build_query(array_merge($_GET, array('page' => $i))) . '">Page ' . $i . '</a>';
                    }
                }
                echo '</div>';
                echo '</div>';
                ?>
            </div>

        </div>
    </div>
</div>

<?php include 'footer.php';?>

</body>
</html>
