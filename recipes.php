<?php

    include 'db.php';

    if(!isset($_GET['rid'])) { // Check if we don't have the recipe ID 
        die("Recipe ID not provided. Cannot proceed without a recipe ID.");
    }

    $rid = $_GET['rid']; // Get the recipe ID
    $sql = "SELECT r.*, username FROM recipes r JOIN users u ON r.uid = u.uid WHERE r.rid = ?"; //Query to get the recipe details
    $stmt = $connection->prepare($sql); // Prepare the statement
    $stmt->bind_param("i", $rid); // Bind the parameter
    $stmt->execute(); // Execute
    $result = $stmt->get_result(); // Get the result

    if($result->num_rows == 0) { //If we don't have any result...
        die("No recipe was founded");
    }

    $recipe = $result->fetch_assoc(); // Fetch the recipe details

?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Details of the recipes</title>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" /> <!-- Link to the bootstrap -->
<link rel="stylesheet" type="text/css" href="CSS/style.css"> <!-- Link to the stylesheet -->
<link rel="icon" type="image/x-icon" href="favicon.ico"> <!-- This is our favicon -->
<script defer src="js/basic.js"></script> <!-- Link to the JavaScript file -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> <!-- Link to the icons website, from where I extracted the icon of the map for the address part -->
 <!-- This is the CSS code to customize the page-->
<style>

    body {
        background-color: rgb(246, 229, 163);
        text-align: center;
    }

    a {
        text-decoration: none;
        color:rgb(250, 222, 169);
        font-weight: bold;
        font-size: 25px;
    }

    a:hover {
        color: rgba(210, 198, 142, 0.59);
    }

    .card {
        background-color: rgb(250, 244, 225);
        border-radius: 10px;
        padding: 20px;
        margin: 20px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .btn-primary {
        font-weight: bold;
        display: block;
        color: rgb(93, 76, 44);
        width: 100%;
        margin: 20px auto;
        background-color: rgb(250, 222, 169);
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        font-size: 26px;
    }

    .btn-primary:hover {
        background-color: rgb(124, 108, 55);
        color: white;
    }

    .title {
        font-weight: bold;
        padding: 20px;
        margin: 20px;
    }

</style>
</head>

<body>
   
    <div class = "container">
        <h1 class = "title"><?= htmlspecialchars($recipe['name']) ?></h1> <!-- Display the name of the recipe -->
        <div class = "cardOutline">
            <div class = "card">
                <h5 class="card-title">Type: <?= htmlspecialchars($recipe['type']) ?></h5> <!-- The type of the recipe -->
                <p class="card-text">Description: <?= htmlspecialchars($recipe['description']) ?></p> <!-- The description -->
                <h5 class="card-text-title"> Ingredients </h5> <!-- The title of ingredients -->
                <p class="card-text"><?= nl2br(htmlspecialchars($recipe['ingredients'])) ?></p> <!-- The ingredients -->
                <h5 class="card-text-title"> Instructions </h5> <!-- The title of instructions -->
                <p class="card-text"><?= nl2br(htmlspecialchars($recipe['instructions'])) ?></p> <!-- The instructions -->
                <p class="card-text">Created by: <?= htmlspecialchars($recipe['username']) ?></p> <!-- The name of the user who created the recipe -->

                <a href="index.php" class="btn btn-primary">Return to the recipes list</a> <!-- Button to return to the recipes list -->
            </div>
        </div>
    </div>

</body>

</html>