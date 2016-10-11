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
					<?php if (isset($views['pageTitle'])) echo $views['pageTitle']; ?>
				</div>

				<div class="panel-body">
					<div class="row">
						<div class="col-lg-6">

							<form method="post"
								action="index.php?module=products&action=<?php echo $views['formAction']; ?>"
								role="form" enctype="multipart/form-data">
								<input type="hidden" name="prodcat_id"
									value="<?php if (isset($views['Prodcat'])) echo htmlspecialchars($views['Prodcat'] -> getId()); ?> " />

								<!-- Nama Kategori produk -->
								<div class="form-group">
									<label>*Kategori</label> <input type="text"
										class="form-control" placeholder="category name"
										name="cat_name"
										value="<?php if (isset($views['Prodcat'])) echo htmlspecialchars($views['Prodcat'] -> getProdcat_Name()); ?>"
										required>
								</div>

								<!-- description -->
								<div class="form-group">
									<label>*Keterangan</label>
									<textarea class="form-control" id="pilus" name="description" rows="3"
										required maxlength="500">
										<?php if (isset($views['Prodcat'])) echo $views['Prodcat'] -> getProdcat_Desc(); ?>
									</textarea>
								</div>

								<!-- actived -->
								<div class="form-group">
									<label>*Diaktifkan</label> <label class="radio-inline"> <input
										type="radio" name="active" id="optionsRadiosInline1" value="Y"
										<?php if (isset($views['activedProdcat']) && $views['activedProdcat'] == 'Y') echo 'checked="checked"';  ?>>
										Ya
									</label> <label class="radio-inline"> <input type="radio"
										name="active" id="optionsRadiosInline2" value="N"
										<?php if (isset($views['activedProdcat']) && $views['activedProdcat'] == 'N') echo 'checked="checked"';  ?>>
										Tidak
									</label>

								</div>

								<!-- Image product category -->
								<?php 
								if (!empty($views['ProdcatImg'])) {
								?>
								<div class="form-group">
									<label>*Photo/Gambar</label>
									<?php 
									$image = '../content/uploads/products/' . $views['Prodcat'] -> getProdcat_Image();

									$image_thumb = '../content/uploads/products/thumbs/thumb_' . $views['Prodcat'] -> getProdcat_Image();

									if (!is_file($image_thumb)) :

									$image_thumb = '../content/uploads/products/thumbs/nophoto.jpg';

									endif;

									if (is_file($image)) :

									?>
									<br> <a href="<?php echo $image; ?>"><img
										src="<?php  echo $image_thumb; ?>"> </a> <br> <label>ganti
										Photo/gambar</label> <input type="file" name="image"
										accept="image/jpeg" maxlength="255" />
									<p class="help-block">*Tipe gambar harus JPG/JPEG dan ukuran
										lebar maksimal: 400 px</p>
									<?php else : ?>

									<br> <img src="<?php echo $image_thumb; ?>"> <br> <label>ganti
										Photo/gambar</label> <input type="file" name="image"
										accept="image/jpeg" maxlength="255" />
									<p class="help-block">*Tipe gambar harus JPG/JPEG dan ukuran
										lebar maksimal: 400 px</p>
									<?php endif; ?>

								</div>
								<?php } else { ?>
								<div class="form-group">
									<label>*Photo/Gambar</label> <input type="file" name="image"
										accept="image/jpeg" maxlength="255">
									<p class="help-block">*Tipe gambar harus JPG/JPEG dan ukuran
										lebar maksimal: 400 px</p>
								</div>
								<?php } ?>
								<input type="submit" class="btn btn-primary" name="saveProdcat"
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
