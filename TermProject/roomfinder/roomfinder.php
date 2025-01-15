<?php
require '../resources/checkLogin.php';
$config = require '../resources/config.php';
?>

<!DOCTYPE html>

<html>
<head>
    <!-- <meta http-equiv='refresh' content='0; URL=./roomfinder.php'>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> -->
    <meta charset="UTF-8">
    <title>roomfinder</title>
    <link rel="stylesheet" href="roomfinder.css">
    <link rel="stylesheet" href="roomfinderfilter.css">
    <link rel="stylesheet" type="text/css" href="../resources/navbar.css">
    <script src="roomfinder.js"></script>
    <script src="../resources/resources/jquery-1.4.3.min.js"></script>


</head>

<body>
    <nav>
        <a href="../index.html"><img src="../resources/roomeaselogo.png" id="logoButton" alt="logo"></a>
        <ul>
            <li><a href="../Roommate/RoommateFinder.html">Find a Roommate</a></li>
            <li><a href="../roomfinder/roomfinder.html">Find a Room</a></li>
            <!-- 
            <li><a href = "../login.php">Login</a></li>
            <li><a href = "../signup.php">Signup</a></li>
             -->
            <li>
                <div class="dropdown">
                    <button class="dropbtn">Account</button>
                    <div class="dropdown-content">
                        <a href="../Roommate/roommateSurvey.php">Edit Preferences</a>
                        <a href="../resources/logout.php">Logout</a>
                    </div>
                </div>
            </li>

        </ul>
    </nav>

    <div id="filterButtons">
        <button id="filterHouse">Filter</button>
        <button id="postHouse" onclick="window.location.href='./roomposting.html'">Room Posting</button>
        <button id="sortBy">Sort By</button>

    </div>

    <!-- What to filter by: Price (include range with min and max), bedrooms, bathrooms, home type
    (house, apartment, townhouse), space(entire place or room), pets (large dogs, small dogs, cats, no pets)
    amenities (must have A/C, furnished), square feet,  -->


    <div id="popupFilter">
        <div id="formFilter">   
            <!-- post for method or else it will send the data in the URL -->
            <form action="roomfiltering.php" method="post">    

                <!-- only values with the name attribute will get sent when the form is submitted -->
                <legend id="filterTitle">Filter By:</legend>
                <button id="xButton">X</button>
                <fieldset> 
                    <!-- Price -->
                    <label for="price">Price (monthly):</label>
                    <textarea type="number" id="price" name="price"></textarea>
                    <br><br>

                    <!-- Bedrooms -->
                    <label for="bedrooms">Number of Bedrooms:</label>
                    <input type="number" id="bedrooms" name="bedrooms">
                    <br><br>

                    <!-- Bathrooms -->
                    <label for="bathrooms">Number of Bathrooms:</label>
                    <input type="number" id="bathrooms" name="bathrooms">
                    <br><br>

                    <!-- Home Type -->
                    <label>Home Type:</label>
                    <br>

                    <input id=house type="checkbox" name="hometype[]" value="House">
                    <label for="house">House</label>

                    <input id=apartment type="checkbox" name="hometype[]" value="Apartment">
                    <label for="apartment">Apartment</label>

                    <input id=townhouse type="checkbox" name="hometype[]" value="Townhouse">
                    <label for="townhouse">Townhouse</label>
                    <br><br>

                    <!-- Space Type -->
                    <label>Space:</label>
                    <br>

                    <input id=spaceRoom type="checkbox" name="roomspace[]" value="Room">
                    <label for="spaceRoom">Room</label>

                    <input id=spaceEntire type="checkbox" name="roomspace[]" value="Entire Place">
                    <label for="spaceEntire">Entire Place</label>
                    <br><br>


                    <!-- Distance -->
                    <label for="distance">Distance From Campus (miles):</label>
                    <input type="number" id="distance" name="distance">
                    <br><br>

                    <!-- Utilities -->
                    <label>Utilities:</label><br>
                    <input type="checkbox" id="washdry" name="utilities[]" value="Washer/Dryer">
                    <label for="washdry">Washer/Dryer</label>
                    <input type="checkbox" id="ac" name="utilities[]" value="Air Conditioner">
                    <label for="ac">Air Conditioner</label>
                    <input type="checkbox" id="heater" name="utilities[]" value="Heater">
                    <label for="heater">Heater</label>
                    <input type="checkbox" id="dishwasher" name="utilities[]" value="Dishwasher">
                    <label for="dishwasher">Dishwasher</label>
                    <input type="checkbox" id="wifi" name="utilities[]" value="WiFi">
                    <label for="wifi">WiFi</label>
                    <input type="checkbox" id="other" name="utilities[]" value="Other">
                    <label for="other">Other</label>
                    <br><br>

                    <!-- Parking -->
                    <label>Parking:</label><br>
                    <input type="radio" id="parking_lot" name="parking" value="Parking Lot">
                    <label for="parking_lot">Parking Lot</label>
                    <input type="radio" id="street_parking" name="parking" value="Street Parking">
                    <label for="street_parking">Street Parking</label>
                    <input type="radio" id="no_parking" name="parking" value="No Parking">
                    <label for="no_parking">No Parking</label>
                    <br><br>

                    <!-- Accessibility -->
                    <label>Accessibility:</label><br>
                    <input type="checkbox" id="elevator" name="accessibility[]" value="Elevator">
                    <label for="elevator">Elevator</label>
                    <input type="checkbox" id="ramp" name="accessibility[]" value="Ramp">
                    <label for="ramp">Ramp</label>
                    <input type="checkbox" id="other_accessibility" name="accessibility[]" value="Other">
                    <label for="other_accessibility">Other</label>

                </fieldset>

                <button>Filter</button>

            </form>
        </div>
    </div>




    <div class="housingMain">
        <div class="box">
            <ul id="housingField">
                <!-- <li>
                    <a href="../room/room.html">
                        <img src= "Listing1.jpg" width="230" height="230" alt="House Picture"> 
                        <p class = "imageDesc"> 51 14th St</p>
                    </a>
                </li>


   
                <li>
                    <a href="../room/room2.html">
                        <img src= "Listing2.jpg" width="230" height="230" alt="House Picture"> 
                        <p class = "imageDesc"> 1820 Highland Ave</p>
                    </a>
                </li> -->

                <!-- https://www.zillow.com/b/66-13th-st-troy-2-troy-ny-97twtY/ -->

                <!-- <li>
                    <a href="../room/room3.html">
                        <img src= "Listing3.jpg" width="230" height="230" alt="House Picture"> 
                        <p class = "imageDesc"> 66 13th St</p>
                    </a>
                </li>



                <li>
                    <a href="../room/room4.html">
                        <img src= "Listing4.jpg" width="230" height="230" alt="House Picture"> 
                        <p class = "imageDesc"> 2326 15th St</p>
                    </a>
                </li>
     


                <li>
                    <a href="../room/room5.html">
                        <img src= "Listing5.jpg" width="230" height="230" alt="House Picture"> 
                        <p class = "imageDesc"> 169 Hutton St </p>
                    </a>
                </li>


               
                <li>
                    <a href="../room/room6.html">
                        <img src= "Listing6.jpg" width="230" height="230" alt="House Picture"> 
                        <p class = "imageDesc"> 539 Congress St</p>
                    </a>
                </li> -->

    <div class = "housingMain">
        <div class = "box">
            <ul id = "housingField">
                <!-- <li>
                    <a href="../room/room.html">
                        <img src= "Listing1.jpg" width="230" height="230" alt="House Picture"> 
                        <p class = "imageDesc"> 51 14th St</p>
                    </a>
                </li>


   
                <li>
                    <a href="../room/room2.html">
                        <img src= "Listing2.jpg" width="230" height="230" alt="House Picture"> 
                        <p class = "imageDesc"> 1820 Highland Ave</p>
                    </a>
                </li> -->

                <!-- https://www.zillow.com/b/66-13th-st-troy-2-troy-ny-97twtY/ -->

                <!-- <li>
                    <a href="../room/room3.html">
                        <img src= "Listing3.jpg" width="230" height="230" alt="House Picture"> 
                        <p class = "imageDesc"> 66 13th St</p>
                    </a>
                </li>



                <li>
                    <a href="../room/room4.html">
                        <img src= "Listing4.jpg" width="230" height="230" alt="House Picture"> 
                        <p class = "imageDesc"> 2326 15th St</p>
                    </a>
                </li>
     


                <li>
                    <a href="../room/room5.html">
                        <img src= "Listing5.jpg" width="230" height="230" alt="House Picture"> 
                        <p class = "imageDesc"> 169 Hutton St </p>
                    </a>
                </li>


               
                <li>
                    <a href="../room/room6.html">
                        <img src= "Listing6.jpg" width="230" height="230" alt="House Picture"> 
                        <p class = "imageDesc"> 539 Congress St</p>
                    </a>
                </li> -->


            </ul>
        </div>


    </div>


</body>



</html>