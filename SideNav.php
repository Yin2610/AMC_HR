<?php 
session_start();
$id = $_SESSION['Employee_ID']
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
<!--               	if normal user -->
				<a href="Home.php" class="nav-menu text-dark" style="text-decoration: none; display: block;">Home</a>
				<br>
				<a href="profile.php?id=<?php echo $id?>" class="nav-menu text-dark" style="text-decoration: none; display: block;">Profile</a>
				<br>
				<a href="CreateLeave.php" class="nav-menu text-dark" style="text-decoration: none; display: block;">Request Leave</a>
<!-- 				if admin  -->
<!-- 				<a href="#">View Employees</a> -->
<!-- 				<a href="#">Submit Payroll</a> -->
				
<!-- 				if supervisor -->
<!-- 				<a href="#">View Employees</a> -->
				
            </div>
          </div>
        </div>
        <a class="text-dark" href="#" style="text-decoration: none; display: inline-block; font-size:20px">
        	<b>AMC-HRM System</b>
        </a>
        <a class="text-dark mt-1" href="#" style="text-decoration: none; display: inline-block; float: right;">Logout</a>
	</div>	
</nav>
