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

    $pfnew = $_FILES['profile_pic'];
    $namepfnew = $pfnew['name'];

    $tnamepfnew = $pfnew['tmp_name'];
    $file_type = mime_content_type($tnamepfnew);
    if ($file_type == 'image/png' || $file_type == 'image/jpeg') {
        $uploadpfDir = 'Employee_Info/Profile_Pics/';

        $pathpfnew = $uploadpfDir . basename($namepfnew);
        $pathpfnew = substr($pathpfnew, 0, 50);

        move_uploaded_file($tnamepfnew, $pathpfnew);

        $pdo = DBConnection::connectToDB();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "UPDATE employee
         SET
         employee.Profile_Pic = ?
         WHERE employee.Employee_ID = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array(
            $pathpfnew,
            $id
        ));

        DBConnection::disconnect();

        header("Location: employee.php");
    } else {
        $filetypeError = "Invalid file type. Please upload a PNG / JPEG file!";
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
<link href="css/form.css" rel="css stylesheet">
<script
	src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
	<div class="container" id="form">
		<div class="text-center">
			<h3>Change Profile Image</h3>
		</div>
		<form action="updatepf.php?id=<?php echo $id?>" method="post" enctype="multipart/form-data">

			<!-- Profile Image -->
			<div class="control-group">
				<label class="form-label" for="profile_pic">Profile Image</label> 
				<input class="form-control" name="profile_pic" id="profile_pic" type="file" accept="image/*" required>
                <small class="form-text text-muted">Please upload a PNG/JPEG file.</small>
				<br>
                <?php if (!empty($filetypeError)): ?>
                	<span class="help-inline"><?php echo $filetypeError;?></span>
                <?php endif; ?>
			</div>

			<!-- Submit button -->
			<div class="form-actions">
				<button type="submit" class="btn btn-success">Update</button>
				<a class="btn" href="employee.php">Back</a>
			</div>
		</form>
	</div>
</body>
</html>

