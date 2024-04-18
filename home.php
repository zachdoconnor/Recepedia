<?php
    include_once 'db.php';

    session_start();

    if (isset($_SESSION['email'])) {
        # If already logged in, redirect to list of recipes homepage
        // header("Location: http://localhost/recepedia/api.php");
        header('Location: ' . filter_var('https://cgi.luddy.indiana.edu/~team31/Profile.php', FILTER_SANITIZE_URL));
    }

    $error = "";

    # Get user login form details
    if (isset($_POST['login'])) {
        # Get username/email
        $username = $_POST['username'];
        # Get password
        $password = $_POST['password'];

        # Retrieve corresponding user from DB based on the email or username
        $sql = "SELECT * FROM users WHERE useremail='$username' or username='$username'";
        # Connect to DB and execute query
        $conn = connectDB();
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            # Get the returned user
            $row = $result->fetch_assoc();
            # Compare the passwords using password_verify
            if (password_verify($password, $row["userpass"])) {
                # Set email session variable to mark user as logged in
                $_SESSION["email"] = $row['useremail'];
                $_SESSION["name"] = $row['username'];
                # Redirect to recipes page
                // header("Location: http://localhost/recepedia/api.php");
                header('Location: ' . filter_var('https://cgi.luddy.indiana.edu/~team31/Profile.php', FILTER_SANITIZE_URL));
            }
        } else {
            # Display error if account is not existent
            header('Location: ' . filter_var('https://cgi.luddy.indiana.edu/~team31/home.php', FILTER_SANITIZE_URL));

        }

        closeDB($conn);
    }

?>

<?php include 'navbar.php';?>

<div class="login">

  <br>
  <br>
  <br>

  <div class="container">
    <div style="text-align: center;">
      <img src="images/Home-Logo.PNG" alt="Logo">
    </div>
    <h2>Welcome Back!</h2>
    <p>Feeling Hungry? Please login so we can start cooking!</p>
    <div>
      <a href="google.php">
        <button>
          <img src="btn_google_signin_light_normal_web.png" alt="Google">
        </button>
      </a>
    </div>
  </div>

</div>
<?php include 'footer.php';?>
</body>
</html>
