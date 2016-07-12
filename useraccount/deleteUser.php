<?php
	//////////////////////////////////////////////////
	//-------------Delete Users Page----------------//
	//When an administrator or moderator access this//
	//page, it should show all users. Admin/Mod can //
	//view all the users and check to see if they   //
	//have been active. If not, the admin/mod can   //
	//delete them from the database.                //
	//////////////////////////////////////////////////

	//gets the database credentials from crucial.php
	include('../crucial.php');

	//if the user is a moderator or administrator, they can view this page
	$isModorAdmin = ($_SESSION['rank'] >= '2' ? true : false);
	if(!$isModorAdmin) {
		header("Location: ../viewSites.php");
	} 
	//gets certain rows from the user table
    $sql = $conn->prepare("SELECT userName, firstName, lastName, numSitesAllowed, lastAccess
            FROM User
			WHERE User.approved = '1'");

	$sql->execute();

	//takes the selection of users and deletes them from the database
	$aUser = $_POST['chUser'];
		if(empty($aUser)){
			//echo "You didn't select a user";
		}else{
			$count = count($aUser);
			//echo "Deleting user: " . $aUser[$i]. "";
			for($i = 0; $i < $count; $i++){
				$sql = $conn->prepare("DELETE FROM User WHERE userID=" . $aUser[$i]."");
				$sql->execute();
			}
		}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Password Protector|Manage Users</title>
 
        <script type="text/javascript" src="../plugins\jquery.js"></script>
        <script type="text/javascript" src="../plugins\bootstrap\js\bootstrap.min.js"></script>
        <script type="text/javascript" src="../plugins/datatables/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="../plugins/datatables/TableTools.min.js"></script>

        <link type="text/css" rel="stylesheet" href="../plugins\bootstrap\css\bootstrap.min.css" />
        <link type="text/css" rel="stylesheet" href="../plugins\bootstrap\css\bootstrap-theme.min.css" />
        <link type="text/css" rel="stylesheet" href="../plugins/datatables/jquery.dataTables.css"/>
        <link type="text/css" rel="stylesheet" href="../plugins/datatables/TableTools.css"/>
        <link type="text/css" rel="stylesheet" href="../css/extra.css"/>
        <link type="text/css" rel="stylesheet" href="../css/style.css"/>

		<style>
		body {
			margin: 0;
		}

		ul {
			list-style-type: none;
			margin: 0;
			padding: 0;
			width: 25%;
			background-color: #f1f1f1;
			position: fixed;
			height: 50%;
			overflow: auto;
		}

		li a {
			display: block;
			color: #000;
			padding: 8px 0 8px 16px;
			text-decoration: none;
		}

		li a.active {
			background-color: #4CAF50;
			color: white;
		}

		li a:hover:not(.active) {
			background-color: #555;
			color: white;
		}
		</style>

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
			<!-- Menu for managing user accounts -->
			<ul> <h3> User Accounts </h3>
				<li><a href="accountRequests.php">New Account Requests</a></li>
				<li><a href="manageUsers.php">Manage User Accounts</a></li>
				<!-- if user is a moderator, they have more access -->
				<?php if ($_SESSION['rank'] == 3){
					echo "<li><a href='manageAdmin.php'>Manage Admin Accounts</a></li>";
					echo "<li><a href='adminRequests.php'>Open Admin Access Requests</a></li>";
					}	?>
			</ul>

		<div style="margin-left:10%;padding:1px 16px;height:1000px;">
        <div class="row content" style="margin-top:5%" >
            <div class="col-xs-8 col-xs-offset-2">
                <div class="well" style="background:lightgray;padding-bottom:35px">
					<h4><strong>Select and Delete a User</strong></h4>
					<div class="row">
						<form action="deleteUser.php" method="post" name="fromManageUsers">

                    <!-- loop through database outputting site info -->
                                <?php
                                    while($row = $sql->fetch(PDO::FETCH_ASSOC)){
										echo "<input type='checkbox' name='chUser[]' id='chUser";
										echo $row['userID'] . "' value='" . $row['userID'] . "' />";
									echo "<label for='chUser" .$row['userID'] . "'>" . $row['userName'] . " | " . 
											$row['firstName'] . " " . $row['lastName']. " | " . $row['lastAccess'];
										echo "</label></br>\n"; 
									}
                                ?>                                           
						<input id="btnSubmit" type="submit" value="Delete User" />
                    </div>   
					</form>
                </div>
            </div>
        </div>
		</div>
    </body>
	 
</html>