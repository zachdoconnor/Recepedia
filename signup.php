<?php
    # Include db file to connect to DB
    include_once 'db.php';
    
    $error = $username_error = $email_error = $password_error = $confirm_password_error = "";
    $invalid = False;

    # Function to insert a user into DB
    function insertUser($username, $email, $password) {
        # Connect to DB
        $conn = connectDB();

        # Check if user already exists by trying to retrieve a user with given username or email
        $sql = "SELECT * FROM users WHERE username='$username' OR useremail='$email'";
        $result = $conn->query($sql);
        # If the returned result has more than 0 rows, there's such a record, user exists
        if ($result->num_rows > 0) {
            # Display error
            $GLOBALS['error'] .= "User account already exists! Please <a href='home.php' style='text-decoration: none;'>log in</a>!<br>";
        } else {
            # If user doesn't already exist, insert new user
            # Query to insert the new user
            $sql = "INSERT INTO users(username, useremail, userpass) VALUES('$username', '$email', '$password')";       
            try {
                # Execute the query
                $conn->query($sql);
                # Log the user in before redirecting
                session_start();
                $_SESSION["email"] = '$email';
                # Redirect to profile page if user is created successfully
                header('Location: profile.php');
            } catch (Exception $e) {
                # If insertion failed, display error 
                $GLOBALS['error'] .= "Error occurred! Account not created!<br>";
            }
        }

        # Close the DB connection
        closeDB($conn);
    }

    # Get the user details
    if (isset($_POST['signup'])) {
        # For every field, first check that it's not empty, otherwise display an error and set invalid to true
        if (!empty($_POST['username'])) {
            $username = clean_data($_POST['username']);
        } else {
            $GLOBALS['username_error'] .= "Please enter a valid username!";
            $GLOBALS['invalid'] = True;
        }

        # Also validate email format besides checking it's not empty
        if (!empty($_POST['useremail']) && filter_var($_POST['useremail'], FILTER_VALIDATE_EMAIL)) {
            $email = clean_data($_POST['useremail']);
        } else {
            $GLOBALS['email_error'] .= "Please enter a valid email address!";
            $GLOBALS['invalid'] = True;
        }

        if (!empty($_POST['password'])) {
            # Compare passwords to make sure they match
            if (strcmp($_POST['confirmpass'], $_POST['password']) != 0) {
                $GLOBALS['confirm_password_error'] .= "Passwords do not match!";
                $GLOBALS['invalid'] = True;
            } else {
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            }    
        } else {
            $GLOBALS['password_error'] .= "Please enter a valid password!";
            $GLOBALS['invalid'] = True;
        }
        
        # Only insert user if invalid is false
        if ($GLOBALS['invalid'] == False) {
            insertUser($username, $email, $password);
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipedia | Sign Up</title>
    
</head>
<body style="margin: 0;">
    <div style="width: 100%; margin: 0; text-align: center; background-color: beige; padding: 2px; margin-bottom: 8px;">
        <h2>Navbar</h2>
    </div>
    <div style="width:20%; margin:auto; background-color:white; text-align:center; border:1px solid black;">
        <div style="border-bottom:1px solid black; text-align:center;">
            <h3>Sign Up</h3>
        </div>
        <div style="width:90%; margin:auto; margin-bottom:8px; padding:4px; background-color:white; text-align:center;">
            <img src="logo.jpg" alt="Logo">
            <p style="color: red;"><?php echo $GLOBALS['error'] ?></p>
            <form action="#" method="post" style="text-align:left; width:90%; margin:auto;">
                <label for="username">Username</label><br>
                <input type="text" name="username" id="" placeholder="" style="width:90%; margin:auto; padding:8px; background-color:white; margin-top:8px; margin-bottom:16px;"><br>
                <p style="color: red;"><?php echo $GLOBALS['username_error'] ?></p>
                <label for="username">Email Address</label><br>
                <input type="text" name="useremail" id="" placeholder="" style="width:90%; margin:auto; padding:8px; background-color:white; margin-top:8px; margin-bottom:16px;"><br>
                <p style="color: red;"><?php echo $GLOBALS['email_error'] ?></p>
                <label for="password">Password</label><br>
                <input type="password" name="password" id="" placeholder="" style="width:90%; margin:auto; padding:8px; background-color:white; margin-top:8px; margin-bottom:16px;">
                <p style="color: red;"><?php echo $GLOBALS['password_error'] ?></p>
                <label for="password">Confirm Password</label><br>
                <input type="password" name="confirmpass" id="" placeholder="" style="width:90%; margin:auto; padding:8px; background-color:white; margin-top:8px; margin-bottom:16px;">
                <p style="color: red;"><?php echo $GLOBALS['confirm_password_error'] ?></p>
                <input type="submit" name="signup" value="Create Account" style="width:99%; margin:auto; padding:8px; background-color:skyblue; color:white; margin-top:32px; margin-bottom:64px;">
            </form>
        </div>
    </div>
</body>
</html>