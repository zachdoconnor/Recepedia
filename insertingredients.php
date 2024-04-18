<?php
    $ingredient = $_POST['form_ingredients'];

    // Connect to the database
    $conn = mysqli_connect("db.luddy.indiana.edu", "i494f22_team31", "my+sql=i494f22_team31", "i494f22_team31");

    // Check if the ingredient already exists in the database
    $query = "SELECT * FROM User_Ingredients WHERE name = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $ingredient);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $num_rows = mysqli_num_rows($result);

    if ($num_rows == 0) {
        // Insert the ingredient into the database
        $query = "INSERT INTO User_Ingredients (name) VALUES (?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $ingredient);
        mysqli_stmt_execute($stmt);

        echo "<script>location.href = 'ingredients.php';</script>";
    } else {
        echo "<script>alert('This ingredient already exists in your pantry. Please enter a different ingredient.');</script>";
        echo "<script>location.href = 'ingredients.php';</script>";
    }

    // Close the database connection
    mysqli_close($conn);
?>
