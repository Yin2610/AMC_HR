<?php
require 'DBConnection.php';

// Verify if the user had login, if the user had login, get the Employee_ID of the selected employee
$id = null;

session_start();

if(!isset($_SESSION['Employee_ID']) || $_SESSION['Employee_ID'] == '') {
    echo "<script>alert('Please login first.')</script>";
    header("Location: index.php");
}
else {
    if (! empty($_GET['id'])) {
        $id = $_REQUEST['id'];
    }
    
    if (null == $id) {
        header("Location: RetrieveEmployee.php");
    }
}

//Retrieve user's information
$pdo = DBConnection::connectToDB();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = 'SELECT 
        employee.Profile_Pic, employee.Name, employee.Gender, employee.Date_Of_Birth, employee.Phone_Num, employee.Email, employee.Address, employee.Onboard_Date, employee.Offboard_Date,
        bank.Bank_Name, 
        sensitive_info.Bank_Account, sensitive_info.IC_Number,
        department.Department_Name,
        designation.Designation, designation.Salary,
        role.Role_Name
        
            FROM employee
            INNER JOIN role ON employee.Role_ID = role.Role_ID
            INNER JOIN designation ON employee.Designation_ID = designation.Designation_ID
            INNER JOIN bank ON employee.Bank_ID = bank.Bank_ID
            INNER JOIN department ON designation.Department_ID = department.Department_ID
            INNER JOIN sensitive_info ON employee.Employee_ID = sensitive_info.Employee_ID
            WHERE employee.Employee_ID = ?';
$q = $pdo->prepare($sql);
$q->execute(array(
    $id
));
$data = $q->fetch(PDO::FETCH_ASSOC);
$pf = $data['Profile_Pic'];
$name = $data['Name'];
$gender = $data['Gender'];
$dob = $data['Date_Of_Birth'];
$nric = $data['IC_Number'];
$mobile = $data['Phone_Num'];
$email = $data['Email'];
$address = $data['Address'];
$bank = $data['Bank_Name'];
$bankacc = $data['Bank_Account'];
$department = $data['Department_Name'];
$designation = $data['Designation'];
$role = $data['Role_Name'];
$ondate = $data['Onboard_Date'];
$offdate = $data['Offboard_Date'];
$salary = $data['Salary'];

DBConnection::disconnect();

