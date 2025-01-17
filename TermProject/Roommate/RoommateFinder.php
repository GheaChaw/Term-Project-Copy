<?php
// header('Content-Type: application/json');

require '../resources/checkLogin.php';
$config = require '../resources/config.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$conn = new mysqli(
    $config['servername'],
    $config['username'],
    $config['password'],
    $config['userDB']
);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // get name from user_profiles, rest of prefs from user_prefs
    $sql = "SELECT user_profiles.first_name, user_profiles.last_name, user_prefs.* 
            FROM user_profiles
            JOIN user_prefs ON user_profiles.user_id = user_prefs.user_id
            LIMIT 5";
    $stmt = $conn->prepare($sql);
    // header("Location: http://www.google.com");
    // $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $users = $result->fetch_all(MYSQLI_ASSOC);
        echo /* "<script>console.log(" . */ json_encode($users) /* . ")</script>" */ ;
    } else {
        echo json_encode(array('error' => 'User not found'));
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- <meta http-equiv='refresh' content='0; URL= RoommateFinder.php'> -->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="roommate.css">
    <link rel="stylesheet" type="text/css" href="../resources/navbar.css">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
    <title>Document</title>
</head>

<body>
    <nav>
        <a href="../index.html"><img src="../resources/roomeaselogo.png" id="logoButton" alt="logo"></a>
        <ul>
            <li><a href="./RoommateFinder.php">Find a Roommate</a></li>
            <li><a href="../roomfinder/roomfinder.php">Find a Room</a></li>
            <li>
                <div class="dropdown">
                    <button class="dropbtn">Account</button>
                    <div class="dropdown-content">
                        <a href="./roommateSurvey.php">Edit Preferences</a>
                        <a href="../resources/logout.php">Logout</a>
                    </div>
                </div>
            </li>
        </ul>
    </nav>

    <div class="filter" id="roommateFilter">
        <span class="material-symbols-outlined">
            filter_list
        </span>Filter

    </div>

    <div id="formFilter">
        <!-- post for method or else it will send the data in the URL -->
        <form action="" method="POST" class="formyForm">

            <!-- only values with the name attribute will get sent when the form is submitted -->
            <legend id="filterTitle">Filter By:</legend>
            <button id="xButton">X</button>
            <fieldset>
                <label for="quiet-hours" class="label-title">Quiet Hours:</label>
                <select id="quiet-hours" name="quiet-hours">
                    <option value="morning">Morning</option>
                    <option value="afternoon">Afternoon</option>
                    <option value="evening">Evening</option>
                    <option value="night">Night</option>
                </select>
                <br><br>

                <!-- Bedtime -->
                <label for="bedtime" class="label-title">Bedtime:</label>
                <select id="bedtime" name="bedtime">
                    <option value="9pm">9 PM</option>
                    <option value="10pm">10 PM</option>
                    <option value="11pm">11 PM</option>
                    <option value="12am">12 AM</option>
                    <option value="1am-later">1AM - later</option>

                </select>
                <br><br>

                <!-- Wake Up Time -->
                <label for="wake-up-time" class="label-title">Wake Up Time:</label>
                <select id="wake-up-time" name="wake-up-time">
                    <option value="before-6am">Before 6 AM</option>
                    <option value="6am-8am">6 AM - 8 AM</option>
                    <option value="after-8am">After 8 AM</option>
                </select>
                <br><br>

                <!-- Preferred Gender -->
                <label for="gender" class="label-title">Preferred Gender:</label>
                <label for="male" class="indent">Male</label>
                <input type="radio" id="male" name="gender" value="male">
                <label for="female" class="indent">Female</label>
                <input type="radio" id="female" name="gender" value="female">
                <label for="no-preference" class="indent">No Preference</label>
                <input type="radio" id="no-preference" name="gender" value="no-preference">

                <br><br>

                <!-- Cleanliness Rating -->
                <label for="cleanliness" class="label-title">Cleanliness Rating (1-10):</label>
                <input type="number" id="cleanliness" name="cleanliness" min="1" max="10">
                <br><br>

                <!-- Social Level -->
                <label for="social-level" class="label-title">How social are you (1-10):</label>
                <input type="number" id="social-level" name="social-level" min="1" max="10">
                <br><br>

                <!-- Preferred Temperature -->
                <label for="temperature" class="label-title">Preferred Temperature (°F):</label>
                <input type="range" id="temperature" name="temperature" min="60" max="80">
                <br><br>

                <!-- Noise Level -->
                <label for="noise-level" class="label-title">Noise Level (1-10):</label>
                <input type="number" id="noise-level" name="noise-level" min="1" max="10">
                <br><br>

                <!-- Wanted Privacy Level -->
                <label for="privacy-level" class="label-title">Wanted Privacy Level:</label>
                <label for="low-privacy" class="indent">Low</label>
                <input type="radio" id="low-privacy" name="privacy-level" value="low">
                <label for="medium-privacy" class="indent">Medium</label>
                <input type="radio" id="medium-privacy" name="privacy-level" value="medium">
                <label for="high-privacy" class="indent">High</label>
                <input type="radio" id="high-privacy" name="privacy-level" value="high">
                <br><br>

                <!-- Morning or Night Person -->
                <label for="day-person" class="label-title">Morning or Night Person:</label>

                <label for="morning-person" class="indent">Morning</label>
                <input type="radio" id="morning-person" name="day-person" value="morning">
                <label for="night-person" class="indent">Night</label>
                <input type="radio" id="night-person" name="day-person" value="night">
                <br><br>

                <!-- Snorer -->
                <label for="snore-level" class="label-title">Snorer:</label>
                <label for="no-snore" class="indent">No</label>
                <input type="radio" id="no-snore" name="snore-level" value="no">
                <label for="light-snore" class="indent">Light</label>
                <input type="radio" id="light-snore" name="snore-level" value="light">
                <label for="medium-snore" class="indent">Medium</label>
                <input type="radio" id="medium-snore" name="snore-level" value="medium">
                <label for="heavy-snore" class="indent">Heavy</label>
                <input type="radio" id="heavy-snore" name="snore-level" value="heavy">
                <br><br>

                <!-- Pets -->
                <label for="pets" class="label-title">Pets:</label>
                <label for="yes-pets" class="indent">Yes</label>
                <input type="radio" id="yes-pets" name="pets" value="yes">
                <label for="no-pets" class="indent">No</label>
                <input type="radio" id="no-pets" name="pets" value="no">
                <br><br>

            </fieldset>

            <button>Filter</button>

        </form>
    </div>

    <div id="card">
        <div class="box">
        </div>

    </div>

    <script src="../resources/resources/jquery-1.4.3.min.js"></script>
    <script src="./RoommateFinder.js"></script>
</body>

</html>