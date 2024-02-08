<?php 
include('DBConnection.php');

session_start();

// if session is not set, redirect to login page.
if(!isset($_SESSION['Employee_ID']) || $_SESSION['Employee_ID'] == '') {
    echo "<script>alert('Please login first.')</script>";
    header("Location: index.php");
}

// if employee role is not administrator, no permission to view
if($_SESSION['Role_Name'] != 'Administrator') {
    echo "You don't have permission to view this page.";
    exit();
}

// connect to database
$pdo = DBConnection::connectToDB();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// keep track of validation errors
$phoneNumError = null;
$emailError = null;
$bankAccError = null;
$ICNumberError = null;
$pwError = null;

if(isset($_POST['btnRegister'])) {
    
    $name = $_POST['txtName'];
    $gender = $_POST['rdoGender'];
    $dob = $_POST['dtDOB'];
    $phoneNum = $_POST['txtPhoneNum'];
    $email = $_POST['txtEmail'];
    $address = $_POST['txtAddress'];
    $onboardDate = $_POST['dtOnBoard'];
    $offboardDate = $_POST['dtOffBoard'];
    $bankAcc = $_POST['txtBankAcc'];
    $ICNumber = $_POST['txtICNumber'];
    
    //validate all the inputs
    $valid = true;
    
    // validate phone number 8 digits
    if(!preg_match('/^[0-9]{8}$/', $phoneNum)) {
        $phoneNumError = 'Phone number should be 8 digits.';
        $valid = false;
    }
    
    // validate email address
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) ) {
        $emailError = 'Please enter a valid email address.';
        $valid = false;
    }
    
    // validate bank account number 9 to 12 digits
    if (! preg_match('/^\d{9,12}$/', $bankAcc)) {
        $bankAccError = 'Bank account number should be between 9 to 12 digits.';
        $valid = false;
    }
    
    // validate IC number capital starting alphabet, 7 digits in the middle and capital end alphabet
    if(!preg_match('/^[A-Z][0-9]{7}[A-Z]$/', $ICNumber)) {
        $ICNumberError = 'Please enter a valid IC number.';
        $valid = false;
    }
    
    // setting a default profile picture whether user submits profile or not
    $profilePicPath = "Website_Images/Default_pp.png";
    
    // upload profile picture file
    if(isset($_FILES['fProfilePic']['name'])) {
        $profilePicName = $_FILES['fProfilePic']['name'];
        $tempProfilePicName = $_FILES['fProfilePic']['tmp_name'];
        $profilePicPath = "Employee_Info/Profile_Pics/" . $profilePicName;
        if(!move_uploaded_file($tempProfilePicName, $profilePicPath)) {
            echo "<script>alert('Failed uploading profile pic.')</script>";
        }
    }
    
     // upload resume file
    if(!empty($_FILES['fResume']['name'])) {
        $resumeName = $_FILES['fResume']['name'];
        $tempResumeName = $_FILES['fResume']['tmp_name'];
        $resumePath = "Employee_Info/Resumes/" . $resumeName;
        if(!move_uploaded_file($tempResumeName, $resumePath)) {
            echo "<script>alert('Failed uploading resume.')</script>";
        }
    }
    else {
        $resumePath = null;
    }
    
    // upload contract file
    if(!empty($_FILES['fContract']['name'])) {
        $contractName = $_FILES['fContract']['name'];
        $tempContractName = $_FILES['fContract']['tmp_name'];
        $contractPath = "Employee_Info/Contracts/" . $contractName;
        
        if(!move_uploaded_file($tempContractName, $contractPath)) {
            echo "<script>alert('Failed uploading contract.')</script>";
        }
    }
    else {
        $contractPath = null;
    }
    
    $password = $_POST['txtPassword'];
    if(!preg_match('/^[a-zA-Z0-9@#$!%*#?&]{7,}$/', $password)) {
        $pwError = 'Password minimum length is 7, with combination of uppercase, lowercase letters, numbers and symbols.';
        $valid = false;
    }
    
    // hashing password with bcrypt algorithm
    $hashPassword = password_hash($password, PASSWORD_BCRYPT);
    
    $roleID = $_POST['sRole'];
    $designationID = $_POST['sDesignation'];
    $bankID = $_POST['sBank'];

    // insert employee data
    if($valid) {
        try {
            $insertEmployeeSQL = "INSERT INTO employee (Name, Gender, Date_Of_Birth, Phone_Num, Email, Address, Onboard_Date, Offboard_Date, Profile_Pic, Resume, Contract, Role_ID, Designation_ID, Bank_ID) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $insertEmployeeStmt = $pdo->prepare($insertEmployeeSQL);
            $insertEmployeeStmt->execute(array($name, $gender, $dob, $phoneNum, $email, $address, $onboardDate, $offboardDate, $profilePicPath, $resumePath, $contractPath, $roleID, $designationID, $bankID));
            
            $lastInsertedEmployeeID = $pdo->lastInsertId();
            
            $insertSensitiveInfoSQL = "INSERT INTO sensitive_info (Password, Bank_Account, IC_Number, Employee_ID) VALUES (?, ?, ?, ?)";
            $insertSensitiveInfoStmt = $pdo->prepare($insertSensitiveInfoSQL);
            $insertSensitiveInfoStmt->execute(array($hashPassword, $bankAcc, $ICNumber, $lastInsertedEmployeeID));
            
            echo "<script>alert('Employee registration successful')</script>";
        }
        catch (PDOException $e) {
            $pdo->rollBack();
            echo "Error:" .$e->getMessage();
        }
    }
    
    else {
        echo "<script>alert('Please check the error messages and enter correct and valid information.')</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Create Employee page of AMC HR system">
    <title>Register Employee page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<!--     <script src="js/bootstrap.min.js"></script> -->
    <style>
        table td {
            padding-right: 20px;
            padding-top: 15px;
        }
        .warning {
            color: red;
            font-size: 12px;
        }
         caption { 
             display: none; 
         } 
    </style>
    <script>
    	// preview uploaded profile image
    	function readURL(fileInput) {
    		if(fileInput.files && fileInput.files[0]) {
    			let reader = new FileReader();
                reader.onload = function () {
                	let imgProfile = document.getElementById("imgProfile");
                	imgProfile.src = reader.result;
                }
                
                reader.readAsDataURL(fileInput.files[0]);
    		}
    	}
    	
    	//validating or showing error msg for when phone no input is not 8 digits
    	function validatePhoneNo(txtPhoneNo) {
    		let phoneNoRE = /^[0-9]{8}$/;
    		if(txtPhoneNo.value.match(phoneNoRE)) {
        		document.getElementById("phoneNoError").innerHTML = "";
    		}
    		else {
    			document.getElementById("phoneNoError").innerHTML = "Phone number should be 8 digits.";
    		}
    	}
    	
    	//validating or showing error msg for when bank account no input is not between 9 and 12 digits
    	function validateBankAccNo(txtBankAccNo) {
    		let bankAccNoRE = /^[0-9]{9,12}$/;
    		if(txtBankAccNo.value.match(bankAccNoRE)) {
        		document.getElementById("bankAccNoError").innerHTML = "";
    		}
    		else {
    			document.getElementById("bankAccNoError").innerHTML = "Bank account number should be between 9 and 12 digits.";
    		}
    	}
    	
    	//validating or showing error msg for when IC no input has 1 start alphabet, 7 middle digits and 1 end alphabet
    	function validateICNo(txtICNumber) {
    		let ICNoRE = /^[A-Z][0-9]{7}[A-Z]$/;
    		if(txtICNumber.value.match(ICNoRE)) {
        		document.getElementById("ICnumberError").innerHTML = "";
    		}
    		else {
    			document.getElementById("ICnumberError").innerHTML = "IC number is not valid.";
    		}
    	}
    	
    	//showing error msg to enforce password strength, password minimum length is 7, with combination of uppercase, lowercase letters, numbers and symbols.
    	function strengthenPw(txtPassword) {
    		let pwRE = /^[a-zA-Z0-9@#$!%*#?&]{7,}$/;
    		if(txtPassword.value.match(pwRE)) {
    			document.getElementById("pwError").innerHTML = "";
    		}
    		else {
    			document.getElementById("pwError").innerHTML = "Password minimum length is 7, with combination of uppercase, lowercase letters, numbers and symbols.";
    		}
    	}
	</script> 
</head>

<body>
	<?php include('SideNav.php')?>
	<div class="container-fluid mt-4">
	
<!-- 	breadcrumb for navigation  -->
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-5">
				<li class="breadcrumb-item"><a href="Home.php">Home</a></li>
				<li class="breadcrumb-item"><a href="RetrieveEmployee.php">View Employees</a></li>
				<li class="breadcrumb-item active" aria-current="page">Register Employee</li>
			</ol>
		</nav>
		
		
        <form action="CreateEmployee.php" method="post" enctype="multipart/form-data">
        	<div class="row">
        		<div class="col-md-1"></div>
        		
        		<div class="col-md-5">
        		
<!--         		card for employee information input -->
        		<div class="card p-3" style="width:500px;">
        			<table>
        			<caption>Table for Employee Registration form for filling in personal information</caption>
        				<tr>
        				<td></td>
        					<td><img src="Website_Images/Default_pp.png" alt="Default profile picture" id="imgProfile" width="100px" height="100px" class="border rounded-circle"></td>
        				</tr>
        				<tr>
        					<td><label for="fProfilePic">Profile picture:</label></td>
        					<td><input name="fProfilePic" id="fProfilePic" class="form-control-sm border rounded" type="file" onchange="readURL(this);" required></td>
        				</tr>
        				<tr>
        					<td><label for="txtName">Name: </label></td>
                    		<td><input name="txtName" id="txtName" class="form-control-sm border rounded" type="text" required>
                        		
                    		</td>
                        </tr>
                        <tr>
                        	<td><label for="rdoGender">Gender: </label></td>
                        	<td>
                        		<input name="rdoGender" type="radio" id="rdoMale" value="Male" checked="checked" required>
                        		<label for="rdoMale">Male</label>
                                <input name="rdoGender" type="radio" id="rdoFemale" value="Female" required>
                                <label for="rdoFemale">Female</label>
                        	</td>
                        </tr>
                        <tr>
                        	<td><label for="dtDOB">Date of birth: </label></td>
                        	<td><input name="dtDOB" id="dtDOB" class="form-control-sm border rounded" type="date" required></td>
                        </tr>
                        <tr>
                        	<td><label for="txtPhoneNum">Phone number: </label></td>
                        	<td><input name="txtPhoneNum" id="txtPhoneNum" class="form-control-sm border rounded" type="text" maxlength="8" required onkeyup="validatePhoneNo(this);">
                        	<br><span class="warning" id="phoneNoError"><?php if($phoneNumError != null){echo $phoneNumError;} ?></span>
                    		</td>
                        </tr>
                        <tr>
                        	<td><label for="txtEmail">Email: </label></td>
                        	<td><input name="txtEmail" id="txtEmail" class="form-control-sm border rounded" type="email" required>
                         		<br><span class="warning" id="emailError"><?php if($emailError != null){echo $emailError;} ?></span>
                    		</td>
                        </tr>
                        <tr>
                        	<td><label for="txtAddress">Address: </label></td>
                        	<td><input name="txtAddress" id="txtAddress" class="form-control-sm border rounded" type="text" required></td>
                        </tr>
                        <tr>
                        	<td><label for="txtICNumber">IC number: </label></td>
                         	<td><input name="txtICNumber" id="txtICNumber" class="form-control-sm border rounded" type="text" maxlength="9" required onkeyup="validateICNo(this);">
                         		<br><span class="warning" id="ICnumberError"><?php if($ICNumberError != null){echo $ICNumberError;} ?></span> 
                        	</td>
                        </tr>
                    </table>
                    </div>
            	</div>
            	
            	<div class="col-md-5">
            	
<!--             	card for work-related information input -->
            	<div class="card p-3" style="width:500px;">
            		<table>
            		<caption>Table for Employee Registration form for filling in job-related information</caption>
            			<tr>
                        	<td><label for="dtOnBoard">Onboarding date: </label></td>
                        	<td><input name="dtOnBoard" id="dtOnboard" class="form-control-sm border rounded" type="date" placeholder="Onboard date" required></td>
                        </tr>
                        <tr>
                        	<td><label for="dtOffBoard">Offboarding date: </label></td>
                        	<td><input name="dtOffBoard" id="dtOffboard" class="form-control-sm border rounded" type="date" placeholder="Offboard date"></td>
                        </tr>
            			<tr>
            				<td><label for="sBank">Bank: </label></td>
            				<td><select name="sBank" id="sBank" class="form-control-sm border rounded" required>
            				
<!--              				select bank SQL to allow admin select a bank from registered banks in the database for the employee --> 
                        		<?php 
                                    $selectBankSQL = "SELECT * FROM Bank";
                                    $query = $pdo->prepare($selectBankSQL, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                                    $query->execute();
                                    $data = $query->fetchAll();
                                    foreach ($data as $row) {
                                        echo "<option value=".$row['Bank_ID'].">".$row['Bank_Name']."</option>";
                                    }
                                 ?>
                                 </select></td> 
             			</tr> 
             			<tr> 
                         	<td><label for="txtBankAcc">Bank account number: </label></td> 
                         	<td><input name="txtBankAcc" id="txtBankAcc" class="form-control-sm border rounded" type="text" maxlength="12" required onkeyup="validateBankAccNo(this)">
                         		<br><span id="bankAccNoError" class="warning"><?php if($bankAccError != null){echo $bankAccError;} ?></span>
                        	</td>
                        </tr>
                        <tr>
                        	<td><label for="fResume">Resume: </label></td>
                        	<td><input name="fResume" id="fResume" class="form-control-sm border rounded" type="file"></td>
                        </tr>
                        <tr>
                         	<td><label for="fContract">Contract: </label></td> 
                         	<td><input name="fContract" id="fContract" class="form-control-sm border rounded" type="file"></td> 
                         </tr>
                         <tr> 
                         	<td><label for="txtPassword">Password: </label></td> 
                       	<td><input name="txtPassword"  autocomplete="new-password" class="form-control-sm border rounded" type="password" placeholder="Password" required onkeyup="strengthenPw(this)">
                         	<br><span class="warning" id="pwError"><?php if($pwError != null){echo $pwError;} ?></span> 
                        	</td>
                        </tr>
                        <tr>
                        	<td><label for="sRole">User role: </label></td>
                        	<td>
                        		<select name="sRole" id="sRole" class="form-control-sm border rounded" required>
                        		
<!--                         	select role SQL to allow admin select a role from registered roles in the database for the employee -->
                                <?php 
                                    $selectRoleSQL = "SELECT * from Role";
                                    $query = $pdo->prepare($selectRoleSQL, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                                    $query->execute();
                                    $data = $query->fetchAll();
                                    foreach ($data as $row) {
                                        echo "<option value=".$row['Role_ID'].">".$row['Role_Name']."</option>";
                                    }
                                  ?>
                                </select>
                        	</td>
                        </tr>
                        <tr>
                        	<td><label for="sDesignation">Designation: </label></td>
                        	<td><select name="sDesignation" id="sDesignation" class="form-control-sm border rounded" required>
                        	
<!--                        select designation SQL to allow admin select a designation from registered designations in the database for the employee -->                        	
                            <?php 
                                $selectDesignationSQL = "SELECT * FROM Designation";
                                $query = $pdo->prepare($selectDesignationSQL, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                                $query->execute();
                                $data = $query->fetchAll();
                                foreach ($data as $row) {
                                    echo "<option value=".$row['Designation_ID'].">".$row['Designation']."</option>";
                                }
                             ?>
                            </select></td>
                        </tr>
                        <tr>
                        <td></td>
                        	<td><input name="btnRegister" class="btn btn-info" type="submit" value="Register"></td>
                        </tr>
            		</table>
            		</div>
            	</div>
        	</div>
        </form>
        </div> 
</body>
</html>