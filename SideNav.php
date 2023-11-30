<?php 

if(!isset($_SESSION['Employee_ID']) || $_SESSION['Employee_ID'] == '') {
    echo "<script>alert('Please login first.')</script>";
    header("Location: index.php");
}
else {
    $id = $_SESSION['Employee_ID'];
}
?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<style>
    .nav-menu:hover {
         text-decoration: underline !important; 
    }
</style>

<nav class="navbar navbar-light bg-info">
	<div class="mx-3" style="width: 100%">
		<a class="btn border-dark" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample">
          <i class="fa-solid fa-bars"></i>
        </a>
        <div class="offcanvas offcanvas-start bg-info" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel" style="width: 200px">
          <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasExampleLabel"><b>Menu</b></h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
          </div>
          <div class="offcanvas-body">
            <div>
			<?php 
			
			$homeLink = "<a href='Home.php' class='nav-menu text-dark' style='text-decoration: none; display: block;'>Home</a>";
			$profileLink = "<a href='Profile.php?id=".$id."' class='nav-menu text-dark' style='text-decoration: none; display: block;'>View Profile</a>";
			$createLeaveLink = "<a href='CreateLeave.php' class='nav-menu text-dark' style='text-decoration: none; display: block;'>Request Leave</a>";
			$retrieveEmployeeLink = "<a href='RetrieveEmployee.php' class='nav-menu text-dark' style='text-decoration: none; display: block;'>Manage Employees</a>";
			$retrieveLeaveRequestLink = "<a href='RetrieveLeaveSupervisor.php' class='nav-menu text-dark' style='text-decoration: none; display: block;'>Manage Leave Requests</a>";
			$retrievePayrollLink = "<a href='RetrievePayroll.php' class='nav-menu text-dark' style='text-decoration: none; display: block;'>Manage Payroll</a>";
			
			if($_SESSION['Role_Name'] == "Administrator") {
			    echo "$homeLink
    				<br>
    				$profileLink
    				<br>
                    $createLeaveLink
                    <br>
                    $retrieveEmployeeLink
                ";
			}
			
			else if($_SESSION['Role_Name'] == "Department Head") {
			    echo "$homeLink
			    <br>
				$profileLink
				<br>
			    $createLeaveLink
			    <br>
				$retrieveEmployeeLink
			    <br>
                $retrievePayrollLink
                <br>
                $retrieveLeaveRequestLink";
			}
			
			else if($_SESSION['Role_Name'] == "Employee"){
			    echo "$homeLink
				<br>
				$profileLink
				<br>
				$createLeaveLink";
			}
			?>
				
            </div>
          </div>
        </div>
        <a class="text-dark" href="#" style="text-decoration: none; display: inline-block; font-size:20px">
        	<b>AMC-HRM System</b>
        </a>
        <a class="text-dark mt-1" href="#" style="text-decoration: none; display: inline-block; float: right;">Logout</a>
	</div>	
</nav>
