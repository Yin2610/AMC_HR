 
<?php
session_start();
//to connect the db_conn file to here 
include('DBConnection.php');
$pdo = DBConnection::connectToDB();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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
        
        $query = $pdo->prepare($sql);
        $query->execute();
        $data = $query->fetch(PDO::FETCH_ASSOC);
        
//         $result = mysqli_query($conn, $sql);
        
        if (count($data)== 1) {
//             $row = mysqli_fetch_assoc($result);
            //comparing the username and password is the same 
            if ($data['Name'] == $uname && $data['Password'] == $pass) {
              $_SESSION['Name'] =$data['Name'];
              $_SESSION['Employee_ID'] =$data['Employee_ID'];
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

<!DOCTYPE html>
<html>
<head>
<meta charset="ISO-8859-1">
<title>Login</title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<form action="Login.php" method="post">
		<h2>Login</h2>
		<?php if (isset($_GET['error'])){ ?>
		    <p class="error"><?php echo $_GET['error'];?></p>
		<?php }
		    ?>
		<label>User Name</label>
		<input type="text" name="uname" placeholder="Username"><br>
		<label>Password</label>
		<input type="password" name="password" placeholder="Password"><br>
		<button type="submit">Login</button>
	</form>
</body>
</html>