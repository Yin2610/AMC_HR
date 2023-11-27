<?php
require 'DBConnection.php';

//Retrieve employee_id
$id = null;

if (! empty($_GET['id'])) {
    $id = $_REQUEST['id'];
}

if (null == $id) {
    header("Location: employee.php");
}


$filetypeError = null;

//Check if there is file being uploaded

//if there is files being uploaded
if (! empty($_FILES)) {

    //retrieve details of uploaded file
    $contractnew = $_FILES['contract'];
    $namecontractnew = $contractnew['name'];
    $tnamecontractnew = $contractnew['tmp_name'];
    $file_type = mime_content_type($tnamecontractnew);
    
    //if file uploaded is a pdf file
    if ($file_type == 'application/pdf') {
        
        $uploadcontractDir = 'Employee_Info/Contracts/';
        $pathcontractnew = $uploadcontractDir . basename($namecontractnew);
        $pathcontractnew = substr($pathcontractnew, 0, 50);
        move_uploaded_file($tnamecontractnew, $pathcontractnew);

        $pdo = DBConnection::connectToDB();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "UPDATE employee
                SET employee.Contract = ?
                WHERE employee.Employee_ID = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array(
            $pathcontractnew,
            $id
        ));

        DBConnection::disconnect();

        // Direct user back to employee.php after they have successfully submitted the form
        header("Location: employee.php");
    } else {
        $filetypeError = "Invalid file type. Please upload a PDF file!";
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
    			<h3>Update Contract</h3>
    		</div>
    		<form action="UpdateContract.php?id=<?php echo $id?>" method="post" enctype="multipart/form-data">
    			<!-- Upload Contract -->
    			<label class="form-label" for="contract">Contract</label> 
    			<input class="form-control" name="contract" id="contract" type="file" accept=".pdf" required> 
    			<small class="form-text text-muted">Please upload a PDF file.</small> <br>
                <?php if (!empty($filetypeError)): ?>
                	<span class="help-inline"><?php echo $filetypeError;?></span>
                <?php endif; ?>
    
    			<!-- Submit button -->
    			<div class="form-actions">
    				<button type="submit" class="btn btn-success">Update</button>
    				<a class="btn" href="employee.php">Back</a>
    			</div>
    		</form>
    	</div>
    </body>
   </html>