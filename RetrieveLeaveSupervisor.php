<?php
include "DBConnection.php";
session_start();

if(!isset($_SESSION['Employee_ID']) || $_SESSION['Employee_ID'] == '') {
    echo "<script>alert('Please login first.')</script>";
    header("Location: index.php");
}
else {
    $designation = $_SESSION['Designation'];
}

$pdo = DBConnection::connectToDB();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$sql = "SELECT e.Name, d.Department_Name, ds.Designation, l.Leave_ID, l.Leave_Category, l.From_Date, l.Until_Date, l.Status 
FROM employee as e
JOIN designation as ds ON ds.Designation_ID = e.Designation_ID
JOIN department as d ON d.Department_ID = ds.Department_ID 
JOIN `leave` as l ON l.Submitted_By = e.Employee_ID";

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
<body class='bg-light'>
<?php include('SideNav.php')?>
	<div class="container-fluid mt-4">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-5">
				<li class="breadcrumb-item"><a href="Home.php">Home</a></li>
				<li class="breadcrumb-item active">View Leave Requests</li>
			</ol>
		</nav>
		<table id="leaveTable" class="display hover" style="width: 100%">
			<thead>
				<tr>
					<th>Name</th>
					<th>Department</th>
					<th>Designation</th>
					<th>Leave Category</th>
					<th>From Date</th>
					<th>Until Date</th>
					<th>Status</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody> 
	
	<?php
foreach ($data as $row) {
    echo "<tr><td>" . $row['Name'] . "</td>";
    echo "<td>" . $row['Department_Name'] . "</td>";
    echo "<td>" . $row['Designation'] . "</td>";
    echo "<td>" . $row['Leave_Category'] . "</td>";
    echo "<td>" . $row['From_Date'] . "</td>";
    echo "<td>" . $row['Until_Date'] . "</td>";
    echo "<td>" . $row['Status'] . "</td>";
//     echo "<td>".$row['Leave_ID']."</td>";
    echo "<td><a class='btn btn-info' href='UpdateLeaveSupervisor.php?id=".$row['Leave_ID']."'>Edit</a></td></tr>";
}

?>


                      
    </tbody>
		</table>
	</div>
	<script type="text/javascript">
		$(document).ready(function () {
            $('#leaveTable').DataTable({
            	'scrollX': true
            });
        } );
	</script>
</body>
</html>
