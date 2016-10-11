<?php
if (!defined('PILUS_SHOP'))
{
	header("Location: ../../../cabini/403.php");
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
				<!-- .panel-heading -->

				<div class="panel-body">
					<div class="row">
						<div class="col-lg-6">
							<form name="option" onSubmit="return cekForm(this)" method="post"
								action="index.php?module=option&action=<?php echo $views['formAction']; ?>"
								role="form" enctype="multipart/form-data" autocomplete="off">
								<input type="hidden" name="option_id"
									value="<?php if (isset($views['Option'])) echo htmlspecialchars($views['Option'] -> getOption_Id()); ?>" />

								<!-- site name -->
								<div class="form-group">
									<label>*Nama Toko Online</label> <input type="text"
										name="site_name" class="form-control"
										placeholder="online shop name"
										value="<?php if (isset($views['Option'])) echo htmlspecialchars($views['Option'] -> getSite_Name()); ?>"
										required>
								</div>

								<!-- shop name -->
								<div class="form-group">
									<label>*Tagline</label> <input type="text" name="tagline"
										class="form-control" placeholder="tagline or slogan"
										value="<?php if (isset($views['Option'])) echo htmlspecialchars($views['Option'] -> getTagline()); ?>"
										required>
								</div>

								<!-- shop address -->
								<div class="form-group">
									<label>*Alamat Toko</label> <input type="text"
										name="shop_address" class="form-control" placeholder="address"
										value="<?php if (isset($views['Option'])) echo htmlspecialchars($views['Option'] -> getShopAddress()); ?>"
										required>
								</div>

								<!-- owner e-mail -->
								<div class="form-group">
									<label>*E-mail </label> <input type="text" name="email"
										class="form-control" placeholder="E-mail address"
										value="<?php if (isset($views['Option'])) echo htmlspecialchars($views['Option'] -> getOwnerEmail()); ?>"
										required>
								</div>

								<!-- No.Telpon -->
								<div class="form-group">
									<label>*No.Hp/Telepon</label> <input type="text" name="phone"
										class="form-control" placeholder="phone number"
										value="<?php if (isset($views['Option'])) echo htmlspecialchars($views['Option'] -> getNoTelpon()); ?>"
										required>
								</div>
								
								<!-- No.Rekening -->
								<div class="form-group">
									<label>*Nomor Rekening</label> <input type="text"
										name="no_rekening" class="form-control"
										placeholder="Nomor rekening bank"
										value="<?php if (isset($views['Option'])) echo htmlspecialchars($views['Option'] -> getNoRekening()); ?>"
										required>
								</div>

								<!-- Pin BBM -->
								<div class="form-group">
									<label>*Pin BB</label> <input type="text" name="pin_bb"
										class="form-control" placeholder="pin BBM"
										value="<?php if (isset($views['Option'])) echo htmlspecialchars($views['Option'] -> getPinBB()); ?>"
										required>
								</div>

                                <!-- No.faksimile -->
								<div class="form-group">
									<label>No.Fax</label> <input type="text" name="fax"
										class="form-control" placeholder="fax number"
										value="<?php if (isset($views['Option'])) echo htmlspecialchars($views['Option'] -> getNoFaximile()); ?>">
								</div>
								
								<!-- Instagram -->
								<div class="form-group">
									<label>Instagram</label> <input type="text" name="instagram"
										class="form-control" placeholder="instagram"
										value="<?php if (isset($views['Option'])) echo htmlspecialchars($views['Option'] -> getInstagramAccount()); ?>">
								</div>
								
								<!-- Twitter -->
								<div class="form-group">
									<label>Twitter</label> <input type="text" name="twitter"
										class="form-control" placeholder="twitter"
										value="<?php if (isset($views['Option'])) echo htmlspecialchars($views['Option'] -> getTwitterAccount()); ?>">
								</div>
								
								<!-- Facebook -->
								<div class="form-group">
									<label>Facebook</label> <input type="text" name="facebook"
										class="form-control" placeholder="facebook"
										value="<?php if (isset($views['Option'])) echo htmlspecialchars($views['Option'] -> getFacebookAccount()); ?>">
								</div>
								
								<!-- Meta deskripsi -->
								<div class="form-group">
									<label>Meta Deskripsi</label> <input type="text"
										name="meta_desc" class="form-control" maxlength="255"
										value="<?php if (isset($views['Option'])) echo htmlspecialchars($views['Option'] -> getMeta_Description()); ?>">
								</div>

								<!-- Meta Keyword -->
								<div class="form-group">
									<label>Meta Keyword</label> <input type="text" name="meta_key"
										class="form-control" maxlength="255"
										value="<?php if (isset($views['Option'])) echo htmlspecialchars($views['Option'] -> getMeta_Keywords()); ?>">
								</div>

								<!-- Favicon -->
								<?php if (!empty($views['favicon'])) { ?>
								<div class="form-group">
									<label>Gambar Favicon</label>
									<?php 
									$image = '../content/uploads/images/' . $views['Option'] -> getFavicon();

									if (!is_file($image)) :

									$image = '../content/uploads/images/thumbs/nophoto.jpg';

									endif;

									if (is_file($image)) :

									?>
									<br> <img src="<?php  echo $image; ?>"> <br> <label>ganti
										gambar</label> <input type="file" name="image"
										accept="image/png" />

									<?php else : ?>

									<br> <img src="<?php echo $image; ?>"> <br> <label>ganti
										gambar</label> <input type="file" name="image"
										accept="image/png" />

									<?php endif; ?>
								</div>
								<?php } else { ?>

								<div class="form-group">
									<label>Gambar Favicon</label> <input type="file" name="image"
										accept="image/png">
								</div>
								<?php } ?>
								<input type="submit" class="btn btn-primary" name="saveOption"
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
<!-- /#page-wrapper -->

<script type="text/javascript">
//JavaScript Document
function cekForm(form){
	
if (option.site_name.value == ""){
alert("Anda belum mengisikan Nama Toko Online");
option.site_name.focus();
return false;
}

if (option.tagline.value == ""){
alert("Anda belum mengisikan Tagline atau slogan");
option.tagline.focus();
return false;
}

if (option.shop_address.value == ""){
alert("Anda belum mengisikan Alamat Toko");
option.shop_address.focus();
return false;
}

if (option.email.value == ""){
alert("Anda belum mengisikan E-mail");
option.email.focus();
return false;
}

if (option.phone.value == ""){
alert("Anda belum mengisikan Nomor Hp atau Telpon");
option.phone.focus();
return false;
}

return true;
}</script>
