<?php

include 'db.php'; //We include the database to this file, to be able to access its information

$mysqlquery = "SELECT rid, name, type, description FROM recipes"; //The query
$result = $connection->query($mysqlquery);

?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Home of your virtual kitchen</title>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" /> <!-- Link to the bootstrap -->
<link rel="stylesheet" type="text/css" href="CSS/style.css"> <!-- Link to the stylesheet -->
<link rel="icon" type="image/x-icon" href="favicon.ico"> <!-- This is our favicon -->
<script defer src="js/basic.js"></script> <!-- Link to the JavaScript file -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> <!-- Link to the icons website, from where I extracted the icon of the map for the address part -->

<!-- This is the style of the page, which is in the CSS file -->
<style>
    
    body {
        background-color:rgb(246, 229, 163);
    }

    .list-recipes {
        background-color: rgb(255, 255, 255);
        border-radius: 10px;
        padding: 10px;
        margin: 10px;
    }

    .text-decor {
        text-decoration: none;
        color: black;
        font-weight: bold;
        font-size: 25px;
    }

    .text-decor:hover {
        text-decoration: underline;
        color: rgba(210, 198, 142, 0.59);
    }

    .searchArea {
        width: 50%;
        padding: 10px;
        border-radius: 5px;
        border: 2px solid rgb(78, 21, 21);
        margin: 20px;
        margin-left: 3%;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .searchButton {
        padding: 10px 20px;
        border-radius: 5px;
        border: none;
        background-color:rgb(91, 220, 96);
        color: white;
        font-weight: bold;
        cursor: pointer;
        margin: 10px;
    }

    .searchButton:hover {
        background-color: rgb(74, 234, 20);
        color: white;
    }

</style> 

</head>

<body>
<header>
    <div class="main-header" > <!-- This is the header of the page, which contains the logo and the navigation bar -->
        <h1 id="title"><b>Cook-it!</b></h1><!-- Logo of Cook-it! -->
    </div>
    <nav>
    <div class="navbar"> <!-- The navigation bar, which allows us to go to the recipe page and contact page of the website -->
        <ul>
         <li><a href="index.php">Home</a></li>
         <li style="float:right"><a href="login.php"><i class="fa fa-user-o"></i> Log in to Cook-it!</a></li> <!-- login and register buttons -->
         <li style="float:right"><a href="register.php"><i class="fa fa-cutlery"></i> Register here!</a></li>
        </ul>
       </div>
    </nav>
    </header>
    <main class="main">
    <div class = "first-line"> <!-- This is the first line, which introduces the website -->
        <h2><b>Welcome to Cook-it!</b></h2>
        <p><b>The place where you will learn to eat well and be a <em>"pro"</em> in the kitchen</b></p>
        <img src="https://images.pexels.com/photos/16743523/pexels-photo-16743523.jpeg?cs=srgb&dl=pexels-marceloverfe-16743523.jpg&fm=jpg" alt="paella" width="650" height="350"> <!-- A picture of a paella, famous spanish food -->
    </div>
    </main>

    <form method="GET" action="searchRecipes.php"> <!-- This is the search bar -->
        <input type = "text" name = "search" class = "searchArea" placeholder="Search for a recipe per type or name" size = "50">
        <input type = "submit" class = "searchButton" value = "Search!"> <!-- This is the button to search -->
    </form>
    <ul>
        <?php
            if ($result->num_rows > 0) { //Here I Check if there are any matching results
                while($row = $result->fetch_assoc()) {
                    echo '<li class = "list-recipes">'; //I create a list where will appear all recipes matching with the searched string
                    echo '<h5> <a href="recipes.php?rid=' . $row["rid"] . '" class = "text-decor">' . $row["name"] . '</a> </h5>'; //I create a link (title) to the recipe page, where the user can see the recipe
                    echo '<p class = "text">' . $row["type"] . '</p>'; //Type of the recipe
                    echo '<p>' . $row["description"] . '</p>'; // Description
                    echo '</li>';
                }
            } else {
                echo "0 results, try again!"; //If there are no results...
            }
        ?>
    </ul>
</body>

</html>