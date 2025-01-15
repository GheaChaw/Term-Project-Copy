<?php
// session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$config = include 'config.php';
require 'checkLogin.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email']) && isset($_POST['password'])) {
  $email = $password = "";
  $email = $_POST['email'];
  $password = $_POST['password'];

  $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['userDB']);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $sql = "SELECT * FROM user_profiles WHERE email = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc(); 

    if (password_verify($password, $user['password_hash'])) {
      login($user['user_id'], "../index.html");
      exit();
    } else {
      // echo "Invalid email or password.";
      $_SESSION['message'] = "Invalid email or password.";
      $_SESSION['message_type'] = "error";
      // header("Location: loginTest.php");
      exit();
    }
  } else {
    // echo "Invalid email or password.";
    $_SESSION['message'] = "Invalid email or password.";
    $_SESSION['message_type'] = "error";
    // header("Location: loginTest.php");
    exit();
  }
}
?>

<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <style>
    body{
      font-family: 'Comic Sans MS', sans-serif;
    }
    .message {
      padding: 10px;
      margin: 10px 0;
      border: 1px solid transparent;
      border-radius: 5px;
    }
    .message.success {
      color: #155724;
      background-color: #d4edda;
      border-color: #c3e6cb;
    }
    .message.error {
      color: #721c24;
      background-color: #f8d7da;
      border-color: #f5c6cb;
    }
  </style>
</head>
<body>
  <?php
  if (isset($_SESSION["message"])) {
    echo "<div class='message " . $_SESSION["message_type"] . "'>" . $_SESSION["message"] . "</div>";
    unset($_SESSION["message"]);
    unset($_SESSION["message_type"]);
  }
  ?>
  <h1>login</h1>
  <form action="" method="POST">
    <label for="email">Email:</label>
    <input type="text" name="email" required>
    <br>
    <label for="password">Password:</label>
    <input type="password" name="password" required>
    <br>
    <input type="submit" value="Login">
  </form>
</body>
</html>
