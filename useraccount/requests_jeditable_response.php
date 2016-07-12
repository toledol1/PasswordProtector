<?php
include('../crucial.php');
 

$statement = $conn->prepare("SELECT * FROM User");
$statement->execute();

	
    /*if($result = $sql->execute()) {
        $row = $sql->fetchAll(PDO::FETCH_ASSOC);
        $count = count($row);
        $conn = null;
    }
	*/

//retrieve id and value 
$post_id = $_POST['id'];
$post_value = $_POST['value'];

$tokens = explode("_", $post_id); //break compound id into parts
// for example breaks AK_CapitalMunicPop into AK and CapitalMunicPop
//http://php.net/explode


$sql = "UPDATE User SET " . $tokens[1] . "=? WHERE userID=?";  
$q = $conn->prepare($sql);  
$q->execute(array($post_value,$tokens[0])); 
//should add some code to make sure $tokens[1] does not include any SQL injection


// we will get result from database instead of just echoing back $post_value in case UPDATE did not work
$sql = "SELECT " . $tokens[1] . " FROM User WHERE userID=?";  
$q = $conn->prepare($sql);  
$q->execute(array($tokens[0])); 
$result = $q->fetchColumn();

echo $result;

?>