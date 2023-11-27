 
<?php
session_start();
//to connect the db_conn file to here 
include('DBConnection.php');
$pdo = DBConnection::connectToDB();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//if (isset($_POST['uname'])&& isset($_POST['password'])){
if (isset($_POST['uname'])&& isset($_POST['password']) && isset($_POST['role'])){
    function validate($data){
        $data= trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
        
    }
    //giving a variable that the user typw in the box
    $uname = validate($_POST['uname']);
    $pass = validate($_POST['password']);
    //$hashPassword = password_hash($pass, PASSWORD_BCRYPT);
    $role = validate($_POST['role']);
    
    //if the username box is empty
     if (empty($uname)) {
        header("Location: index.php?error=User name is required");
        exit();
     }elseif (empty($pass)){
         header("Location: index.php?error=Password is required");
         exit();
     }else {
     
    //$sql = "SELECT employee.Employee_ID, employee.Name, sensitive_info.Password
        ////FROM employee
        //INNER JOIN sensitive_info ON employee.Employee_ID = sensitive_info.Sensitive_Info_ID
        //WHERE BINARY employee.Name = :uname AND BINARY sensitive_info.Password = :pass";
         $sql = "SELECT employee.Employee_ID, employee.Name, sensitive_info.Password, employee.Profile_Pic, employee.Role_ID, role.Role_Name 
                FROM employee
                INNER JOIN sensitive_info ON employee.Employee_ID = sensitive_info.Sensitive_Info_ID
                INNER JOIN role ON employee.Role_ID = role.Role_ID
                WHERE BINARY employee.Name = :uname";
               
         
//          $sql = "SELECT employee.Employee_ID, employee.Name, sensitive_info.Password,role.Role_Name 
//                  FROM employee
//                  INNER JOIN sensitive_info ON employee.Employee_ID = sensitive_info.Sensitive_Info_ID
//                  INNER JOIN role ON employee.Role_ID = role.Role_ID
//                  WHERE BINARY employee.Name = :uname AND BINARY sensitive_info.Password = :pass AND role.Role_ID = :role";
         
         $query = $pdo->prepare($sql);
         $query->bindParam(':uname', $uname, PDO::PARAM_STR);
//        $query->bindParam(':pass', $hashPassword, PDO::PARAM_STR);
//         $query->bindParam(':role', $role, PDO::PARAM_STR);
         $query->execute();
         $data = $query->fetch(PDO::FETCH_ASSOC);
         
         //var_dump($data['Role_Name']);
         //var_dump($role);
//        var_dump($data);
    
        
         //if ($data && password_verify($pass, $data['Password'])&&strcasecmp($data['role.Role_Name'], $role) == 0) {
         if ($data && password_verify($pass, $data['Password'])&& $data['Role_Name'] ===  $role) {
            
             //Password is correct
             $_SESSION['Name'] = $data['Name'];
 //            $_SESSION['Role_Name'] = $data['Role_Name'];
             $_SESSION['Role_Name'] = $data['Role_Name'];
             $_SESSION['Employee_ID'] = $data['Employee_ID'];
             //$_SESSION['Role_ID'] = $data['Role_ID']; 
//             var_dump($_SESSION);           
            //$_SESSION['Profile_pic'] = $data['Profile_pic'];
             header("Location: home.php");
             exit();
        
        } else {
            //if ($data && strcasecmp($data['Role_Name'], $role) != 0) {
            if ( $data['Role_ID'] !=  $role) {
                header("Location: index.php?error=Incorrect Role");
                
                exit();
            }
            
            header("Location: index.php?error=Incorrect User name or password");
            exit();
         }
}
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="ISO-8859-1">
<title>Login</title>
<!-- <link rel="stylesheet" type="text/css" href="style.css"> -->


</head>
<body style="background-color: #DBF9FC; display: flex; justify-content: center; align-items: center;">

	
	<form action="index.php" method="post" style="width:500px;border:2px solid #ccc;padding: 30px;background: #fff;border-radius:15px;">
	<?php
	//$password = "alyssa";
	//echo password_hash($password, PASSWORD_BCRYPT);
	?>
		 <h1 style="text-align:center;margin-bottom:40px;">Human Resource Management</h1>
		  <h2 style="color:black;text-align:center;margin-bottom:40px;">Login</h2>
		<?php if (isset($_GET['error'])){ ?>
		<div style="background-color: #F2DEDE">
		    <p class="error" style="color:black;"><?php echo $_GET['error'];?></p></div>
		<?php }
		    ?>
		    
		    <label style="color:#888;font-size:18px;padding:10px">User Name</label>
			<input type="text" name="uname" placeholder="Username" style="display: block; border:2px solid #ccc;width:95%;padding:10px;margin:10px auto;"><br>
			<label style="color:#888;font-size:18px;padding:10px">Password</label>
			<input type="password" name="password" placeholder="Password" style="display: block; border:2px solid #ccc;width:95%;padding:10px;margin:10px auto;"><br>
			<!--  <input type="text" name="role" placeholder="name" style="display: block; border:2px solid #ccc;width:95%;padding:10px;margin:10px auto;"><br>-->
			<button type="submit" style="float: right; background: #555;padding:10px 15px; color:#fff;border-radius:5px;margin-right:10px;">Login</button>
		     <div class="control-group">
					<label for="Role">role</label>
					<div class="controls">

						<select name="role" required>
						<?php
                       
                       $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        $selectRoleSQL = "SELECT * FROM role";
                        $query = $pdo->prepare($selectRoleSQL, array(
                            PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL
                        ));
                        $query->execute();
                        $data = $query->fetchAll();
                        foreach ($data as $row) {
                            echo "<option value=" . $row['Role_Name'] . ">" . $row['Role_Name'] . "</option>";
                        }
                        ?>
            			</select>
					</div>
				</div>
		<!-- 
		<label>User Name</label>
		<input type="text" name="uname" placeholder="Username"><br>
		<label>Password</label>
		<input type="password" name="password" placeholder="Password"><br>
		<button type="submit">Login</button>
		 -->
	</form>
	
</body>
</html>