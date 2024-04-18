<?php    
    include_once 'db.php';    

    session_start();

    if (isset($_SESSION['email'])) {
        header("Location: https://cgi.luddy.indiana.edu/~team31/api.php");
    }

       
    
    if (isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];       

        $sql = "SELECT * FROM users WHERE useremail='$username' or username='$username'";
        $conn = connectDB();
        $result = $conn->query($sql);
        if ($result->num_rows > 0) { 
            $row = $result->fetch_assoc();
            
            if (password_verify($password, $row["userpass"])) {              
                $_SESSION["email"] = $row['useremail'];
                header("Location: https://cgi.luddy.indiana.edu/~team31/api.php");
            }          
            
            
        } else {
            echo '<div class="container mt-2 alert alert-danger text-center" role="alert">Account not found! Please sign up!</div>';
        } 
        closeDB($conn);                   
    }   
    
?> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Home Page</h1>
    <div style="width:20%; margin:auto; padding:8px; background-color:lightgray; text-align:center;">
        <h3 style="font-size: 2em; margin-bottom: 1px;">Welcome back</h3>
        <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
        <div style="width:90%; margin:auto; margin-bottom:8px; padding:4px; background-color:white; text-align:center;">
            <a href="googlelogin.php"><img src="btn_google_signin_light_normal_web.png" alt=""></a>
            <p>----- Or continue with username/email -----</p>
            <form action="" method="post" style="text-align:left; width:90%; margin:auto;">
                <label for="username">Username or email address</label><br>
                <input type="text" name="username" id="" placeholder="" style="width:90%; margin:auto; padding:8px; background-color:white; margin-top:8px; margin-bottom:24px;"><br>
                <label for="password">Password</label><br>
                <input type="password" name="password" id="" placeholder="" style="width:90%; margin:auto; padding:8px; background-color:white; margin-top:8px; margin-bottom:16px;">
                <input type="submit" name="login" value="Sign In" style="width:99%; margin:auto; padding:8px; background-color:skyblue; color:white; margin-top:32px; margin-bottom:96px;">
            </form>
        </div>
    </div>
</body>
</html>


