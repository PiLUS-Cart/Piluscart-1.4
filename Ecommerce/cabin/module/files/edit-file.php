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
				<?php  echo $views['pageTitle']; ?>
			</h1>
		</div>
		<!-- /.col-lg-12 -->
	</div>
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
	
	<!-- /.row -->

	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<?php  echo $views['pageTitle']; ?>
				</div>
				<!-- .panel-heading -->

				<div class="panel-body">
					<div class="row">
						<div class="col-lg-6">
							<form method="post"
								action="index.php?module=files&action=<?php echo $views['formAction']; ?>"
								role="form" enctype="multipart/form-data">

								<input type="hidden" name="file_id"
									value="<?php echo htmlspecialchars($views['Files'] -> getDownload_Id()); ?>">
								<!-- title -->
								<div class="form-group">
									<label>*Judul</label> <input type="text" name="title"
										class="form-control" placeholder="Title"
										value="<?php echo htmlspecialchars($views['Files'] -> getDownload_Title()); ?>">
								</div>

								<!-- File Berkas -->
								<?php if (!empty($views['filePath'])) { ?>
								<div class="form-group">
									<label>File</label> <input type="text" class="form-control"
										value="<?php echo htmlspecialchars($views['Files'] -> getDownload_Filename()); ?>"
										disabled>
								</div>

								<?php } ?>
								<!-- Upload berkas -->
								<div class="form-group">
									<label>*Upload Berkas baru</label> <input type="file"
										name="fdoc">
								</div>

								<input type="submit" class="btn btn-primary" name="saveFile"
									value="Simpan" />

								<button type="button" class="btn btn-danger"
									onClick="self.history.back();">Batal</button>

							</form>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>

	<?php } ?>
</div>
<!-- #page-wrapper -->
