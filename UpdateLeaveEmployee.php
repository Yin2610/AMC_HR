<?php

session_start();

/*
 * Establish database connection
 */
require 'DBConnection.php';

/*
 * Retrieve 'id' from URL
 * If id is not present in the URL, directs user to index.php.
 * When user click on update button in employee.php, it will pass the employee_id to the URL and direct the user to UpdateEmployee.php
 */
$id = null;

if (! empty($_GET['leave_id'])) {
    $id = $_REQUEST['leave_id'];
}

// if (null == $id) {
//     header("Location: Home.php");
// }

/*
 * If the user clicked "Submit" button
 */

// Retrieve data from database
$id = intval($id);
$pdo = DBConnection::connectToDB();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = "SELECT * FROM `leave` WHERE Leave_ID = :leave_id";
$q = $pdo->prepare($sql);
$q->bindParam(':leave_id', $id, PDO::PARAM_INT);
$q->execute();
$data = $q->fetch(PDO::FETCH_ASSOC);

// $Leave_Category= null;
// $From_Date = null;
// $Until_Date = null;
// $Notes = null;
// $Supporting_Doc = null;

$Leave_Category= $data['Leave_Category'];
$From_Date = $data['From_Date'];
$Until_Date = $data['Until_Date'];
$Notes = $data['Notes'];
$Supporting_Doc = $data['Supporting_Doc'];

DBConnection::disconnect();

//$pdo = DBConnection::connectToDB();
//$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//$designationQuery = 'SELECT Designation_ID, Designation FROM designation';
//$designationResult = $pdo->query($designationQuery);
//$designations = $designationResult->fetchAll(PDO::FETCH_ASSOC);
//DBConnection::disconnect();

if (! empty($_POST)) { // check if there's any data submitted in the html form

//     $Leave_CategoryError= null;
//     $Submission_DateError = null;
//     $From_DateError = null;
//     $Until_DateError = null;
//     $NotesError = null;
//     $Supporting_DocError = null;
//     $Submitted_ByError = null;
    $Leave_Category= $_POST['ddLeaveCatagory'];
    $From_Date = $_POST['fromDate'];
    $Until_Date = $_POST['untilDate'];
    $Notes = $_POST['notes'];
    
    if(!empty($_FILES['supportingDoc']['name'])) {
        $DocName = $_FILES['supportingDoc']['name'];
        $TempDocName = $_FILES['supportingDoc']['tmp_name'];
        $LeaveSupportingDocPath = "Employee_Info/Leave_Supporting_Docs/" . $DocName;
        if(!move_uploaded_file($TempDocName, $LeaveSupportingDocPath)) {
            echo "<script>alert('Failed uploading resume.')</script>";
        }
    }
    
    
    
   
    

    // validate input (if input is empty) (only need if the field is editable/ cannot be empty)
    $valid = true;
//     if (empty($Leave_Category)) {
//         $Leave_CategoryError = 'Please enter date payslip ';
//         $valid = false;
//     }
//     if (empty($Submission_Date)) {
//         $Submission_DateError = 'Please enter payslip number ';
//         $valid = false;
//     }
//     if (empty($From_Date)) {
//         $From_DateError = 'Please enter the designation number ';
//         $valid = false;
//     }
//     if (empty($Until_Date)) {
//         $Until_DateError = 'Please enter the designation number ';
//         $valid = false;
//     }
//     if (empty($Notes)) {
//         $NotesError  = 'Please enter the designation number ';
//         $valid = false;
//     }
//     if (empty($Supporting_Doc)) {
//         $Supporting_DocError = 'Please enter the designation number ';
//         $valid = false;
//     }

    
    
    

    // if the input data is correct, update the database
    if ($valid) {
        $sql = "UPDATE `leave` SET Leave_Category = ?, From_Date = ?, Until_Date = ?, Notes =?, Supporting_Doc =?
                WHERE Leave_ID = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array(
            $Leave_Category,
            $From_Date,
            $Until_Date,
            $Notes,
            $LeaveSupportingDocPath,
            $id
            
        ));
        
        
        

        DBConnection::disconnect();

        // Direct user back to employee.php after they have successfully submitted the form
        header("Location: RetrieveLeaveEmployee.php");
    }
} // If the user never click "Submit" button / When the form is first loaded
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<link href="css/bootstrap.min.css" rel="stylesheet">
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery-2.1.4.min.js"></script>
</head>

<body>

<?php

include ('SideNav.php');
?>
	<div class="container-fluid mt-4">
	<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-4">
				<li class="breadcrumb-item"><a href="Home.php">Home</a></li>
				<li class="breadcrumb-item"><a href="RetrieveLeaveEmployee.php">View Own Leave Requests</a></li>
				<li class="breadcrumb-item active" aria-current="page">Update leave</li>
			</ol>
		</nav>

		<div class="span10 offset1">
			<div class="row">
				<h3>Update Information</h3>
			</div>

			<form class="form-horizontal"
				action="UpdateLeaveEmployee.php?leave_id=<?php echo $id?>" method="post"
				enctype="multipart/form-data">
				
				<label for="ddLeaveCatagory">Select Leave Catagory:</label>
  				<select id="ddLeaveCatagory" name="ddLeaveCatagory">
    			<option value="Medical Appointment">Medical appointment</option>
    			<option value="Family Matter">Family Matter</option>
    			<option value="Vacation">Vacation</option>
    			<option value="Others">Others</option>
  			</select>


                <!-- Date -->
				<div class="control-group">
					<label class="control-label">From Date</label>
					<div class="controls">
						<input name="fromDate" type="date" placeholder="date"
							value="<?php echo !empty($From_Date)?$From_Date:'';?>">
                        
                    </div>
				</div>
				
				<div class="control-group">
					<label class="control-label">Until Date</label>
					<div class="controls">
						<input name="untilDate" type="date" placeholder="date"
							value="<?php echo !empty($Until_Date)?$Until_Date:'';?>">
                        
                    </div>
				</div>
				
				<div class="control-group">
					<label class="control-label">Notes</label>
					<div class="controls">
						<input name="notes" type="text"
							value="<?php echo !empty($Notes)?$Notes:'';?>">
                        
                    </div>
				</div>
				
				<div class="control-group">
					<label class="control-label">Supporting Doc:</label>
					<div class="controls">
						<input name="supportingDoc" type="file" placeholder="date"
							value="<?php echo !empty($date)?$date:'';?>">
                        
                    </div>
				</div>
				
				
				<!-- Submit button -->
				<div class="form-actions">
					<button type="submit" class="btn btn-success">Update</button>
					<a class="btn" href="RetrieveLeaveEmployee.php">Back</a>
				</div>



			</form>
		</div>

	</div>
	<!-- /container -->
</body>
</html>


