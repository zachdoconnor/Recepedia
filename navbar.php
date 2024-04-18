<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recipedia</title>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDOrzfkg3-vo4RVc81yGasj1rvrNsVq3Ao&libraries=places"></script>
  <script src="maps.js"></script>
  <script src="near_me.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
          integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
          crossorigin="anonymous"></script>
  <link rel="stylesheet" href="Traditional_Dishes.css">
  <link rel="stylesheet" href="home.css">
  <link rel="stylesheet" href="Missing_Ingredients.css">
  <link rel="stylesheet" href="navbar.css">
  <link rel="stylesheet" href="login.css">
  <link rel="stylesheet" href="Profile.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD"
            crossorigin="anonymous">
</head>

<body onload="initMap()" id = "MI_body">
  <nav>
    <input type="checkbox" id="check">
    <label for="check" class="checkbtn">
      <i class="fas fa-bars"></i>
    </label>
    <label class="logo">Recipedia</label>
    <ul style = "display: inline;">
      <li><a href="https://cgi.luddy.indiana.edu/~team31/Recipedia_Home.php"><img src="images/home.png" width="35" height="35" alt="Home"></a></li>
      <li><a href="https://cgi.luddy.indiana.edu/~team31/ingredients.php">Virtual Pantry</a></li>
      <li><a href="https://cgi.luddy.indiana.edu/~team31/Whats_Hot.php">Whats Hot</a></li>
      <li><a href="https://cgi.luddy.indiana.edu/~team31/Traditional_Dishes.php">Traditional Dishes</a></li>
      <li><a href="https://cgi.luddy.indiana.edu/~team31/Missing_Ingredients.php">Missing Ingredients?</a></li>

      <?php
        session_start();

        if (isset($_SESSION['email'])) {
          echo"<li><a href='https://cgi.luddy.indiana.edu/~team31/logout.php'>Logout</a></li>";
        }

        else {
        echo "<li><a href='https://cgi.luddy.indiana.edu/~team31/home.php'>Login</a></li>";
        }
      ?>
        <li><a href="https://cgi.luddy.indiana.edu/~team31/Profile.php"><img src="images/profile.png" width="35" height="35" alt="What's Hot"></a></li>
    </ul>
  </nav>
  <section id="hamburger" style="display: none;"></section>

<script>
  const hamburger = document.querySelector('.checkbtn');
  const mainSection = document.getElementById('hamburger');

  hamburger.addEventListener('click', () => {
    if (mainSection.style.display === 'none') {
      mainSection.style.display = 'block';
    } else {
      mainSection.style.display = 'none';
    }
  });
</script>
