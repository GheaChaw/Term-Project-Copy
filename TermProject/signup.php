<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

$config = include 'resources/config.php';

function validateEmail($email)
{
  $emailRegex = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
  return preg_match($emailRegex, $email);
}

$firstname = $lastname = $email = $password = "";
$invalidField = "";

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
    $invalidField = "email";
  } else {
    $conn = new mysqli(
      $config['servername'],
      $config['username'],
      $config['password'],
      $config['userDB']
    );

    if ($conn->connect_error) {
      $_SESSION['error'] = "Connection failed: " . $conn->connect_error;
    } else {
      // check if email already exists
      $emailCheckSql = "SELECT email FROM user_profiles WHERE email = ?";
      $emailCheckStmt = $conn->prepare($emailCheckSql);
      $emailCheckStmt->bind_param("s", $email);
      $emailCheckStmt->execute();
      $emailCheckStmt->store_result();

      if ($emailCheckStmt->num_rows > 0) {
        $_SESSION['error'] = "This email is already in use.";
        $invalidField = "email";
        $emailCheckStmt->close();
        $conn->close();
      } else {
        $emailCheckStmt->close();

        // insert new user record
        $insertUserSql = "INSERT INTO user_profiles (first_name, last_name, email, password_hash) VALUES (?, ?, ?, ?)";
        $insertUserStmt = $conn->prepare($insertUserSql);
        $insertUserStmt->bind_param("ssss", $firstname, $lastname, $email, $hashedPassword);

        if ($insertUserStmt->execute()) {
          $_SESSION['success'] = "New record created successfully.";

          $_SESSION["logged_in"] = true;
          $_SESSION["user_id"] = $conn->insert_id;
          header("Location: ./Roommate/roommateSurvey.php");
          exit();
        } else {
          $_SESSION['error'] = "Error: " . $insertUserStmt->error;
        }

        $insertUserStmt->close();
        $conn->close();
      }
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Signup</title>
  <link rel="stylesheet" href="index.css">
  <style>
    .success {
      color: #155724;
      background-color: #d4edda;
      border-color: #c3e6cb;
    }

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
  <?php if (!empty($_SESSION['success']) && empty($_SESSION['error'])): ?>
    <p class="success">new record created successfully</p>
  <?php elseif (!empty($_SESSION['error'])): ?>
    <p class="error"><?= htmlspecialchars($_SESSION['error']) ?></p>
    <?php unset($_SESSION['error']); ?>
  <?php endif; ?>

  <form action="" method="POST">
    <div class="imgcontainer">
      <img src="./resources/roomeaselogo.png" alt="Avatar" class="avatar">
    </div>

    <div class="container">
      <label for="first_name"><b>First Name</b></label>
      <input type="text" placeholder="Enter First Name" name="first_name" id="first_name" value="<?= htmlspecialchars($firstname) ?>" required>

      <label for="last_name"><b>Last Name</b></label>
      <input type="text" placeholder="Enter Last Name" name="last_name" id="last_name" value="<?= htmlspecialchars($lastname) ?>" required>

      <label for="email"><b>Email</b></label>
      <input type="text" placeholder="Enter Email" name="email" id="email" value="<?= htmlspecialchars($email) ?>" required>

      <label for="password"><b>Password</b></label>
      <input type="password" placeholder="Enter Password" name="password" id="password" value="<?= htmlspecialchars($password) ?>" required>

      <button type="submit">Signup</button>
    </div>

    <div class="container" style="background-color:#f1f1f1">
      <button type="button" class="cancelbtn" onclick="window.location.replace('/roomease/index.html')">Cancel</button>
      <span class="password"> <a href="login.php">Login Here</a></span>
    </div>
  </form>
</body>

</html>