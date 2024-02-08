<?php

include_once 'DBConnection.php';

session_start();

// if there is no session, redirect to login page.
if(!isset($_SESSION['Employee_ID']) || $_SESSION['Employee_ID'] == '') {
    echo "<script>alert('Please login first.')</script>";
//     header("Location: index.php");
}
else {
    // if the user is not department head, they shouldn't be able to view payroll.
    if($_SESSION['Role_Name'] != 'Department Head') {
        echo "You don't have permission to view this page.";
        exit();
    }
    
    $designation = $_SESSION['Designation'];
}

$pdo = DBConnection::connectToDB();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// select relevant employee data and payroll data for specific department handled by the logged-in department head
$selectPayrollSQL = "SELECT e.Name, ds.Designation, ds.Salary, dp.Department_Name, p.Payroll_ID, p.Date, p.Payslip
            FROM employee as e
            INNER JOIN payroll as p ON p.Employee_ID = e.Employee_ID
            INNER JOIN designation as ds ON ds.Designation_ID = p.Designation_ID
            INNER JOIN department as dp ON dp.Department_ID = ds.Department_ID";

if($designation == "Purchasing director") {
    $selectPayrollSQL .= " WHERE dp.Department_Name = 'Purchasing Department'";
}
elseif($designation == "Sales director") {
    $selectPayrollSQL .= " WHERE dp.Department_Name = 'Sales Department'";
}
elseif($designation == "HR director") {
    $selectPayrollSQL .= " WHERE dp.Department_Name = 'HR Department'";
}
    
$selectPayrollStmt = $pdo->prepare($selectPayrollSQL, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$selectPayrollStmt->execute();
$data = $selectPayrollStmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="View Payroll page of AMC HR system">
    <title>RetrievePayroll page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" rel="stylesheet">
<<<<<<< HEAD
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">	
</head>
<body>
	<?php include('SideNav.php')?>	
=======
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
</head>
<style>
     caption {
         display: none;
     }
</style>
<body>
	<?php include_once 'SideNav.php'?>
>>>>>>> 6b7ba8253d8a1802421af134a172f71a0f4fb25b
	<div class="container-fluid mt-4">
		<nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-3">
            <li class="breadcrumb-item"><a href="Home.php">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">View Payrolls</li>
          </ol>
        </nav>
        <a href="CreatePayroll.php" style="text-decoration: none;
        float: right" class="text-dark btn btn-outline-info mb-5">
        Create Payroll
        </a>
		<table id="payrollTable" class="display hover" style="width: 100%">
		<caption>Table for displaying payroll of employees</caption>
			<thead>
				<tr>
        			<th>Date</th>
        			<th>Employee Name</th>
        			<th>Department</th>
        			<th>Designation</th>
        			<th>Salary</th>
        			<th>Payslip</th>
        			<th>Actions</th>
    			</tr>
			</thead>
			<tbody>
    			<?php
        			foreach ($data as $row) {
        			    echo "<tr>";
        			    echo "<td>".$row['Date']."</td>";
        			    echo "<td>".$row['Name']."</td>";
        			    echo "<td>".$row['Department_Name']."</td>";
        			    echo "<td>".$row['Designation']."</td>";
        			    echo "<td>".$row['Salary']."</td>";
        			    if ($row['Payslip'] == null) {
        			        echo "<td>No payslip</td>";
        			    }
        			    else {
        			        echo "<td>
                                    <button>
                                    <a href='".$row['Payslip']."' download
                                        style='text-decoration: none' class='text-dark'>
                                        View payslip
                                    </a>
                                    </button>
                                    </td>";
        			    }
        			    echo "<td>
                            <a class='btn btn-info' href='UpdatePayroll.php?id=". $row['Payroll_ID'] . "'>Edit</a>
                            <a class='btn btn-danger' href='DeletePayroll.php?id=". $row['Payroll_ID'] . "'>Delete</a>
                            </td>";
        			    echo "</tr>";
        			}
    			?>
    		</tbody>
		</table>
	</div>
	<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
	<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function () {
            $('#payrollTable').DataTable({
            	'scrollX': true
            });
        } );
	</script>
</body>
</html>