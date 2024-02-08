<?php
/*
 * Establish database connection
 */
//include_once 'DBConnection.php';
include "dbConnection.php";
session_start();
/*
 * Retrieve 'id' from URL
 * If id is not present in the URL, directs user to index.php.
 * When user click on update button in employee.php,
 * it will pass the employee_id to the URL and direct
 *  the user to UpdateEmployee.php
 */
$id = null;

if (! empty($_GET['id'])) {
    $id = $_REQUEST['id'];
}

if (null == $id) {
    header("Location: RetrievePayroll.php");
}

/*
 * If the user clicked "Submit" button
 */

//Retrieve data from database so that when the use is edit the value will still be there

$pdo = DBConnection::connectToDB();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// SQL query to retrieve payroll information based on the provided ID
$sql = 'SELECT payroll.Payroll_ID,payroll.Date,payroll.Payslip FROM `payroll` 
        INNER JOIN employee on payroll.Employee_ID = employee.Employee_ID 
        INNER Join designation on payroll.Designation_ID = designation.Designation_ID 
        WHERE payroll.Payroll_ID = ?  ';
$q = $pdo->prepare($sql);
$q->execute(array(
    $id
));
// Fetch the data and store it in variables
$data = $q->fetch(PDO::FETCH_ASSOC);
$date = $data['Date'];
$payslipPath = $data['Payslip'];



if (! empty($_POST)) { // check if there's any data submitted in the html form

   
    $dateError = null;
    
    $date = $_POST['date'];
    

    // validate input (if input is empty) (only need if the field is editable/ cannot be empty)
    $valid = true;
    if (empty($date)) {
        $dateError = 'Please enter date payslip ';
        $valid = false;
    }

    if(isset($_FILES['payslip']['name'])) {
        $payslipName = $_FILES['payslip']['name'];
        $TemppayslipName = $_FILES['payslip']['tmp_name'];
        $payslipPath = "Employee_Info/Payslips/" . $payslipName;
        if(!move_uploaded_file($TemppayslipName, $payslipPath)) {
            echo "<script>alert('Failed uploading profile pic.')</script>";
        }
    }
    
    

    // if the input data is correct, update the database
    if ($valid) {
        // SQL query to update the payroll information in the database
        $sql = "UPDATE payroll
                INNER JOIN employee ON payroll.Employee_ID = employee.Employee_ID
                INNER JOIN designation ON payroll.Designation_ID = designation.Designation_ID
                SET
                payroll.Payslip = ?,
                payroll.Date = ?
                WHERE payroll.Payroll_ID = ?;";
        $q = $pdo->prepare($sql);
        $q->execute(array(
            
            $payslipPath,
            $date,
            $id
            
        ));
        
        
        
//close the db connection
        DBConnection::disconnect();

        // Direct user back to employee.php after they have successfully submitted the form
        header("Location: RetrievePayroll.php");
    }
} // If the user never click "Submit" button / When the form is first loaded
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery-2.1.4.min.js"></script>-->
<link
	href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
	rel="stylesheet">
<script
	src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<meta name="description" content="AMC website">
<title>Update Payrol</title>
</head>

<body class='bg-light'>

	<?php

	include_once 'SideNav.php';?>
   

	<div class="container">
	
    <nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-4">
				<li class="breadcrumb-item"><a href="Home.php">Home</a></li>
				<li class="breadcrumb-item"><a href="RetrievePayroll.php">Payroll</a></li>
				<li class="breadcrumb-item active" aria-current="page">Update Payroll</li>
			</ol>
	</nav>

		<div class="span10 offset1">
			<div class="col-md-5 mx-auto">
				<h3>Update Information</h3>
			</div>

			<form class="col-md-5 mx-auto"
				action="updatepayroll.php?id=<?php echo $id?>" method="post"
				enctype="multipart/form-data">

                <!-- Date -->
				<div class="control-group">
					<label class="control-label">Date</label>
					<div class="controls">
						<input name="date" type="date" placeholder="date"
							value="<?php echo !empty($date)?$date:'';?>">
                        <?php if (!empty($dateError)): ?>
                        	<span class="help-inline"><?php echo $dateError;?></span>
                        <?php endif; ?>
                    </div>
				</div>
				<!-- payslip -->
				<div class="control-group">
					<label class="control-label">Payslip
						<div class="controls">
							<input name="payslip" type="file" required>
						</div>
					</label>
				</div>
				
				
				<!-- Submit button -->
				<div class="text-left mb-2">
				<br>
					<button type="submit" class="btn btn-outline-info">Update</button>
					<a class="btn" href="RetrievePayroll.php">Back</a>
				</div>



			</form>
		</div>

	</div>
	<!-- /container -->
</body>
</html>
