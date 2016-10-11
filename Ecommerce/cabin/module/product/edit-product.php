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
								<input type="hidden" name="product_id"
									value="<?php if (isset($views['Product'])) echo $views['Product'] -> getId(); ?>">
								<!-- product category -->
								<div class="form-group">
									<?php if (isset($views['cat_Dropdown'])) echo $views['cat_Dropdown']; ?>
								</div>

								<!-- product name -->
								<div class="form-group">
									<label>*Nama Produk</label> <input type="text"
										class="form-control" placeholder="product's name" required
										name="product_name"
										value="<?php echo htmlspecialchars($views['Product'] -> getProduct_Name()); ?> ">
								</div>

								<!-- Berat Produk -->
								<div class="form-group">
									<label>Berat(KG)</label> <input type="text"
										class="form-control" placeholder="product's weight"
										name="weight"
										value="<?php  echo htmlspecialchars($views['Product'] -> getProduct_Weight()); ?> ">
									<p class="help-block"><b>Contoh: 2 ons ditulis 0.2, 1 Kg ditulis 1</b></p>
								</div>

								<!-- Harga -->
								<div class="form-group">
									<label>*Harga</label> <input type="text" class="form-control"
										placeholder="product's price" required name="price"
										value="<?php echo htmlspecialchars($views['Product'] -> getProduct_Price()); ?>">
									<p class="help-block">Contoh: 150000 - *ketikkan tanpa titik(.)
										dan koma(,)</p>
								</div>

								<!-- Diskon -->
								<div class="form-group">
									<label>Diskon(%)</label> <input type="text"
										class="form-control" placeholder="product's discount"
										name="discount"
										value="<?php  echo htmlspecialchars($views['Product'] -> getProduct_Discount()); ?> ">
									<p class="help-block">Contoh: 50</p>
								</div>

								<!-- Stok/Ketersediaan -->
								<div class="form-group">
									<label>*Stok</label> <input type="text" class="form-control"
										placeholder="product's stock" name="stock"
										value="<?php  echo htmlspecialchars($views['Product'] -> getProduct_Stock()); ?>"
										required>
								</div>

								<!-- description -->
								<div class="form-group">
									<label>*Deskripsi</label>
									<textarea class="form-control" id="pilus" name="description"
										rows="3">
										<?php  echo $views['Product'] -> getProduct_Description(); ?>
									</textarea>
								</div>

								<!-- photo/gambar -->
								<?php if (!empty($views['productImg'])) { ?>
								<div class="form-group">
									<label>*Photo/Gambar</label>
									<?php 
									$image = '../content/uploads/products/' . $views['productImg'];

									$image_thumb = '../content/uploads/products/thumbs/thumb' . $views['Product'] -> getProduct_Image();

									if (!is_file($image_thumb)) :

									$image_thumb = '../content/uploads/products/thumbs/nophoto.jpg';

									endif;

									if (is_file($image)) :

									?>
									<br> <img src="<?php  echo $image_thumb; ?>"> <br> <label>ganti
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
								<?php } else {?>
								<div class="form-group">
									<label>*Photo/Gambar</label> <input type="file" name="image"
										accept="image/jpeg" maxlength="255">
									<p class="help-block">*Tipe gambar harus JPG/JPEG dan ukuran
										lebar maksimal: 400 px</p>
								</div>
								<?php } ?>
								<input type="submit" class="btn btn-primary" name="saveProduct"
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