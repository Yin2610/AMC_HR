<?php
//establish connection to database
require 'DBConnection.php';
$pdo = DBConnection::connectToDB();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

/*
 * If the user had not login, direct them to index.php and ask them to login first.
 *
 * If the user had login but role is not "Department Head",
 * tell them that they does not have permission to view this page and exit the script.
 */

session_start();

if(!isset($_SESSION['Employee_ID']) || $_SESSION['Employee_ID'] == '') {
    echo "<script>alert('Please login first.')</script>";
    header("Location: index.php");
}
else {
    if($_SESSION['Role_Name'] != 'Department Head') {
        echo "You don't have permission to view this page.";
        exit();
    }
}

//Check if user had submitted any data through the HTTP POST method
if (! empty($_POST)) {
    
    /*
     * Initialise "Error Message".
     * If its empty, it means that there is no error,
     * else it means that some error occurred and it will prompt user on what is the error.
     */
    $filetypeError = null;
    
    //Retrieve data submitted in the form
    $employee = $_POST['pemployee'];
    $date = $_POST['pdate'];
    $payslip = $_FILES['payslip'];
    $namepayslipnew = $payslip['name'];
    
    //Retrieve Designation_ID of employee who Employee_ID is $employee from the database.
    try{
        $sqlRetrieve = 'SELECT Designation_ID
                FROM employee
                WHERE employee.Employee_ID = ?';
        $q = $pdo->prepare($sqlRetrieve);
        $q->execute(array(
            $employee
        ));
        $data = $q->fetch(PDO::FETCH_ASSOC);
        $designation = $data['Designation_ID'];
    }catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    
    /*Check if the file type of the uploaded file is pdf.
    * if incorrect filetype, prompt the user to upload file with correct file type.
    * else, move the uploaded file to "Employee_Info/Payslips/" and ensure the file path is 50 characters or less 
    * and
    * insert the data to the database
    */
    $tnamepayslipnew = $payslip['tmp_name'];
    $file_type = mime_content_type($tnamepayslipnew);

    if ($file_type != 'application/pdf') {
        $filetypeError = "Invalid file type. Please upload a PDF file!";
    } else {

        $uploadpayslipDir = 'Employee_Info/Payslips/';

        $pathpayslipnew = $uploadpayslipDir . basename($namepayslipnew);
        $pathpayslipnew = substr($pathpayslipnew, 0, 50);

        move_uploaded_file($tnamepayslipnew, $pathpayslipnew);

        try{
            $sqlInsert = "INSERT INTO payroll(Date,Payslip,Employee_ID, Designation_ID) VALUES (?,?,?,?)";
            $q = $pdo->prepare($sqlInsert);
            $q->execute(array(
                $date,
                $pathpayslipnew,
                $employee,
                $designation
            ));
        }catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        

        // Direct user back to RetrieveEmployee.php after they have successfully submitted the form
        header("Location: RetrievePayroll.php");
    }
}
DBConnection::disconnect();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    	<link href="css/form.css" rel="css stylesheet">
    	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class='bg-light'>
	<?php include 'SideNav.php'?>
	<div class="container-fluid mt-4">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-5">
				<li class="breadcrumb-item"><a href="Home.php">Home</a></li>
				<li class="breadcrumb-item"><a href="RetrievePayroll.php">View Payrolls</a></li>
				<li class="breadcrumb-item active" aria-current="page">Insert New Payroll</li>
			</ol>
		</nav>
	<div id="form">

		<div class="text-center">
				<h3>New Payroll</h3>
			</div>

			<form action="CreatePayroll.php" method="post"
				enctype="multipart/form-data">

				<!-- Employee Name -->
					<label class="form-label" for="pemployee">Employee</label>
					<select class="form-select" name="pemployee" id="pemployee" required>
                            <?php
                            $pdo = DBConnection::connectToDB();
                            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            $selectEmployeeSQL = "SELECT * FROM Employee";
                            $query = $pdo->prepare($selectEmployeeSQL, array(
                                PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL
                            ));
                            $query->execute();
                            $data = $query->fetchAll();
                            foreach ($data as $row) {
                                echo "<option value=" . $row['Employee_ID'] . ">" . $row['Name'] . "</option>";
                            }
                            ?>
            		</select>



				<!-- Pay Day -->
					<label class="form-label" for="pdate">Date</label>
					<input class="form-control" name="pdate" id="pdate" type="date" placeholder="Date" value="<?php echo !empty($date)?$date:'';?>" required>
				
				<!-- Payslip -->
					<label class="form-label" for="payslip">Upload Payslip</label>
					

						<input class="form-control" name="payslip" id="payslip" type="file" accept=".pdf" required>
						<small class="form-text text-muted">Please upload a PDF file!</small>
						<br>
                       	<?php if (!empty($filetypeError)): ?>
                                <span class="help-inline"><?php echo $filetypeError;?></span>
                       	<?php endif; ?>
                       	
				

				<!-- Submit button -->
				<div class="form-actions">
					<button type="submit" class="btn btn-success">Create</button>
					<a class="btn" href="RetrievePayroll.php">Back</a>
				</div>



			</form>
		</div>
</div>

	<!-- /container -->
</body>
</html>






