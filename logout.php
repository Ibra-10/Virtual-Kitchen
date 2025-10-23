<?php

    session_start(); // First, we start the session

    session_destroy(); // After creating the session, we destroy it
    header('Location: login.php'); // We redirect to the login page of the website (main page)
    exit(); // We exit (no more code executed)
?>