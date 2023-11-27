<?php
include "dbConnection.php";
$pdo = DBConnection::connectToDB();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = "SELECT * FROM employee";
$query = $pdo->prepare($sql);
$query->execute();
$data = $query->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Page</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <h2>Employees</h2>
<table class="table">
    <thead>
        <tr>
        <th>Name</th>
        <th>Gender</th>
        <th>Date of Birth</th>
        <th>Phone Number</th>
        <th>Email</th>
        <th>Address</th>
        <th>Onboard Date</th>
        <th>Offboard Date</th>
        <th>Profile Picture</th>
        <th>Resume</th>
        <th>Contract</th>
        
    </tr>
    </thead>
    <tbody> 
	
	<?php 
	foreach ($data as $row) {
	    echo "<tr><td>".$row['Name']."</td>";
	    echo "<td>".$row['Gender']."</td>";
	    echo "<td>".$row['Date_Of_Birth']."</td>";
	    echo "<td>".$row['Phone_Num']."</td>";
	    echo "<td>".$row['Email']."</td>";
	    echo "<td>".$row['Address']."</td>";
	    echo "<td>".$row['Onboard_Date']."</td>";
	    echo "<td>".$row['Offboard_Date']."</td>";
	    echo "<td>".$row['Profile_Pic']."</td>";
	    echo "<td>".$row['Resume']."</td>";
	    echo "<td>".$row['Contract']."</td>";
	    echo "<td><a class='btn btn-info' href='update.php?id=". $row['Employee_ID'] . "'>Edit</a></td>";
	    echo "<td><a class='btn btn-danger' href='delete.php?id=". $row['Employee_ID'] . "'>Delete</a></td></tr>";
	}
	
	?>


                      
    </tbody>
</table>
    </div> 
</body>
</html>
