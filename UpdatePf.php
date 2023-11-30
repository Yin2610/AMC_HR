<?php
require 'DBConnection.php';

$id = null;

if (! empty($_GET['id'])) {
    $id = $_REQUEST['id'];
}

if (null == $id) {
    header("Location: employee.php");
}

$filetypeError = null;

if (! empty($_FILES)) {

    $pfnew = $_FILES['profile_pic'];
    $namepfnew = $pfnew['name'];

    $tnamepfnew = $pfnew['tmp_name'];
    $file_type = mime_content_type($tnamepfnew);
    if ($file_type == 'image/png' || $file_type == 'image/jpeg') {
        $uploadpfDir = 'Employee_Info/Profile_Pics/';

        $pathpfnew = $uploadpfDir . basename($namepfnew);
        $pathpfnew = substr($pathpfnew, 0, 50);

        move_uploaded_file($tnamepfnew, $pathpfnew);

        $pdo = DBConnection::connectToDB();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "UPDATE employee
         SET
         employee.Profile_Pic = ?
         WHERE employee.Employee_ID = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array(
            $pathpfnew,
            $id
        ));

        DBConnection::disconnect();

        header("Location: RetrieveEmployee.php");
    } else {
        $filetypeError = "Invalid file type. Please upload a PNG / JPEG file!";
    }
}

?>

