<?php
require 'DBConnection.php';
$id = null;

if (! empty($_GET['id'])) {
    $id = $_REQUEST['id'];
}

if (null == $id) {
    header("Location: index.php");
}

$pdo = DBConnection::connectToDB();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = 'SELECT *
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
$resume = $data['Resume'];
$contract = $data['Contract'];

DBConnection::disconnect();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<link
	href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
	rel="stylesheet">
<link href="css/pf.css" rel="css stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>

	<?php include('SideNav.php')?>
	<div class="container-fluid mt-4">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-5">
				<li class="breadcrumb-item"><a href="Home.php">Home</a></li>
				<li class="breadcrumb-item"><a href="#">View Employees</a></li>
				<li class="breadcrumb-item active" aria-current="page">Register Employee</li>
			</ol>
		</nav>
	</div>
	
	<div class="container row" id="profile">
		<a href="employee.php">Back</a>
		<div class="text-center col">
			<h1>Profile</h1>
			<img class="img-fluid" id="pf" src="<?php echo !empty($pf)?$pf:'';?>">
		</div>
		<div class="col">
			<p><strong class="pf">Name: </strong><?php echo !empty($name)?$name:'';?></p>
			<p><strong class="pf">Gender: </strong><?php echo !empty($gender)?$gender:'';?></p>
			<p><strong class="pf">DOB: </strong><?php echo !empty($dob)?$dob:'';?></p>
			<p><strong class="pf">NRIC: </strong><?php echo !empty($nric)?$nric:'';?></p>
			<p><strong class="pf">Mobile: </strong><?php echo !empty($mobile)?$mobile:'';?></p>
			<p><strong class="pf">Email: </strong><?php echo !empty($email)?$email:'';?></p>
			<p><strong class="pf">Address: </strong><?php echo !empty($address)?$address:'';?></p>
			<p><strong class="pf">Bank Company: </strong><?php echo !empty($bank)?$bank:'';?></p>
			<p><strong class="pf">Bank Account: </strong><?php echo !empty($bankacc)?$bankacc:'';?></p>
			<p><strong class="pf">Department: </strong><?php echo !empty($department)?$department:'';?></p>
			<p><strong class="pf">Designation: </strong><?php echo !empty($designation)?$designation:'';?></p>
			<p><strong class="pf">Role: </strong><?php echo !empty($role)?$role:'';?></p>
			<p><strong class="pf">Onboard Date: </strong><?php echo !empty($ondate)?$ondate:'';?></p>
			<p><strong class="pf">Offboard Date: </strong><?php echo !empty($offdate)?$offdate:'';?></p>
			<p><strong class="pf">Salary: </strong>$<?php echo !empty($salary)?$salary:'';?></p>
			<a href="<?php echo !empty($contract)?$contract:'';?>" target="_blank">Open Contract</a>
			<a href="<?php echo !empty($resume)?$resume:'';?>" target="_blank">Open Resume</a>
		</div>
	</div>
</body>
</html>

