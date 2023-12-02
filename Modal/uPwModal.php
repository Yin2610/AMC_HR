<button type="button" class="btn" data-bs-toggle="modal"
		data-bs-target="#uPwModal<?php echo $id?>" style="text-align: left;"><i class="fa-solid fa-key"></i> Change Password</button>
	<!-- Modal -->
	<div class="modal fade" id="uPwModal<?php echo $id?>" tabindex="-1"
		aria-labelledby="uPwModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<form action="UpdatePassword.php?id=<?php echo $id?>" method="post">
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