//Check if user had submitted any data through the HTTP POST method
if (! empty($_POST)) { 
    
    /*
     * Initialise "Error Message". 
     * If its empty, it means that there is no error, 
     * else it means that some error occurred and it will prompt user on what is the error.
     */
    $nameError = null;
    $nricError = null;
    $mobileError = null;
    $emailError = null;
    $addressError = null;
    $bankaccError = null;
    
    /*
     * Retrieve data submitted
     */

    $name = trim($_POST['name']);
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $nric = $_POST['nric'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $address = trim($_POST['address']);
    $bank = $_POST['bank'];
    $bankacc = $_POST['bankacc'];
    $designation = $_POST['designation'];
    $role = $_POST['role'];
    $ondate = $_POST['ondate'];
    $offdate = $_POST['offdate'];
    
    /*
     * Initialise $valid.
     * If $valid is true, it means all data submitted is correct, and will be updated to the database.
     * else, it means there's error in the data submitted, either one or more. Hence, will prompt the user on what is the error and will not update the database
     */

    $valid = true;
    
    //Ensure $name is not empty and including spaces, is 30 characters or less
    if (empty($name)) {
        $nameError = 'Please enter Name';
        $valid = false;
    } elseif (strlen($name) > 30) {
        $nameError = 'Name (including spaces) must be 30 characters or less';
        $valid = false;
    }

    /*
     * Ensure $nric is not empty and is in the correct format. 
     * i.e.
     * Must be 9 characters
     * No spacing
     * Capital Letters only
     * First and Last character must be alphabet
     * Rest of the character must be number
     */
    if (! preg_match('/^[A-Z][0-9]{7}[A-Z]$/', $nric)) {
        $nricError = 'NRIC format is invalid';
        $valid = false;
    }
    
    /*
     * Ensure $mobile is not empty and is in the correct format.
     * i.e.
     * Must be 8 characters
     * Numeber only
     * No spacing
     */
    if (! preg_match('/^[0-9]{8}$/', $mobile)) {
        $mobileError = 'Mobile Number should be 8 numeric characters';
        $valid = false;
    }

    //Ensure $email is not empty and is in the correct format
    if (! filter_var($email, FILTER_VALIDATE_EMAIL)) { 
        $emailError = 'Please enter a valid Email Address';
        $valid = false;
    }
    
    //Ensure $address is not empty and including spaces, is 50 characters or less
    if (empty($address)) {
        $addressError = 'Please enter Address';
        $valid = false;
    } elseif (strlen($address) > 50) {
        $addressError = 'Address (including spaces) must be 50 characters or less';
        $valid = false;
    }

    /* Ensure $bankacc is not empty and is in the correct format
     * i.e.
     * Only 9 to 12 characters
     * Only have number
     * No spacing
     */
    
    if (!preg_match('/^\d{9,12}$/', $bankacc)) {
        $bankaccError = 'Bank Account Number should be numeric and have 9 to 12 digits or less';
        $valid = false;
    }
    
    //if all data submitted is correct, update the database and redirect the user to RetrieveEmployee.php
    if ($valid) {
        $pdo = DBConnection::connectToDB();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "UPDATE employee
         INNER JOIN role ON employee.Role_ID = role.Role_ID
         INNER JOIN designation ON employee.Designation_ID = designation.Designation_ID
         INNER JOIN bank ON employee.Bank_ID = bank.Bank_ID
         INNER JOIN department ON designation.Department_ID = department.Department_ID
         INNER JOIN sensitive_info ON employee.Employee_ID = sensitive_info.Employee_ID
         SET 
         employee.Name = ?,
         employee.Gender = ?,
         employee.Date_Of_Birth = ?,
         employee.Phone_Num = ?,
         employee.Email = ?,
         employee.Address = ?,
         employee.Onboard_Date = ?,
         employee.Offboard_Date = ?,
         employee.Role_ID = ?,
         employee.Designation_ID = ?,
         employee.Bank_ID = ?,
         sensitive_info.Bank_Account = ?,
         sensitive_info.IC_Number = ?
         WHERE employee.Employee_ID = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array(
            $name,
            $gender,
            $dob,
            $mobile,
            $email,
            $address,
            $ondate,
            $offdate,
            $role,
            $designation,
            $bank,
            $bankacc,
            $nric,
            $id
        ));
        
        DBConnection::disconnect();
        header("Location: RetrieveEmployee.php");
        
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
<body class="bg-light">

	<!-- Side Navigation Bar -->
    <?php include('SideNav.php')?>
    
    <div class="container-fluid mt-4">
    
    	<!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
    		<ol class="breadcrumb mb-5">
    			<li class="breadcrumb-item"><a href="Home.php">Home</a></li>
    			<li class="breadcrumb-item"><a href="RetrieveEmployee.php">View Employees</a></li>
    			<li class="breadcrumb-item active" aria-current="page">Update Employee</li>
    		</ol>
    	</nav>
    	
    	<!-- Form -->
		<div id="form">
			<div class="text-center">
				<h1>Update <?php echo !empty($name)?$name:'';?> Details</h1>
				<img width="200px" height="200px" id="pf" src="<?php echo !empty($pf)?$pf:'';?>">
			</div>
			
			<form action="UpdateEmployee.php?id=<?php echo $id?>" method="post" enctype="multipart/form-data">
			
			     <!-- Name -->
				<div class="mb-3">
					<label class="form-label" for="name">Name</label> 
					<input class="form-control" name="name" id="name" type="text" placeholder="Name" maxlength="30" value="<?php echo !empty($name)?$name:'';?>" autocomplete="on" required>
					<small class="form-text text-muted">30 characters or less(including spaces)</small>
					<br>
                    <?php if (!empty($nameError)): ?>
                    	<span class="help-inline"><?php echo $nameError;?></span>
                    <?php endif; ?>
				</div>
				
				<div class="row mb-3">
				
				    <!-- Gender -->
					<div class="col">
						<label class="form-label" for="gender">Gender</label> 
						<select class="form-select" name="gender" id="gender">
							<option value="Male" <?php echo ($gender === 'Male') ? 'selected' : ''; ?>>Male</option>
							<option value="Female" <?php echo ($gender === 'Female') ? 'selected' : ''; ?>>Female</option>
							<option value="Other" <?php echo ($gender === 'Other') ? 'selected' : ''; ?>>Other</option>
						</select>
					</div>
					
				    <!-- DOB -->
					<div class="col">
						<label class="form-label" for="dob">DOB</label> <input class="form-control" name="dob" id="dob" type="date" placeholder="DOB" required value="<?php echo !empty($dob)?$dob:'';?>">
					</div>
				</div>

			    <!-- NRIC Number -->
				<div class="mb-3">
					<label class="form-label" for="nric">NRIC</label> 
					<input class="form-control" name="nric" id="nric" type="text" placeholder="NRIC Number" required maxlength="9" value="<?php echo !empty($nric)?$nric:'';?>" autocomplete="on"> 
                    <small class="form-text text-muted">Input in CAPITAL LETTER!</small>
					<br>
					<?php if (!empty($nricError)): ?>
                        <span class="help-inline"><?php echo $nricError;?></span>
                    <?php endif;?>
				</div>

			    <!-- Mobile Number -->
				<div class="mb-3">
					<label class="form-label" for="mobile">Mobile Number</label> 
					<input class="form-control" name="mobile" id="mobile" type="text" placeholder="Mobile Number" required maxlength="8" value="<?php echo !empty($mobile)?$mobile:'';?>" autocomplete="on">
					<small class="form-text text-muted">Please enter your phone number without spacing and country code. SG number only!</small>
					<br>
                    <?php if (!empty($mobileError)): ?>
                    	<span class="help-inline"><?php echo $mobileError;?></span>
                    <?php endif;?>
				</div>

			    <!-- Email -->
				<div class="mb-3">
					<label class="form-label" for="email">Email Address</label> 
					<input class="form-control" name="email" id="email" type="text" placeholder="Email Address" required maxlength="254" value="<?php echo !empty($email)?$email:'';?>" autocomplete="on">
                   	<?php if (!empty($emailError)): ?>
                    	<span class="help-inline"><?php echo $emailError;?></span>
                    <?php endif;?>
				</div>

			     <!-- Address -->
				<div class="mb-3">
					<label class="form-label" for="address">Address</label> 
					<input class="form-control" name="address" id="address" type="text" placeholder="Address" required maxlength="50" value="<?php echo !empty($address)?$address:'';?>" autocomplete="on">
                    <small class="form-text text-muted">50 characters or less(including spaces)</small>
                    <br>
                    <?php if (!empty($addressError)): ?>
                    	<span class="help-inline"><?php echo $addressError;?></span>
                    <?php endif;?>
				</div>

				<div class="row mb-3">
				    <!-- Bank Company -->
					<div class="col">
					<label class="form-label" for="bank">Bank Company</label> 
					<select class="form-select" name="bank" id="bank" required>
        				<?php
                            $pdo = DBConnection::connectToDB();
                            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            $selectBankSQL = "SELECT * FROM bank";
                            $query = $pdo->prepare($selectBankSQL, array(
                                PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL
                            ));
                            $query->execute();
                            $data = $query->fetchAll();
                            foreach ($data as $row) {
                                echo "<option value=" . $row['Bank_ID'] . ">" . $row['Bank_Name'] . "</option>";
                            }
                        ?>
            		</select>
				</div>

				<!-- Bank Account Number -->
				<div class="col">
					<label class="form-label" for="bankacc">Bank Account Number</label>
					<input class="form-control" name="bankacc" id="bankacc" type="text" maxlength="12" placeholder="Bank Account Number" value="<?php echo !empty($bankacc)?$bankacc:'';?>" autocomplete="on" required>
                    <small class="form-text text-muted">Number only!</small>
                    <br>
                    <?php if (!empty($bankaccError)): ?>
                    	<span class="help-inline"><?php echo $bankaccError;?></span>
                    <?php endif;?>
				</div>
			</div>

			<div class="row mb-3">

				<!-- Designation -->
				<div class="col">
    				<label class="form-label" for="designation">Designation</label> 
    				<select class="form-select" name="designation" id="designation" required>
                        <?php
                            $selectDesignationSQL = "SELECT * FROM Designation";
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
				</div>

				<!-- Department -->
				<div class="col">
					<label class="form-label" for="department">Department</label> 
					<input class="form-control" name="department" id="department" type="text" placeholder="Department" value="<?php echo !empty($department)?$department:'';?>" readonly>
				</div>

			</div>
			
			<!-- Role -->
			<div class="mb-3">
				<label class="form-label" for="role">Role</label> 
				<select class="form-select" name="role" id="role" required>
            		<?php
                        $selectRoleSQL = "SELECT * FROM Role";
                        $query = $pdo->prepare($selectRoleSQL, array(
                            PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL
                        ));
                        $query->execute();
                        $data = $query->fetchAll();
                        foreach ($data as $row) {
                            $selected = ($row['Role_ID'] == $role) ? 'selected' : ''; 
                            echo "<option value=" . $row['Role_ID'] . " $selected>" . $row['Role_Name'] . "</option>";
                        }
                        
                    ?>
            	</select>
			</div>

			<div class="row mb-3">

				<!-- Onboard Date -->
				<div class="col">
					<label class="form-label" for="ondate">Onboard Date</label> 
					<input class="form-control" name="ondate" id="ondate" type="date" placeholder="Onboard Date" value="<?php echo !empty($ondate)?$ondate:'';?>" required>
				</div>

				<!-- Offboard Date -->
				<div class="col">
					<label class="form-label" for="offdate">Offboard Date</label> 
					<input class="form-control" name="offdate" id="offdate" type="date" placeholder="Offboard Date" value="<?php echo !empty($offdate)?$offdate:'';?>">
				</div>
			</div>

			<!-- Salary -->
			<div class="mb-3">
				<label class="form-label" for="salary">Salary</label>
				<div class="input-group">
					<span class="input-group-text">$</span> <input class="form-control" name="salary" id="salary" type="text" value="<?php echo !empty($salary)?$salary:'';?>" readonly>
				</div>
			</div>

			<!-- Submit and Back button -->
			<div class="form-actions">
				<button type="submit" class="btn btn-success">Update</button>
				<a class='btn' href='RetrieveEmployee.php'>Back</a>
			</div>
		</form>
	</div>
	</div>
</body>
</html>

