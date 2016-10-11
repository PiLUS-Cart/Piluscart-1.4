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
					ID :
					<?php if (isset($views['profilMeta'])) echo htmlspecialchars($views['profilMeta'] -> getAdmin_Id()); ?>
				</div>

				<div class="panel-body">
					<div class="row">
						<div class="col-lg-6">

							<form method="post"
								action="index.php?module=users&action=<?php echo $views['formAction']; ?>"
								role="form" enctype="multipart/form-data">
								<input type="hidden" name="admeta_id"
									value="<?php if (isset($views['myMetaId'])) echo (int)$views['myMetaId']; ?>" />


								<!-- avatar -->
								<?php if (!empty($views['imagePath'])) { ?>
								<div class="form-group">
									<label>*Foto</label>
									<?php 
									$image = '../content/uploads/images/'. $views['imagePath'];

									$image_thumb = '../content/uploads/images/thumbs/thumb_'.$views['imagePath'];

									if (!is_file($image_thumb)) :

									$image_thumb = "../content/uploads/images/thumbs/avatar.png";

									endif;

									if (is_file($image)) :

									?>
									<br> <a href="<?php echo $image; ?>"> <img
										src="<?php  echo $image_thumb; ?>">
									</a> <br> <label>ganti Foto</label> <input type="file"
										name="image" accept="image/jpeg" maxlength="200" />
									<p class="help-block">*Tipe Foto harus JPG/JPEG dan ukuran
										lebar maksimal: 150 px</p>

									<?php else : ?>

									<br> <img src="<?php echo $image_thumb; ?>"> <br> <label>ganti
										Foto</label> <input type="file" name="image"
										accept="image/jpeg" maxlength="200" />
									<p class="help-block">*Tipe Foto harus JPG/JPEG dan ukuran
										lebar maksimal: 150 px</p>

									<?php endif; ?>
								</div>

								<?php  } else { 

									$avatar = "../content/uploads/images/thumbs/avatar.png";
									?>
								<div class="form-group">

									<br> <img src="<?php echo $avatar; ?>"> <br> <label>*Upload
										Foto Baru</label> <input type="file" name="image"
										maxlength="200">
									<p class="help-block">*Tipe avatar harus JPG/JPEG</p>

								</div>

								<?php } ?>

								<!-- Tanggal Lahir -->
								<div class="form-group">
									<label>*Tanggal Lahir</label> <a href="javascript:"
										onClick="return showCalendar('tgl', 'dd-mm-y');"><img border=0
										src="../studio/kalender/ico_calendar.gif"> </a> <input
										type="text" class="form-control" name="tanggal" id="tgl"
										value="<?php if (isset($views['profilMeta'])) echo htmlspecialchars(tgl_eng_to_ind($views['profilMeta'] -> getAdmeta_Borndate()));  ?> ">
									<p class="help-block">contoh: 10-11-1945</p>
								</div>

								<!-- myGender -->
								<div class="form-group">
									<label>*Jenis Kelamin</label>
									<div class="checkbox">
										<label> <input name="gender" type="checkbox" value="L"
										<?php if (isset($views['myGender']) && $views['myGender'] == 'L') echo 'checked="checked"'; ?>>Laki-Laki
										</label>
									</div>
									<div class="checkbox">
										<label> <input name="gender" type="checkbox" value="P"
										<?php if (isset($views['myGender']) && $views['myGender'] == 'P') echo 'checked="checked"'; ?>>Perempuan
										</label>
									</div>
								</div>

								<!-- Phone -->
								<div class="form-group">
									<label>*No.Telepon/Hp</label> <input type="text" name="phone"
										class="form-control"
										value="<?php if (isset($views['profilMeta'])) echo htmlspecialchars($views['profilMeta'] -> getAdmeta_Phone()); ?>">
								</div>

								<!-- Alamat -->
								<div class="form-group">
									<label>Alamat</label> <input type="text" name="address"
										class="form-control" maxlength="255"
										value="<?php if (isset($views['profilMeta'])) echo htmlspecialchars($views['profilMeta'] -> getAdmeta_Address()); ?>">
								</div>

								<!-- Bio -->
								<div class="form-group">
									<label>Biografi</label>
									<textarea class="form-control" rows="3" id="pilus" name="bio" maxlength="1000" required>
										<?php if (isset($views['profilMeta'])) echo $views['profilMeta'] -> getAdmeta_Bio(); ?>
									</textarea>
								</div>

								<input type="submit" class="btn btn-primary" name="saveProfil"
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
