<?php

    session_start(); // We start the session

    include 'db.php';

    if(empty($_SESSION['CSRF_token'])) { // If the CSRF token is not set, we generate a new one, we use this to do the CSRF protection. The bin2hex function converts the random bytes to hexadecimal representation
        $_SESSION['CSRF_token'] = bin2hex(random_bytes(32)); // This generates a new CSRF token
    }

    if($_SERVER["REQUEST_METHOD"] == "POST") { // If the request method is POST, it means that the form has been submitted

        if(!isset($_POST['CSRF_token']) || $_POST['CSRF_token'] !== $_SESSION['CSRF_token']) { // We check if the CSRF token is not set and if it is not equal to the one we generated before
            die("We have a security error due to an invalid CSRF token.");
        }

        $username = trim($_POST['username']); // We trim the username and password to remove any whitespace
        $password = trim($_POST['password']); 

        $query = "SELECT uid, username, password FROM users WHERE username = ?";

        $stmt = $connection->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows == 1) { // If the number of rows is 1, it means that the user exists
            $user = $result->fetch_assoc();
            if(password_verify($password, $user['password'])) { // We use the password_verify function to check if the password is correct
                $_SESSION['uid'] = $user['uid']; 
                $_SESSION['username'] = $user['username'];
                header("Location: loginPage.php"); // We redirect the user to the login page
                exit();
            } else {
                echo "Invalid password! Please try again.";
            }
        } else {
            echo "No user found";
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

    h1 {
        margin-top: 20px;
    }

    .area {
        width: 50%;
        padding: 10px;
        font-size: 20px;
        border-radius: 5px;
        border: 1px solid rgb(93, 76, 44);
    }

    .label {
        font-weight: bold;
        font-size: 20px;
        padding: 10px;
        margin: 10px;
    }

</style>
</head>

<body>
    <h1>Log in</h1> <!-- This is the title of the page -->
    <form method="POST" action="login.php"> <!-- Form to log in, with the username and password input areas and the submit button -->
        <div class="username"> 
        <label for="username" class="label">Username</label>
        <input type="text" class="area" name="username" placeholder="Username" required>
        </div>
        <div class="password">
        <label for="password" class="label">Password</label>
        <input type="password" class="area" name="password" placeholder="Password" required>
        </div>

        <button type="submit" class="btn btn-primary">Log in!</button>
        <a href="index.php" class="btn btn-primary">Return to the main page</a> <!-- This is the button to return to the main page -->
        <input type="hidden" name="CSRF_token" value="<?php echo $_SESSION['CSRF_token']; ?>"> <!-- We add the CSRF token in the form -->
    </form>
</body>
</html>