<?php
session_start();

include "DBConnection.php";
$id = null;
//checks the id is in the url and assigns the value to the id
if ( !empty($_GET['id'])) {
    $id = $_GET['id'];
    }
//checks the form submission status and deletes the record
    if ( !empty($_POST)) {
        $id = $_POST['id'];
        $pdo = DBConnection::connectToDB();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "DELETE FROM payroll WHERE Payroll_ID = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($id));
        DBConnection::disconnect();
        header("Location: RetrievePayroll.php");
    }
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
                <li class="breadcrumb-item"><a href="RetrievePayroll.php">Retrieve Payroll</a></li>
                <li class="breadcrumb-item active" aria-current="page">Delete Payroll</li>
            </ol>
        </nav>

        <div class="container">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="text-center">
                        <h3>Delete Payroll</h3>
                    </div>

                    <form class="form-horizontal" action="" method="post">
                    	<input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                        <p class="alert alert-danger">Are you sure you want to delete this payroll?</p>
                        <div class="form-actions">
                            	<button type="submit" class="btn btn-danger" >Yes</button>
                            <a class="btn btn-secondary" href="RetrievePayroll.php">No</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>