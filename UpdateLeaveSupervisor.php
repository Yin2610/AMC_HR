<?php

session_start();

// if there is no session, redirect to login page.
if(!isset($_SESSION['Employee_ID']) || $_SESSION['Employee_ID'] == '') {
    echo "<script>alert('Please login first.')</script>";
    header("Location: index.php");
}

// if the user is not department head, they shouldn't be able to update leave status.
if($_SESSION['Role_Name'] != 'Department Head') {
    echo "You don't have permission to view this page.";
    exit();
}
else {
    $employee_ID = $_SESSION['Employee_ID'];
}

include 'DBConnection.php';
$pdo = DBConnection::connectToDB();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (isset($_GET['id'])) {
    $leave_id = intval($_GET['id']);
}

// setting status variable to update
if (isset($_POST['btnApprove'])) {
    $status = "Approved";
}

if (isset($_POST['btnReject'])) {
    $status = "Rejected";
}

// update leave status along with approval date, approved by
if (isset($_POST['btnApprove']) || isset($_POST['btnReject'])) {
    $leave_id = $_POST['txtLeaveID'];
    $approval_date = date("Y-m-d");
    $updateLeaveSQL = "UPDATE `leave` SET Status=:status, Approval_Date=:approval_date, Approved_By=:approved_by WHERE Leave_ID=:leave_id";
    $updateLeaveStmt = $pdo->prepare($updateLeaveSQL);
    $updateLeaveStmt->bindParam(':status', $status);
    $updateLeaveStmt->bindParam(':leave_id', $leave_id);
    $updateLeaveStmt->bindParam(':approval_date', $approval_date);
    $updateLeaveStmt->bindParam(':approved_by', $employee_ID);
    if ($updateLeaveStmt->execute()) {
        echo "<script>alert('The leave status has been updated.')</script>";
        echo "<script>window.location.assign('RetrieveLeaveSupervisor.php')</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="Update Leave Status page (Supervisor view) of AMC HR system">
<title>UpdateLeaveSupervisor page</title>
<link
	href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
	rel="stylesheet">
<style>
#downloadLink:hover {
	color: white !important;
}
.profilePicContainer {
    height: 60px;
    width: 60px;
}
.profilePic {
    width: 100%;
    object-fit: contain;
    aspect-ratio: 1;
}
</style>
</head>

<body>
	<?php include 'SideNav.php'?>
	<div class="container-fluid mt-4">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-5">
				<li class="breadcrumb-item"><a href="Home.php">Home</a></li>
				<li class="breadcrumb-item"><a href="RetrieveLeaveSupervisor.php">View Leave Requests</a></li>
				<li class="breadcrumb-item active" aria-current="page">Update Leave Status</li>
			</ol>
		</nav>
		
        	<?php
        	
        	//select relevant information about employee and his/her leave request to display on this page
        if (isset($_GET['id'])) {
            $selectLeaveSQL = "SELECT e.Name, e.Profile_Pic, 
                                dp.Department_Name, ds.Designation, 
                                l.* FROM Employee as e, Department as dp, Designation as ds, `Leave` as l
                                WHERE l.Leave_ID = $leave_id 
                                AND l.Submitted_By = e.Employee_ID 
                                AND e.Designation_ID = ds.Designation_ID 
                                AND ds.Department_ID = dp.Department_ID";
            $selectLeaveStmt = $pdo->prepare($selectLeaveSQL);
            $selectLeaveStmt->execute();
            $data = $selectLeaveStmt->fetchAll();
            foreach ($data as $row) {
                $name = $row['Name'];
                $designation = $row['Designation'];
                $departmentName = $row['Department_Name'];
                echo "<form action='UpdateLeaveSupervisor.php' method='POST'><div class='row'>
            <div class='col-md-1'></div>
			<div class='col-md-10'>
				<div class='row'>";
                echo "<div class='col-md-1'><div class='profilePicContainer'><img src='" . $row['Profile_Pic'] . "' alt='Profile picture' class='rounded-circle profilePic'></div></div>";

                echo "<div class='col-md-6'><span class='h5'>" . $name . "</span><br>";
                echo "<span>" . $designation . " (" . $departmentName . ")</span></div>";

                echo "<div class='col-md-5'><div class='d-flex justify-content-end'><input type='submit' name='btnApprove' value='Approve' class='btn btn-info mx-4'><input type='submit' name='btnReject' class='btn btn-secondary' value='Reject'></div></div>";
                echo "</div>
			</div>
		</div>";

                echo "<div class='mt-3 row'>";
                echo "<div class='col-md-1'></div>";
                echo "<div class='col-md-5'>";
                echo "<input type='text' name='txtLeaveID' hidden value='$leave_id'>";
                echo "<div>Submitted leave on " . $row['Submission_Date'] . ".</div>";
                echo "<div class='mt-3'>Leave category: " . $row['Leave_Category'] . "</div>";
                echo "<div class='mt-3'>Leave status: " . $row['Status'] . "</div>";
                echo "<div class='mt-3'><span>From: " . $row['From_Date'] . "</span>";
                echo "<span class='mx-5'>To: " . $row['Until_Date'] . "</span></div>";
                echo "<button class='btn btn-outline-secondary mt-3'><a id='downloadLink' href='" . $row['Supporting_Doc'] . "' download style='text-decoration: none' class='text-dark'>Download supporting document</a></button></div>";

                echo "<div class='col-md-6'><label for='Note'>Note:</label><br><br><textarea id='Note' class='col-md-10' style='resize:none' disabled rows='5'>" . $row['Notes'] . "</textarea></div></form>";
            }
        }
        ?>	
        	
	</div>
<!-- 	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script> -->
</body>
</html>