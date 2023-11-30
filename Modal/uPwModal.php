<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<link
	href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
	rel="stylesheet">
<link href="css/form.css" rel="css stylesheet">
<script
	src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
	

<button type="button" class="btn" data-bs-toggle="modal"
		data-bs-target="#uPwModal" style="float: left;"><i class="fa-solid fa-key"></i> Change Password</button>
	<!-- Modal -->
	<div class="modal fade" id="uPwModal" tabindex="-1"
		aria-labelledby="uPwModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<form action="updatepassword.php?id=<?php echo $id?>" method="post">
					<div class="modal-header">
						<h1 class="modal-title fs-5" id="uPwModalLabel">Change Password</h1>
					</div>
					<div class="modal-body">
						<!-- New Password -->
						<label class="form-label" for="password">Password</label> <input
							class="form-control" name="password" type="text"
							placeholder="Password" id="password"
							value="<?php echo isset($password) ? $password:'';?>" required>
                <?php if (!empty($passwordError)): ?>
                	<span class="help-inline"><?php echo $passwordError;?></span>
                <?php endif;?>
				

				<!-- Confirm New Password -->

						<label class="form-label" for="cpassword">Confirm Password</label>
						<input class="form-control" name="cpassword" id="cpassword"
							type="text" placeholder="Confirm Password"
							value="<?php echo isset($cpassword) ? $cpassword:'';?>" required>
            <?php if (!empty($cpasswordError)): ?>
            	<span class="help-inline"><?php echo $cpasswordError;?></span>
            <?php endif;?>
                
					</div>
					<div class="modal-footer">
						<!-- Submit button -->
						<div class="form-actions">
							<button type="submit" class="btn btn-success">Change</button>
							<button type="button" class="btn btn-secondary"
								data-bs-dismiss="modal">Close</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

</body>
</html>
