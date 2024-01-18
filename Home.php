<?php
session_start();

// if there is no session, redirect to login page.
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
	
	<?php 
	$retrieveEmployeeMenu = "<div class='card w-100 h-100 p-3'>
                        	   <div class='card-body text-center'>
                        	       <h5 class='card-title'>Manage Employees</h5>
                                	<a class='card-text text-dark stretched-link' href='RetrieveEmployee.php'>
                                    Click here to manage employee records.</a>
	                           </div>
	                       </div>";
	
	$profileMenu = "<div class='card w-100 h-100 p-3'>
                	   <div class='card-body text-center'>
                	       <h5 class='card-title'>View Profile</h5>
                        	<a class='card-text text-dark stretched-link' href='Profile.php'>
                            Click here to view your profile.</a>
                       </div>
                   </div>";
	
	$createLeaveMenu = "<div class='card w-100 h-100 p-3'>
                    	   <div class='card-body text-center'>
                    	       <h5 class='card-title'>Request Leave</h5>
                            	<a class='card-text text-dark stretched-link' href='CreateLeave.php'>
                                Click here to request leave.</a>
                           </div>
                        </div>";
	
	$retrievePayrollMenu = "<div class='card w-100 h-100 p-3'>
                        	   <div class='card-body text-center'>
                        	       <h5 class='card-title'>Manage Payroll</h5>
                                	<a class='card-text text-dark stretched-link' href='RetrievePayroll.php'>
                                    Click here to manage payroll.</a>
                               </div>
                           </div>";
	
	$retrieveLeaveMenu = "<div class='card w-100 h-100 p-3'>
                        	   <div class='card-body text-center'>
                        	       <h5 class='card-title'>Manage Leave Requests</h5>
                                	<a class='card-text text-dark stretched-link' href='RetrieveLeaveSupervisor.php'>
                                    Click here to manage leave requests.</a>
                               </div>
                           </div>";
	
	$viewOwnLeave = "<div class='card w-100 h-100 p-3'>
                        	   <div class='card-body text-center'>
                        	       <h5 class='card-title'>View Own Leave Requests</h5>
                                	<a class='card-text text-dark stretched-link' href='RetrieveLeaveEmployee.php'>
                                    Click here to view your leave requests.</a>
                               </div>
                           </div>";
	
			if($_SESSION['Role_Name'] == "Administrator") {
			    echo "<div class='row mt-5'>
                	<div class='col-md-1'></div>
                	<div class='col-md-3 mx-auto'>
                		$profileMenu
                	</div>
                    <div class='col-md-3 mx-auto'>
                		$createLeaveMenu
            		</div>
                    <div class='col-md-3 mx-auto'>
                        $retrieveEmployeeMenu
                    </div>
                	<div class='col-md-1'></div>
            	</div>";
			}
			
			else if($_SESSION['Role_Name'] == "Department Head") {
			    echo "<div class='row mt-5'>
                        <div class='col-md-1'></div>
                    	<div class='col-md-3 mx-auto'>
                    		$profileMenu
                    	</div>
                    	<div class='col-md-3 mx-auto'>
                    		$createLeaveMenu
                		</div>
                		<div class='col-md-3 mx-auto'>
                    		$viewOwnLeave
                		</div>
                        <div class='col-md-1'></div>
                	</div>";
			    echo "<div class='row mt-5'>
                        <div class='col-md-1'></div>
                        <div class='col-md-3 mx-auto'>
                            $retrieveEmployeeMenu
                        </div>
                    	<div class='col-md-3 mx-auto'>
                    		$retrievePayrollMenu
                    	</div>
                    	<div class='col-md-3 mx-auto'>
                    		$retrieveLeaveMenu
                		</div>
                        <div class='col-md-1'></div>
                	</div>";
			}
			
			else if($_SESSION['Role_Name'] == "Employee"){
                echo "<div class='row mt-5'>
                    	<div class='col-md-3'></div>
                    	<div class='col-md-3'>
                    		$profileMenu
                    	</div>
                    	<div class='col-md-3'>
                    		$createLeaveMenu
                		</div>
                		<div class='col-md-3'></div>
                	</div>";			
			}
			?>
</div>
</body>