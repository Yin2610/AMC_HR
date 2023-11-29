<?php
include "DBConnection.php";
session_start();

IF(!isset($_SESSION['Employee_ID']) || $_SESSION['Employee_ID'] == '') {
    echo "<script>alert('Please login first.')</script>";
    header("Location: index.php");
}
ELSE {
    $designation = $_SESSION['Designation'];
}

$pdo = DBConnection::connectToDB();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$sql = "SELECT e.Name, d.Department_Name, ds.Designation, l.Leave_Category, l.From_Date, l.Until_Date, l.Status 
FROM employee as e
JOIN department as d ON d.Department_ID = ds.Department_ID            JOIN designation as ds ON ds.Designation_ID = e.Designation_ID
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
<link rel="stylesheet"
	href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
</head>
<body class='bg-light'>
<?php include('SideNav.php')?>
	<div class="container-fluid mt-4">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-5">
				<li class="breadcrumb-item"><a href="Home.php">Home</a></li>
				<li class="breadcrumb-item"><a href="#">View Employees</a></li>
				<li class="breadcrumb-item active" aria-current="page">Register
					Employee</li>
			</ol>
		</nav>
	</div>
</body>

<body>
	<div class="container">
		<h2>Leave</h2>
		<table class="table">
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
    echo "<td>" . $row['Department'] . "</td>";
    echo "<td>" . $row['Designation'] . "</td>";
    echo "<td>" . $row['Leave_Category'] . "</td>";
    echo "<td>" . $row['From_Date'] . "</td>";
    echo "<td>" . $row['Until_Date'] . "</td>";
    echo "<td>" . $row['Status'] . "</td>";
    echo "<td><a class='btn btn-info' href='updatepayroll.php?id=" . $row['Name'] . "'>Edit</a></td>";
}

?>


                      
    </tbody>
		</table>
	</div>
</body>
</html>
