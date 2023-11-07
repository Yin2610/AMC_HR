<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link   href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
</head>
 
<body>
    <div class="container">
            <div class="row">
                <h3>PHP CRUD Grid</h3>
            </div>
            <div class="row">
 
                <table class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>Name</th>
                          <th>Email Address</th>
                          <th>Mobile Number</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php
                       include 'DBConnection.php';
                       $pdo = DBConnection::connecttoDB();
                       $sql = 'SELECT * FROM employee ORDER BY Employee_ID DESC';
                       foreach ($pdo->query($sql) as $row) {
                                echo '<tr>';
                                echo '<td>'. $row['Name'] . '</td>';
                                echo '<td>'. $row['Gender'] . '</td>';
                                echo '<td>'. $row['Phone_Num'] . '</td>';
                                echo '<td width=250>';
                                echo '<a class="btn btn-success" href="UpdateEmployee.php?id='.$row['Employee_ID'].'">Update</a>';
                                echo '</td>';
                                echo '</tr>';
                       }
                       DBConnection::disconnect();
                      ?>
                      </tbody>
                </table>
        </div>
    </div> <!-- /container -->
  </body>
</html>
