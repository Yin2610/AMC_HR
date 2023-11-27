<?php
require 'DBConnection.php';

$id = null;

if (! empty($_GET['id'])) {
    $id = $_REQUEST['id'];
}

if (null == $id) {
    header("Location: employee.php");
}

if (! empty($_POST)) {
    $pdo = DBConnection::connectToDB();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "UPDATE employee
         SET Contract = DEFAULT
         WHERE Employee_ID = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array(
        $id
    ));

    DBConnection::disconnect();

    header("Location: employee.php");
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
			<h3>Delete Contract</h3>
		</div>

		<form class="text-center"
			action="DeleteContract.php?id=<?php echo $id?>" method="post"
			enctype="multipart/form-data">

			
				
		
				<input class="form-control" type="hidden" name="dcontract" value="null">

			

			<div class="form-actions">
				<button type="submit" class="btn btn-danger">Delete</button>
				<a class="btn" href="employee.php">Cancel</a>
			</div>



		</form>


	</div>
	
</body>
</html>




