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

				<div class="panel-body">
					<div class="row">
						<div class="col-lg-6">

							<form method="post"
								action="index.php?module=postimage&action=<?php echo $views['formAction']; ?>"
								role="form" enctype="multipart/form-data">

								<input type="hidden" name="image_id"
									value="<?php  echo htmlspecialchars($views['postImages'] -> getId()); ?>">

								<!-- filename -->
								<div class="form-group">
									<label>Caption</label> <input type="text" class="form-control"
										name="caption" placeholder="caption"
										value="<?php  echo htmlspecialchars($views['postImages'] -> getImage_Caption()); ?>">
								</div>

								<!-- upload file -->
								<?php if (!empty($views['imagePath'])) { ?>
								<div class="form-group">
									<label>*Gambar</label>
									<?php 
									$image_thumb = '../content/uploads/images/thumbs/' . $views['imagePath'];
									if (!is_file($image_thumb)) :
									$image_thumb = '../content/uploads/images/thumbs/nophoto.jpg';
									endif;
									?>
									<br> <img alt="" src="<?php echo $image_thumb; ?>">
								</div>
								<?php } ?>
								<div class="form-group">
									<label>*Upload Gambar baru</label> <input type="file"
										name="image" maxlength="200">
									<p class="help-block">*Tipe gambar harus JPG/JPEG dengan
										size/ukuran maksimal 50KB</p>
								</div>


								<input type="submit" class="btn btn-primary" name="saveImage"
									value="Simpan" />

								<button type="button" class="btn btn-danger"
									onClick="self.history.back();">Batal</button>

							</form>
						</div>
						<!-- /.col-lg-6 (nested) -->
						<div class="col-lg-6"></div>
						<!-- /.col-lg-6 (nested) -->
					</div>
					<!-- /.row (nested) -->
				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<?php } ?>
	<!-- /.row -->
</div>
<!-- #Page-Wrapper -->
