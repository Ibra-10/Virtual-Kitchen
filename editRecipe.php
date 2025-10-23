<?php

    session_start();

    include 'db.php';

    if(empty($_SESSION['CSRF_token'])) { // This checks if the CSRF token is set
        $_SESSION['CSRF_token'] = bin2hex(random_bytes(32)); // This generates a new CSRF token
    }

    if(!isset($_SESSION['uid'])) { // This checks if the user is logged in
        header('Location: login.php');
        exit();
    }

    if(!isset($_GET['rid'])) { // This checks if the recipe ID is set
        echo 'Recipe ID is necessary!';
        exit();
    }

    $rid = $_GET['rid'];
    $uid = $_SESSION['uid'];

    $query = "SELECT * FROM recipes WHERE rid = ? AND uid = ?"; // Query

    $stmt = $connection->prepare($query);
    $stmt->bind_param("ii", $rid, $uid);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows == 0) { // We check if the recipe exists
        echo "No recipe was found!";
        exit();
    }

    $recipe = $result->fetch_assoc();

    if($_SERVER["REQUEST_METHOD"] == "POST") { // If the form is submitted...

        if(!isset($_POST['CSRF_token']) || $_POST['CSRF_token'] !== $_SESSION['CSRF_token']) { // If the CSRF token is valid...
            die("We have a security error due to an invalid CSRF token.");
        }

        // We get all the values from the form
        $name = $_POST['name'];
        $description = $_POST['description'];
        $type = $_POST['type'];
        $cookingTime = $_POST['Cookingtime'];
        $ingredients = $_POST['ingredients'];
        $instructions = $_POST['instructions'];
        $image = $recipe['image'];
        
        if(isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) { // Here we check if the image is uploaded successfully
            $file = $_FILES['image']['tmp_name']; // This is the temporary file name
            $fileName = basename($_FILES['image']['name']); // This is the name of the file
            $upload = 'images/' . $fileName; // This is the path where we want to upload the file
            if(move_uploaded_file($file, $upload)) { // This moves the file to the desired location
                $image = $upload;
            } else {
                echo "Failed to upload image.";
            }
        }

        $update = "UPDATE recipes SET name = ?, description = ?, type = ?, Cookingtime = ?, ingredients = ?, instructions = ?, image = ? WHERE rid = ? AND uid = ?"; //In this query we update the recipe
        $updateStmt = $connection->prepare($update);
        $updateStmt->bind_param("sssssssii", $name, $description, $type, $cookingTime, $ingredients, $instructions, $image, $rid, $uid);
        $updateStmt->execute();

        header('Location: loginPage.php'); // This redirects the user to the login page
        exit();
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editing Recipe</title>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" /> <!-- Link to the bootstrap -->
    <link rel="stylesheet" type="text/css" href="CSS/style.css"> <!-- Link to the stylesheet -->
    <link rel="icon" type="image/x-icon" href="favicon.ico"> <!-- This is our favicon -->
    <script defer src="js/basic.js"></script> <!-- Link to the JavaScript file -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> <!-- Link to the icons website, from where I extracted the icon of the map for the address part -->
    <style>
        
        body {
            background-color: rgb(253, 249, 210);
            padding: 20px;
            margin: 5px;
            text-align: center;
        }

        h1 {
            margin: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 10px;
            padding: 20px;
            background-color: rgb(254, 238, 146);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border: 2px solid rgb(78, 21, 21);
        }

        label {
            font-size: 22px;
            font-weight: bold;
            margin: 10px;
        }

        .input {
            width: 80%;
            padding: 10px;
            margin: 10px;
            border-radius: 15px;
            border: 1px solid rgb(78, 21, 21);
            box-shadow: 0 0 5px rgb(79, 30, 30);
        }

        .btn {
            padding: 10px;
            margin: 10px;
            border-radius: 15px;
            border: none;
            font-size: 20px;
        }

        .image {
            margin: 10px;
            text-align: center;
        }

        .image img {
            width: auto;
            height: auto;
            border-radius: 15px;
        }

        select {
            width: 80%;
            padding: 10px;
            margin: 10px;
            border-radius: 15px;
            border: 1px solid rgb(78, 21, 21);
            box-shadow: 0 0 5px rgb(79, 30, 30);
        }

        textarea {
            width: 80%;
            padding: 10px;
            margin: 10px;
            border-radius: 15px;
            min-height: 100px;
        }

    </style>
</head>
<body>

    <h1>Edit Your Recipe!</h1>

    <form method="POST" enctype="multipart/form-data"> <!-- This is the form where we edit the recipe -->

        <label for="name">Recipe Name:</label>
        <input type="text" class="input" name="name" value="<?php echo htmlspecialchars($recipe['name']); ?>" required>

        <label for="description">Description:</label>
        <textarea class="input" name="description" required><?php echo htmlspecialchars($recipe['description']); ?></textarea>

        <label for="type">Type:</label>
        <select name="type" required>
            <?php

                $types = ['French', 'Italian', 'Chinese', 'Indian', 'Mexican', 'Others']; // These are the types of recipes
                foreach($types as $selectedType) { // We loop through the types of recipes
                    $selected = $selectedType == $recipe['type'] ? 'selected' : ''; // We check if the type is the same as the one in the database
                    echo "<option value='$selectedType' $selected>$selectedType</option>"; // This is the option in the select
                }

            ?>
        </select>

        <label for="Cookingtime">Cooking Time (min):</label>
        <input type="text" class="input" name="Cookingtime" value="<?php echo htmlspecialchars($recipe['Cookingtime']); ?>" required>

        <label for="ingredients">Ingredients:</label>
        <textarea class="input" name="ingredients" required><?php echo htmlspecialchars($recipe['ingredients']); ?></textarea>

        <label for="instructions">Instructions:</label>
        <textarea class="input" name="instructions" required><?php echo htmlspecialchars($recipe['instructions']); ?></textarea>

        <label for="image">Image:</label>
        <input type="file" class="input" name="image" accept="image/*"> <!-- This is the input for the image -->
            <?php if(!empty($recipe['image'])): ?> <!-- This checks if the image is not empty -->
                <div class="image">
                    <p>The current image is: </p>
                    <img src="<?php echo htmlspecialchars($recipe['image']); ?>" alt="Recipe's Image"> <!-- We display the current image of the recipe -->
                </div>
            <?php endif; ?>
        
        <div class="buttons">
            <input type="submit" class="btn btn-success" value="Update Recipe!">
            <a href="loginPage.php" class="btn btn-danger">Cancel</a>
            <input type="hidden" name="CSRF_token" value="<?php echo $_SESSION['CSRF_token']; ?>">
        </div>

    </form>
</body>
</html>