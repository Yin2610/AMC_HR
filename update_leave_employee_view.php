<?php
require 'DBConnection.php';

$id = 0;

if (!empty($_GET['leave_id'])) {
    $id = $_REQUEST['leave_id'];
}

if (!empty($_POST)) {
    // Keep track of post values
    $id = $_POST['leave_id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $reason = $_POST['reason'];

    // Update data
    $pdo = DBConnection::connectToDB();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "UPDATE leave_records SET start_date = ?, end_date = ?, reason = ? WHERE leave_id = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($start_date, $end_date, $reason, $id));
    DBConnection::disconnect();
    header("Location: index.php"); // Assuming you have an index page for leave records
}

// Retrieve leave details for the selected employee
if (isset($_GET["leave_id"])) {
    $id = $_GET["leave_id"];

    // Retrieve leave details from the database
    $pdo = DBConnection::connectToDB();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM leave_records WHERE leave_id = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($id));
    $row = $q->fetch(PDO::FETCH_ASSOC);

    // Display the form with current leave details
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <script src="js/bootstrap.min.js"></script>
    </head>
    <body>
        <div class="container">
            <div class="span10 offset1">
                <div class="row">
                    <h3>Update Leave</h3>
                </div>
                <form class="form-horizontal" action="update_leave.php" method="post">
                    <input type="hidden" name="leave_id" value="<?php echo $row['leave_id']; ?>">
                    Start Date: <input type="date" name="start_date" value="<?php echo $row['start_date']; ?>"><br>
                    End Date: <input type="date" name="end_date" value="<?php echo $row['end_date']; ?>"><br>
                    Reason: <textarea name="reason"><?php echo $row['reason']; ?></textarea><br>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a class="btn" href="index.php">Cancel</a>
                    </div>
                </form>
            </div>
        </div> <!-- /container -->
    </body>
    </html>
    <?php

    // Close the database connection
    DBConnection::disconnect();
}
?>

