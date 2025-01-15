<?php
require '../resources/checkLogin.php';
$config = require '../resources/config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli(
  $config['servername'],
  $config['username'],
  $config['password'],
  $config['userDB']
);

if ($conn->connect_error) {
  $_SESSION['message'] = "Connection failed: " . $conn->connect_error;
  $_SESSION['message_type'] = "error";
  header("Location: " . $_SERVER['PHP_SELF']);
  exit();
}

// validate time format. 24hr clock (00:00 - 23:59), str in format HH:MM
function validateTime($time) {
  $timeRegex = "/^(([01]?[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9])|(([01]?[0-9]|2[0-3]):[0-5][0-9])$/";
  return preg_match($timeRegex, $time);
}

// validate rating to be between 1-10. applies to cleanliness_rating, noise_level
function validateOneToTen($rating) {
  return $rating >= 1 && $rating <= 10;
}

// validate temperature to be between 60-80
function validateTemperature($temperature) {
  return $temperature >= 60 && $temperature <= 80;
}

// validate major string to be less than 255 chars
function validateStr($str) {
  return strlen($str) <= 255;
}

// validate enum and its options
function validateEnum($value, $options) {
  return in_array($value, $options);
}

function validate(
  $quiet_start,
  $quiet_end,
  $bedtime,
  $wake_up,
  $gender,
  $clean,
  $guest_policy,
  $sharing_policy,
  $media_likes,
  $temperature,
  $noise,
  $privacy,
  $morningNight,
  $snorer,
  $major,
  $allergies,
  $pets,
  $about_me,
  $interests
) {

  $isValid = true;
  $invalid = "Invalid: ";

  $times = [$quiet_start, $quiet_end, $bedtime, $wake_up];
  $oneToTen = [$clean, $noise];
  $enums = array(
    $gender => ['Male', 'Female', 'Non-binary', 'Other'],
    $privacy => ['Low', 'Medium', 'High'],
    $morningNight => ['Morning', 'Night', 'Neither'],
    $snorer => ['No', 'Low', 'Medium', 'High'],
    $pets => ['Yes', 'No'],
  );
  $strings = [$guest_policy, $sharing_policy, $media_likes, $major, $allergies, $about_me, $interests];


  foreach ($times as $time) {
    if (!validateTime($time)) {
      $isValid = false;
      $invalid .= "time " . $time . ", ";
    }
  }

  foreach ($oneToTen as $rating) {
    if (!validateOneToTen($rating)) {
      $isValid = false;
      $invalid .= "rating " . $rating . ", ";
    }
  }

  foreach ($enums as $value => $options) {
    if (!validateEnum($value, $options)) {
      $isValid = false;
      $invalid .= "option \"" . $value . "\", ";
    }
  }

  if (!validateTemperature($temperature)) {
    $isValid = false;
    $invalid .= "temperature " . $temperature . ", ";
  }

  foreach ($strings as $string) {
    if (!validateStr($string)) {  
      $isValid = false;
      $invalid .= "too many characters, ";
    }
  }

  return [
    'isValid' => $isValid, 
    'invalid_vals' => $invalid
  ];
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $quiet_start = htmlspecialchars($_POST['quiet-hours-start']);
  $quiet_end = htmlspecialchars($_POST['quiet-hours-end']);
  $bedtime = htmlspecialchars($_POST['bedtime']);
  $wake_up = htmlspecialchars($_POST['wake-up-time']);
  $gender = htmlspecialchars($_POST['preferred_gender']);
  $clean = htmlspecialchars($_POST['cleanliness_rating']);
  $guest_policy = htmlspecialchars($_POST['guest_policy']);
  $sharing_policy = htmlspecialchars($_POST['sharing_policy']);
  $media_likes = htmlspecialchars($_POST['media_likes']);
  $temperature = htmlspecialchars($_POST['temperature']);
  $noise = htmlspecialchars($_POST['noise_level']);
  $privacy = htmlspecialchars($_POST['wanted_privacy']);
  $morningNight = htmlspecialchars($_POST['morning_or_night_person']);
  $snorer = htmlspecialchars($_POST['snorer']);
  $major = htmlspecialchars($_POST['major']);
  $allergies = htmlspecialchars($_POST['allergies']);
  $pets = htmlspecialchars($_POST['pets']);
  $about_me = htmlspecialchars($_POST['about_me']);
  $interests = htmlspecialchars($_POST['general_interests']);

  $validation = validate(
    $quiet_start,
    $quiet_end,
    $bedtime,
    $wake_up,
    $gender,
    $clean,
    $guest_policy,
    $sharing_policy,
    $media_likes,
    $temperature,
    $noise,
    $privacy,
    $morningNight,
    $snorer,
    $major,
    $allergies,
    $pets,
    $about_me,
    $interests
  );


  // // test invalid vals
  // $strTooLong = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
  //                Suspendisse suscipit diam et finibus commodo. Sed faucibus rutrum commodo. 
  //                Aliquam sodales ac risus nec posuere. Nunc feugiat nunc velit, sit amet ornare 
  //                mauris vulputate in. In venenatis, sapien et auctor fermentum, diam quam vestibulum 
  //                quam, a venenatis eros sapien quis nisl. Praesent mollis neque ut orci cursus 
  //                hendrerit tempor nec magna. Proin non venenatis felis. Phasellus non maximus risus, 
  //                fringilla gravida sem. Suspendisse interdum justo at nulla cursus, eget faucibus 
  //                justo egestas. Morbi aliquam arcu vel tincidunt consequat. Cras blandit vel enim ut 
  //                placerat. Sed lobortis arcu vel mauris vehicula varius. Maecenas condimentum lacus id 
  //                felis congue, eu lacinia dolor viverra. Pellentesque habitant morbi tristique senectus 
  //                et netus et malesuada fames ac turpis egestas. Phasellus sapien leo, dictum id dui 
  //                molestie, euismod scelerisque tellus. Vivamus rhoncus ligula ut sapien ";
  // $validation = validate(
  //   "24:00",
  //   "8:00 PM",
  //   "eight o'clock",
  //   "sleep(10000000)",
  //   "Attack Helicopter",
  //   11,
  //   $strTooLong,
  //   $strTooLong,
  //   $strTooLong,
  //   100,
  //   0,
  //   "leave me alone",
  //   "Afternoon",
  //   "certified snoozer male",
  //   $strTooLong,
  //   $strTooLong,
  //   "i have a pet rock",
  //   $strTooLong,
  //   $strTooLong
  // );

  
  if (!$validation['isValid']) {
    $_SESSION['message'] = $validation['invalid_vals'];
    $_SESSION['message_type'] = "error";
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
  }

  /*
  make new record if doesnt exist, update if it does exist

  1	  user_id                 	int(11)               Primary key
  2	  quiet_hours_start	        time			
  3	  quiet_hours_end 	        time			
  4	  bed_time	                time			
  5 	wake_up_time	            time			
  6	  preferred_gender	        enum('Male', 'Female', 'Non-binary', 'Other')			
  7	  cleanliness_rating  	    tinyint(4)			
  8 	guest_policy  	          varchar(255)			
  9	  sharing_policy	          varchar(255)			
  10	media_likes	              varchar(255)			
  11	temperature	              tinyint(4)			
  12	noise_level	              tinyint(4)			
  13	wanted_privacy  	        enum('Low', 'Medium', 'High')			
  14	morning_or_night_person	  enum('Morning', 'Night', 'Neither')			
  15	snorer  	                enum('No', 'Low', 'Medium', 'High')			
  16	major	                    varchar(255)			
  17	allergies	                varchar(255)			
  18	pets	                    enum('Yes', 'No')			
  19	about_me	                text			
  20	general_interests 	      text			
  */

  // big ass sql statement for all 20 cols
  // accounts for if the user already has a record, updates instead of insert
  $sql = "INSERT INTO user_prefs (
            user_id,
            quiet_hours_start,
            quiet_hours_end,
            bed_time,
            wake_up_time,
            preferred_gender,
            cleanliness_rating,
            guest_policy,
            sharing_policy,
            media_likes,
            temperature,
            noise_level,
            wanted_privacy,
            morning_or_night_person,
            snorer,
            major,
            allergies,
            pets,
            about_me,
            general_interests
          )
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
          ON DUPLICATE KEY UPDATE
            quiet_hours_start = VALUES(quiet_hours_start),
            quiet_hours_end = VALUES(quiet_hours_end),
            bed_time = VALUES(bed_time),
            wake_up_time = VALUES(wake_up_time),
            preferred_gender = VALUES(preferred_gender),
            cleanliness_rating = VALUES(cleanliness_rating),
            guest_policy = VALUES(guest_policy),
            sharing_policy = VALUES(sharing_policy),
            media_likes = VALUES(media_likes),
            temperature = VALUES(temperature),
            noise_level = VALUES(noise_level),
            wanted_privacy = VALUES(wanted_privacy),
            morning_or_night_person = VALUES(morning_or_night_person),
            snorer = VALUES(snorer),
            major = VALUES(major),
            allergies = VALUES(allergies),
            pets = VALUES(pets),
            about_me = VALUES(about_me),
            general_interests = VALUES(general_interests)";

  $stmt = $conn->prepare($sql);
  $stmt->bind_param(
    "isssssisssiissssssss",
    $_SESSION['user_id'],           //i
    $quiet_start,
    $quiet_end,
    $bedtime,
    $wake_up,
    $gender,
    $clean,
    $guest_policy,
    $sharing_policy,
    $media_likes,
    $temperature,
    $noise,
    $privacy,
    $morningNight,
    $snorer,
    $major,
    $allergies,
    $pets,
    $about_me,
    $interests
  );

  if ($stmt->execute()) {
    $_SESSION['message'] = "Preferences saved successfully.";
    $_SESSION['message_type'] = "success";
  } else {
    $_SESSION['message'] = "Error: " . $stmt->error;
    $_SESSION['message_type'] = "error";
  }

  $stmt->close();
  $conn->close();

  header("Location: " . $_SERVER['PHP_SELF']);
  // header("Location: https://www.google.com");
  exit();
} else {
  // prefill form if user already has a record
  $sql = "SELECT * FROM user_prefs WHERE user_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $_SESSION['user_id']);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($result->num_rows > 0) {
    $preferences = $result->fetch_assoc();
  }
}

