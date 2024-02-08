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
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="Home page of AMC HR system">
<title>Home page</title>
<style>
    a.stretched-link {
        text-decoration: none;
    }
    h2.card-title {
        font-size: 20px;
    }
</style>
</head>
<body class="bg-light">
<?php include_once 'SideNav.php'?>
<div class="container-fluid mt-5">
	<h1 class="text-center">Welcome to AMC-HRM System, <?php echo $_SESSION['Name']?>.</h1>
	<p class="text-center mt-3">Here's a quick look at some of the things you can do in this system. Enjoy your day!</p>
	
	<?php
	$retrieveEmployeeMenu = "<div class='card w-100 h-100 p-3'>
                        	   <div class='card-body text-center'>
                        	       <h2 class='card-title'>Manage Employees</h2>
<<<<<<< HEAD
                                	<a class='card-text text-dark stretched-link' aria-label='Click here to manage employee records.' href='RetrieveEmployee.php'>
=======
                                	<a class='card-text text-dark stretched-link'
                                        aria-label='Click here to manage employee records.'
                                        href='RetrieveEmployee.php'>
>>>>>>> 6b7ba8253d8a1802421af134a172f71a0f4fb25b
                                    Click here to manage employee records.</a>
	                           </div>
	                       </div>";
	
	$profileMenu = "<div class='card w-100 h-100 p-3'>
                	   <div class='card-body text-center'>
                	       <h2 class='card-title'>View Profile</h2>
<<<<<<< HEAD
                        	<a class='card-text text-dark stretched-link' aria-label='Click here to view your profile.' href='Profile.php'>
=======
                        	<a class='card-text text-dark stretched-link'
                                aria-label='Click here to view your profile.'
                                href='Profile.php'>
>>>>>>> 6b7ba8253d8a1802421af134a172f71a0f4fb25b
                            Click here to view your profile.</a>
                       </div>
                   </div>";
	
	$createLeaveMenu = "<div class='card w-100 h-100 p-3'>
                    	   <div class='card-body text-center'>
                    	       <h2 class='card-title'>Request Leave</h2>
<<<<<<< HEAD
                            	<a class='card-text text-dark stretched-link' aria-label='Click here to request leave.' href='CreateLeave.php'>
=======
                            	<a class='card-text text-dark stretched-link'
                                    aria-label='Click here to request leave.'
                                    href='CreateLeave.php'>
>>>>>>> 6b7ba8253d8a1802421af134a172f71a0f4fb25b
                                Click here to request leave.</a>
                           </div>
                        </div>";
	
	$retrievePayrollMenu = "<div class='card w-100 h-100 p-3'>
                        	   <div class='card-body text-center'>
                        	       <h2 class='card-title'>Manage Payroll</h2>
<<<<<<< HEAD
                                	<a class='card-text text-dark stretched-link' aria-label='Click here to manage payroll.' href='RetrievePayroll.php'>
=======
                                	<a class='card-text text-dark stretched-link'
                                        aria-label='Click here to manage payroll.'
                                        href='RetrievePayroll.php'>
>>>>>>> 6b7ba8253d8a1802421af134a172f71a0f4fb25b
                                    Click here to manage payroll.</a>
                               </div>
                           </div>";
	
	$retrieveLeaveMenu = "<div class='card w-100 h-100 p-3'>
                        	   <div class='card-body text-center'>
                        	       <h2 class='card-title'>Manage Leave Requests</h2>
<<<<<<< HEAD
                                	<a class='card-text text-dark stretched-link' aria-label='Click here to manage leave requests.' href='RetrieveLeaveSupervisor.php'>
=======
                                	<a class='card-text text-dark stretched-link'
                                        aria-label='Click here to manage leave requests.'
                                        href='RetrieveLeaveSupervisor.php'>
>>>>>>> 6b7ba8253d8a1802421af134a172f71a0f4fb25b
                                    Click here to manage leave requests.</a>
                               </div>
                           </div>";
	
	$viewOwnLeave = "<div class='card w-100 h-100 p-3'>
                        	   <div class='card-body text-center'>
                        	       <h2 class='card-title'>View Own Leave Requests</h2>
<<<<<<< HEAD
                                	<a class='card-text text-dark stretched-link' aria-label='Click here to view your leave requests.' href='RetrieveLeaveEmployee.php'>
=======
                                	<a class='card-text text-dark stretched-link'
                                        aria-label='Click here to view your leave requests.'
                                        href='RetrieveLeaveEmployee.php'>
>>>>>>> 6b7ba8253d8a1802421af134a172f71a0f4fb25b
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
			
			elseif($_SESSION['Role_Name'] == "Department Head") {
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
			
			elseif($_SESSION['Role_Name'] == "Employee"){
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