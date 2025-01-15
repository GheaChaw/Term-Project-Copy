<?php
require 'resources/checkLogin.php'; // session started in checkLogin.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$config = include 'resources/config.php';

$email = $password = $invalidField = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email']) && isset($_POST['password'])) {
  $email = $_POST['email'];
  $password = $_POST['password'];

  $conn = new mysqli(
    $config['servername'], 
    $config['username'], 
    $config['password'], 
    $config['userDB']
  );

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
      login($user['user_id'], "./");
      exit();
    } else {
      $_SESSION['message'] = "Invalid password.";
      $_SESSION['message_type'] = "error";
      $invalidField = "password";
      // header("Location: " . $_SERVER['PHP_SELF']);
      // exit();
    }
  } else {
    $_SESSION['message'] = "Invalid email.";
    $_SESSION['message_type'] = "error";
    $invalidField = "email";
    // header("Location: " . $_SERVER['PHP_SELF']);
    // exit();
  }

  $stmt->close();
  $conn->close();

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link rel="stylesheet" href="index.css">
  <style>
    .error {
      color: #721c24;
      background-color: #f8d7da;
      border-color: #f5c6cb;
    }
  </style>
  <script>
    window.onload = function() {
      var invalidField = "<?= $invalidField ?>";
      if (invalidField) {
        document.getElementById(invalidField).focus();
      }
    };
  </script>
</head>

<body>
  <?php
  if (isset($_SESSION["message"])) {
    echo "<div class='message " . $_SESSION["message_type"] . "'>" . $_SESSION["message"] . "</div>";
    unset($_SESSION["message"]);
    unset($_SESSION["message_type"]);
  }
  ?>
  <form action="" method="POST">
    <div class="imgcontainer">
      <img src="./resources/roomeaselogo.png" alt="RoomEase" class="avatar">
    </div>

    <div class="container">
      <label for="email"><b>Email</b></label>
      <input type="text" id="email" placeholder="Enter Email" name="email" value="<?= htmlspecialchars($email) ?>" required>

      <label for="password"><b>Password</b></label>
      <input type="password" id="password" placeholder="Enter Password" name="password" value="<?= htmlspecialchars($password) ?>" required>

      <button type="submit">Login</button>
      
      <hr>
      <button type="button" class="cancelbtn" onclick="window.location.replace('/roomease/index.html')">Cancel</button>
      <!-- <span><a href="https://www.youtube.com/watch?v=dQw4w9WgXcQ">Forgot password?</a></span> -->
      <span class="password"><a href="signup.php" class = "other">Signup Here</a></span>
    </div>
  </form>
  
</body>

</html>