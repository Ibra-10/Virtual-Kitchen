<?php

    session_start();

    include 'db.php';

    if(empty($_SESSION['CSRF_token'])) {
        $_SESSION['CSRF_token'] = bin2hex(random_bytes(32)); // This generates a new CSRF token
    }

    if($_SERVER["REQUEST_METHOD"] == "POST") { // Check if the form is submitted

        if(!isset($_POST['CSRF_token']) || $_POST['CSRF_token'] !== $_SESSION['CSRF_token']) {
            die("We have a security error due to an invalid CSRF token.");
        }

        $username = trim($_POST['username']); // Get the username from the form
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
        $email = trim($_POST['email']); // Get the email from the form

        $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)"; // SQL query to insert the user into the database
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("sss", $username, $password, $email);     
        
        if($stmt->execute()) { // We execute the sql query statement
            $message = '<p style="font-size:20px; font-weight:bold">Registrated successfully! You can now <a href="login.php" class="login">log in</a>.</p>';
        } else {
            $message = '<p>Error in the registration: " . $stmt->error . "</p>';
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Registration page</title>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" /> <!-- Link to the bootstrap -->
<link rel="stylesheet" type="text/css" href="CSS/style.css"> <!-- Link to the stylesheet -->
<link rel="icon" type="image/x-icon" href="favicon.ico"> <!-- This is our favicon -->
<script defer src="js/basic.js"></script> <!-- Link to the JavaScript file -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> <!-- Link to the icons website, from where I extracted the icon of the map for the address part -->
<style>

    body {
        background-color: rgb(255, 244, 209);
        text-align: center;
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

    .username {
        margin: 20px;
        padding: 10px;
    }

    .password {
        margin: 20px;
        padding: 10px;
    }

    .email {
        margin: 20px;
        padding: 10px;
    }

    .label {
        font-weight: bold;
        font-size: 20px;
        padding: 10px;
        margin: 10px;
    }

    .area {
        width: 50%;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
        box-shadow: 0 4px 12px rgba(87, 14, 14, 0.1);
        margin: 20px;
    }

    .login {
        text-decoration: none;
        color: rgb(124, 108, 55);
        font-weight: bold;
        font-size: 25px;
    }

    .login:hover {
        color: rgb(246, 225, 187);
    }

</style>
</head>
<body>
    <h1>Register right now!</h1> <!-- This is the title of the page -->
    <form method="POST" action="register.php">
        <div class="username"> <!-- This is the container of the username area -->
            <label for="username" class="label">Username</label>
            <input type="text" class="area" id="username" name="username" required>
        </div>
        <div class="password"> <!-- This is the container of the password area -->
            <label for="password" class="label">Password</label>
            <input type="password" class="area" id="password" name="password" required>
        </div>
        <div class="email"> <!-- This is the container of the email area -->
            <label for="email" class="label">Email</label>
            <input type="email" class="area" id="email" name="email" required>
        </div>

        <?php
            if (isset($message)) { // If the message is set, we display it
                echo $message;
            }
        ?>

        <button type="submit" class="btn btn-primary">Register</button> <!-- This is the button to submit the form -->
        <a href="index.php" class="btn btn-primary">Return to the main page</a> <!-- This is the button to return to the main page -->
        <input type="hidden" name="CSRF_token" value="<?php echo $_SESSION['CSRF_token']; ?>"> <!-- We add the CSRF token in the form -->
    </form>
</body>
</html>