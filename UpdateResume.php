

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

    $resumenew = $_FILES['resume'];
    $nameresumenew = $resumenew['name'];
    
        $tnameresumenew = $resumenew['tmp_name'];
        $file_type = mime_content_type($tnameresumenew);
        if ($file_type == 'application/pdf') {

        $uploadresumeDir = 'Employee_Info/Resumes/';
        
        
        $pathresumenew = $uploadresumeDir . basename($nameresumenew);
        $pathresumenew = substr($pathresumenew, 0, 50);
      
        move_uploaded_file($tnameresumenew, $pathresumenew);
        

        $pdo = DBConnection::connectToDB();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "UPDATE employee
         SET employee.Resume = ?
         WHERE employee.Employee_ID = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array(
            $pathresumenew,
            $id
        ));

        DBConnection::disconnect();

        header("Location: employee.php");
    
        } 
else{
    $filetypeError = "Invalid file type. Please upload a PDF file!";
}}
?>







