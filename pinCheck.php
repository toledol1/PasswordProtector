<?php
session_start();
require('Credentials.php');
	if(isset($_POST['btnPin'])) {
        $pin = $_POST['pin'];
        
            if($pin == $_SESSION['pin']) {
                $verified = true;
                header("Location: viewSites.php?validate=true");
            } else {
                $verified = false;
                header("Location: viewSites.php");
            }
        }

?>