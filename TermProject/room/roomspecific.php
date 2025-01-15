<?php
// require '../resources/checkLogin.php';
$config = require '../resources/config.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

    //getting the id parameter from the query string of the URL using the $_GET array
    $house_id = isset($_GET['id']);
    //? intval($_GET['id']) : -1;

    if (!is_numeric($house_id)) {
        $house_id = intval($house_id);
    }

    //if houseid is less than 0 it's invalid
    // if($house_id==-1){
    //     echo json_encode(["error" => "Invalid ID"]);
    //     //stops the execution of the program if invalid
    //     exit();
    // }

    //prepare and execute the SQL query
    $stmt = $pdo->prepare("SELECT * FROM room_posting WHERE id = :id");
    $stmt->bindParam(':id', $house_id, PDO::PARAM_INT);
    $stmt->execute();
 
    //fetch the results as an associative array, which makes it easier to retrieve information
    //fetch gives you only one value back
    $house = $stmt->fetch(PDO::FETCH_ASSOC);
    //if it returns something send it back to the js
    echo json_encode($house);  

}   

catch (PDOException $e) {
    //handle connection or query errors
    //if there's anything caught by the pdo exection, it will return a erro message
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
    exit();
}
?>
