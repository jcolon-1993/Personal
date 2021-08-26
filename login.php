<?php

  // Insert the page header
  $page_title = 'Log in';
  require_once('header.php');

  require_once('connectvars.php');

  require_once('navmenu.php');


  // Start the session
  session_start();

  // Clear the error message
  $error_msg = "";

  // If the user isn't logged in, try to log them in.
  if (!isset($_SESSION['user_id']))
  {
    if (isset($_POST['submit']))
    {
      // Connect to the database
      $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

      // Grab the user log-in data
      $user_username = mysqli_real_escape_string($dbc, trim($_POST['username']));
      $user_password = mysqli_real_escape_string($dbc, trim($_POST['password']));

      if (!empty($user_username) && !empty($user_password))
      {
        // Look up the username and password in the database
        $query = "SELECT user_id, username FROM mismatch_user WHERE username = '$user_username'
                  And password = SHA('$user_password')";

        $data = mysqli_query($dbc, $query);

        // If row matches, then set the user_id and $username cookies and redirect to home page
        if (mysqli_num_rows($data) == 1)
        {
          $row = mysqli_fetch_array($data);
          $_SESSION['user_id'] = $row['user_id'];
          $_SESSION['username'] = $row['username'];
          $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SEVER['PHP_SELF']) .
          '/PHP_HeadFirst/Personal/index.php';
          header('Location: ' . $home_url);
        }
        else
        {
          // The username / password are incorrect so set an error message
          $error_msg = 'Sorry, you must enter a valid username and password to log in';
        }
      }
      else
      {
        // The username / password are incorrect so set an error message
        $error_msg = 'sorry, you must enter your username and password to log in.';
      }
    }
  }
?>
<!DOCTYPE html>
<html>
<head>
  <title>Mismatch - Log In</title>
  <link rel="stylesheet" type="text/css" href="css/styles.css" />
</head>
<body>
  <h3>Mismatch - Log in</h3>

  <?php
  // If the cookie is empty, show any error message and the log-in form;
  // otherwise confirm the log-in
    if (empty($_SESSION['user_id']))
    {
      echo '<p class="error">' . $error_msg . '</p>';

  ?>

  <form method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
    <fieldset>
      <label for="username">Username:</label>
      <input type="text" id="username" name="username"
      value="<?php if (!empty($user_username)) echo $user_username; ?>" /><br />
      <label for="password">Password:</label>
      <input type="password" id="password" name="password" />
    </fieldset>
    <input type="submit" value="Log in" name="submit" />
  </form>
  <?php
    }
    else
    {
      // Confirm the successful log in
      echo('<p class="login">You are logged in as ' . $_SESSION['username'] . '.</p>' . ' <a href="logout.php">Log Out</a></p>');
    }

    // Insert the page footer
    require_once('footer.php');
  ?>
