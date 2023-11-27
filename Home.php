<?php ?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"> -->
<!-- <script src="js/bootstrap.min.js"></script> -->
<style>
    a.stretched-link {
        text-decoration: none;
    }
</style>
</head>
<body class="bg-light">
<?php include('SideNav.php')?>
<div class="container-fluid mt-5">
	<h3 class="text-center">Welcome to AMC-HRM System.</h3>
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