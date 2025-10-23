<?php

    session_start(); // Firstly, we start the session, we control the access to the page, which is a type of authentication
    
    include 'db.php'; // We include the database connection file in every page, so we can access the database

    if(!isset($_SESSION['uid'])) { // We check if the user is logged in or not
        header('Location: login.php'); // Redirect to the login page if the user isn't logged in
        exit(); // After that, we exit
    }

    $uid = $_SESSION['uid']; // Get the user id (uid)

    $query = "SELECT * FROM users WHERE uid = ?"; // define the query, to get all the info of the user using his id
    $stmt = $connection->prepare($query); // We protect query against SQL injection using prepared statements
    $stmt->bind_param("i", $uid); // Bind the parameter (i = integer)
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $username = $user['username']; // We get the username

    $recipeQuery = "SELECT * FROM recipes WHERE uid = ?"; // Now, we do the query to get all recipes related to the specific user
    $recipeStmt = $connection->prepare($recipeQuery); // Protection against SQL injection
    $recipeStmt->bind_param("i", $uid);
    $recipeStmt->execute();
    $recipeResult = $recipeStmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Logged in users page</title>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" /> <!-- Link to the bootstrap -->
<link rel="stylesheet" type="text/css" href="CSS/style.css"> <!-- Link to the stylesheet -->
<link rel="icon" type="image/x-icon" href="favicon.ico"> <!-- This is our favicon -->
<script defer src="js/basic.js"></script> <!-- Link to the JavaScript file -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> <!-- Link to the icons website, from where I extracted the icon of the map for the address part -->
<!-- CSS -->
<style>

    body {
        background-color:rgb(253, 249, 210);
    }

    .main-header {
        text-align: center;
        margin-top: 20px;
        margin-bottom: 20px;
        border-radius: 5px;
        border: 1px solid;
        border-color:rgb(78, 21, 21);
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .allContent {
        margin: 20px;
        padding: 20px;
        border-radius: 5px;
        background-color:rgb(255, 252, 245);
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    
    .buttons {
        display: flex;
        justify-content: left;
        margin: 20px;
        padding: 10px;
        border-radius: 5px;
    }

    .buttons a {
        margin: 0 10px;
    }

    .noRecipes {
        color: #6c757d;
    }

    .table {
        width: 100%;
        margin-top: 20px;
        margin-bottom: 20px;
        border-collapse: collapse;
    }

    .table th, .table td {
        padding: 10px;
        text-align: center;
        border-bottom: 1px solid #ddd;
    }

    .table th {
        background-color:rgb(247, 241, 213);
        border-radius: 25px;
    }

    h2 {
        text-align: center;
        margin: 20px;
        font-size: 55px;
    }

    .noRecipes {
        text-align: center;
        font-size: 25px;
        font-weight: bold;
        color:rgb(145, 102, 102);
        margin: 20px;
    }

    .btn-primary {
        background-color: rgb(78,21,21);
        padding: 10px 20px;
        border-radius: 10px;
        border: none;
        text-decoration: none;
    }

    .btn-primary:hover {
        background-color: rgb(164, 95, 95);
        color: white;
    }

    .btn-secondary {
        background-color: rgb(45, 98, 23);
        padding: 10px 20px;
        border-radius: 10px;
        border: none;
        text-decoration: none;
    }

    .btn-secondary:hover {
        background-color: rgb(30, 210, 45);
        color: white;
    }

    .btn-danger {
        background-color: rgb(198, 11, 11);
        padding: 10px 20px;
        border-radius: 10px;
        border: none;
        text-decoration: none;
    }

    .btn-danger:hover {
        background-color: rgb(255, 0, 0);
        color: white;
    }

    .btn-warning {
        background-color: rgb(255, 255, 0);
        padding: 10px 20px;
        border-radius: 10px;
        border: none;
        text-decoration: none;
    }

    .btn-warning:hover {
        background-color: rgb(255, 230, 0);
        color: white;
    }


</style>
</head>

<body>

    <div class="main-header">
        <h1 id="title"><b>Cook-it!</b></h1><!-- Logo of Cook-it! -->
    </div>

    <div class="allContent"> <!-- This div contains all the content of the page -->
        
        <h2>Welcome to your page <?php echo htmlspecialchars($username); ?>!</h2> <!-- We display the username of the user, using htmlspecialchars to protect against XSS attacks -->

        <div class="buttons"> <!-- Here we define 3 buttons -->
            <a href="addRecipe.php" class="btn btn-primary"> Add a new Recipe! </a>
            <a href="index.php" class="btn btn-secondary"> Home </a>
            <a href="logout.php" class="btn btn-danger"> Logout </a>
        </div>

        <?php if($recipeResult->num_rows > 0): ?> <!-- If the user has recipes, we display them in a table -->
            <table class="table">
                <thead>
                    <tr> <!-- This is the first row -->
                        <th>Recipe Name </th>
                        <th>Description </th>
                        <th>Operation </th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $recipeResult->fetch_assoc()): ?> <!-- We fetch all the recipes of the user and display them in the table -->
                        <tr>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['description']); ?></td>
                            <td> <a href="editRecipe.php?rid=<?php echo $row['rid']; ?>" class="btn btn-warning"> Edit </a></td> <!-- Button to edit the recipe -->
                        </tr>
                    <?php endwhile; ?> <!-- We end the while loop -->
                </tbody>
            </table>

        <?php else: ?> <!-- If the user doesn't have any recipes, we display a message -->
            <p class="noRecipes"> No recipes found. Start by adding one! </p>
        <?php endif; ?> <!-- We end if statement -->

    </div>

</body>
</html>