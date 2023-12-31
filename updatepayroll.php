<?php
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

if (! empty($_GET['id'])) {
    $id = $_REQUEST['id'];
}

if (null == $id) {
    header("Location: payroll.php");
}

/*
 * If the user clicked "Submit" button
 */

// Retrieve data from database
$pdo = DBConnection::connectToDB();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = 'SELECT payroll.Payroll_ID,payroll.Date,payroll.Payslip,employee.Name,designation.Designation FROM `payroll` 
        INNER JOIN employee on payroll.Employee_ID = employee.Employee_ID 
        INNER Join designation on payroll.Designation_ID = designation.Designation_ID
        WHERE payroll.Payroll_ID = ?  ';
$q = $pdo->prepare($sql);
$q->execute(array(
    $id
));
$data = $q->fetch(PDO::FETCH_ASSOC);
$date = $data['Date'];
$payslip = $data['Payslip'];
$designation = $data['Designation'];


DBConnection::disconnect();

//$pdo = DBConnection::connectToDB();
//$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//$designationQuery = 'SELECT Designation_ID, Designation FROM designation';
//$designationResult = $pdo->query($designationQuery);
//$designations = $designationResult->fetchAll(PDO::FETCH_ASSOC);
//DBConnection::disconnect();

if (! empty($_POST)) { // check if there's any data submitted in the html form

   
    $dateError = null;
    $payslipError = null;
    $designationError = null;
    
    $date = $_POST['date'];
    $payslip = $_POST['payslip'];
   
    $designation = $_POST['designation'];
    

    // validate input (if input is empty) (only need if the field is editable/ cannot be empty)
    $valid = true;
    if (empty($date)) {
        $dateError = 'Please enter date payslip ';
        $valid = false;
    }
    if (empty($payslip)) {
        $payslipError = 'Please enter payslip number ';
        $valid = false;
    }
    if (empty($designation)) {
        $designationError = 'Please enter the designation number ';
        $valid = false;
    }

    
    
    

    // if the input data is correct, update the database
    if ($valid) {
        $pdo = DBConnection::connectToDB();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "UPDATE payroll
                INNER JOIN employee ON payroll.Employee_ID = employee.Employee_ID
                INNER JOIN designation ON payroll.Designation_ID = designation.Designation_ID
                SET 
                payroll.Payslip = ?,
                payroll.Date = ?,
                payroll.Designation_ID =?
                WHERE payroll.Payroll_ID = ?;";
        $q = $pdo->prepare($sql);
        $q->execute(array(
            
            $payslip,
            $date,
            $designation,
            $id
            
        ));
        
        
        

        DBConnection::disconnect();

        // Direct user back to employee.php after they have successfully submitted the form
        header("Location: payroll.php");
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
	<div class="container">

		<div class="span10 offset1">
			<div class="row">
				<h3>Update Information</h3>
			</div>

			<form class="form-horizontal"
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
					<label class="control-label">Payslip</label>
					<div class="controls">
						<input name="payslip" type="text" placeholder="Payslip" 
							value="<?php echo !empty($payslip)?$payslip:'';?>">
                            <?php if (!empty($payslipError)): ?>
                                <span class="help-inline"><?php echo $payslipError;?></span>
                            <?php endif; ?>
					</div>
				</div>
        <div class="control-group">
					<label for="sDesignation">Bank Company</label>
					<div class="controls">

						<select name="designation" required>
						<?php
                        $pdo = DBConnection::connectToDB();
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        $selectDesignationSQL = "SELECT * FROM designation";
                        $query = $pdo->prepare($selectDesignationSQL, array(
                            PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL
                        ));
                        $query->execute();
                        $data = $query->fetchAll();
                        foreach ($data as $row) {
                            echo "<option value=" . $row['Designation_ID'] . ">" . $row['Designation'] . "</option>";
                        }
                        ?>
            			</select>
            			<?php if (!empty($designationError)): ?>
            			<span class="help-inline"><?php echo $designationError; ?></span>
        				<?php endif; ?>
					</div>
				</div>
				
				
				<!-- Submit button -->
				<div class="form-actions">
					<button type="submit" class="btn btn-success">Update</button>
					<a class="btn" href="payroll.php">Back</a>
				</div>



			</form>
		</div>

	</div>
	<!-- /container -->
</body>
</html>


