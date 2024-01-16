<?php
include "DBConnection.php";
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
        $sql = "DELETE FROM payroll WHERE Payroll_ID = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($id));
        DBConnection::disconnect();
        header("Location: RetrievePayroll.php");
    }
?>