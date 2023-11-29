<?php 
include('DBConnection.php');

session_start();

if(!isset($_SESSION['Employee_ID']) || $_SESSION['Employee_ID'] == '') {
    echo "<script>alert('Please login first.')</script>";
    header("Location: index.php");
}

if($_SESSION['Role_Name'] != 'Administrator' && $_SESSION['Role_Name'] != 'Department Head') {
    echo "You don't have permission to view this page.";
    exit();
}

$pdo = DBConnection::connectToDB();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if(isset($_POST['btnRegister'])) {
    $valid = true;
    
    $Name = $_POST['txtName'];
    $Gender = $_POST['rdoGender'];
    $DOB = $_POST['dtDOB'];
    $PhoneNum = $_POST['txtPhoneNum'];
    $Email = $_POST['txtEmail'];
    $Address = $_POST['txtAddress'];
    $OnboardDate = $_POST['dtOnBoard'];
    $OffboardDate = $_POST['dtOffBoard'];
    $BankAcc = $_POST['txtBankAcc']; 
    $ICNumber = $_POST['txtICNumber'];
    
    if(isset($_FILES['fProfilePic']['name'])) {
        $ProfilePicName = $_FILES['fProfilePic']['name'];
        $TempProfilePicName = $_FILES['fProfilePic']['tmp_name'];
        $ProfilePicFolder = "Employee_Info/Profile_Pics/" . $ProfilePicName;
        if(!move_uploaded_file($TempProfilePicName, $ProfilePicFolder)) {
            echo "<script>alert('Failed uploading profile pic.')</script>";
        }
    }
    
    if(isset($_FILES['fResume']['name'])) {
        $ResumeName = $_FILES['fResume']['name'];
        $TempResumeName = $_FILES['fResume']['tmp_name'];
        $ResumeFolder = "Employee_Info/Resumes/" . $ResumeName;
        if(!move_uploaded_file($TempResumeName, $ResumeFolder)) {
            echo "<script>alert('Failed uploading resume.')</script>";
        }
    }
    
    if(isset($_FILES['fContract']['name'])) {
        $ContractName = $_FILES['fContract']['name'];
        $TempContractName = $_FILES['fContract']['tmp_name'];
        $ContractFolder = "Employee_Info/Contracts/" . $ContractName;
        
        if(!move_uploaded_file($TempContractName, $ContractFolder)) {
            echo "<script>alert('Failed uploading contract.')</script>";
        }
    }
    
    $Password = $_POST['txtPassword'];
    
    $HashPassword = password_hash($Password, PASSWORD_BCRYPT);
    
    $RoleID = $_POST['sRole'];
    $DesignationID = $_POST['sDesignation'];
    $BankID = $_POST['sBank'];

    // insert employee data
    if($valid) {
        try {
            $insertEmployeeSQL = "INSERT INTO employee (Name, Gender, Date_Of_Birth, Phone_Num, Email, Address, Onboard_Date, Offboard_Date, Profile_Pic, Resume, Contract, Role_ID, Designation_ID, Bank_ID) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $insertEmployeeStmt = $pdo->prepare($insertEmployeeSQL);
            $insertEmployeeStmt->execute(array($Name, $Gender, $DOB, $PhoneNum, $Email, $Address, $OnboardDate, $OffboardDate, $ProfilePicFolder, $ResumeFolder, $ContractFolder, $RoleID, $DesignationID, $BankID));
            
            $lastInsertedEmployeeID = $pdo->lastInsertId();
            
            $insertSensitiveInfoSQL = "INSERT INTO sensitive_info (Password, Bank_Account, IC_Number, Employee_ID) VALUES (?, ?, ?, ?)";
            $insertSensitiveInfoStmt = $pdo->prepare($insertSensitiveInfoSQL);
            $insertSensitiveInfoStmt->execute(array($HashPassword, $BankAcc, $ICNumber, $lastInsertedEmployeeID));
            
            echo "<script>alert('Employee registration successful')</script>";
        }
        catch (PDOException $e) {
            $pdo->rollBack();
            echo "Error:" .$e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
    <style>
        table td {
            padding-right: 20px;
            padding-top: 15px;
        }
    </style>
    <script>
		$(document).ready(() => {
		alert('x');
			$("#fProfilePic").change(function() {
			alert('y');
				const file = this.files[0];
                    if (file) {
                        let reader = new FileReader();
                        reader.onload = function (event) {
                            $("#imgProfile")
                              .attr("src", event.target.result);
                        };
                        reader.readAsDataURL(file);
                    }
			});
		});
	</script> 
</head>

<body class='bg-light'>
	<?php include('SideNav.php')?>
	<div class="container-fluid mt-4">
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
        		<div class="card p-3" style="width:500px; height:530px">
        			<table>
        				<tr>
        					<td colspan="2" style="text-align:center"><img src="Website_Images/Default_pp.png" id="imgProfile" width="100px" height="100px"></td>
        				</tr>
        				<tr>
        					<td><label for="fProfilePic">Profile picture:</label></td>
        					<td><input name="fProfilePic" id="fProfilePic" class="form-control-sm border rounded" type="file" required></td>
        				</tr>
        				<tr>
        					<td><label for="txtName">Name: </label></td>
                    		<td><input name="txtName" class="form-control-sm border rounded" type="text" required></td>
                        </tr>
                        <tr>
                        	<td><label for="rdoGender">Gender: </label></td>
                        	<td>
                        		<input name="rdoGender" type="radio" id="rdoMale" value="Male" required>
                        		<label for="rdoMale">Male</label>
                                <input name="rdoGender" type="radio" id="rdoFemale" value="Female" required>
                                <label for="rdoFemale">Female</label>
                        	</td>
                        </tr>
                        <tr>
                        	<td><label for="dtDOB">Date of birth: </label></td>
                        	<td><input name="dtDOB" class="form-control-sm border rounded" type="date" required></td>
                        </tr>
                        <tr>
                        	<td><label for="txtPhoneNum">Phone number: </label></td>
                        	<td><input name="txtPhoneNum" class="form-control-sm border rounded" type="text" required></td>
                        </tr>
                        <tr>
                        	<td><label for="txtEmail">Email: </label></td>
                        	<td><input name="txtEmail" class="form-control-sm border rounded" type="email" required></td>
                        </tr>
                        <tr>
                        	<td><label for="txtAddress">Address: </label></td>
                        	<td><input name="txtAddress" class="form-control-sm border rounded" type="text" required></td>
                        </tr>
                        <tr>
                        	<td><label for="txtICNumber">IC number: </label></td>
                        	<td><input name="txtICNumber" class="form-control-sm border rounded" type="text" required></td>
                        </tr>
                    </table>
                    </div>
            	</div>
            	<div class="col-md-5">
            	<div class="card p-3" style="width:500px; height:530px">
            		<table>
            			<tr>
                        	<td><label for="dtOnBoard">Onboarding date: </label></td>
                        	<td><input name="dtOnBoard" class="form-control-sm border rounded" type="date" placeholder="Onboard date" required></td>
                        </tr>
                        <tr>
                        	<td><label for="dtOffBoard">Offboarding date: </label></td>
                        	<td><input name="dtOffBoard" class="form-control-sm border rounded" type="date" placeholder="Offboard date"></td>
                        </tr>
            			<tr>
            				<td><label for="sBank">Bank: </label></td>
            				<td><select name="sBank" class="form-control-sm border rounded" required>
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
                        	<td><input name="txtBankAcc" class="form-control-sm border rounded" type="text" required></td>
                        </tr>
                        <tr>
                        	<td><label for="fResume">Resume: </label></td>
                        	<td><input name="fResume" class="form-control-sm border rounded" type="file"></td>
                        </tr>
                        <tr>
                        	<td><label for="fContract">Contract: </label></td>
                        	<td><input name="fContract" class="form-control-sm border rounded" type="file"></td>
                        </tr>
                        <tr>
                        	<td><label for="txtPassword">Password: </label></td>
                        	<td><input name="txtPassword" class="form-control-sm border rounded" type="password" placeholder="Password" required></td>
                        </tr>
                        <tr>
                        	<td><label for="sRole">User role: </label></td>
                        	<td>
                        		<select name="sRole" class="form-control-sm border rounded" required>
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
                        	<td><select name="sDesignation" class="form-control-sm border rounded" required>
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
                        	<td colspan="2" style="text-align:center"><input name="btnRegister" class="btn btn-outline-info" type="submit" value="Register"></td>
                        </tr>
            		</table>
            		</div>
            	</div>
        	</div>
        </form>
        </div> 
</body>
</html>