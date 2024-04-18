<?php
include_once 'db.php';

session_start();

if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $fname = $lname = $uname = $bio = "-";
    # Get user details from DB
    $conn = connectDB();
    $sql = "SELECT * FROM users WHERE useremail = '$email'";
    $results = $conn->query($sql);
    if ($results->num_rows > 0) {
        $row = $results->fetch_assoc();
        $fname = ucfirst($row['firstname']);
        $lname = ucfirst($row['lastname']);
        $uname = $row['username'];
        $bio = $row['bio'];
        $profpic = $row['profilepic'];
    }
    #############################
    ### Edit profile ############
    #############################
    $edit = false;
    if (isset($_POST['editProfile'])) {
        $edit = true;
    } else if (isset($_POST['cancelEdit'])) {
        $edit = false;
    } else if (isset($_POST['submit'])) {
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $uname = $_POST['uname'];
        $bio = $_POST['bio'];
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["profpic"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        $uploadOk = 1;
        $check = false;
        if ($_FILES["profpic"]["name"] !== "") {
            $check = getimagesize($_FILES["profpic"]["tmp_name"]);
        }
        if($check !== false) {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
        }
        // Check file size
        if ($_FILES["profpic"]["size"] > 5000000) {
            $uploadOk = 0;
        }
        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            $profpic = $profpic;
        } else {
            if (move_uploaded_file($_FILES["profpic"]["tmp_name"], $target_file)) {
                $profpic = $target_file;
            }
        }
        # Update user details in DB
        $sql = "UPDATE users SET firstname='$fname', lastname='$lname', username='$uname', bio='$bio', profilepic='$profpic' WHERE useremail='$email'";
        $conn->query($sql);
        $conn->close();
        header('Location: ' . filter_var('https://cgi.luddy.indiana.edu/~team31/Profile.php', FILTER_SANITIZE_URL));
    }
} else {
    # Redirect to login page if user is not logged in
    header('Location: ' . filter_var('https://cgi.luddy.indiana.edu/~team31/home.php', FILTER_SANITIZE_URL));
}
?>

<?php include 'navbar.php';?>


  <div class="profile">

	<div class="container">
        <?php
        if ($edit) {
            echo '<form action="" method="post" enctype="multipart/form-data">';
        }
        ?>
			<div class="profile-header">
				<div class="profile-header__image">
					<?php
					if ($profpic == NULL) {
						echo '<img src="profilepic.jpg" alt="Profile Pic" title="Click Edit Profile to change your profile pic">';
					} else {
						echo "<img src='$profpic' alt='Profile Pic' title='Click Edit Profile to change your profile pic'>";
					}
					if ($edit) {
                        echo '<input type="file" name="profpic" title="Change your profile pic">';
                    }
					?>
				</div>
				<div class="profile-header__info">
					<h1 class="profile-header__name">
						<?php
						if ($edit) {
                            echo '<h2 class="profile-body__bio-title">First Name</h2>';
							echo '<input type="text" name="fname" value="' . $fname . '" placeholder="First Name">';
                            echo '<h2 class="profile-body__bio-title">Last Name</h2>';
							echo '<input type="text" name="lname" value="' . $lname . '" placeholder="Last Name">';
						} else {
                            echo "$fname $lname";
                        }
						?>
					</h1>
                    <h2 class="profile-body__bio-title">Username</h2>
					<p class="profile-header__username">
                        <?php
                        if ($edit) {
                            echo '<input type="text" name="uname" value="' . $uname . '" placeholder="Username">';
                        } else {
                            echo '<input type="text" name="uname" value="' . $uname . '" disabled title="Click Edit Profile to change your username">';
                        }
                        ?>
					</p>
					<h2 class="profile-body__bio-title">Email</h2>
					<p class="profile-header__email">
						<?php
						if ($email == NULL) {
							echo "-";
						} else {
							echo "$email";
						}
						?>
					</p>
				</div>
			</div>
			<div class="profile-body">
				<div class="profile-body__bio">
					<h2 class="profile-body__bio-title">Bio</h2>
					<p class="profile-body__bio-text">
						<?php

						if ($edit) {
							echo '<textarea cols="82" rows="5" name="bio" placeholder="Tell us something about yourself...">' . $bio . '</textarea>';
						} else {
                            echo '<textarea cols="82" rows="5" name="bio" placeholder="Tell us something about yourself..." disabled title="Click Edit Profile to change your bio">' . $bio . '</textarea>';
                        }
						?>
					</p>
				</div>
			</div>
			<?php
			if ($edit) {
				echo '<button type="submit" class="profile-header__edit-btn" name="submit">Save Changes</button>
                </form>';
                echo '<form action="Profile.php" method="post">
				<button type="submit" class="profile-header__cancel-edit-btn" name="cancelEdit">Cancel</button>
				</form>';
			} else {
				echo '<form action="Profile.php" method="post">
				<button type="submit" class="profile-header__edit-btn" name="editProfile">Edit Profile</button>
				</form>';
			}
			?>

	</div>

  </div>

<?php include 'footer.php';?>

</body>
