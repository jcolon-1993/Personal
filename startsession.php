<?php
  session_start();

  // If the seesion vars aren't set, try to set them with a cookie
  if (!isset($_SESSION['user_id']))
  {
    // If user is not logged in via the session, check to if the cookies are set
    if (isset($_COOKIE['user_id']) && isset($_COOKIE['username']))
    {
      // Sets sessions via cookies
      $_SESSION['user_id'] = $_COOKIE['user_id'];
      $_SESSION['username'] = $_COOKIE['username'];
    }
  }
?>
