<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Retrieve ingredient to delete from form data
    $ingredient = $_POST["ingredient"];

    // Establish database connection
    $con = mysqli_connect("db.luddy.indiana.edu","i494f22_team31","my+sql=i494f22_team31","i494f22_team31");

    // Check connection
    if (mysqli_connect_errno()) {
        die("Failed to connect to MySQL: " . mysqli_connect_error());
    }

    // Escape ingredient for security
    $sanitized_ingredient = mysqli_real_escape_string($con, $ingredient);

    // Delete ingredient from User_Ingredients table
    $sql = "DELETE FROM User_Ingredients WHERE name = '$sanitized_ingredient'";

    if (mysqli_query($con, $sql)) {
        echo "<script>location.href = 'ingredients.php';</script>";
    } else {
        die("Error deleting ingredient: " . mysqli_error($con));
    }

    mysqli_close($con);
}

?>
