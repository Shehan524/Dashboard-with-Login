<?php
include ('config.php');
session_destroy();  //this will destroy/end all sessions.
header('location:' . SITEURL . 'index.php'); //this will redirect us to the login page