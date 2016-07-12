<?php
include('crucial.php'); //dbconnection, session setting
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['btnPin'])) { 
        $user = $_GET['userID'];
        $pin = $_POST['pin'];
        $sql =  $conn->prepare("SELECT * 
                        FROM User
                        WHERE User.userID = ?");
        
        if($result = $sql->execute(array($user))){
            $row = $sql->fetchAll(PDO::FETCH_ASSOC);
            if($pin == $row[0]['pin']) {
                $_SESSION['validate'] = true;
                header("Location: viewSites.php?userID=" . $_SESSION['userID']);
            } else {
                $_SESSION['validate'] = false;
                header("Location: viewSites.php?userID=" . $_SESSION['userID']);
            }
        }
        else {
            die("There was an error");
        }
    }
}


?>
<!DOCTYPE html>
<html>

	<head>

	</head>	
	<body>
        <form id="formPin" method="post" name="formPin">
            <span id="spnPin" name="spnPin">
                <label for="pin" id="lbPin" name="lbPin">Enter your pin</label>
                    <input type="text" id="pin" name="pin" />&emsp;
                    <input type="submit" id="btnPin" name="btnPin" value="Confirm Pin" />
            </span>
        </form>
    </body>
</html>