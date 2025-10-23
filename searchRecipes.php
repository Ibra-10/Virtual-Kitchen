<?php

    include 'db.php';

    $query = isset($_GET['search']) ? "%" .  $_GET['search'] . "%" : ''; // We define the default to empty string if not set
    $sql = "SELECT rid, name, type, description FROM recipes WHERE name LIKE ? OR type LIKE ?"; //  Query to select the recipes from the database
    $stmt = $connection->prepare($sql); // Prepare
    $stmt->bind_param("ss", $query, $query); // Bind the parameters
    $stmt->execute(); // Execute the statement
    $result = $stmt->get_result(); // Get the results

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Searched recipes</title>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" /> <!-- Link to the bootstrap -->
<link rel="stylesheet" type="text/css" href="CSS/style.css"> <!-- Link to the stylesheet -->
<link rel="icon" type="image/x-icon" href="favicon.ico"> <!-- This is our favicon -->
<script defer src="js/basic.js"></script> <!-- Link to the JavaScript file -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> <!-- Link to the icons website, from where I extracted the icon of the map for the address part -->
<style>

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

    body {
        background-color: rgb(244, 237, 208);
        text-align: center;
    }

    h1 {
        margin: 20px;
        padding: 10px;
    }

    ul {
        list-style-type: none;
        padding: 20px;
        margin: 30px;
        background-color: rgb(254, 244, 225);
        border: 1px solid rgb(93, 76, 44);
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    li a {
        font-size: 40px;
        text-decoration: none;
        color: rgb(87, 14, 14);
        font-weight: bold;
    }

    li a:hover {
        color: rgb(124, 108, 55);
    }

    li p {
        font-size: 25px;
        color: rgb(93, 76, 44);
    }

</style>
</head>

<body>
    <h1>Your search results</h1>
    
    <?php
    if($result->num_rows == 0) { //If there are no results, we display this message
        echo '<p style="color:red;text-align:center;font-size:20px;font-weight:bold;">No results found for your search.</p>';
    }
    ?>

    <ul>
        <?php while($row = $result->fetch_assoc()) { ?> <!-- We loop through all the results and we display the matching ones -->
            <li>
                <a href="recipes.php?rid=<?php echo $row['rid']; ?>"> <!-- This is the link to the recipes info page -->
                    <strong><?php echo htmlspecialchars($row['name']); ?></strong> - <?php echo htmlspecialchars($row['type']); ?>
                </a>
                <p><?php echo htmlspecialchars($row['description']); ?></p> <!-- Description of the recipe -->
            </li>
       <?php } ?>
    </ul>
    <a href="index.php" class="btn btn-primary">Return to the main page</a> <!-- Button to return to the main page -->
</body>
</html>