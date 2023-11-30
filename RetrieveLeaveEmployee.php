<?php
<<<<<<< HEAD
=======
require 'DBConnection.php';
>>>>>>> e550acb7e3a5dad2d489ccdbaa1d26d791314cca

// Start the session
session_start();

<<<<<<< HEAD
require 'DBConnection.php';



// Check if the user is logged in and if an employee_id is set in the session
if (!isset($_SESSION['Employee_ID'])) {
    // Redirect to the login page or handle unauthorized access
//     header("Location: login.php"); // Change 'login.php' to actual login page
=======
// Check if the user is logged in and if an employee_id is set in the session
if (!isset($_SESSION['employee_id'])) {
    // Redirect to the login page or handle unauthorized access
    header("Location: login.php"); // Change 'login.php' to actual login page
>>>>>>> e550acb7e3a5dad2d489ccdbaa1d26d791314cca
    exit();
}

// Retrieve the employee_id from the session
<<<<<<< HEAD
$employee_id = intval($_SESSION['Employee_ID']);
=======
$employee_id = $_SESSION['employee_id'];
>>>>>>> e550acb7e3a5dad2d489ccdbaa1d26d791314cca

// Retrieve leave records for the employee view
$pdo = DBConnection::connectToDB();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
<<<<<<< HEAD
$sql = "SELECT * FROM `leave` WHERE Submitted_By = ? ORDER BY Submission_Date DESC";
=======
$sql = "SELECT * FROM leave WHERE Summited_By = ? ORDER BY Submission_Date DESC";
>>>>>>> e550acb7e3a5dad2d489ccdbaa1d26d791314cca
$q = $pdo->prepare($sql);
$q->execute(array($employee_id));
$leave_records = $q->fetchAll(PDO::FETCH_ASSOC);
DBConnection::disconnect();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="bg-light">
    <?php include('SideNav.php')?>
    <div class="container-fluid mt-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-5">
                <li class="breadcrumb-item"><a href="Home.php">Home</a></li>
                <li class="breadcrumb-item"><a href="#">View Employees</a></li>
                <li class="breadcrumb-item active" aria-current="page">Register Employee</li>
            </ol>
        </nav>

        <div class="container">
            <div class="text-center">
                <h3>Leave Records</h3>
            </div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Leave Category</th>
                        <th>Leave Submission Date</th>
                        <th>From Date</th>
                        <th>Until Date</th>
                        <th>Status</th>
                        <th>Approval Date</th>
                        <th>Approved By</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($leave_records as $record): ?>
                        <tr>
<<<<<<< HEAD
                            <td><?php echo $record['Leave_Category']; ?></td>
=======
                            <td><?php echo $record['leave_Category']; ?></td>
>>>>>>> e550acb7e3a5dad2d489ccdbaa1d26d791314cca
                            <td><?php echo $record['Submission_Date']; ?></td>
                            <td><?php echo $record['From_Date']; ?></td>
                            <td><?php echo $record['Until_Date']; ?></td>
                            <td><?php echo $record['Status']; ?></td>
                            <td><?php echo $record['Approval_Date']; ?></td>
                            <td><?php echo $record['Approved_By']; ?></td>
                            <td>
<<<<<<< HEAD
                                <?php if ($record['Status'] !== 'Approved') { ?>
                                    <a href="UpdateLeaveEmployee.php?leave_id=<?php echo $record['Leave_ID']; ?>" class="btn btn-primary">Update Leave</a>
								<?php } ?>
=======
                                <?php if ($record['Status'] !== 'Approved'): ?>
                                    <a href="update_leave_employee_view.php?leave_id=<?php echo $record['leave_ID']; ?>" class="btn btn-primary">Update Leave</a>
                                <?php endif; ?>
>>>>>>> e550acb7e3a5dad2d489ccdbaa1d26d791314cca
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>
</body>

</html>
