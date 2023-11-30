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

    $passwordError = null;
    $cpasswordError = null;

    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];

    if (strpos($password, ' ') !== false) {
        $passwordError = 'Password cannot contain spaces';
    } else {
        if ($password === $cpassword) {

            $epassword = password_hash($password, PASSWORD_BCRYPT);

            $pdo = DBConnection::connectToDB();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "UPDATE sensitive_info
                SET sensitive_info.Password = ?
                WHERE sensitive_info.Employee_ID = ?";
            $q = $pdo->prepare($sql);
            $q->execute(array(
                $epassword,
                $id
            ));

            DBConnection::disconnect();

            header("Location: RetrieveEmployee.php");
        } else {
            $cpasswordError = "Password does not match!";
        }
    }
}

?>

