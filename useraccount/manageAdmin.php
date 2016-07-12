<?php
	//////////////////////////////////////////////////
	//-------------Manage Admin Page----------------//
	//When the moderator accesses this page it will //
	//Show all the users who have became an admin.  //
	//Mods can change the user's admin privileges   //
	//or delete them from the database.             //
	//////////////////////////////////////////////////

	//gets the database credentials from crucial.php
	include('../crucial.php');

	//if the user is a moderator or administrator, they can view this page
	$isModorAdmin = ($_SESSION['rank'] >= '2' ? true : false);
	if(!$isModorAdmin) {
		header("Location: ../viewSites.php");
	} 
	//gets certain rows from the user table
    $sql = $conn->prepare("SELECT userID, userName, firstName, lastName, lastAccess, accessLevel
            FROM User
			WHERE User.accessLevel = '2'"); //shows only admin accounts

    $sql->execute();
	
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Password Protector|Manage Administrators</title>
 
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
		
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
		<script src="http://cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
		<link rel="StyleSheet" href="http://cdn.datatables.net/1.10.7/css/jquery.dataTables.css" type="text/css" />
		<script src="http://www.appelsiini.net/projects/jeditable/jquery.jeditable.js" type="text/javascript" charset="utf-8"></script>

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
		<script>
           $(document).ready(function(){
                $('#siteTable').dataTable(
				{
					"columnDefs": [ 
					{
						"targets": [ 0 ],
						"visible": false,
						"searchable": false
					}

					]
				} 
			);

                $("#siteTable_paginate").attr("class", $("#siteTable_paginate").attr("class") + " btn-group");
                $("#ToolTables_siteTable_0").attr("class",$("#ToolTables_siteTable_0").attr("class") + " btn btn-success");
                $("#ToolTables_siteTable_1").attr("class",$("#ToolTables_siteTable_1").attr("class") + " btn btn-success");

				//give all the elements with the class edit the Jeditable functionality
				$(".edit").editable("requests_jeditable_response.php", { 
					tooltip   : "Click to edit...",
					style  : "inherit"
				});  //end of editable

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
					<h4><strong>Manage Admin Accounts</strong></h4>
					<div class="row">
						<table id="siteTable">
							<thead>
								<tr>
									<th>User ID </th>
									<th>Username</th>
									<th>First Name</th>
									<th>Last Name</th>
									<th>Last Active</th>
									<th>Access Level</th>
								</tr>
							</thead>
							<tbody>
                    <!-- loop through database outputting site info -->
								<?php
                                    while ($row = $sql->fetch(PDO::FETCH_ASSOC)){
										echo "<tr>\n";
										foreach ($row as $key => $value){
											echo "<td class='edit' id='" . $row['userID'] . "_" . $key . "'>";
											echo $value;
											echo "</td>\n";
										}
									echo "</tr>\n";

                                    }
                                   ?>
                            </tbody>
                        </table>
                    </div>                 
                </div>
            </div>
        </div>
		</div>
    </body>
	 
</html>