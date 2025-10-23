<?php

// Database connection
$dbname = "u_240080721_vkitchen";
$host = "localhost";
$username = "u-240080721";
$password = "zW0Xxaava8T0mxO";

$connection = new mysqli($host, $username, $password, $dbname); //we create a new connection to the database

($connection -> connect_error) { //if connection is failed
    die("Connection failed: " . $connection -> connect_error); //we display the error
}

?>