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

    $passwordError = null;
    $cpasswordError = null;

    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];

    if (strpos($password, ' ') !== false) {
        $passwordError = 'Password cannot contain spaces';
    } else {
        if ($password === $cpassword) {

            $epassword = password_hash($password, PASSWORD_BCRYPT);

            $pdo = DBConnection::connectToDB();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "UPDATE sensitive_info
                SET sensitive_info.Password = ?
                WHERE sensitive_info.Employee_ID = ?";
            $q = $pdo->prepare($sql);
            $q->execute(array(
                $epassword,
                $id
            ));

            DBConnection::disconnect();

            header("Location: employee.php");
        } else {
            $cpasswordError = "Password does not match!";
        }
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
			<h3>Change Password</h3>
		</div>

		<form action="updatepassword.php?id=<?php echo $id?>" method="post">

			<!-- New Password -->
			<label class="form-label" for="password">Password</label> 
			<input class="form-control" name="password" type="text" placeholder="Password" id="password"
				value="<?php echo isset($password) ? $password:'';?>" required>
                <?php if (!empty($passwordError)): ?>
                	<span class="help-inline"><?php echo $passwordError;?></span>
                <?php endif;?>
				

				<!-- Confirm New Password -->

			<label class="form-label" for="cpassword">Confirm Password</label> 
			<input class="form-control" name="cpassword" id="cpassword" type="text" placeholder="Confirm Password" value="<?php echo isset($cpassword) ? $cpassword:'';?>" required>
            <?php if (!empty($cpasswordError)): ?>
            	<span class="help-inline"><?php echo $cpasswordError;?></span>
            <?php endif;?>
				

				<!-- Submit button -->
			<div class="form-actions">
				<button type="submit" class="btn btn-success">Confirm</button>
				<a class="btn" href="employee.php">Cancel</a>
			</div>

		</form>


	</div>
</body>
</html>

