<?php
session_start(); 

error_reporting(E_ALL);
ini_set('display_errors', 1);

$config = include 'config.php';
if (isset($_SESSION["logged_in"]) && isset($_SESSION["user_id"])){
  echo "logged in: " . htmlspecialchars($_SESSION["logged_in"]) . "<br>";
  echo "user id: " . htmlspecialchars($_SESSION["user_id"]) . "<br>";

  $conn = new mysqli(
    $config['servername'],
    $config['username'],
    $config['password'],
    $config['userDB']
  );

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $sql = "SELECT first_name, last_name, email FROM user_profiles WHERE user_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $_SESSION["user_id"]);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc();
    echo "name: " . htmlspecialchars($user['first_name']) . " " . htmlspecialchars($user['last_name']) . "<br>";
    echo "email: " . htmlspecialchars($user['email']) . "<br>";
  } else {
    echo "not logged in";
  }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['logout'])) {
  // Destroy the session and redirect to login page
  session_destroy();
  header("Location: " . $_SERVER['PHP_SELF']); // Change this to the desired redirect page
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <style>
    body {
      font-family: 'Comic Sans MS', sans-serif;
    }
  </style>
</head>
<body>
  <form action="" method="POST">
    <button type="submit" name="logout">logout</button>
  </form>
</body>
</html>