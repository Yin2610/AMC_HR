<?php
session_start();
include ('DBConnection.php');
$pdo = DBConnection::connectToDB();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
if (! empty($_POST)) {
    $valid = true;

    $LeaveCatagory = $_POST['ddLeaveCatagory'];
    $Submissiondate = date('Y-m-d');
    $Fromdate = $_POST['dtFromDate'];
    $Untildate = $_POST['dtUntilDate'];
    $Note = $_POST['txtNote'];
    $SupportingDoc = $_FILES['fSupportingDoc']['name'];
    $Status = "Pending";
    $ApprovalDate = null;
    $ApprovedBy = null;
    $Submittedby = $_SESSION['Employee_ID'];

    if ($valid) {
        try {
            $pdo->beginTransaction();
            $insertleaveSQL = "INSERT INTO `leave` (Leave_Category, Submission_Date, From_Date, Until_Date, Notes, Supporting_Doc, Status, Approval_Date, Approved_By, Submitted_By) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $insertLeaveStmt = $pdo->prepare($insertleaveSQL);
            $insertLeaveStmt->execute(array(
                $LeaveCatagory,
                $Submissiondate,
                $Fromdate,
                $Untildate,
                $Note,
                $SupportingDoc,
                $Status,
                $ApprovalDate,
                $ApprovedBy,
                $Submittedby
            ));

            $pdo->commit();

            echo "<script>alert('Your leave request is successfully posted.')</script>";

            // echo "Leave submission successful";
        } catch (PDOException $e) {
            $pdo->rollBack();
            echo "Error:" . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<link
	href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
	rel="stylesheet">
<script
	src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="bg-light">

<?php

include ('SideNav.php');
?>
 
	<div class="container-fluid mt-4">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-4">
				<li class="breadcrumb-item"><a href="Home.php">Home</a></li>
				<li class="breadcrumb-item active" aria-current="page">Request leave</li>
			</ol>
		</nav>
		<div class="row">
			<form action="Createleave.php" method="post"
				enctype="multipart/form-data" class="col-md-5 mx-auto">
				<label for="ddLeaveCatagory">Select Leave Catagory:</label> 
				<select id="ddLeaveCatagory" name="ddLeaveCatagory" class="form-control">
					<option value="Medical Appointmenr">Medical appointment</option>
					<option value="Family Matter">Family Matter</option>
					<option value="Vacation">Vacation</option>
					<option value="Others">Others</option>
				</select> 
				<br>
				<label for="dtFromDate">From Date: </label> <br>
				<input name="dtFromDate" type="date" placeholder="Date" class="form-control" required> <br> 
				<label for="dtUntilDate">Until Date: </label> <br>
				<input name="dtUntilDate" type="date" placeholder="Date" class="form-control" required> <br> 
				<label for="txtNote">Enter notes for the leave: </label> <br>
				<textarea name="txtNote" rows="4" placeholder="Notes" class="form-control" style="resize:none" required></textarea> <br>
				<label for="fSupportingDoc">Submit supporting document: </label> <br>
				<input name="fSupportingDoc" type="file" class="form-control" required>
				<br>
				<div class="text-center mb-3">
					<button class="btn btn-outline-info" name="btnApply" type="submit">Submit</button>
				</div>
			</form>
		</div>
	</div>
</body>
</html>