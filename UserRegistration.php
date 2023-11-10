<?php 
include('DBConnection.php');
$pdo = DBConnection::connectToDB();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
if(!empty($_POST)) {
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
    
    $ProfilePicName = $_FILES['fProfilePic']['name'];
    $TempProfilePicName = $_FILES['fProfilePic']['tmp_name'];
    $ProfilePicFolder = "Employee_Info/Profile_Pics/" . $ProfilePicName;
    if(!move_uploaded_file($TempProfilePicName, $ProfilePicFolder)) {
        echo "<script>alert('Failed uploading profile pic.')</script>";   
    }
    
    $ResumeName = $_FILES['fResume']['name'];
    $TempResumeName = $_FILES['fResume']['tmp_name'];
    $ResumeFolder = "Employee_Info/Resumes/" . $ResumeName;
    if(!move_uploaded_file($TempResumeName, $ResumeFolder)) {
        echo "<script>alert('Failed uploading resume.')</script>";
    }
    
    $ContractName = $_FILES['fContract']['name'];
    $TempContractName = $_FILES['fContract']['tmp_name'];
    $ContractFolder = "Employee_Info/Contracts/" . $ContractName;
    if(!move_uploaded_file($TempContractName, $ContractFolder)) {
        echo "<script>alert('Failed uploading contract.')</script>";
    }
    
    $Password = $_POST['txtPassword'];
    $RoleID = $_POST['sRole'];
    $DesignationID = $_POST['sDesignation'];
    $BankID = $_POST['sBank'];

    if($valid) {
        try {
            $pdo->beginTransaction();
            $insertEmployeeSQL = "INSERT INTO employee VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $insertEmployeeStmt = $pdo->prepare($insertEmployeeSQL);
            $insertEmployeeStmt->execute(array($Name, $Gender, $DOB, $PhoneNum, $Email, $Address, $OnboardDate, $OffboardDate, $ProfilePicFolder, $ResumeFolder, $ContractFolder, $RoleID, $DesignationID, $BankID));
            
            $lastInsertedEmployeeID = $pdo->lastInsertId();
            
            echo "<script>alert(".$lastInsertedEmployeeID.")</script>";
            
            $insertSensitiveInfoSQL = "INSERT INTO sensitive_info VALUES (?, ?, ?, ?)";
            $insertSensitiveInfoStmt = $pdo->prepare($insertSensitiveInfoSQL);
            $insertSensitiveInfoStmt->execute(array($Password, $BankAcc, $ICNumber, $lastInsertedEmployeeID));
            
            $pdo->commit();
            echo "Employee registration successful";
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
    <link   href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
    <style>
        form 
        {
            display: flex;
        }
    </style>
</head>

<body>
	<div>
        <form action="UserRegistration.php" method="post" enctype="multipart/form-data">
            <div class="form-row">
            	<label for="txtName">Enter employee's name: </label>
                <input name="txtName" type="text" placeholder="Name" required>
                
                <br>
                <label for="rdoGender">Select employee's gender: </label>
                <input name="rdoGender" type="radio" id="rdoMale" value="Male" required>
                <label for="rdoMale">Male</label>
                <input name="rdoGender" type="radio" id="rdoFemale" value="Female" required>
                <label for="rdoFemale">Female</label>
                
                <br>
                <label for="dtDOB">Enter employee's date of birth: </label>
                <input name="dtDOB" type="date" placeholder="Date of birth" required>
                
            <br>
                <label for="txtPhoneNum">Enter employee's phone number: </label>
                <input name="txtPhoneNum" type="text" placeholder="Phone number" required>
                <br>
                <label for="txtEmail">Enter employee's email: </label>
                <input name="txtEmail" type="text" placeholder="Email" required>
                
                <br>
                <label for="txtAddress">Enter employee's address: </label>
                <input name="txtAddress" type="text" placeholder="Address" required>
            </div>
            
            
            <div class="form-row">
                <label for="dtOnBoard">Enter employee's onboarding date: </label>
                <input name="dtOnBoard" type="date" placeholder="Onboard date" required>
                
                <br>
                <label for="dtOffBoard">Enter employee's offboarding date: </label>
                <input name="dtOffBoard" type="date" placeholder="Offboard date">
                
                <br>
                <label for="txtBankAcc">Enter employee's bank account number: </label>
                <input name="txtBankAcc" type="text" placeholder="Bank account" required>
                
                <br>
                <label for="txtICNumber">Enter employee's IC number: </label>
                <input name="txtICNumber" type="text" placeholder="IC Number" required>
                
                <br>
                <label for="fProfilePic">Submit employee's profile picture: </label>
                <input name="fProfilePic" type="file" required>
                
                <br>
                <label for="fResume">Submit employee's resume: </label>
                <input name="fResume" type="file">
                
                <br>
                <label for="fContract">Submit employee's contract: </label>
                <input name="fContract" type="file">
                
                <br>
                <label for="txtPassword">Enter employee's password: </label>
                <input name="txtPassword" type="password" placeholder="Password" required>
            </div>
            
            <div class="form-row">
            <label for="sRole">Select employee's user role: </label>
            <select name="sRole" required>
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
            <br>
            
            <label for="sDesignation">Select employee's designation: </label>
            <select name="sDesignation" required>
            <?php 
                $selectDesignationSQL = "SELECT * FROM Designation";
                $query = $pdo->prepare($selectDesignationSQL, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                $query->execute();
                $data = $query->fetchAll();
                foreach ($data as $row) {
                    echo "<option value=".$row['Designation_ID'].">".$row['Designation']."</option>";
                }
            ?>
            </select>
            <br>
            
            <label for="sBank">Select employee's bank: </label>
            <select name="sBank" required>
    		<?php 
                $selectBankSQL = "SELECT * FROM Bank";
                $query = $pdo->prepare($selectBankSQL, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                $query->execute();
                $data = $query->fetchAll();
                foreach ($data as $row) {
                    echo "<option value=".$row['Bank_ID'].">".$row['Bank_Name']."</option>";
                }
            ?>
            </select>
            <br>
            
	            <button name="btnRegister" type="submit">Register</button>
            </div>
        </form>
    </div>
</body>
</html>