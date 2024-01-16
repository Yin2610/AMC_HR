<?php
include "dbConnection.php";

session_start();

if(!isset($_SESSION['Employee_ID']) || $_SESSION['Employee_ID'] == '') {
    echo "<script>alert('Please login first.')</script>";
        header("Location: index.php");
}

if($_SESSION['Role_Name'] != 'Administrator' && $_SESSION['Role_Name'] != 'Department Head') {
    echo "You don't have permission to view this page.";
    exit();
}

$pdo = DBConnection::connectToDB();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = "SELECT * FROM employee";
$query = $pdo->prepare($sql);
$query->execute();
$data = $query->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    
	<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
	<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="bg-light">
    <?php include('SideNav.php')?>
    <div class="container-fluid mt-4">
        <nav aria-label="breadcrumb">
    		<ol class="breadcrumb mb-4">
    			<li class="breadcrumb-item"><a href="Home.php">Home</a></li>
    			<li class="breadcrumb-item active" aria-current="page">View Employees</li>
    		</ol>
    	</nav>
    	<?php 
    	if($_SESSION['Role_Name'] == 'Administrator') {
    	    echo "<a href='CreateEmployee.php' style='text-decoration: none; float: right' class='text-dark btn btn-outline-info mb-5'>Create New Employee</a>";
    	}
    	?>
        <table id="employeeTable" class="display hover" style="width: 100%">
    		<thead>
    			<tr>
                <th>Name</th>
                <th>Gender</th>
                <th>Date of Birth</th>
                <th>Phone Number</th>
                <th>Email</th>
                <th>Address</th>
                <th>Onboard Date</th>
                <th>Offboard Date</th>
                <th>Profile Picture</th>
                <th>Resume</th>
                <th>Contract</th>
                <th>Actions</th>
            </tr>
            </thead>
        <tbody> 
	
	<?php 
	foreach ($data as $row) {
	    echo "<tr><td>".$row['Name']."</td>";
	    echo "<td>".$row['Gender']."</td>";
	    echo "<td>".$row['Date_Of_Birth']."</td>";
	    echo "<td>".$row['Phone_Num']."</td>";
	    echo "<td>".$row['Email']."</td>";
	    echo "<td>".$row['Address']."</td>";
	    echo "<td>".$row['Onboard_Date']."</td>";
	    echo "<td>".$row['Offboard_Date']."</td>";
	    
	    echo "<td>";
	    echo "<div class='text-center' >";
	    echo "<img style='width:70px;height:70px;border-radius: 50%;' src='".$row['Profile_Pic']."'>";
	    echo "<br>";
	    echo "</div>";
	    echo "</td>";
	    
	    echo "<td>";
	    echo "<a href='".$row['Resume']."'class='btn' target='_blank'><i class='fa-solid fa-download'></i></a>";

	    echo "</td>";
	    
	    echo "<td>";
	    echo "<a href='".$row['Contract']."'class='btn' target='_blank'><i class='fa-solid fa-download'></i></a>";

	    echo "</td>";
	    
	    echo "<td colspan='2'><a class='btn' href='UpdateEmployee.php?id=". $row['Employee_ID'] . "'><i class='fa-solid fa-pen'></i> Edit</a><br>";
	    echo "<a class='btn' href='DeleteEmployee.php?id=". $row['Employee_ID'] . "'><i class='fa-solid fa-trash'></i> Delete</a><br>";

	    echo "</td></tr>";
	}
	
	?>                
            </tbody>
        </table>
    </div> 
    <script type="text/javascript">
		$(document).ready(function () {
            $('#employeeTable').DataTable({
            	'scrollX': true
            });
        } );
	</script>
</body>
</html>

