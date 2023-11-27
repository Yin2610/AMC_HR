<?php

// if HR director or payroll manager
include('DBConnection.php');
$pdo = DBConnection::connectToDB();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$selectPayrollSQL = "SELECT p.Date, p.Payslip, e.Name, ds.Designation, ds.Salary, dp.Department_Name FROM payroll as p, employee as e, designation as ds, department as dp WHERE p.Employee_ID = e.Employee_ID AND p.Designation_ID = ds.Designation_ID AND ds.Department_ID = dp.Department_ID";
$selectPayrollStmt = $pdo->prepare($selectPayrollSQL, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$selectPayrollStmt->execute();
$data = $selectPayrollStmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
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
          <ol class="breadcrumb mb-5">
            <li class="breadcrumb-item"><a href="Home.php">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Payroll</li>
          </ol>
        </nav>
		<table id="payrollTable" class="display hover" style="width: 100%">
			<thead>
				<tr>
        			<th>Date</th>
        			<th>Employee Name</th>
        			<th>Department</th>
        			<th>Designation</th>
        			<th>Salary</th>
        			<th>Payslip</th>
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
        			    echo "<td><button><a href='".$row['Payslip']."' download style='text-decoration: none' class='text-dark'>View payslip</a></button></td>";
        			    echo "</tr>";
        			}
    			?>
    		</tbody>
		</table>
	</div>
	<script type="text/javascript">
		$(document).ready(function () {
            $('#payrollTable').DataTable({
            	'scrollX': true
            });
        } );
	</script>
</body>
</html>