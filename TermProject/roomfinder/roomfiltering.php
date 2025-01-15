<?php
    //using AJAX to process the forms
    print_r($_POST);

    //FILTER_SANITIZE_NUMBER_INT filters to make sure it's digits only
    $price = filter_input(INPUT_POST, "price", FILTER_SANITIZE_NUMBER_INT) ?? -1;

    //makes sure that the bedrooms is an int
    $bedrooms = filter_input(INPUT_POST, "bedrooms", FILTER_SANITIZE_NUMBER_INT) ?? -1;

    //makes sure that the bathrooms is an int
    $bathrooms = filter_input(INPUT_POST, "bathrooms", FILTER_SANITIZE_NUMBER_INT) ?? -1;

    $hometype = filter_input(INPUT_POST, "hometype", FILTER_REQUIRE_ARRAY) ?? [] ;

    $roomspace = filter_input(INPUT_POST, "roomspace", FILTER_REQUIRE_ARRAY) ?? [] ;

    $distance = filter_input(INPUT_POST, "distance", FILTER_SANITIZE_NUMBER_FLOAT) ?? -1;

    $utilities = filter_input(INPUT_POST, "utilities", FILTER_REQUIRE_ARRAY) ?? [] ;

    $parking = filter_input(INPUT_POST, "parking", FILTER_SANITIZE_STRING) ?? -1 ;

    //filter_require_array tells the function that accessibility is an array
    //if the array is empty(NULL) it will make an empty array, NULL gives us errors
    $accessibility = filter_input(INPUT_POST, "accessibility", FILTER_REQUIRE_ARRAY) ?? [] ;

    var_dump($price,$bedrooms,$bathrooms, $hometype, $roomspace, $distance, $utilities, $parking, $accessibility);


    //to do: change utilities to a list value in sql or ids
?>