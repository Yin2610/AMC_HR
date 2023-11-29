 
<?php
session_start();
//to connect the db_conn file to here 
include('DBConnection.php');
$pdo = DBConnection::connectToDB();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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
    $role = validate($_POST['role']);
    
    //if the username box is empty
     if (empty($uname)) {
        header("Location: index.php?error=User name is required");
        exit();
     }elseif (empty($pass)){
         header("Location: index.php?error=Password is required");
         exit();
     }else {
     
         $sql = "SELECT employee.Employee_ID, employee.Name, sensitive_info.Password, employee.Profile_Pic, employee.Role_ID, designation.Designation, role.Role_Name 
                FROM employee 
                INNER JOIN sensitive_info ON employee.Employee_ID = sensitive_info.Employee_ID
                INNER JOIN role ON employee.Role_ID = role.Role_ID
                INNER JOIN designation ON employee.Designation_ID = designation.Designation_ID
                WHERE BINARY employee.Name = :uname";
         
         $query = $pdo->prepare($sql);
         $query->bindParam(':uname', $uname, PDO::PARAM_STR);
         $query->execute();
         $data = $query->fetch(PDO::FETCH_ASSOC);
    
         if ($data && password_verify($pass, $data['Password'])&& $data['Role_ID'] ==  $role) {
            
             //Password is correct
             $_SESSION['Name'] = $data['Name'];
             $_SESSION['Role_Name'] = $data['Role_Name'];
             $_SESSION['Employee_ID'] = $data['Employee_ID'];
             $_SESSION['Designation'] = $data['Designation'];
             header("Location: Home.php");
             exit();
        
        } else {
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


</head>
<body style="background-color: #DBF9FC; display: flex; justify-content: center; align-items: center;">

	
	<form action="index.php" method="post" style="width:500px;border:2px solid #ccc;padding: 30px;background: #fff;border-radius:15px;">
	<?php
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
                            echo "<option value=" . $row['Role_ID'] . ">" . $row['Role_Name'] . "</option>";
                        }
                        ?>
            			</select>
					</div>
				</div>
	</form>
	
</body>
</html>