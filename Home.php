<?php
session_start();

if(!isset($_SESSION['Employee_ID']) || $_SESSION['Employee_ID'] == '') {
    echo "<script>alert('Please login first.')</script>";
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<style>
    a.stretched-link {
        text-decoration: none;
    }
</style>
</head>
<body class="bg-light">
<?php include('SideNav.php')?>
<div class="container-fluid mt-5">
	<h3 class="text-center">Welcome to AMC-HRM System, <?php echo $_SESSION['Name']?>.</h3>
	<p class="text-center mt-3">Here's a quick look at some of the things you can do in this system. Enjoy your day!</p>
	<div class="row mt-5">
    	<div class="col-md-3"></div>
    	<div class="col-md-3">
    		<div class="card w-100 h-100 p-3 mx-auto">
    			<div class="card-body text-center">
    				<h5 class="card-title">Leave Requests</h5>
    				<a class="card-text text-dark stretched-link" href="#">Click here to submit or view leave requests.</a>
    			</div>
    		</div>
    	</div>
    	<div class="col-md-3">
    		<div class="card w-100 h-100 p-3 mx-auto">
    			<div class="card-body text-center">
    				<h5 class="card-title">Payroll Data</h5>
    				<a class="card-text text-dark stretched-link" href="RetrievePayroll.php">Click here to view past payroll data.</a>
    			</div>
    		</div>
		</div>
		<div class="col-md-3"></div>
	</div>
</div>
</body>