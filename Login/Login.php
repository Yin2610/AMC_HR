<html>
<head>
<title>welcome</title>
</head>
<body>  
<?php
session_start();
//to connect the db_conn file to here
include 'db_conn.php'; 

if (isset($_POST['uname'])&& isset($_POST['password'])){
    function validate($data){
        $data= trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
        
    }
    //giving a variable that the user typw in the box
    $uname = validate($_POST['uname']);
    $pass = validate($_POST['password']);
    //if the username box is empty
    if (empty($uname)) {
        header("Location: index.php?error=User name is required");
        exit();
    }elseif (empty($pass)){
        header("Location: index.php?error=Password is required");
        exit();
    }else {
        $sql = "SELECT * FROM employee where Name='$uname' AND Password='$pass'";
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result)=== 1) {
            $row = mysqli_fetch_assoc($result);
            //comparing the username and password is the same 
            if ($row['Name'] === $uname && $row['Password'] === $pass) {
              $_SESSION['Name'] =$row['Name'];
              $_SESSION['Employee_ID'] =$row['Employee_ID'];
              header("Location: home.php");
              exit();
            }else {
                header("Location: index.php?error=Incorrect User name or password");
                exit();;
            }
        }else {
            header("Location: index.php?error=Incorrect User name or password");
            exit();;
        }
    }
    
    
}else {
    header("Location: index.php");
    exit();
}
?>
</body>
</html>
