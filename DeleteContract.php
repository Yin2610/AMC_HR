<?php
require 'DBConnection.php';

$id = null;

if (! empty($_GET['id'])) {
    $id = $_REQUEST['id'];
}

if (null == $id) {
    header("Location: employee.php");
}

if (! empty($_POST)) {
    $pdo = DBConnection::connectToDB();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "UPDATE employee
         SET Contract = DEFAULT
         WHERE Employee_ID = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array(
        $id
    ));

    DBConnection::disconnect();

    header("Location: employee.php");
}

?>






