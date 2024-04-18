<?php
    # Declare database details
    $server = "db.luddy.indiana.edu";
    $database = "i494f22_team31";
    $user = "i494f22_team31";
    $pass = "my+sql=i494f22_team31";

    # Function to connect to DB
    function connectDB() {
        $conn = mysqli_connect($GLOBALS['server'], $GLOBALS['user'], $GLOBALS['pass'], $GLOBALS['database']) or
                                 die("Error : " .mysqli_error());
        return $conn;
    }

    # Function to close DB
    function closeDB($conn) {
        mysqli_close($conn);
    }

    # Function to clean data input
    function clean_data($input) {
        #Remove leading and trailing whitespaces
        $input = trim($input);
        #Remove special characters
        $input = stripslashes($input);
        #Escape quotes or code
        $input = htmlspecialchars($input);
        return $input;
    }
?>