$conn->close()

  ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="survey.css">
  <title>Roommate Preferences Form</title>

  <style>
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

  <script>
    function updateTemperatureValue(value) {
      document.getElementById('temperature-value').textContent = value;
    }

    // Initialize the temperature value on page load
    document.addEventListener('DOMContentLoaded', function () {
      var temperatureInput = document.getElementById('temperature');
      updateTemperatureValue(temperatureInput.value);
    });
  </script>

  <h1>Roommate Preferences Form</h1>
  <div class="back">
    <form action="" method="POST">
      <!-- 24 hr clock. cant be bothered to make it am/pm, rpi should know how to subtract 12 -->
      <!-- Quiet Hours Start -->
      <label for="quiet-hours-start" class="label-title">Quiet Hours Start:</label>
      <input type="time" id="quiet-hours-start" name="quiet-hours-start"
        value="<?php echo htmlspecialchars($preferences['quiet_hours_start'] ?? ''); ?>">
      <br><br>

      <!-- Quiet Hours End -->
      <label for="quiet-hours-end" class="label-title">Quiet Hours End:</label>
      <input type="time" id="quiet-hours-end" name="quiet-hours-end"
        value="<?php echo htmlspecialchars($preferences['quiet_hours_end'] ?? ''); ?>">
      <br><br>

      <!-- Bedtime -->
      <label for="bedtime" class="label-title">Bedtime:</label>
      <input type="time" id="bedtime" name="bedtime"
        value="<?php echo htmlspecialchars($preferences['bed_time'] ?? ''); ?>">
      <br><br>

      <!-- Wake Up Time -->
      <label for="wake-up-time" class="label-title">Wake Up Time:</label>
      <input type="time" id="wake-up-time" name="wake-up-time"
        value="<?php echo htmlspecialchars($preferences['wake_up_time'] ?? ''); ?>">
      <br><br>

      <!-- Preferred Gender -->
      <label class="label-title">Preferred Gender:</label>
      <label for="male" class="indent">Male</label>
      <input type="radio" id="male" name="preferred_gender" value="Male" <?php echo (isset($preferences['preferred_gender']) && $preferences['preferred_gender'] == 'Male') ? 'checked' : ''; ?>>
      <label for="female" class="indent">Female</label>
      <input type="radio" id="female" name="preferred_gender" value="Female" <?php echo (isset($preferences['preferred_gender']) && $preferences['preferred_gender'] == 'Female') ? 'checked' : ''; ?>>
      <label for="non-binary" class="indent">Non-binary</label>
      <input type="radio" id="non-binary" name="preferred_gender" value="Non-binary" <?php echo (isset($preferences['preferred_gender']) && $preferences['preferred_gender'] == 'Non-binary') ? 'checked' : ''; ?>>
      <label for="other" class="indent">Other</label>
      <input type="radio" id="other" name="preferred_gender" value="Other" <?php echo (isset($preferences['preferred_gender']) && $preferences['preferred_gender'] == 'Other') ? 'checked' : ''; ?>>
      <br><br>

      <!-- Cleanliness Rating -->
      <label for="cleanliness_rating" class="label-title">Cleanliness Rating (1-10):</label>
      <input type="number" id="cleanliness_rating" name="cleanliness_rating" min="1" max="10"
        value="<?php echo htmlspecialchars($preferences['cleanliness_rating'] ?? ''); ?>">
      <br><br>

      <!-- Guest Policy -->
      <label for="guest_policy" class="label-title">Guest Policy:</label>
      <textarea rows="5" id="guest_policy"
        name="guest_policy"><?php echo htmlspecialchars($preferences['guest_policy'] ?? ''); ?></textarea>
      <br><br>

      <!-- Sharing Items Policy -->
      <label for="sharing_policy" class="label-title">Sharing Items Policy:</label>
      <textarea rows="5" id="sharing_policy"
        name="sharing_policy"><?php echo htmlspecialchars($preferences['sharing_policy'] ?? ''); ?></textarea>
      <br><br>

      <!-- Music/Movies/TV Shows -->
      <label for="media_likes" class="label-title">Music/Movies/TV Shows (Genres):</label>
      <textarea rows="5" id="media_likes"
        name="media_likes"><?php echo htmlspecialchars($preferences['media_likes'] ?? ''); ?></textarea>
      <br><br>

      <!-- Preferred Temperature -->
      <label for="temperature" class="label-title">Preferred Temperature (°F):</label>
      <div class="slider-container">
      <input type="range" id="temperature" name="temperature" min="60" max="80"
      value="<?php echo htmlspecialchars($preferences['temperature'] ?? ''); ?>"
      oninput="updateTemperatureValue(this.value)">
      <span id="temperature-value"><?php echo htmlspecialchars($preferences['temperature'] ?? ''); ?></span>
      </div>
      <br><br>


      <!-- Noise Level -->
      <label for="noise_level" class="label-title">Noise Level (1-10):</label>
      <input type="number" id="noise_level" name="noise_level" min="1" max="10"
        value="<?php echo htmlspecialchars($preferences['noise_level'] ?? ''); ?>">
      <br><br>

      <!-- Wanted Privacy Level -->
      <label class="label-title">Wanted Privacy Level:</label>
      <label for="low-privacy" class="indent">Low</label>
      <input type="radio" id="low-privacy" name="wanted_privacy" value="Low" <?php echo (isset($preferences['wanted_privacy']) && $preferences['wanted_privacy'] == 'Low') ? 'checked' : ''; ?>>
      <label for="medium-privacy" class="indent">Medium</label>
      <input type="radio" id="medium-privacy" name="wanted_privacy" value="Medium" <?php echo (isset($preferences['wanted_privacy']) && $preferences['wanted_privacy'] == 'Medium') ? 'checked' : ''; ?>>
      <label for="high-privacy" class="indent">High</label>
      <input type="radio" id="high-privacy" name="wanted_privacy" value="High" <?php echo (isset($preferences['wanted_privacy']) && $preferences['wanted_privacy'] == 'High') ? 'checked' : ''; ?>>
      <br><br>

      <!-- Morning or Night Person -->
      <label class="label-title">Morning or Night Person:</label>
      <label for="morning-person" class="indent">Morning</label>
      <input type="radio" id="morning-person" name="morning_or_night_person" value="Morning" <?php echo (isset($preferences['morning_or_night_person']) && $preferences['morning_or_night_person'] == 'Morning') ? 'checked' : ''; ?>>
      <label for="night-person" class="indent">Night</label>
      <input type="radio" id="night-person" name="morning_or_night_person" value="Night" <?php echo (isset($preferences['morning_or_night_person']) && $preferences['morning_or_night_person'] == 'Night') ? 'checked' : ''; ?>>
      <label for="neither-person" class="indent">Neither</label>
      <input type="radio" id="neither-person" name="morning_or_night_person" value="Neither" <?php echo (isset($preferences['morning_or_night_person']) && $preferences['morning_or_night_person'] == 'Neither') ? 'checked' : ''; ?>>
      <br><br>

      <!-- Snorer -->
      <label class="label-title">Snorer:</label>
      <label for="no-snore" class="indent">No</label>
      <input type="radio" id="no-snore" name="snorer" value="No" <?php echo (isset($preferences['snorer']) && $preferences['snorer'] == 'No') ? 'checked' : ''; ?>>
      <label for="low-snore" class="indent">Low</label>
      <input type="radio" id="low-snore" name="snorer" value="Low" <?php echo (isset($preferences['snorer']) && $preferences['snorer'] == 'Low') ? 'checked' : ''; ?>>
      <label for="medium-snore" class="indent">Medium</label>
      <input type="radio" id="medium-snore" name="snorer" value="Medium" <?php echo (isset($preferences['snorer']) && $preferences['snorer'] == 'Medium') ? 'checked' : ''; ?>>
      <label for="high-snore" class="indent">High</label>
      <input type="radio" id="high-snore" name="snorer" value="High" <?php echo (isset($preferences['snorer']) && $preferences['snorer'] == 'High') ? 'checked' : ''; ?>>
      <br><br>

      <!-- Major -->
      <label for="major" class="label-title">Major:</label>
      <input type="text" id="major" name="major" value="<?php echo htmlspecialchars($preferences['major'] ?? ''); ?>">
      <br><br>

      <!-- Allergies -->
      <label for="allergies" class="label-title">Allergies (specify severity):</label>
      <textarea rows="5" id="allergies"
        name="allergies"><?php echo htmlspecialchars($preferences['allergies'] ?? ''); ?></textarea>
      <br><br>

      <!-- Pets -->
      <label class="label-title">Pets:</label>
      <label for="yes-pets" class="indent">Yes</label>
      <input type="radio" id="yes-pets" name="pets" value="Yes" <?php echo (isset($preferences['pets']) && $preferences['pets'] == 'Yes') ? 'checked' : ''; ?>>
      <label for="no-pets" class="indent">No</label>
      <input type="radio" id="no-pets" name="pets" value="No" <?php echo (isset($preferences['pets']) && $preferences['pets'] == 'No') ? 'checked' : ''; ?>>
      <br><br>

      <!-- Additional Information/About Me -->
      <label for="about_me" class="label-title">Additional Information/About Me:</label>
      <textarea rows="5" id="about_me"
        name="about_me"><?php echo htmlspecialchars($preferences['about_me'] ?? ''); ?></textarea>
      <br><br>

      <!-- General Interests -->
      <label for="general_interests" class="label-title">General Interests:</label>
      <textarea rows="5" id="general_interests"
        name="general_interests"><?php echo htmlspecialchars($preferences['general_interests'] ?? ''); ?></textarea>
      <br><br>

      <input type="submit" value="Submit">
      <button type="button" class="cancelbtn" onclick="window.location.replace('/roomease/index.html')">Cancel</button>
    </form>
  </div>
</body>

</html>