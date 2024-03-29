<?php
include "dbConnection.php";

session_start();
//checks if the user is logged in or not. 
if(!isset($_SESSION['Employee_ID']) || $_SESSION['Employee_ID'] == '') {
    echo "<script>alert('Please login first.')</script>";
        header("Location: index.php");
}
ELSE {
    $designation = $_SESSION['Designation'];
}
//checks if the user is allowed to view the page. 
if($_SESSION['Role_Name'] != 'Administrator' && $_SESSION['Role_Name'] != 'Department Head') {
    echo "You don't have permission to view this page.";
    exit();
}
//establishes a connection to the DB and sets the error mode to exception handling
$pdo = DBConnection::connectToDB();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//Creates a query where it combinines specific columns from different tables into one using the join clause.
$sql = "SELECT e.*, d.Department_Name, ds.Designation, b.Bank_Name, r.Role_Name
        FROM employee as e
        JOIN designation as ds ON ds.Designation_ID = e.Designation_ID
        JOIN department as d ON d.Department_ID = ds.Department_ID
        JOIN bank as b ON b.Bank_ID = e.Bank_ID
        JOIN role as r ON r.Role_ID = e.Role_ID
        ";
//modifies the sql query based on the user's designation
IF($designation == "Purchasing director") {
    $sql .= " WHERE d.Department_Name = 'Purchasing Department'";
}
ELSE IF($designation == "Sales director") {
    $sql .= " WHERE d.Department_Name = 'Sales Department'";
}
ELSE IF($designation == "HR director") {
    $sql .= " WHERE d.Department_Name = 'HR Department'";
}

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
    				<th>Profile Picture</th>
                    <th>Name</th>
                    <th>Gender</th>
                    <th>Date of Birth</th>
                    <th>Phone Number</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Onboard Date</th>
                    <th>Offboard Date</th>
                    <th>Department</th>
                    <th>Designation</th>
                    <th>Role</th>
                    <th>Bank</th>
                    <th>Resume</th>
                    <th>Contract</th>
                    <th>Actions</th>
                </tr>
            </thead>
        <tbody> 
	
	<?php 
	foreach ($data as $row) {
	    echo "<tr><td>";
	    echo "<div class='text-center' >";
	    echo "<img style='width:70px;height:70px;border-radius: 50%;' src='".$row['Profile_Pic']."'>";
	    echo "<br>";
	    echo "</div>";
	    echo "</td>";
	    
	    echo "<td>".$row['Name']."</td>";
	    echo "<td>".$row['Gender']."</td>";
	    echo "<td>".$row['Date_Of_Birth']."</td>";
	    echo "<td>".$row['Phone_Num']."</td>";
	    echo "<td>".$row['Email']."</td>";
	    echo "<td>".$row['Address']."</td>";
	    echo "<td>".$row['Onboard_Date']."</td>";
	    echo "<td>".$row['Offboard_Date']."</td>";
	    echo "<td>".$row['Department_Name']."</td>";
	    echo "<td>".$row['Designation']."</td>";
	    echo "<td>".$row['Role_Name']."</td>";
	    echo "<td>".$row['Bank_Name']."</td>";
	    echo "<td>";
	    echo "<a href='".$row['Resume']."'class='btn' target='_blank'><i class='fa-solid fa-download'></i></a>";

	    echo "</td>";
	    
	    echo "<td>";
	    echo "<a href='".$row['Contract']."'class='btn' target='_blank'><i class='fa-solid fa-download'></i></a>";

	    echo "</td>";
	    
	    echo "<td colspan='2'><a class='btn' href='UpdateEmployee.php?id=". $row['Employee_ID'] . "'><i class='fa-solid fa-pen'></i> Edit</a><br>";
	    echo "<a class='btn' href='DeleteEmployee.php?id=". $row['Employee_ID'] . "'><i class='fa-solid fa-trash'></i> Delete</a><br>";
	    echo "<a class='btn' href='UpdatePassword.php?id=". $row['Employee_ID'] . "&name=" . $row['Name'] ."'><i class='fa-solid fa-key'></i> Change Password</a><br>";

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

