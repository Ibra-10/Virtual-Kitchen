<?php

    session_start();

    include 'db.php';

    if(empty($_SESSION['CSRF_token'])) { // Check if the CSRF token is set
        $_SESSION['CSRF_token'] = bin2hex(random_bytes(32)); // This generates a new CSRF token
    }

    if(!isset($_SESSION['uid'])) { // Check if the user is logged in
        header('Location: login.php');
        exit();
    }

    $uid = $_SESSION['uid'];

    if($_SERVER['REQUEST_METHOD'] == "POST") { // Check if the form is submitted

        if(!isset($_POST['CSRF_token']) || $_POST['CSRF_token'] !== $_SESSION['CSRF_token']) { // Check if the CSRF token is valid
            die("We have a security error due to an invalid CSRF token.");
        }

        // We validate the inputs from the form
        $recipeName = $_POST['name'];
        $recipeDescription = $_POST['description'];
        $recipeType = $_POST['type'];
        $cookingTime = $_POST['cooking_time'];
        $ingredients = $_POST['ingredients'];
        $instructions = $_POST['instructions'];
        $image = null;

        if(isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) { // Here we check if the image is uploaded
            $imageFile = $_FILES['image']['tmp_name']; // Temporary file name
            $fileName = basename($_FILES['image']['name']); // Original file name
            $upload = 'images/' . $fileName; // Path to upload the image

            if(move_uploaded_file($imageFile, $upload)) { // Move the uploaded file to the specified directory
                $image = $upload;
            } else {
                echo "Failed to upload image.";
            }
        }

        $query = $connection->prepare("INSERT INTO recipes (name, description, type, Cookingtime, ingredients, instructions, image, uid) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $query->bind_param("sssssssi", $recipeName, $recipeDescription, $recipeType, $cookingTime, $ingredients, $instructions, $image, $uid); // We bind the parameters to the query (i = integer, s = string)
        $query->execute();
        header('Location: loginPage.php');
        exit();

    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Adding Recipe</title>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" /> <!-- Link to the bootstrap -->
<link rel="stylesheet" type="text/css" href="CSS/style.css"> <!-- Link to the stylesheet -->
<link rel="icon" type="image/x-icon" href="favicon.ico"> <!-- This is our favicon -->
<script defer src="js/basic.js"></script> <!-- Link to the JavaScript file -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> <!-- Link to the icons website, from where I extracted the icon of the map for the address part -->
<style>

    body {
        background-color:rgb(253, 249, 210);
        margin: 0;
        padding: 20px;
    }

    h2 {
        text-align: center;
        margin: 20px;
        font-size: 55px;
    }

    form {
        max-width: 50%;
        margin: auto;
        padding: 20px;
        border-radius: 10px;
        background-color: rgb(250, 245, 233);
        border: 1px solid rgb(78, 21, 21);
        box-shadow: 0 0 10px rgb(79, 30, 30);
    }

    .label {
        font-size: 22px;
        font-weight: bold;
        margin-bottom: 10px;
        display: block;
    }

    .input, textarea, .select {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border-radius: 5px;
        border: 1px solid rgb(78, 21, 21);
        box-shadow: 0 0 5px rgb(79, 30, 30);
    }

    .description label {
        text-align: left;
        font-family: 'Arial', sans-serif;
        font-size: 22px;
        font-weight: bold;
    }

    textarea {
        min-height: 100px;
        resize: vertical;
        font-family: 'Arial', sans-serif;
        font-weight: normal;
        font-size: 14px;
    }

    .buttons {
        display: flex;
        justify-content: center;
        margin: 20px;
        padding: 10px;
    }

    .btn {
        margin: 10px;
        padding: 10px 20px;
        border-radius: 10px;
        font-size: 20px;
    }

    .btn-primary {
        background-color: rgb(33, 184, 48);
        color: white;
        border: none;
    }

    .btn-primary:hover {
        background-color: rgb(27, 95, 33);
        color: white;
    }

    .btn-danger:hover {
        background-color: rgb(110, 14, 14);
        color: white;
        
    }

</style>
</head>

<body>
    <h2>Add a new recipe!</h2>

    <form method="POST" action="addRecipe.php" enctype="multipart/form-data"> <!-- We use the POST method to send the data to the server -->

        <div class="name">
            <label for="name" class="label"> Recipe Name: </label>
            <input type="text" class="input" name="name" required>
        </div>

        <div class="description">
            <label for="description" class="label"> Description: </label>
            <textarea id="description" class="label" name="description" required></textarea>
        </div>

        <div class="type">
            <label for="type" class="label"> Type: </label>
            <select name="type" class="select" required>
                <option value="French">French</option>
                <option value="Italian">Italian</option>
                <option value="Chinese">Chinese</option>
                <option value="Indian">Indian</option>
                <option value="Mexican">Mexican</option>
                <option value="Others">Others</option>
            </select>
        </div>

        <div class="cooking_time">
            <label for="cooking_time" class="label"> Cooking Time (min): </label>
            <input type="text" class="input" name="cooking_time" required>
        </div>

        <div class="ingredients">
            <label for="ingredients" class="label"> Ingredients: </label>
            <textarea id="ingredients" class="label" name="ingredients" required></textarea>
        </div>

        <div class="instructions">
            <label for="instructions" class="label"> Instructions: </label>
            <textarea id="instructions" class="label" name="instructions" required></textarea>
        </div>

        <div class="image">
            <label for="image" class="label"> Image: </label>
            <input type="file" name="image" class="input" accept="image/*">
        </div>

        <div class="buttons">
            <button type="submit" class="btn btn-primary">Add Recipe</button>
            <a href="loginPage.php" class="btn btn-danger">Cancel</a>
        </div>

        <input type="hidden" name="CSRF_token" value="<?php echo $_SESSION['CSRF_token']; ?>"> <!-- We add the CSRF token in the form -->

    </form>
</body>
</html>