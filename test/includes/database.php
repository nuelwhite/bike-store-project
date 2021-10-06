<?php
//These are the four parameters that is passed into the mysqli_connect() function
    $dbHost = "localhost";
    $dbUser = "root";
    $dbPass = "";
    $dbName = "phpTutorial";

    $connection = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);

    if(!$connection){
        die("Database not connected!!!");

    }

?>