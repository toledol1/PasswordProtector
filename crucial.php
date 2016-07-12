<?php

	session_start();
	error_reporting(E_ALL);
	//Database credentials
	require('Credentials.php');

	//Database Connection
	try{
    	$conn = new PDO("mysql:host=$hostname;dbname=$database", $username, $password);
    //echo "<p>Database Connection OK.<p>";
	} catch(PDOException $e){
	    $conn->setArribute(PDO::ATT_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    echo "Could not open database.";
	    exit();
	}


    $fname = $_SESSION['firstName'];
    $lname = $_SESSION['lastName'];
    $email = $_SESSION['email'];
    $uid = $_SESSION['userID'];
    $upin = $_SESSION['pin'];
    $validate = $_SESSION['validate'];

?>