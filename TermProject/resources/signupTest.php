<?php
session_start(); 


error_reporting(E_ALL);
ini_set('display_errors', 1);

$config = include 'config.php';

function validateEmail($email)
{
  $emailRegex = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
  return preg_match($emailRegex, $email);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $_SESSION['error'] = $_SESSION['success'] = "";

  $firstname = filter_input(INPUT_POST, 'first_name');
  $lastname = filter_input(INPUT_POST, 'last_name');
  $email = filter_input(INPUT_POST, 'email');
  $password = $_POST['password'];

  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  // validate email format
  if (!validateEmail($email)) {
    $_SESSION['error'] = "Invalid email format.";
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
  }

  $conn = new mysqli(
    $config['servername'],
    $config['username'],
    $config['password'],
    $config['userDB']
  );

  if ($conn->connect_error) {
    $_SESSION['error'] = "Connection failed: " . $conn->connect_error;
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
  }

  // check if email already exists
  $emailCheckSql = "SELECT email FROM user_profiles WHERE email = ?";
  $emailCheckStmt = $conn->prepare($emailCheckSql);
  $emailCheckStmt->bind_param("s", $email);
  $emailCheckStmt->execute();
  $emailCheckStmt->store_result();

  if ($emailCheckStmt->num_rows > 0) {
    $_SESSION['error'] = "This email is already in use.";
    $emailCheckStmt->close();
    $conn->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
  }

  $emailCheckStmt->close();

  // insert new user record
  $insertUserSql = "INSERT INTO user_profiles (first_name, last_name, email, password_hash) VALUES (?, ?, ?, ?)";
  $insertUserStmt = $conn->prepare($insertUserSql);
  $insertUserStmt->bind_param("ssss", $firstname, $lastname, $email, $hashedPassword);
  

  if ($insertUserStmt->execute()) {
    $_SESSION['success'] = "New record created successfully.";

    $_SESSION["logged_in"] = true;
    $_SESSION["user_id"] = $conn->insert_id;
    // $redir = "./surveyTest.php";

  } else {
    $_SESSION['error'] = "Error: " . $insertUserStmt->error;
  }

  $insertUserStmt->close();
  $conn->close();

  if (isset($_SESSION['logged_in']) && isset($_SESSION['user_id'])) {
    header("Location: ../Roommate/roommateSurvey.html");
  } else {
    header("Location: " . $_SERVER['PHP_SELF']);
  }

  exit();
}
ob_end_flush();
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

    .error {
      color: red;
    }

    .success {
      color: green;
    }
  </style>
</head>

<body>

  <h1>sign up</h1>

  <?php if (!empty($_SESSION['success']) && empty($_SESSION['error'])): ?>
    <p class="success">new record created successfully</p>
  <?php elseif (!empty($_SESSION['error'])): ?>
    <p class="error"><?= htmlspecialchars($_SESSION['error']) ?></p>
    <?php unset($_SESSION['error']); ?>
  <?php endif; ?>

  <form action="" method="POST">

    <label for="first_name">first name</label>
    <input type="text" id="first_name" name="first_name" required><br>

    <label for="last_name">last name</label>
    <input type="text" id="last_name" name="last_name" required><br>

    <label for="email">email</label>
    <input type="email" id="email" name="email" required><br>

    <label for="password">password</label>
    <input type="password" id="password" name="password" required><br>

    <button type="submit">submit form</button>

  </form>

</body>

</html>