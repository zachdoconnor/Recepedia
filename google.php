<?php

require_once 'googleapi/vendor/autoload.php';
include_once 'db.php';
// Load the Google API client library

session_start();

// Create a new Google client object
$client = new Google_Client();
$client->setAuthConfig('client_secret.json'); // Set the path to your Google API credentials file
$client->setRedirectUri('https://cgi.luddy.indiana.edu/~team31/google.php');
//$client->setRedirectUri('http://localhost/recepedia/google.php');
$client->addScope(Google_Service_Oauth2::USERINFO_EMAIL); // Request access to the user's email address
$client->addScope(Google_Service_Oauth2::USERINFO_PROFILE); // Request access to the user's profile information

// Check if the user has granted permission to your app
if (isset($_GET['code'])) {
  $client->authenticate($_GET['code']); // Exchange the authorization code for an access token
  $_SESSION['access_token'] = $client->getAccessToken(); // Store the access token in a session variable
  header('Location: ' . filter_var($_SERVER['PHP_SELF'], FILTER_SANITIZE_URL)); // Redirect to the current page to clear the URL of the authorization code
}

// If the user has not granted permission to your app, redirect them to the Google login page
if (!isset($_SESSION['access_token'])) {
  $authUrl = $client->createAuthUrl();
  header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
  exit;
}

// If the user has granted permission to your app, get their profile information
$client->setAccessToken($_SESSION['access_token']);
$oauth2Service = new Google_Service_Oauth2($client);
$userInfo = $oauth2Service->userinfo->get();

$email = $userInfo->getEmail(); // Get the user's email address
$name = $userInfo->getName(); // Get the user's full name

$firstlast = explode(" ", $name, 2);
$firstname = $firstlast[0];
$lastname = $firstlast[1];

$name = str_replace("'", "", $name);

$conn = connectDB();
$sql = "SELECT * FROM users WHERE username='$name' and useremail='$email'";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
    $sql = "INSERT INTO users(username, useremail, firstname, lastname) VALUES('$name', '$email', '$firstname', '$lastname')";
    try {
        # Execute the query
        $conn->query($sql);
        # Log the user in before redirecting
        $_SESSION['email'] = $email;
        $_SESSION['name'] = $name;
        # Redirect to profile page if user is created successfully
    } catch (Exception $e) {
        echo "<div style='text-align:center; color:palevioletred;'>User account creation failed!</div>";
    }
}

# Log the user in before redirecting
$_SESSION['email'] = $email;
$_SESSION['name'] = $name;

echo $_SESSION['email'];
echo $_SESSION['name'];

//header('Location: ' . filter_var('http://localhost/recepedia/api.php', FILTER_SANITIZE_URL));
header('Location: ' . filter_var('https://cgi.luddy.indiana.edu/~team31/Profile.php', FILTER_SANITIZE_URL));

?>
