<?php
//HS
include('crucial.php');

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    
    //check is userID is set
    if(!isset($_SESSION['userID'] && ($_SESSION['approved'] == '1'))){

        //pulling the information for the current user
        $sql = $conn->prepare("SELECT * FROM USER WHERE userName = ? AND password = ?");

        if($result = $sql->execute(array($_SESSION['username'], $_SESSION['password']))){
            $row = $sql->fetchAll(PDO::FETCH_ASSOC);
            $conn = null;

            //if user has info in the database set userID else send to login/create page
            if(count($row) > 0 && $row != ""){

                $_SESSION['userID'] = $row[0]['userID'];

            }else{

                $_SESSION['validUser'] = false;
                header("Location: index.php"); 

            }
        }
    }
    $webName = $_POST['addSiteWebsite'];
    $webUser = $_POST['addSiteUserName'];
    $webPass = $_POST['addSitePassword'];
    //because of the required attribute (and eventually active validation), adding an empty string
    //before the field is unnecessary to prevent null inserts



    //insert new site into database
    $sql = $conn->prepare("INSERT INTO `Account`(`siteName`, `sUserName`, `sitePassword`, `userID`) VALUES (?,?,?,?)");
    if($result = $sql->execute(array($webName, $webUser, base64_encode($webPass), $_SESSION['userID']))){
        $conn = null;
        header("Location: viewSites.php?userID=" . $_GET['userID'] . "&validate=false");
    }
	
}
?>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <title>Password Protector|Add Site</title>

    <script type="text/javascript" src="plugins\jquery.js"></script>
    <script type="text/javascript" src="plugins\bootstrap\js\bootstrap.min.js"></script>
    <script type="text/javascript" src="plugins\validate.js"></script>

    <link type="text/css" rel="stylesheet" href="plugins\bootstrap\css\bootstrap.min.css" />
    <link type="text/css" rel="stylesheet" href="plugins\bootstrap\css\bootstrap-theme.min.css" />
    <link type="text/css" rel="stylesheet" href="css/style.css"/>
    <script>
		//generates a random password
        $().ready(function() { 
            
            $("#genPass").click(function() {
                var pass = ""; //empty pw string
                var x = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()[]{}><-=`~"; //possible characters in pw

                for(var i = 0; i<16;i++) 
                    pass += x.charAt(Math.floor(Math.random() * x.length));

                $("#addSitePassword").attr("type", "text"); //remove password attribute so the user can see what the password has been created as before encryption
                $("#addSitePassword").val(pass); //display
            });
           

        });
    </script>
</head>
<body>
     <!-- Header -->
        <div class="row header">
            <!-- logo -->
            <div class="col-xs-2 logo">
                <img src="../img/logo_good.png" width="150">
            </div>
            <div class="col-xs-4" style="padding-top:35px;">   
                <span><h1><strong>Password Protector</strong></h1></span>
            </div>
            <div class="col-xs-4 col-xs-offset-2" style="padding-top: 30px; padding-right:35px;">
                <!-- Welcome message -->
                <div class="row welcome">
                    <div class="col-xs-12">
						<!--shows name and ranking if admin or moderator-->
                        <h2><strong>Welcome, <?php echo $_SESSION['firstName'];?>!
						<?php if ($_SESSION['rank'] == 3) echo " (mod)";
						else if ($_SESSION['rank'] == 2) echo " (admin)"; ?></strong></h2>
                    </div>
                </div>
                <!-- User Options -->
                <div class="row navbarHeader">
                    <div class="col-xs-12">
						<!--Account goes back to viewSites page-->
                        <strong><a href="../viewSites.php" class="navbarLabel">Account</a></strong>
						<!-- User Accounts. if user is an admin or a moderator the will see and have access to this page -->
						<?php if($_SESSION['rank'] >= 2)
							echo "| <strong><a href='../useraccount/accountRequests.php' class='navbarLabel'>User Accounts</a></strong> "
						?>
						<!-- Settings. User can edit their information and pay for for more accounts -->
                        | <strong><a href="#" class="navbarLabel">Settings</a></strong>
						<!-- Logout. User can logout of the site -->
                        | <strong><a href="../logout.php" class="navbarLabel">Logout</a></strong>
                    </div>
                </div>
            </div>
        </div>
    <!-- end Header -->
	<a href="#"><input type="button" value="Need Help?" class="btn btn-sm btn-default" style="float:right"/></a>
    <div class="row content" style="margin-top:5%">
        <div class="col-xs-4 col-xs-offset-4">
            <div class="well" style="background:lightgray">
                <!-- add site form -->
                <h4><strong>Add Site Information</strong></h4>
                <form action="addSite.php" method="post" id="loginForm">
					<div class="form-group">
                        <label for="addSiteWebsite">Website</label>
                        <input type="text" id="addSiteWebsite" name="addSiteWebsite" class="form-control" placeholder="Website" required/>
                    </div>
                    <div class="form-group">
                        <label for="addSiteUserName">User Name</label>
                        <input type="text" id="addSiteUserName" name="addSiteUserName" class="form-control" placeholder="User Name" required/>
                    </div>
                    <div class="form-group">
                        <label for="addSitePassword">Password</label>
                        <input type="password" id="addSitePassword" name="addSitePassword" class="form-control" placeholder="Password" required/>
                    </div>
                    
                    <!-- submit form button -->
                    <input type="submit" id="addSiteSubmit" value="Add Site" class="btn btn-success"/>
                    <!-- generate pw button 1/27/2016 -->
                    <div id="divGenPass" style="display:inline;text-align:center;padding-left:31px;">
                        <a href="#"><input type="button" name="genPass" id="genPass" value="Generate Password" class="btn btn-warning" />                            
                    </div>
                    <!-- cancel form button -->
                    <a href="viewSites.php?userID=<?php echo $_GET['userID']; ?>">
                        <button id="cancelBtn" name="cancelBtn" value="Cancel" class="btn btn-warning" style="float:right;">Cancel</button>
                    </a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>