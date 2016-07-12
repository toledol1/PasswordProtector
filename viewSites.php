<?php
include('crucial.php');
//check if validate is set, simplify
if(isset($_GET['validate'])) { 
    $auth=$_GET['validate'];
}


//check if there are sites for this account
$sql = $conn->prepare("SELECT * 
            FROM `Account` 
            JOIN `User` ON Account.userID = User.userID
            WHERE User.userID = ?"); //changed to user id from username


//if no sites added send to add site page
if($result = $sql->execute(array($_SESSION['userID']))){ //changed to user id from username
    $row = $sql->fetchAll(PDO::FETCH_ASSOC);
    $count = count($row);
    $conn = null;
    if($count == 0 || $row == ""){
        //header("Location: addSite.php");
    }
}

//var_dump("Is mod: " . $_SESSION['rank']);

	
?>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="utf-8" />
        <title>PasswordProtector|View Sites</title>

       <script type="text/javascript" src="plugins\jquery.js"></script>
        <script type="text/javascript" src="plugins\bootstrap\js\bootstrap.min.js"></script>
        <script type="text/javascript" src="plugins/datatables/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="plugins/datatables/TableTools.min.js"></script>

        <link type="text/css" rel="stylesheet" href="plugins\bootstrap\css\bootstrap.min.css" />
        <link type="text/css" rel="stylesheet" href="plugins\bootstrap\css\bootstrap-theme.min.css" />
        <link type="text/css" rel="stylesheet" href="plugins/datatables/jquery.dataTables.css"/>
        <link type="text/css" rel="stylesheet" href="plugins/datatables/TableTools.css"/>
        <link type="text/css" rel="stylesheet" href="css/extra.css"/>
        <link type="text/css" rel="stylesheet" href="css/style.css"/>

        <script>
            $(document).ready(function(){
                $('#siteTable').dataTable();

                $("#siteTable_paginate").attr("class", $("#siteTable_paginate").attr("class") + " btn-group");
                $("#ToolTables_siteTable_0").attr("class",$("#ToolTables_siteTable_0").attr("class") + " btn btn-success");
                $("#ToolTables_siteTable_1").attr("class",$("#ToolTables_siteTable_1").attr("class") + " btn btn-success");


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
            <div class="col-xs-8 col-xs-offset-2">
                <div class="well" style="background:lightgray;padding-bottom:35px">
                    <h4><strong>View Site Information</strong></h4>
                    <div class="row">
                        <table id="siteTable">
                            <thead>
                                <tr>
                                    <th>Website</th>
                                    <th>Username</th>
                                    <th>Password</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- loop through database outputting site info -->
                                <?php
                                    if(!$_SESSION['validate']) {
                                        foreach($row as $site){
                                            echo "\n<tr>\n";
                                            echo "\t<td>";
                                                echo $site['siteName'];
                                            echo "</td>\n";
                                            echo "\t<td>";
                                                echo $site['sUserName'];
                                            echo "</td>\n";
                                            echo "\t<td>";
                                                echo "*********";
                                            echo "</td>\n";
                                            echo "\n</tr>\n";
                                        }
                                    } else if($_SESSION['validate']) {
                                        foreach($row as $site){
                                            echo "\n<tr>\n";
                                            echo "\t<td>";
                                                echo $site['siteName'];
                                            echo "</td>\n";
                                            echo "\t<td>";
                                                echo $site['sUserName'];
                                            echo "</td>\n";
                                            echo "\t<td>";
                                                echo base64_decode($site['sitePassword']);
                                            echo "</td>\n";
                                            echo "\n</tr>\n";
                                        }
                                    }
                                    ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- add site button -->
                    <div class="row">
                        <a href="addSite.php?userID=<?php echo $_SESSION['userID']; ?>"><input type="button" id="addBtn" name="addBtn" value="Add Site" class="btn btn-success"/></a>
                        <form method="post" action="confirmPin.php?userID=<?php echo $_SESSION['userID']; ?>" style="display:inline-block">
                                <button id="showPass" name="showPass" value="View All Passwords" class="btn btn-success">View All Passwords</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>