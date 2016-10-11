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
								action="index.php?module=banners&action=<?php echo $views['formAction']; ?>"
								role="form" enctype="multipart/form-data">
								<input type="hidden" name="banner_id"
									value="<?php  echo htmlspecialchars($views['Banner'] -> getBanner_Id()); ?> ">

								<!-- banner_title -->
								<div class="form-group">
									<label>*Label</label> <input type="text" name="banner_label"
										class="form-control" placeholder="Banner Title"
										value="<?php  echo htmlspecialchars($views['Banner'] -> getBanner_Label()); ?>"
										required>
								</div>

								<!-- banner_url -->
								<div class="form-group">
									<label>*Tautan</label> <input type="text" name="banner_url"
										class="form-control"
										placeholder="example: www.yoursite.com or yoursite.com"
										value="<?php  echo htmlspecialchars($views['Banner'] -> getBanner_Url()); ?>"
										required>
								</div>

								<!-- banner_image -->
								<?php if (!empty($views['imagePath'])) { ?>
								<div class="form-group">
									<label>*Gambar</label>
									<?php 
									$image = '../content/uploads/images/' . $views['Banner'] -> getBanner_Image();

									$image_thumb = '../content/uploads/images/thumbs/thumb_' . $views['Banner'] -> getBanner_Image();

									if (!is_file($image_thumb)) :

									$image_thumb = '../content/uploads/images/thumbs/nophoto.jpg';

									endif;

									if (is_file($image)) :

									?>
									<br> <a href="<?php echo $image; ?>"><img
										src="<?php  echo $image_thumb; ?>"> </a> <br> <label>ganti
										gambar</label> <input type="file" name="image"
										accept="image/jpeg" maxlength="200" />
									<p class="help-block">*Tipe gambar harus JPG/JPEG dan ukuran
										lebar maksimal: 150 px</p>

									<?php else : ?>

									<br> <img src="<?php echo $image_thumb; ?>"> <br> <label>ganti
										gambar</label> <input type="file" name="image"
										accept="image/jpeg" maxlength="200" />
									<p class="help-block">*Tipe gambar harus JPG/JPEG dan ukuran
										lebar maksimal: 150 px</p>

									<?php endif; ?>
								</div>
								<?php } else { ?>

								<div class="form-group">
									<label>*Upload Gambar</label> <input type="file" name="image"
										accept="image/jpeg" maxlength="200">
									<p class="help-block">*Tipe gambar harus JPG/JPEG dan ukuran
										lebar maksimal: 150 px</p>
								</div>
								<?php } ?>
								<input type="submit" class="btn btn-primary" name="saveBanner"
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
