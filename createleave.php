<?php
session_start();
include('DBConnection.php');
$pdo = DBConnection::connectToDB();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
if(!empty($_POST)) {
    $valid = true;
    
    $LeaveCatagory = $_POST['ddLeaveCatagory'];
    $Submissiondate = $_POST['dtsubmissionDate'];
    $Fromdate = $_POST['dtFromDate'];
    $Untildate = $_POST['dtUntilDate'];
    $Note = $_POST['txtNote'];
    $SupportingDoc = $_FILES['fSupportingDoc']['name'];
    $Status = $_POST['rdoStatus'];
    $ApprovalDate = $_POST['dtApprovalDate'];
    $ApprovedBy = $_POST['sApprovedby'];
    $Submittedby = $_POST['sSubmittedby'];
    
    
   
   
    
    
    if($valid) {
        try {
            $pdo->beginTransaction();
            $insertleaveSQL = "INSERT INTO `leave` (Leave_Category, Submission_Date, From_Date, Until_Date, Notes, Supporting_Doc, Status, Approval_Date, Approved_By, Submitted_By) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $insertLeaveStmt = $pdo->prepare($insertleaveSQL);
            $insertLeaveStmt->execute(array($LeaveCatagory, $Submissiondate, $Fromdate, $Untildate, $Note, $SupportingDoc, $Status, $ApprovalDate, $ApprovedBy, $Submittedby));
            
            $lastInsertedleaveID = $pdo->lastInsertId();
            $pdo->commit();
            
            echo "<script>alert(".$lastInsertedleaveID.")</script>";
            header("Location: leave.php");
            
            
           // echo "Leave submission successful";
        }
        catch (PDOException $e) {
            $pdo->rollBack();
            echo "Error:" .$e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
<!--     <link   href="css/bootstrap.min.css" rel="stylesheet"> -->
<!--     <script src="js/bootstrap.min.js"></script> -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-light">

<?php include('SideNav.php');
?>
 

	<!-- <div class="container-fluid mt-4"> -->
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-5">
				<li class="breadcrumb-item"><a href="Home.php">Home</a></li>
				<li class="breadcrumb-item"><a href="leave.php">View leave</a></li>
				<li class="breadcrumb-item active" aria-current="page">Register leave</li>
			</ol>
		</nav>
	<div class="w-full max-w-sm">
        <form action="createleave.php" method="post" enctype="multipart/form-data">
            <label for="ddLeaveCatagory">Select Leave Catagory:</label>
  				<select id="ddLeaveCatagory" name="ddLeaveCatagory">
    			<option value="MedicalAppointmenr">Medical appointment</option>
    			<option value="FamilyMatter">Family Matter</option>
    			<option value="Vacation">Vacation</option>
    			<option value="Others">Others</option>
  			</select>
                
                <br>
                
                <label for="dtsubmissionDate">Enter Submission Date: </label>
                <input name="dtsubmissionDate" type="date" placeholder="Date" required>
                
            <br>
            
            <br>
                
                <label for="dtFromDate">From Date: </label>
                <input name="dtFromDate" type="date" placeholder="Date" required>
                
            <br>
            
            <br>
                
                <label for="dtUntilDate">Until Date: </label>
                <input name="dtUntilDate" type="date" placeholder="Date" required>
                
            <br>
                <label for="txtNote">Enter employee's phone number: </label>
                <input name="txtNote" type="text" placeholder="Notes" required>
                <br>
                
                <label for="fSupportingDoc">Submit supporting Doc: </label>
                <input name="fSupportingDoc" type="file" required>
                
                <div>
                <br>
                <label for="rdoStatus">Select status: </label>
                <input name="rdoStatus" type="radio" id="rdoPending" value="Pending" checked required>
                <label for="rdoPending">Pending</label>
                <input name="rdoStatus" type="radio" id="rdoApproved" value="Approved" disabled>
                <label for="rdoApproved">Approved</label>
                
            <br>
                
                <label for="dtApprovalDate">Approval Date: </label>
                <input name="dtApprovalDate" type="date" placeholder="Date" required>
                
            <br>
            
            
            
            
            
            <label for="sApprovedby">Approved By: </label>
			<select name="sApprovedby" required>
    <?php 
    $selectEmployeeSQL = "SELECT * FROM employee";
    $query = $pdo->prepare($selectEmployeeSQL, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
    $query->execute();
    $data = $query->fetchAll();

    foreach ($data as $row) {
        // Check if the current user is the logged-in user
        $loggedInUserID = isset($_SESSION['Employee_ID']) ? $_SESSION['Employee_ID'] : null;
        
        // Skip the currently logged-in user in the dropdown
        if ($row['Employee_ID'] != $loggedInUserID) {
            echo "<option value=".$row['Employee_ID'].">".$row['Name']."</option>";
        }
    }
    ?>
</select>
<br>
            
            
           <label for="sSubmittedby">Submitted By: </label>
           <select name="sSubmittedby" required>
          
    		<?php 
    		
                 if (isset($_SESSION['Employee_ID'], $_SESSION['Name'])) {
                // User is logged in
                $name = $_SESSION['Name'];  // Assuming the session variable is set during login
        
                echo '<span >' . $name . '</span>';

        // Adding a variable inside the <select> element
                
                echo "<option value=".$_SESSION['Employee_ID'].">".$_SESSION['Name']."</option>";
            } else {
        // User is not logged in
            
                 echo '<a href="index.php">Login</a>';

    }
    		
    		?>
    		
    		    
    		    
    	
    
</select>
            <br>
            
           
            
	            <button name="btnApply" type="submit">Apply</button>
	            <br>
	            <a class="btn" href="leave.php">Back</a>
            </div>
        </form>
        </div>
</body>
</html>