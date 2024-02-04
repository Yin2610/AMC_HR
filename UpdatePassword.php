<?php
require 'DBConnection.php';

$id = null;
$name = null;

session_start();

/*
 * Check if the user is logged in;
 * if not, redirect to index.php and prompt them to log in first.
 */
if(!isset($_SESSION['Employee_ID']) || $_SESSION['Employee_ID'] == '') {
    echo "<script>
            alert('Please login first.');
            window.location.href='Index.php';
          </script>";
}
else {
    
    /*
     * If the user is logged in but role is neither "Department Head" nor "Administrator",
     * inform them that they does not have permission to view this page and exit the script.
     */
    if($_SESSION['Role_Name'] != 'Department Head' && $_SESSION['Role_Name'] != 'Administrator') {
        exit("You don't have permission to view this page.");
    }
    
    /*
     * If the user is logged in and role is "Department Head" or "Administrator",
     * attempt to retrieve the Employee_ID and Name from the URL and assign it to $id and $name.
     * If there is no Employee_ID or Name in the URL, redirect the user to RetrieveEmployee.php.
     */
    if (! empty($_GET['id'])) {
        $id = $_REQUEST['id'];
    }
    if (null == $id) {
        header("Location: RetrieveEmployee.php");
    }
    
    if (! empty($_GET['name'])) {
        $name = $_REQUEST['name'];
    }
    if (null == $name) {
        header("Location: RetrieveEmployee.php");
    }
}

//Check if the user has submitted any data through the HTTP POST method
if (! empty($_POST)) {

    /*
     * Initialise "Error Message".
     * If its empty, it means that there is no error,
     * else it means that some error occurred and it will prompt user on what teh error is.
     */
    $passwordError = null;
    $cpasswordError = null;

    //Retrieve data submitted in the form
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];

    /*Password validation and Database Update:
     * Check if the password meets the specified requirements:
     * - At least one lowercase letter
     * - At least one uppercase letter
     * - At least one digit
     * - At least one special character (@$!%*#?&).
     * - 12 characters or more
     * - No spaces
     * If the password passes validation:
     * - If both password inputs match, encrypt the password and update the database with the encrypted password.
     * - If passwords do not match, prompt the user to enter the same password.
     */
    if (strpos($password, ' ') !== false ||
    !preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&])[a-zA-Z0-9@#$!%*?&]{12,}$/', $password)) {
        $passwordError = 'Password must be 12 characters and more, consisting of uppercase letter,
        lowercase letter, number, special character(@$!%*#?&) and should not contains spaces';
    } else {
        if ($password === $cpassword) {

            $epassword = password_hash($password, PASSWORD_BCRYPT);

            $pdo = DBConnection::connectToDB();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try{
                $sql = "UPDATE sensitive_info
                    SET sensitive_info.Password = ?
                    WHERE sensitive_info.Employee_ID = ?";
                $q = $pdo->prepare($sql);
                $q->execute(array(
                    $epassword,
                    $id
                ));
            }catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }

            DBConnection::disconnect();

            echo "<script>
                    alert('You have successfully changed the employee\\'s password!');
                    window.location.href='RetrieveEmployee.php';
                 </script>";
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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="This page allows user to change password for themselves or their employees.">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/form.css" rel="css stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <title>Change Password</title>
</head>

<body>
	<!-- Side Navigation Bar -->
    <?php include 'SideNav.php'?>
    
    <div class="container-fluid mt-4">
    
    	<!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
    		<ol class="breadcrumb mb-5">
    			<li class="breadcrumb-item"><a href="Home.php">Home</a></li>
    			<li class="breadcrumb-item"><a href="RetrieveEmployee.php">View Employees</a></li>
    			<li class="breadcrumb-item active" aria-current="page">Change Employee's Password</li>
    		</ol>
    	</nav>
    	
    	<!-- Change Password Form -->
		<div id="form">
			<div class="text-center">
				<h1>Change <?php echo !empty($name)?$name:'';?> Password</h1>
			</div>
			
			<!-- Password Requirement -->
			<div class="row mb-3">
				<div class="col-3 mt-4">
    				<strong>Please ensure that your password meet the following requirement:</strong>
            		<ul>
              			<li>Must be 12 characters or more</li>
              			<li>Must contains at least one uppercase letter</li>
              			<li>Must contains at least one lowercase letter</li>
              			<li>Must contains at least one digit</li>
              			<li>Must contains at least special characters (@$!%*#?&)</li>
              			<li>Must not contain spaces</li>
            		</ul>
        		</div>
        		
    			<form class="col-9" action="UpdatePassword.php?id=<?php echo $id?>&name=<?php echo $name?>" method="post">
    			
    				<!-- New Password -->
    				<div class="mb-3">
        				<label class="form-label" for="password">Password</label>
        				<input
        				class="form-control" name="password" type="password"
        				placeholder="Password" id="password"
        				value="<?php echo isset($password) ? $password:'';?>" required>
                        <?php if (!empty($passwordError)): ?>
                        	<span class="help-inline"><?php echo $passwordError;?></span>
                        <?php endif;?>
                    </div>
    				
    
    				<!-- Confirm New Password -->
    				<div class="mb-3">
        				<label class="form-label" for="cpassword">Confirm Password</label>
        				<input class="form-control" name="cpassword" id="cpassword"
        				type="password" placeholder="Confirm Password"
        				value="<?php echo isset($cpassword) ? $cpassword:'';?>" required>
                    	<?php if (!empty($cpasswordError)): ?>
                    		<span class="help-inline"><?php echo $cpasswordError;?></span>
                    	<?php endif;?>
                	</div>
                	
                	<!-- Submit and Back button -->
                	<br>
    				<div class="form-actions">
    					<button type="submit" class="btn btn-success">Change</button>
    					<a class='btn' href='RetrieveEmployee.php'>Back</a>
    				</div>
    			</form>
			</div>
		</div>
	</div>
</body>
</html>

