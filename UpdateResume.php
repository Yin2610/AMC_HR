

<?php

require 'DBConnection.php';

$id = null;

if (! empty($_GET['id'])) {
    $id = $_REQUEST['id'];
}

if (null == $id) {
    header("Location: employee.php");
}

$filetypeError = null;

if (! empty($_FILES)) {

    $resumenew = $_FILES['resume'];
    $nameresumenew = $resumenew['name'];
    
        $tnameresumenew = $resumenew['tmp_name'];
        $file_type = mime_content_type($tnameresumenew);
        if ($file_type == 'application/pdf') {

        $uploadresumeDir = 'Employee_Info/Resumes/';
        
        
        $pathresumenew = $uploadresumeDir . basename($nameresumenew);
        $pathresumenew = substr($pathresumenew, 0, 50);
      
        move_uploaded_file($tnameresumenew, $pathresumenew);
        

        $pdo = DBConnection::connectToDB();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "UPDATE employee
         SET employee.Resume = ?
         WHERE employee.Employee_ID = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array(
            $pathresumenew,
            $id
        ));

        DBConnection::disconnect();

        header("Location: employee.php");
    
        } 
else{
    $filetypeError = "Invalid file type. Please upload a PDF file!";
}}
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
				<h3>Update Resume</h3>
			</div>

			<form class="form-horizontal"
				action="UpdateResume.php?id=<?php echo $id?>" method="post"
				enctype="multipart/form-data">
				
				<!-- Resume -->
					<label class="form-label" for="resume">Resume</label>
					<input class="form-control" name="resume" id="resume" type="file" accept=".pdf" required>
					<small class="form-text text-muted">Please upload a PDF file.</small>
					<br>
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






