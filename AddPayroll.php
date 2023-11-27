

<?php
require 'DBConnection.php';

$filetypeError = null;

if (! empty($_POST)) { // check if there's data uokoaded

    $employee = $_POST['pemployee'];
    $date = $_POST['pdate'];
    $payslip = $_FILES['payslip'];
    $namepayslipnew = $payslip['name'];

    $pdo = DBConnection::connectToDB();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = 'SELECT Designation_ID
            FROM employee
            WHERE employee.Employee_ID = ?';
    $q = $pdo->prepare($sql);
    $q->execute(array(
        $employee
    ));
    $data = $q->fetch(PDO::FETCH_ASSOC);
    $designation = $data['Designation_ID'];
    DBConnection::disconnect();

    $tnamepayslipnew = $payslip['tmp_name'];
    $file_type = mime_content_type($tnamepayslipnew);

    if (! ($file_type == 'application/pdf')) {
        $filetypeError = "Invalid file type. Please upload a PDF file!";
    } else {

        $uploadpayslipDir = 'Employee_Info/Payslips/';

        $pathpayslipnew = $uploadpayslipDir . basename($namepayslipnew);
        $pathpayslipnew = substr($pathpayslipnew, 0, 50);

        move_uploaded_file($tnamepayslipnew, $pathpayslipnew);

        $pdo = DBConnection::connectToDB();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO payroll(Date,Payslip,Employee_ID, Designation_ID) VALUES (?,?,?,?)";
        $q = $pdo->prepare($sql);
        $q->execute(array(
            $date,
            $pathpayslipnew,
            $employee,
            $designation
        ));

        DBConnection::disconnect();

        // Direct user back to employee.php after they have successfully submitted the form
        header("Location: employee.php");
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    	<link href="css/form.css" rel="css stylesheet">
    	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
	<div class="container" id="form">

		<div class="text-center">
				<h3>New Payroll</h3>
			</div>

			<form action="AddPayroll.php" method="post"
				enctype="multipart/form-data">

				<!-- Employee -->
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



				<!-- Date -->
					<label class="form-label" for="pdate">Date</label>
					<input class="form-control" name="pdate" id="pdate" type="date" placeholder="Date" value="<?php echo !empty($date)?$date:'';?>" required>

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
					<a class="btn" href="employee.php">Back</a>
				</div>



			</form>
		</div>


	<!-- /container -->
</body>
</html>






