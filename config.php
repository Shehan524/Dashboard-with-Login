<?php
session_start(); // Start the session

// Define constants for the database connection
define('LOCALHOST', 'localhost');
define('ROOT', 'root');
define('PASSWORD', '');
define('DATABASE', 'login_db');
define('SITEURL', 'http://localhost/Final-project/'); // Correct the URL

// Establish the database connection
$conn = mysqli_connect(LOCALHOST, ROOT, PASSWORD, DATABASE) or die('Failed to connect: ' . mysqli_connect_error());
?>