<?php

// require '../resources/checkLogin.php';
$config = require '../resources/config.php';

//information needed to connect to database 
$host = $config['servername'];   
$db = $config['userDB']; 
$username = $config['username'];    
$password = $config['password'];    
$charset = 'utf8mb4';  

//create connection
$conn = new mysqli($host, $username, $password, $db);

//check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//todo - print out error messages

//prepare data from form
$description = isset($_POST['description']) ? $_POST['description'] : 'N/A';
$address = isset($_POST['address']) ? $_POST['address'] : 'N/A';
$price = isset($_POST['price']) ? $_POST['price'] : -1;
$contact = isset($_POST['contact']) ? $_POST['contact'] : 'N/A';
$bedrooms = isset($_POST['bedrooms']) ? $_POST['bedrooms'] : -1;
$bathrooms = isset($_POST['bathrooms']) ? $_POST['bathrooms'] : -1;
$distance = isset($_POST['distance']) ? $_POST['distance'] : -1;
//getting the image contents  from the $_Files array that I enabled on the form
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $imageTmpPath = $_FILES['image']['tmp_name'];
    //these two will be sent to the databsae
    //getting the path of the image
    $imageData = file_get_contents($imageTmpPath);
    $imageType = $_FILES['image']['type']; 
} 
else {
    $imageData = file_get_contents('Listing1.jpg'); // Default image path
    $imageType = 'image/jpeg'; // MIME type of the default image
}

// $utilities = isset($_POST['utilities']) ? implode(", ", $_POST['utilities']) : '';
// Different utilities as separate entries
$washdry = isset($_POST['washdry']) ? $_POST['washdry'] : 'N/A';
$ac = isset($_POST['ac']) ? $_POST['ac'] : 'N/A';
$heater = isset($_POST['heater']) ? $_POST['heater'] : 'N/A';
$dishwasher = isset($_POST['dishwasher']) ? $_POST['dishwasher'] : 'N/A';
$wifi = isset($_POST['wifi']) ? $_POST['wifi'] : 'N/A';
$other_utility = isset($_POST['other_utility']) ? $_POST['other_utility'] : 'N/A';

$parking = isset($_POST['parking']) ? $_POST['parking'] : 'No Parking';

// $accessibility = isset($_POST['accessibility']) ? implode(", ", $_POST['accessibility']) : '';
// Different accessibilities
$elevator = isset($_POST['elevator']) ? $_POST['elevator'] : 'N/A';
$ramp = isset($_POST['ramp']) ? $_POST['ramp'] : 'N/A';
$other_accessibility = isset($_POST['other_accessibility']) ? $_POST['other_accessibility'] : 'N/A';


// Validate required fields
// if (empty($description) || empty($address) || $price <= -1) {
//     die("Error: Please fill out all required fields.");
// }

// Insert data into database
$sql = "INSERT INTO room_posting (description, address, price, contact, bedrooms, bathrooms, distance, image, image_type, washdry, ac, heater, dishwasher, wifi, other_utility, parking, elevator, ramp, other_accessibility) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssdsiidssssssssssss", $description, $address, $price, $contact, $bedrooms, $bathrooms, $distance, $imageData, $imageType, $washdry, $ac, $heater, $dishwasher, $wifi, $other_utility, $parking, $elevator, $ramp, $other_accessibility);

if ($stmt->execute()) {
    echo "New record created successfully";
} else {
    echo "Error: " . $stmt->error;
}

//close connections
$stmt->close();
$conn->close();

?>
