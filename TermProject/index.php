<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']) && $_SESSION['logged_in'] === true;
// echo "isLoggedIn: " . $isLoggedIn;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="index.css">
    <title>RoomEase</title>
    <style>
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .dropdown:hover .dropbtn {
            background-color: #3e8e41;
            font-family: 'Trebuchet MS', sans-serif;
        }
    </style>
</head>

<body class="homeBody">
    <!-- Some sort of menu or logo up here -->

    <div class="mainBox">
        <div id="logInSignUp">
            <?php if (!$isLoggedIn): ?>
                <a class="otherButtons" id="button1" href="login.php">Log In</a>
                <a class="otherButtons" href="signup.php">Sign Up</a>
            <?php else: ?>
                <div class="dropdown">
                    <button class="dropbtn">Account</button>
                    <div class="dropdown-content">
                        <a href="Roommate/roommateSurvey.php">Edit Preferences</a>
                        <a href="resources/logout.php">Log Out</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="imgBox">
            <img src="resources/roomeaselogo.png" alt="resources/roomeaselogo.png">
        </div>
        <a class="buttons" href="Roommate/RoommateFinder.php">Find Roomate</a>
        <a class="buttons" href="roomfinder/roomfinder.php">Find Housing</a>
    </div>


</body>

</html>