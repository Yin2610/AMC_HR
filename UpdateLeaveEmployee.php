<?php
require 'DBConnection.php';

// Check if the request is for updating leave details
if (!empty($_POST)) {
    // Update leave details
    $leave_id = $_POST['leave_id'];
    $from_date = $_POST['from_date'];
    $until_date = $_POST['until_date'];
    $notes = $_POST['notes'];

    // Update data in the database
    $pdo = DBConnection::connectToDB();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "UPDATE leave SET From_Date = ?, Until_Date = ?, Notes = ? WHERE leave_ID = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($from_date, $until_date, $notes, $leave_id));
    DBConnection::disconnect();

    // Redirect to employee view page
    header("Location: employee_view.php"); // Assuming you have an employee view page
    exit;
}

// Check if the request is for retrieving leave details
if (isset($_GET["leave_id"])) {
    $leave_id = $_GET["leave_id"];

    // Retrieve leave details from the database
    $pdo = DBConnection::connectToDB();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM leave WHERE leave_ID = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($leave_id));
    $row = $q->fetch(PDO::FETCH_ASSOC);

    // Display the form with current leave details
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

            <div class="container" id="form">
                <div class="text-center">
                    <h3>Update Leave</h3>
                </div>
                <form action="update_leave_employee_view.php?leave_id=<?php echo $leave_id; ?>" method="post">
                    <div class="mb-3">
                        <label for="from_date" class="form-label">From Date</label>
                        <input type="date" class="form-control" id="from_date" name="from_date" value="<?php echo $row['From_Date']; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="until_date" class="form-label">Until Date</label>
                        <input type="date" class="form-control" id="until_date" name="until_date" value="<?php echo $row['Until_Date']; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"><?php echo $row['Notes']; ?></textarea>
                    </div>

                    <div class="mb-3">
                        <button type="submit" class="btn btn-success">Update</button>
                        <a class="btn btn-secondary" href="employee_view.php">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </body>

    </html>
    <?php
}
?>
