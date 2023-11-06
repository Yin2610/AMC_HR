<?php 
include('dbConnection.php');
if(!empty($_POST)) {
    $valid = true;
    $RoleName =  $_POST['txtRoleName'];
    
    if($valid) {
        $pdo = DBConnection::connectToDB();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO role (Role_Name) VALUES (?)";
        $query = $pdo->prepare($sql);
        $query->execute(array($RoleName));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link   href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
</head>

<body>
    <form action="CreateRole.php" method="post">
    	<label for="txtRoleName">Role name: </label>
    	<input name="txtRoleName" type="text">
    	<br>
    	<button name="btnSubmit" type="submit">Submit</button>
    </form>
</body>