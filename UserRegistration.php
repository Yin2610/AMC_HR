<?php 
include('DBConnection.php');
if(!empty($_POST)) {
    $valid = true;
    
    $Name = $_POST['txtName'];
    $Gender = $_POST['rdoGender'];
    $DOB = $_POST['dob'];
    $PhoneNum = $_POST['txtPhoneNum'];
    $Email = $_POST['txtEmail'];
    $Address = $_POST['txtAddress'];
    $OnboardDate = $_POST['dtOnBoard'];
    $OffboardDate = $_POST['dtOffBoard'];
    $ICNumber = $_POST['txtICNumber'];
    $Password = $_POST['txtPassword'];
    if($valid) {
        $pdo = DBConnection::connectToDB();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO employee (Name, Gender, Date_Of_Birth, Phone_Num, Email, Address, Onboard_Date, OffBoard_Date, IC_Number, Password, Role_ID, Designation_ID) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $query = $pdo->prepare($sql);
        $query->execute(array($Name, $Gender, $DOB, $PhoneNum, $Email, $Address, $OnboardDate, $OffboardDate, $ICNumber, $Password, 1, 1));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link   href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
</head>

<body>
    <form action="UserRegistration.php" method="post" enctype="multipart/form-data">
        <input name="txtName" type="text" placeholder="Name">
        <input name="rdoGender" type="radio" id="rdoMale" value="Male">
        <label for="rdoMale">Male</label>
        <input name="rdoGender" type="radio" id="rdoFemale" value="Female">
        <label for="rdoFemale">Female</label>
        <input name="dob" type="date" placeholder="Date of birth">
        <input name="txtPhoneNum" type="text" placeholder="Phone number">
        <input name="txtEmail" type="text" placeholder="Email">
        <input name="txtAddress" type="text" placeholder="Address">
        <input name="dtOnBoard" type="date" placeholder="Onboard date">
        <input name="dtOffBoard" type="date" placeholder="Offboard date">
        <input name="txtICNumber" type="text" placeholder="IC Number">
        <input name="txtPassword" type="password" placeholder="Password">
        <button name="btnRegister" type="submit">Register</button>
    </form>
</body>
</html>