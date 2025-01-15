<?php
header('Content-Type: application/json');

// require '../resources/checkLogin.php';
$config = require '../resources/config.php';

//this is for session storage but not needed in this step, just wrote it to remember
$_SESSION["user_id"];

//information needed to connect to database 
$host = $config['servername'];   
$db = $config['userDB']; 
$username = $config['username'];    
$password = $config['password'];    
$charset = 'utf8mb4';  

//Data Source Name (DSN) which tells PDO how to connect to the database
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {
    //create a new PDO instance to connect to database
    $pdo = new PDO($dsn, $username, $password);
    //enables exceptions to help with error handling
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //prepare and execute the SQL query
    $stmt = $pdo->prepare("SELECT id, address, image, image_type FROM room_posting");
    $stmt->execute();

    //fetch the results as an associative array, which makes it easier to retrieve information
    $houses = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //including Base64-encoded image
    foreach ($houses as &$house) {
        if (!empty($house['image'])) {
            $house['image'] = 'data:' . $house['image_type'] . ';base64,' . base64_encode($house['image']);
        } else {
            //if no image is stored in the database
            $defaultImage = file_get_contents('Listing1.jpg'); 
            $house['image'] = 'data:image/jpeg;base64,' . base64_encode($defaultImage);
        }
    }

    //return the data as json file 
    echo json_encode($houses);
} 
catch (PDOException $e) {
    //handle connection or query errors
    //if there's anything caught by the pdo exection, it will return a erro message
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
    exit();
}

?>
