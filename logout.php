<?php

session_start();

    session_destroy();
    //header('Location: http://localhost/recepedia/home.php');
    header('Location: ' . filter_var('https://cgi.luddy.indiana.edu/~team31/home.php', FILTER_SANITIZE_URL));


?>
