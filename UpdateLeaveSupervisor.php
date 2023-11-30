<?php

session_start();
if(!isset($_SESSION['Employee_ID']) || $_SESSION['Employee_ID'] == '') {
    echo "<script>alert('Please login first.')</script>";
    header("Location: index.php");
}

if($_SESSION['Role_Name'] != 'Department Head') {
    echo "You don't have permission to view this page.";
    exit();
}

include ('DBConnection.php');
global $pdo;
$pdo = DBConnection::connectToDB();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (isset($_GET['id'])) {
    global $leave_id;
    $leave_id = intval($_GET['id']);
}

if (isset($_POST['btnApprove'])) {
    global $status;
    $status = "Approved";
}

if (isset($_POST['btnReject'])) {
    global $status;
    $status = "Rejected";
}

if (isset($_POST['btnApprove']) || isset($_POST['btnReject'])) {
    // update approved by in leave after getting session
    global $pdo;
    global $status;
    $leave_id = $_POST['txtLeaveID'];
    $approval_date = date("Y-m-d");
    $updateLeaveSQL = "UPDATE `leave` SET Status=:status, Approval_Date=:approval_date WHERE Leave_ID=:leave_id";
    $updateLeaveStmt = $pdo->prepare($updateLeaveSQL);
    $updateLeaveStmt->bindParam(':status', $status);
    $updateLeaveStmt->bindParam(':leave_id', $leave_id);
    $updateLeaveStmt->bindParam(':approval_date', $approval_date);
    if ($updateLeaveStmt->execute()) {
        echo "<script>alert('The leave status has been updated.')</script>";
        echo "<script>window.location.assign('UpdateLeaveSupervisor.php?id=$leave_id')</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<link
	href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
	rel="stylesheet">
<script src="js/bootstrap.min.js"></script>
<style>
#downloadLink:hover {
	color: white !important;
}
</style>
</head>

<body class="bg-light">
	<?php include('SideNav.php')?>
	<div class="container-fluid mt-4">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-5">
				<li class="breadcrumb-item"><a href="Home.php">Home</a></li>
				<li class="breadcrumb-item"><a href="RetrieveLeaveSupervisor.php">View Leave Requests</a></li>
				<li class="breadcrumb-item active" aria-current="page">Update Leave Status</li>
			</ol>
		</nav>
		
        	<?php
        if (isset($_GET['id'])) {
            $selectLeaveSQL = "SELECT e.Name, e.Profile_Pic, dp.Department_Name, ds.Designation, l.* FROM Employee as e, Department as dp, Designation as ds, `Leave` as l WHERE l.Leave_ID = $leave_id AND l.Submitted_By = e.Employee_ID AND e.Designation_ID = ds.Designation_ID AND ds.Department_ID = dp.Department_ID";
            $selectLeaveStmt = $pdo->prepare($selectLeaveSQL);
            $selectLeaveStmt->execute();
            $data = $selectLeaveStmt->fetchAll();
            foreach ($data as $row) {
                echo "<form action='UpdateLeaveSupervisor.php' method='POST'><div class='row'>
            <div class='col-md-1'></div>
			<div class='col-md-10'>
				<div class='row'>";
                echo "<div class='col-md-1'><img src='" . $row['Profile_Pic'] . "' width='60px' height='60px' class='rounded-circle'></div>";

                echo "<div class='col-md-6'><span class='h5'>" . $row['Name'] . "</span><br><span>" . $row['Designation'] . " (" . $row['Department_Name'] . ")</span></div>";

                echo "<div class='col-md-5'><div class='d-flex justify-content-end'><input type='submit' name='btnApprove' value='Approve' class='btn btn-outline-info mx-4'><input type='submit' name='btnReject' class='btn btn-outline-secondary' value='Reject'></div></div>";
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

                echo "<div class='col-md-6'>Note: <br><br><textarea class='col-md-10' style='resize:none' disabled rows='5'>" . $row['Notes'] . "</textarea></div></form>";
            }
        }
        ?>	
        	
	</div>
</body>