<?php 
session_start();
if(isset($_SESSION['Employee_ID'])&&isset($_SESSION['Name'])) {

?>
<html>
<head>
<title>Home</title>
</head>
<body>  
	<h1>Hello, <?php echo $_SESSION['Name']?></h1>
</body>
</html>
<?php 
}else{
    header("Location: index.php");
    exit();
}?>