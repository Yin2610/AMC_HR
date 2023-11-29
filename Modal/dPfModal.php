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
	

<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#dPfModal">
  Delete Profile Image
</button>
	
	<!-- Modal -->
	<div class="modal fade" id="dPfModal" tabindex="-1"
		aria-labelledby="dPfModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<form class="text-center"
				action="DeletePf.php?id=<?php echo $id?>" method="post"
				enctype="multipart/form-data">
					<div class="modal-header">
						<h1 class="modal-title fs-5" id="dPfModalLabel">Delete
							Profile Image</h1>
					</div>
					<div class="modal-body">
						<!-- Delete Profile Image -->
							<input type="hidden" name="dprofile_image" value="null">
					
					</div>
					<div class="modal-footer">
						<!-- Submit button -->
						<div class="form-actions">
							<button type="submit" class="btn btn-danger">Delete</button>
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
