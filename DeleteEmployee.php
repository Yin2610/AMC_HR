<?php
    require 'DBConnection.php';
    $id = 0;
     
    if ( !empty($_GET['id'])) {
        $id = $_REQUEST['id'];
    }
     
    if ( !empty($_POST)) {
        // keep track post values
        $id = $_POST['id'];
         
        // delete data
        $pdo = DBConnection::connectToDB();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "DELETE FROM employee  WHERE Employee_ID = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($id));
        DBConnection::disconnect();
        header("Location: index.php");
         
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
                <li class="breadcrumb-item"><a href="#">View Employees</a></li>
                <li class="breadcrumb-item active" aria-current="page">Delete Employee</li>
            </ol>
        </nav>

        <div class="container">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="text-center">
                        <h3>Delete Employee</h3>
                    </div>

                    <form class="form-horizontal" action="delete.php" method="post">
                        <input type="text" name="id" value="<?php echo $id; ?>"/>
                        <p class="alert alert-danger">Are you sure you want to delete this employee?</p>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-danger">Yes</button>
                            <a class="btn btn-secondary" href="index.php">No</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
 