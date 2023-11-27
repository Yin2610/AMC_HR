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
	<button type="button" class="btn btn-primary" data-bs-toggle="modal"
		data-bs-target="#uContractModal">Update Contract</button>
	<!-- Modal -->
	<div class="modal fade" id="uContractModal" tabindex="-1"
		aria-labelledby="uContractModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<form action="UpdateContract.php?id=<?php echo $id?>" method="post"
					enctype="multipart/form-data">
					<div class="modal-header">
						<h1 class="modal-title fs-5" id="uContractModalLabel">Update
							Contract</h1>
					</div>
					<div class="modal-body">
						<!-- Upload Contract -->
						<label class="form-label" for="contract">Contract</label> <input
							class="form-control" name="contract" id="contract" type="file"
							accept=".pdf" required> <small class="form-text text-muted">Please
							upload a PDF file.</small> <br>
                			<?php if (!empty($filetypeError)): ?>
                				<span class="help-inline"><?php echo $filetypeError;?></span>
                			<?php endif; ?>
                
					</div>
					<div class="modal-footer">
						<!-- Submit button -->
						<div class="form-actions">
							<button type="submit" class="btn btn-success">Update</button>
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
