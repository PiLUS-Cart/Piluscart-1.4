<?php

if (!defined('PILUS_SHOP'))
{
	header("Location: ../../../cabin/403.php");
	exit;
}


?>

<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">
				<?php if (isset($views['pageTitle'])) echo $views['pageTitle']; ?>
			</h1>
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->

	<?php 
	if (isset($views['errorMessage'])) { ?>

	<div class="alert alert-danger ">
		<h4>Error!</h4>
		<p>
			<?php echo $views['errorMessage']; ?>
			<button type="button" class="btn btn-danger"
				onClick="self.history.back();">Ulangi</button>
		</p>
	</div>
	
	<?php } else { ?>


	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<?php if (isset($views['pageTitle'])) echo $views['pageTitle']; ?>
				</div>
				<!-- #panel-heading -->

				<div class="panel-body">
					<div class="row">
						<div class="col-lg-6">

							<form method="post"
								action="index.php?module=themes&action=<?php echo $views['formAction']; ?>"
								role="form" enctype="multipart/form-data" autocomplete="off">

								<!-- Theme/Folder -->
								<div class="form-group">
									<label>*Pilih File Template</label> <input type="file"
										name="zip_file" required>
									<p class="help-block">Tipe File Template harus dalam bentuk
										(.zip)</p>
								</div>

								<!-- short description -->
								<div class="form-group">
									<label>*Deskripsi</label>
									<textarea class="form-control" name="description" id="pilus" rows="3"></textarea>
								</div>

								<input type="submit" class="btn btn-primary" name="saveTheme"
									value="Install" />

								<button type="button" class="btn btn-danger"
									onClick="self.history.back();">Batal</button>

							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php }?>
</div>
<!-- /#page-wrapper -->
