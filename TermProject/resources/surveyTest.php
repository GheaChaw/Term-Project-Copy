<?php
require 'checkLogin.php';
$config = require 'config.php';

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

if ($_SERVER["REQUEST_METHOD"] == "POST") { 
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
    $_POST['quiet-hours-start'],   //s
    $_POST['quiet-hours-end'],            //s
    $_POST['bedtime'],                    //s
    $_POST['wake-up-time'],               //s
    $_POST['preferred_gender'],           //s
    $_POST['cleanliness_rating'],         //i
    $_POST['guest_policy'],               //s
    $_POST['sharing_policy'],             //s
    $_POST['media_likes'],                //s
    $_POST['temperature'],                //i
    $_POST['noise_level'],                //i
    $_POST['wanted_privacy'],             //s
    $_POST['morning_or_night_person'],    //s
    $_POST['snorer'],                     //s
    $_POST['major'],                      //s     
    $_POST['allergies'],                  //s 
    $_POST['pets'],                       //s  
    $_POST['about_me'],                   //s    
    $_POST['general_interests']           //s
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
} else{
  // prefill form if user already has a record
  $sql = "SELECT * FROM user_prefs WHERE user_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $_SESSION['user_id']);
  $stmt->execute();
  $result = $stmt->get_result();
  if($result->num_rows > 0){
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
  <title>Document</title>
  <style>
    body {
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

<form action="" method="POST">
    <!-- 24 hr clock. cant be bothered to make it am/pm, rpi should know how to subtract 12 -->
    <!-- Quiet Hours Start -->
    <label for="quiet-hours-start">Quiet Hours Start:</label>
    <input type="time" id="quiet-hours-start" name="quiet-hours-start" value="<?php echo htmlspecialchars($preferences['quiet_hours_start'] ?? ''); ?>">
    <br><br>

    <!-- Quiet Hours End -->
    <label for="quiet-hours-end">Quiet Hours End:</label>
    <input type="time" id="quiet-hours-end" name="quiet-hours-end" value="<?php echo htmlspecialchars($preferences['quiet_hours_end'] ?? ''); ?>">
    <br><br>

    <!-- Bedtime -->
    <label for="bedtime">Bedtime:</label>
    <input type="time" id="bedtime" name="bedtime" value="<?php echo htmlspecialchars($preferences['bed_time'] ?? ''); ?>">
    <br><br>

    <!-- Wake Up Time -->
    <label for="wake-up-time">Wake Up Time:</label>
    <input type="time" id="wake-up-time" name="wake-up-time" value="<?php echo htmlspecialchars($preferences['wake_up_time'] ?? ''); ?>">
    <br><br>

    <!-- Preferred Gender -->
    <label>Preferred Gender:</label>
    <input type="radio" id="male" name="preferred_gender" value="Male" <?php echo (isset($preferences['preferred_gender']) && $preferences['preferred_gender'] == 'Male') ? 'checked' : ''; ?>>
    <label for="male">Male</label>
    <input type="radio" id="female" name="preferred_gender" value="Female" <?php echo (isset($preferences['preferred_gender']) && $preferences['preferred_gender'] == 'Female') ? 'checked' : ''; ?>>
    <label for="female">Female</label>
    <input type="radio" id="non-binary" name="preferred_gender" value="Non-binary" <?php echo (isset($preferences['preferred_gender']) && $preferences['preferred_gender'] == 'Non-binary') ? 'checked' : ''; ?>>
    <label for="non-binary">Non-binary</label>
    <input type="radio" id="other" name="preferred_gender" value="Other" <?php echo (isset($preferences['preferred_gender']) && $preferences['preferred_gender'] == 'Other') ? 'checked' : ''; ?>>
    <label for="other">Other</label>
    <br><br>

    <!-- Cleanliness Rating -->
    <label for="cleanliness_rating">Cleanliness Rating (1-10):</label>
    <input type="number" id="cleanliness_rating" name="cleanliness_rating" min="1" max="10" value="<?php echo htmlspecialchars($preferences['cleanliness_rating'] ?? ''); ?>">
    <br><br>

    <!-- Guest Policy -->
    <label for="guest_policy">Guest Policy:</label>
    <textarea id="guest_policy" name="guest_policy"><?php echo htmlspecialchars($preferences['guest_policy'] ?? ''); ?></textarea>
    <br><br>

    <!-- Sharing Items Policy -->
    <label for="sharing_policy">Sharing Items Policy:</label>
    <textarea id="sharing_policy" name="sharing_policy"><?php echo htmlspecialchars($preferences['sharing_policy'] ?? ''); ?></textarea>
    <br><br>

    <!-- Music/Movies/TV Shows -->
    <label for="media_likes">Music/Movies/TV Shows (Genres):</label>
    <textarea id="media_likes" name="media_likes"><?php echo htmlspecialchars($preferences['media_likes'] ?? ''); ?></textarea>
    <br><br>

    <!-- Preferred Temperature -->
    <label for="temperature">Preferred Temperature (°F):</label>
    <input type="range" id="temperature" name="temperature" min="60" max="80" value="<?php echo htmlspecialchars($preferences['temperature'] ?? ''); ?>">
    <br><br>

    <!-- Noise Level -->
    <label for="noise_level">Noise Level (1-10):</label>
    <input type="number" id="noise_level" name="noise_level" min="1" max="10" value="<?php echo htmlspecialchars($preferences['noise_level'] ?? ''); ?>">
    <br><br>

    <!-- Wanted Privacy Level -->
    <label>Wanted Privacy Level:</label>
    <input type="radio" id="low-privacy" name="wanted_privacy" value="Low" <?php echo (isset($preferences['wanted_privacy']) && $preferences['wanted_privacy'] == 'Low') ? 'checked' : ''; ?>>
    <label for="low-privacy">Low</label>
    <input type="radio" id="medium-privacy" name="wanted_privacy" value="Medium" <?php echo (isset($preferences['wanted_privacy']) && $preferences['wanted_privacy'] == 'Medium') ? 'checked' : ''; ?>>
    <label for="medium-privacy">Medium</label>
    <input type="radio" id="high-privacy" name="wanted_privacy" value="High" <?php echo (isset($preferences['wanted_privacy']) && $preferences['wanted_privacy'] == 'High') ? 'checked' : ''; ?>>
    <label for="high-privacy">High</label>
    <br><br>

    <!-- Morning or Night Person -->
    <label>Morning or Night Person:</label>
    <input type="radio" id="morning-person" name="morning_or_night_person" value="Morning" <?php echo (isset($preferences['morning_or_night_person']) && $preferences['morning_or_night_person'] == 'Morning') ? 'checked' : ''; ?>>
    <label for="morning-person">Morning</label>
    <input type="radio" id="night-person" name="morning_or_night_person" value="Night" <?php echo (isset($preferences['morning_or_night_person']) && $preferences['morning_or_night_person'] == 'Night') ? 'checked' : ''; ?>>
    <label for="night-person">Night</label>
    <input type="radio" id="neither-person" name="morning_or_night_person" value="Neither" <?php echo (isset($preferences['morning_or_night_person']) && $preferences['morning_or_night_person'] == 'Neither') ? 'checked' : ''; ?>>
    <label for="neither-person">Neither</label>
    <br><br>

    <!-- Snorer -->
    <label>Snorer:</label>
    <input type="radio" id="no-snore" name="snorer" value="No" <?php echo (isset($preferences['snorer']) && $preferences['snorer'] == 'No') ? 'checked' : ''; ?>>
    <label for="no-snore">No</label>
    <input type="radio" id="low-snore" name="snorer" value="Low" <?php echo (isset($preferences['snorer']) && $preferences['snorer'] == 'Low') ? 'checked' : ''; ?>>
    <label for="low-snore">Low</label>
    <input type="radio" id="medium-snore" name="snorer" value="Medium" <?php echo (isset($preferences['snorer']) && $preferences['snorer'] == 'Medium') ? 'checked' : ''; ?>>
    <label for="medium-snore">Medium</label>
    <input type="radio" id="high-snore" name="snorer" value="High" <?php echo (isset($preferences['snorer']) && $preferences['snorer'] == 'High') ? 'checked' : ''; ?>>
    <label for="high-snore">High</label>
    <br><br>

    <!-- Major -->
    <label for="major">Major:</label>
    <input type="text" id="major" name="major" value="<?php echo htmlspecialchars($preferences['major'] ?? ''); ?>">
    <br><br>

    <!-- Allergies -->
    <label for="allergies">Allergies (specify severity):</label>
    <textarea id="allergies" name="allergies"><?php echo htmlspecialchars($preferences['allergies'] ?? ''); ?></textarea>
    <br><br>

    <!-- Pets -->
    <label>Pets:</label>
    <input type="radio" id="yes-pets" name="pets" value="Yes" <?php echo (isset($preferences['pets']) && $preferences['pets'] == 'Yes') ? 'checked' : ''; ?>>
    <label for="yes-pets">Yes</label>
    <input type="radio" id="no-pets" name="pets" value="No" <?php echo (isset($preferences['pets']) && $preferences['pets'] == 'No') ? 'checked' : ''; ?>>
    <label for="no-pets">No</label>
    <br><br>

    <!-- Additional Information/About Me -->
    <label for="about_me">Additional Information/About Me:</label>
    <textarea id="about_me" name="about_me"><?php echo htmlspecialchars($preferences['about_me'] ?? ''); ?></textarea>
    <br><br>

    <!-- General Interests -->
    <label for="general_interests">General Interests:</label>
    <textarea id="general_interests" name="general_interests"><?php echo htmlspecialchars($preferences['general_interests'] ?? ''); ?></textarea>
    <br><br>

    <input type="submit" value="Submit">
  </form>
</body>

</html>