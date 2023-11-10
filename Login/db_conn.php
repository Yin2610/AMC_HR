<html>
<head>
<title>db_conn</title>
</head>
<body>  
<?php
//info of the database
$sname="localhost";
$unmae="root";
$password="";
$db_name="amc_hr";

$conn = new mysqli($sname,$unmae,$password,$db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
</body>
</html>

