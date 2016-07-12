<?php

include('crucial.php');

//Post fork
if($_SERVER['REQUEST_METHOD'] == 'POST'){
	
	//variable for login/create/invalid branch
	if(isset($_GET['type'])){
		$type=$_GET['type'];
	}
		
    //if login then check user exists else return invalid
	if($type == 'login'){
		$_SESSION['username'] = $username = $_POST['userNameLogin'];
		$_SESSION['password'] = $password = $_POST['passwordLogin'];
        
        //pull info for user logging in
		$sql = $conn->prepare("SELECT * FROM USER WHERE userName = ? AND password = ?");
		
		if($result = $sql->execute(array($username, $password))){
			$row = $sql->fetchAll(PDO::FETCH_ASSOC);
			$conn = null;
			if(count($row) == 1){
            
                //set variables for the user
				$_SESSION['validUser'] = true;
                $_SESSION['firstName'] = $row[0]['firstName'];
                $_SESSION['lastName'] = $row[0]['lastName'];
                $_SESSION['email'] = $row[0]['email'];
                $_SESSION['userID'] = $row[0]['userID'];
                $_SESSION['pin'] = $row[0]['pin'];
                $_SESSION['rank'] = $row[0]['accessLevel'];
                if(!isset($_SESSION['validate'])) {
                    $_SESSION['validate'] = false;    
                }
                
				header("Location: viewSites.php?userID=" . $_SESSION['userID']); //2/17/2016 BF set GET variable in URL
            
			}else{
            
				$_SESSION['validUser'] = false;
				header("Location: index.php?type=invalid"); 
            
			}
		}
    //if create insert new user into database
	}else if($type == 'create'){
        //set variables for the user
		$_SESSION['firstName'] = $firstName = ''.$_POST['firstNameCreate'];
		$_SESSION['lastName'] = $lastName = ''.$_POST['lastNameCreate'];
		$_SESSION['email'] = $email = ''.$_POST['emailCreate'];
		$_SESSION['password'] = $password = ''.$_POST['passwordCreate'];
		$_SESSION['username'] = $username = ''.$_POST['usernameCreate'];
        $_SESSION['pin'] = $pin = createPin(); //create genuine pin so it is not easily guessed
        $_SESSION['rank'] = '1';
        $_SESSION['validate'] = false;
		
        //add user to database -> need to check to make sure username does not exist
		$sql = $conn->prepare("INSERT INTO `User`(`userName`, `password`, `firstName`, `lastName`, `email`, `pin`) VALUES (?,?,?,?,?,?)"); 
		if(!filter_var($email,FILTER_VALIDATE_EMAIL) )
                {
                    header("Location: index.php"); //BF 2/17/2016 --> if email is not correct reload index
                                                   //should show message eventually
                }
        else {
            if($result = $sql->execute(array($username, $password, $firstName, $lastName, $email, $pin))){
			$conn = null;
			header("Location: viewSites.php");
            
        
            $return_address = "foranb1@student.lasalle.edu"; //sends pin to user after successful insert into database
            $subject = "Membership Info";
            $body = "<html><body><p>Your created username is: " . $username . "</p>";
            $body = $body . "<p>Your generated pin is: " . $pin ."</p></body></html>";
            $extra = "from: $return_address\nContent-type: text/html; charset=iso-8859-1 ";
            mail(htmlspecialchars($email), $subject, $body, $extra);
            
		}
    }
		
    //if invalid unhandled
	}else if($type == 'invalid'){
		
	}
	
}

function createPin() { //separate function for creating pin -> send via email upon account creation
    $poss = "0123456789";
    $pin = "";
    for($i = 0; $i<4; $i++) {
        $pin .= $poss[rand(0, strlen($poss) - 1)];
    }
    return $pin;
}

?>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <title>Password Protector|Login</title>

    <script type="text/javascript" src="plugins\jquery.js"></script>
    <script type="text/javascript" src="plugins\bootstrap\js\bootstrap.min.js"></script>

    <link type="text/css" rel="stylesheet" href="plugins\bootstrap\css\bootstrap.min.css" />
    <link type="text/css" rel="stylesheet" href="plugins\bootstrap\css\bootstrap-theme.min.css" />
    <link type="text/css" rel="stylesheet" href="css/style.css"/>

</head>
<body>
    <!-- logo -->
    <div class="row">
        <div class="col-xs-12 logo">
            <img src="img/logo_good.png" width="250">
        </div>
    </div>
    <div class="row">
        <div class="col-xs-4 col-xs-offset-1">
            <h2 ><strong>Password Protector</strong></h2>
            <div class="well" style="background:lightgray">
                <!-- login form -->
                <h4><strong>Login</strong></h4>
                <form action="index.php?type=login" method="post" id="loginForm">
                    <div class="form-group">
                        <label for="userNameLogin">User Name</label>
                        <input type="text" id="userNameLogin" name="userNameLogin" class="form-control" placeholder="User Name" required/>
                    </div>
                    <div class="form-group">
                        <label for="passwordLogin">Password</label>
                        <input type="password" id="passwordLogin" name="passwordLogin" class="form-control" placeholder="Password" required/>
                    </div>
                    <input type="submit" id="loginSubmit" onClick="alert(You logged in!)" value="Login" class="btn btn-success"/>
                </form>
            </div>
        </div>
        <div class="col-xs-4 col-xs-offset-2">
            <h2 ><strong>Not a User?</strong></h2>
            <div class="well" style="background:lightgray">
                <!-- create account form -->
                <h4><strong>Create Account</strong></h4>
                <form action="index.php?type=create" method="post" id="createAccountForm">
                    <div class="form-group">
                        <label for="firstNameCreate" >First Name</label>
                        <input type="text" id="firstNameCreate" name="firstNameCreate" class="form-control" placeholder="First Name" required/>
                    </div>
                    <div class="form-group">
                        <label for="lastNameCreate" >Last Name</label>
                        <input type="text" id="lastNameCreate" name="lastNameCreate" class="form-control" placeholder="Last Name" required/>
                    </div>
                    <div class="form-group">
                        <label for="emailCreate" >Email</label>
                        <input type="email" id="emailCreate" name="emailCreate" class="form-control" placeholder="Email" required/>
                    </div>
                    <div class="form-group">
                        <label for="usernameCreate" >User Name</label>
                        <input type="text" id="usernameCreate" name="usernameCreate" class="form-control" placeholder="User Name" required/>
                    </div>
                    <div class="form-group">
                        <label for="passwordCreate" >Password</label>
                        <input type="password" id="passwordCreate" name="passwordCreate" class="form-control" placeholder="Password" required/>
                    </div>
                    <input type="submit" id="createSubmit" value="Sign Up" class="btn btn-success"/>
                </form>
            </div>
        </div>
    </div>
</body>
</html>