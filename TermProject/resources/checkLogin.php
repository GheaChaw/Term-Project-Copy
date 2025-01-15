<?php
// checks if the user is logged in. separate file for reusability
session_start();

function login($id, $redir = "#", $doRedir = true)
{
  // echo "Login successful!";
  $_SESSION["logged_in"] = true;
  $_SESSION["user_id"] = $id;
  $_SESSION["message"] = "Login successful!";
  $_SESSION["message_type"] = "success";
  
  if(isset($_SESSION["return_to"])){
    header("Location: " . $_SESSION["return_to"]);
    unset($_SESSION["return_to"]);
    exit();
  } else if ($doRedir == true) {
    header("Location: $redir");
    exit();
  }
}

// if user is already logged in, redirect to home if on login page
// otherwise, protected page redirect to login
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true && isset($_SESSION['user_id'])) {
  // if already on login page, redirect to homepage
  if (basename($_SERVER['PHP_SELF']) == "loginTest.php") {
    login($_SESSION['user_id'], "../index.html");
  } else if (basename($_SERVER['PHP_SELF']) == "login.php") {
    login($_SESSION['user_id'], "./index.html");
  }
} else if(basename($_SERVER['PHP_SELF']) != "loginTest.php" && basename($_SERVER['PHP_SELF']) != "login.php"){
  $_SESSION["message"] = "You must be logged in to access this page!";
  $_SESSION["message_type"] = "error";
  $_SESSION["logged_in"] = false;
  $_SESSION["return_to"] = $_SERVER['PHP_SELF'];
  // echo "..." . basename($_SERVER['PHP_SELF']) . "...";
  header("Location: /roomease/login.php");
  exit();
}

?>