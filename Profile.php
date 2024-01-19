<?php
//Establish connection to database
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
     * If the user is logged in,
     * retrieve the Employee_ID from the session and assign it to $id.
     */
    $id = $_SESSION['Employee_ID'];
}


//Retrieve employee's information from database
try{
    $sqlRetrieve = 'SELECT
                    employee.Profile_Pic, employee.Name, employee.Gender, employee.Date_Of_Birth,
                    employee.Phone_Num, employee.Email,
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
    
}catch (PDOException $e) {
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
<title>Profile</title>
</head>

<body>
	
	<!-- Side Navigation Bar -->
	<?php include 'SideNav.php'?>
	
	<div class="container-fluid mt-4">
	
		<!-- Breadcrumb -->
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-5">
				<li class="breadcrumb-item"><a href="Home.php">Home</a></li>
				<li class="breadcrumb-item active" aria-current="page">Profile</li>
			</ol>
		</nav>
	</div>
	
	
	<div class="container row" id="profile">
		<div class="text-center col">
			<h1>Profile</h1>
			
			<!-- Display the user's Profile Image -->
			<img class="img-fluid" id="pf" src="<?php echo !empty($pf)?$pf:'';?>" alt="The user's profile image.">
		</div>
		
		<!-- Display user's details -->
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
			
			<!-- Download button for contract and resume -->
			<?php if (!empty($contract)): ?>
    			<a href="<?php echo $contract; ?>" class='btn' style="border:solid;" target="_blank">
    				<i class='fa-solid fa-download'></i>
    			 Contract</a>
    		<?php else: ?>
    			<a class='btn' style="border:solid;" target="_blank" >
    				<i class='fa-solid fa-download'></i>
    			No contact available</a>
			<?php endif; ?>
			
			<?php if (!empty($resume)): ?>
    			<a href="<?php echo $resume; ?>" class='btn' style="border:solid;" target="_blank">
    				<i class='fa-solid fa-download'></i>
    			 Resume</a>
    		<?php else: ?>
    			<a class='btn' style="border:solid;" target="_blank" >
    				<i class='fa-solid fa-download'></i>
    			No resume available</a>
			<?php endif; ?>
		</div>
	</div>
</body>
</html>

