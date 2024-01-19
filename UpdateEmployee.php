<?php
//establish connection to database
require 'DBConnection.php';
$pdo = DBConnection::connectToDB();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$id = null;

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
     * attempt to retrieve the Employee_ID from the URL and assign it to $id.
     */
    if (! empty($_GET['id'])) {
        $id = $_REQUEST['id'];
    }
    
    
    //If there is no Employee_ID in the URL, redirect the user to RetrieveEmployee.php.
    if (null == $id) {
        header("Location: RetrieveEmployee.php");
    }
}

//Retrieve employee's information from the database.
try {
    $sqlRetrieve = 'SELECT
                    employee.Profile_Pic, employee.Name, employee.Gender, employee.Date_Of_Birth, employee.Phone_Num, employee.Email,
                    employee.Address, employee.Onboard_Date, employee.Offboard_Date, employee.Contract, employee.Resume,
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
    $q = $pdo->prepare($sqlRetrieve);
    $q->execute(array(
        $id
    ));
    
    $data = $q->fetch(PDO::FETCH_ASSOC);
    
    if (!$data && !is_array($data)){
        echo "<script>
                    alert('Invalid employee!');
                    window.location.href='RetrieveEmployee.php';
              </script>";
    }
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

//Assign retrieved data to variables
$pf = $data['Profile_Pic'];
$name = $data['Name'];
$gender = $data['Gender'];
$dob = $data['Date_Of_Birth'];
$nric = $data['IC_Number'];
$mobile = $data['Phone_Num'];
$email = $data['Email'];
$address = $data['Address'];
$contract = $data['Contract'];
$resume = $data['Resume'];
$bank = $data['Bank_Name'];
$bankacc = $data['Bank_Account'];
$department = $data['Department_Name'];
$designation = $data['Designation'];
$role = $data['Role_Name'];
$ondate = $data['Onboard_Date'];
$offdate = $data['Offboard_Date'];
$salary = $data['Salary'];


//Check if the user has submitted any data through the HTTP POST method
if (! empty($_POST)) {
    
    /*
     * Initialise $valid.
     * If $valid is true, it means all data submitted is correct, and will be updated to the database.
     * If false, it means there's an error in the data submitted, and the user will be prompted on what the error is.
     */
    $valid = true;
    
    /*
     * Initialise "Error Message".
     * If empty, it means that there is no error,
     * else it measns that there's an error, and the user will be prompted on what the error is.
     */
    $pffiletypeError = null;
    $cfiletypeError = null;
    $rfiletypeError = null;
    $nameError = null;
    $nricError = null;
    $mobileError = null;
    $emailError = null;
    $addressError = null;
    $cfiletypeError = null;
    $bankaccError = null;
  
    /*
     * Determine the Profile Image based on the user's selection.
     * - If "epf" radio button is checked, set $pfnew to original Profile Image.
     * - If "npf" radio button is checked, validate and set $pfnew to the uploaded file.
     * - If "dpf" radio button is checked, set $pfnew to the default Profile Image.
     */
    $pfnew = null;
    $selectedPfOption = $_POST['hiddenpf'];
    
    if ($selectedPfOption === 'epf') {
        $pfnew = $pf;
    }
    elseif ($selectedPfOption === "npf") {
        $pfnew = $_FILES['profile_pic'];
        $namepfnew = $pfnew['name'];
        $tnamepfnew = $pfnew['tmp_name'];
        $file_type = exif_imagetype($tnamepfnew);
        
        //Validate file type is PNG or JPEG.
        if ($file_type === IMAGETYPE_PNG || $file_type === IMAGETYPE_JPEG) {
            
            /*
             * Move the uploaded file to "Employee_Info/Profile_Pics/"
             * and ensure the filepath is 50 characters or less.
             */
            $uploadpfDir = 'Employee_Info/Profile_Pics/';
            $pfnew = $uploadpfDir . basename($namepfnew);
            $pfnew = substr($pfnew, 0, 50);
            move_uploaded_file($tnamepfnew, $pfnew);
        }
        else{
            $pffiletypeError = "Invalid file type. Please upload a PNG / JPEG file!";
            $valid = false;
        }
    }
    elseif ($selectedPfOption === "dpf") {
        $pfnew = "Employee_Info/Profile_Pics/Default_Image.jpg";
    }
    
    /*
     * Determine the Contract based on the user's selection.
     * If "ec" radio button is checked, set $cnew to original Contract.
     * If "nc" radio button is checked, validate and set $cnew to file uploaded.
     * If "dc" radio button is checked, set $cnew to null. (I.e. delete contract)
     */
    $cnew = null;
    $selectedCOption = $_POST['hiddenc'];
    
    if ($selectedCOption === 'ec') {
        $cnew = $contract;
    }
    elseif ($selectedCOption === "nc") {
        $cnew = $_FILES['contract'];
        $namecnew = $cnew['name'];
        $tnamecnew = $cnew['tmp_name'];
        $file_type = mime_content_type($tnamecnew);
        
        //Validate file type is PDF.
        if ($file_type != 'application/pdf'){
            $cfiletypeError = "Invalid file type. Please upload a PDF file!";
            $valid = false;
        }else{
            
            /*
             * Move the uploaded file to "Employee_Info/Contracts/"
             * and ensure the filepath is 50 characters or less.
             */
            $uploadcDir = 'Employee_Info/Contracts/';
            $cnew = $uploadcDir . basename($namecnew);
            $cnew = substr($cnew, 0, 50);
            move_uploaded_file($tnamecnew, $cnew);
        }
    }
    elseif ($selectedCOption === "dc") {
        $cnew = null;
    }
    
    /*
     * Determine the Resume based on the user's selection.
     * If "er" radio button is checked, set $rnew to original Resume.
     * If "nr" radio button is checked, validate and set $rnew to file uploaded.
     * If "dr" radio button is checked, set $rnew to null. (I.e. delete resume)
     */
    $rnew = null;
    $selectedROption = $_POST['hiddenr'];
    
    if ($selectedROption === 'er') {
        $rnew = $resume;
    }
    elseif ($selectedROption === "nr") {
        $rnew = $_FILES['resume'];
        $namernew = $rnew['name'];
        $tnamernew = $rnew['tmp_name'];
        $file_type = mime_content_type($tnamernew);
        
        //Validate file type is PDF.
        if ($file_type != 'application/pdf'){
            $rfiletypeError = "Invalid file type. Please upload a PDF file!";
            $valid = false;
        }else{
            
            /*
             * Move the uploaded file to "Employee_Info/Resume/"
             * and ensure the filepath is 50 characters or less.
             */
            $uploadrDir = 'Employee_Info/Resume/';
            $rnew = $uploadrDir . basename($namernew);
            $rnew = substr($rnew, 0, 50);
            move_uploaded_file($tnamernew, $rnew);
        }
    }
    elseif ($selectedROption === "dr") {
        $rnew = null;
    }
    
    
    //Retrieve data submitted in the form
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
    
    //Validate the data submitted in the form

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
    if (empty($nric)){
        $nricError = 'Please enter NRIC';
        $valid = false;
    }elseif(! preg_match('/^[A-Z][0-9]{7}[A-Z]$/', $nric)) {
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
    if (empty($mobile)){
        $mobileError = 'Please enter Mobile Number';
        $valid = false;
    }elseif (! preg_match('/^[0-9]{8}$/', $mobile)) {
        $mobileError = 'Mobile Number should be 8 numeric characters';
        $valid = false;
    }

    //Ensure $email is not empty and is in the correct format
    if (empty($email)){
        $emailError = 'Please enter Email';
        $valid = false;
    }elseif (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
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
    if (empty($bankacc)){
        $bankaccError = 'Please enter Bank Account Number';
        $valid = false;
    }elseif (!preg_match('/^\d{9,12}$/', $bankacc)) {
        $bankaccError = 'Bank Account Number should be numeric and have 9 to 12 digits or less';
        $valid = false;
    }
    
    //if all data submitted is correct, update the database and redirect the user to RetrieveEmployee.php.
    if ($valid) {
        try{
            $sqlUpdate = "UPDATE employee
             INNER JOIN role ON employee.Role_ID = role.Role_ID
             INNER JOIN designation ON employee.Designation_ID = designation.Designation_ID
             INNER JOIN bank ON employee.Bank_ID = bank.Bank_ID
             INNER JOIN department ON designation.Department_ID = department.Department_ID
             INNER JOIN sensitive_info ON employee.Employee_ID = sensitive_info.Employee_ID
             SET
             employee.Profile_Pic = ?,
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
             employee.Contract = ?,
             employee.Resume = ?,
             sensitive_info.Bank_Account = ?,
             sensitive_info.IC_Number = ?
             WHERE employee.Employee_ID = ?";
            $q = $pdo->prepare($sqlUpdate);
            $q->execute(array(
                $pfnew,
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
                $cnew,
                $rnew,
                $bankacc,
                $nric,
                $id
            ));
            
            echo "<script>
                    alert('You have successfully updated the employee\\'s information!');
                    window.location.href='RetrieveEmployee.php';
                 </script>";
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
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
<title>Update Employee</title>
<script>
		
		//Preview uploaded file
		var loadFile = function(input) {
    		var output = document.getElementById('pf');
    		output.src = URL.createObjectURL(input.target.files[0]);
    		output.onload = function() {
    		
    			//Release the object URL to free up memory once the image has loaded.
      			URL.revokeObjectURL(output.src);
    		};
    		
    		//Store the uploaded file in the variable "uploadedFile".
    		uploadedFile = input.target.files[0];
  		};
		
		/*
		- Show and hide upload button based on the radio button selected.
		- Set the value for hiddenpf, hiddenc, and hiddenr based on the radio button selected.
		- Display existing image, uploaded file, and default image accordingly
		when the respective radio button is selected.
		*/
		
		//Profile Image Radio Button
        function togglePfButton() {
            var uploadPfButton = document.getElementById("uploadPfButton");
            var profile_pic = document.getElementById("profile_pic");
            var epfRadio = document.getElementById("epf");
            var npfRadio = document.getElementById("npf");
            var dpfRadio = document.getElementById("dpf");
            var hiddenpf = document.getElementById("hiddenpf");
            var outputpf = document.getElementById('pf');

            if (npfRadio.checked) {
            	uploadPfButton.style.display = "block";
            	profile_pic.setAttribute("required", "");
            	hiddenpf.value = "npf";
            	
            	
            	if (uploadedFile) {
            		loadFile({ target: { files: [uploadedFile] } });
        		}
            }
        	else {
            	uploadPfButton.style.display = "none";
            	profile_pic.required = false;
            	if (epfRadio.checked){
        			hiddenpf.value = "epf";
        			
        			outputpf.src = "<?php echo !empty($pf)?$pf:'';?>";
        			
        		}
        		else {
        			hiddenpf.value = "dpf";
        			
        			outputpf.src = "Employee_Info/Profile_Pics/Default_Image.jpg";
        		}
        	}
        }
        
        //Contract Radio Button
        function toggleCButton() {
            var uploadCButton = document.getElementById("uploadCButton");
            var contract = document.getElementById("contract");
            var ecRadio = document.getElementById("ec");
            var ncRadio = document.getElementById("nc");
            var dcRadio = document.getElementById("dc");
            var hiddenc = document.getElementById("hiddenc");

            if (ncRadio.checked) {
            	uploadCButton.style.display = "block";
            	contract.setAttribute("required", "");
            	hiddenc.value = "nc";
            }
        	else {
            	uploadCButton.style.display = "none";
            	contract.required = false;
            	if (ecRadio.checked){
        			hiddenc.value = "ec";
        		}
        		else {
        			hiddenc.value = "dc";
        		}
        	}
        }
        
        //Resume Radio Button
        function toggleRButton() {
            var uploadRButton = document.getElementById("uploadRButton");
            var resume = document.getElementById("resume");
            var erRadio = document.getElementById("er");
            var nrRadio = document.getElementById("nr");
            var drRadio = document.getElementById("dr");
            var hiddenr = document.getElementById("hiddenr");

            if (nrRadio.checked) {
            	uploadRButton.style.display = "block";
            	resume.setAttribute("required", "");
            	hiddenr.value = "nr";
            }
        	else {
            	uploadRButton.style.display = "none";
            	resume.required = false;
            	if (erRadio.checked){
        			hiddenc.value = "er";
        		}
        		else {
        			hiddenr.value = "dr";
        		}
        	}
        }
</script>
</head>
<body class="bg-light">

	<!-- Side Navigation Bar -->
    <?php include 'SideNav.php'?>
    
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
				
			<!-- Profile Image -->
			<div class="mb-3">
				<label class="form-label">Profile Image</label>
				
				<!-- Radio buttons for Profile Image selection -->
				
				<!-- Existing Profile Image -->
    			<div class="form-check">
      				<input class="form-check-input" type="radio" name="pf_radio" id="epf" onchange="togglePfButton()" checked>
      				<label class="form-check-label" for="epf">
        				Existing Profile Image
      				</label>
    			</div>
    			
    			<!-- New Profile Image -->
    			<div class="form-check">
      				<input class="form-check-input" type="radio" name="pf_radio" id="npf" onchange="togglePfButton()">
      				<label class="form-check-label" for="npf">
        				New Profile Image
      				</label>
    			</div>
    			
    			<!-- Default Profile Image -->
    			<div class="form-check">
      				<input class="form-check-input" type="radio" name="pf_radio" id="dpf" onchange="togglePfButton()">
      				<label class="form-check-label" for="dpf">
        				Default Profile Image
      				</label>
    			</div>
    			
                <?php if (!empty($pffiletypeError)): ?>
                	<span class="help-inline"><?php echo $pffiletypeError;?></span>
                <?php endif; ?>
    		</div>
    		
    		<!-- Hidden input to track which Profile Image radio button is selected. -->
    		<input class="form-control" type="hidden" name="hiddenpf" id="hiddenpf" value="epf">
    		
    		<!-- Display only when "npf" radio button is checked. -->
    		<div id="uploadPfButton" style="display: none;">
				<input class="form-control" name="profile_pic" id="profile_pic" type="file" accept="image/*" onchange="loadFile(event)">
				<small class="form-text text-muted">Please upload a PNG/JPEG file.</small>
			</div>
				
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
				
			<!-- Gender and DOB -->
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
					<label class="form-label" for="dob">DOB</label>
					<input class="form-control" name="dob" id="dob" type="date" placeholder="DOB" required value="<?php echo !empty($dob)?$dob:'';?>">
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
				<input class="form-control" name="email" id="email" type="text" placeholder="Email Address" required maxlength="30" value="<?php echo !empty($email)?$email:'';?>" autocomplete="on">
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
				
			<!-- Bank Company and Bank Account Number -->
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
                                $selected = (!empty($bank) && $bank == $row['Bank_Name']) ? 'selected' : '';
                                echo "<option value=".$row['Bank_ID']." $selected>".$row['Bank_Name']."</option>";
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

			<!-- Designation and Department -->
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
                                $selected = (!empty($designation) && $designation == $row['Designation']) ? 'selected' : '';
                                echo "<option value=".$row['Designation_ID']." $selected>".$row['Designation']."</option>";
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
                        $query = $pdo->prepare($selectRoleSQL, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                        $query->execute();
                        $data = $query->fetchAll();
                        foreach ($data as $row) {
                            $selected = (!empty($role) && $role == $row['Role_Name']) ? 'selected' : '';
                            echo "<option value=".$row['Role_ID']." $selected>".$row['Role_Name']."</option>";
                        }
                    ?>
            	</select>
			</div>

			<!-- Onboard Date and Offboard Date -->
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
			
			<!-- Contract -->
			<div class="mb-3">
				<label class="form-label">Contract</label>
				
				<!-- Radio buttons for Contract selection -->
				
				<!-- Existing Contract -->
    			<div class="form-check">
      				<input class="form-check-input" type="radio" name="c_radio" id="ec" onchange="toggleCButton()" checked>
      				<label class="form-check-label" for="ec">
        				Existing Contract
      				</label>
    			</div>
    			
    			<!-- New Contract -->
    			<div class="form-check">
      				<input class="form-check-input" type="radio" name="c_radio" id="nc" onchange="toggleCButton()">
      				<label class="form-check-label" for="nc">
        				New Contract
      				</label>
    			</div>
    			
    			<!-- Delete Contract -->
    			<div class="form-check">
      				<input class="form-check-input" type="radio" name="c_radio" id="dc" onchange="toggleCButton()">
      				<label class="form-check-label" for="dc">
        				Delete Contract
      				</label>
    			</div>
                <?php if (!empty($cfiletypeError)): ?>
                	<span class="help-inline"><?php echo $cfiletypeError;?></span>
                <?php endif; ?>
    		</div>
    		
    		<!-- Hidden input to track which Contract radio button is selected.  -->
    		<input class="form-control" type="hidden" name="hiddenc" id="hiddenc" value="ec">
    		
    		<!-- Display only when the "nc" radio button is checked. -->
    		<div id="uploadCButton" style="display: none;">
				<input class="form-control" name="contract" id="contract" type="file" accept=".pdf">
				<small class="form-text text-muted">Please upload a PDF file.</small>
			</div>
			
			<!-- Resume -->
			<div class="mb-3">
				<label class="form-label">Resume</label>
				
				<!-- Radio buttons for Resume selection -->
				
				<!-- Existing Resume -->
    			<div class="form-check">
      				<input class="form-check-input" type="radio" name="r_radio" id="er" onchange="toggleRButton()" checked>
      				<label class="form-check-label" for="er">
        				Existing Resume
      				</label>
    			</div>
    			
    			<!-- New Resume -->
    			<div class="form-check">
      				<input class="form-check-input" type="radio" name="r_radio" id="nr" onchange="toggleRButton()">
      				<label class="form-check-label" for="nr">
        				New Resume
      				</label>
    			</div>
    			
    			<!-- Delete Resume -->
    			<div class="form-check">
      				<input class="form-check-input" type="radio" name="r_radio" id="dr" onchange="toggleRButton()">
      				<label class="form-check-label" for="dr">
        				Delete Resume
      				</label>
    			</div>
    			
    			<?php if (!empty($rfiletypeError)): ?>
                	<span class="help-inline"><?php echo $rfiletypeError;?></span>
                <?php endif; ?>
    		</div>
    		
    		<!-- Hidden input to track which Resume radio button is selected. -->
    		<input class="form-control" type="hidden" name="hiddenr" id="hiddenr" value="er">
    		
    		<!-- Display only when the "nr" radio button is checked. -->
    		<div id="uploadRButton" style="display: none;">
				<input class="form-control" name="resume" id="resume" type="file" accept=".pdf">
				<small class="form-text text-muted">Please upload a PDF file.</small>
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

