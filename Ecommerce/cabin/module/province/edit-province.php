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

							<form method="post" action="index.php?module=provinces&action=<?php echo $views['formAction']; ?>"
								role="form">
								<input type="hidden" name="province_id" class="form-control"
									value="<?php if (isset($views['province'])) echo $views['province'] -> getProvinceId(); ?>">

								<!-- province name -->
								<div class="form-group">
									<label>*Nama Provinsi</label><input type="text" name="province_name" maxlength="100"
										class="form-control" placeholder="Nama kota atau kabupaten"
										value="<?php if (isset($views['province'])) echo $views['province'] -> getProvinceName(); ?>"
										required>
								</div>
                                            
								<input type="submit" class="btn btn-primary" name="saveProvince"
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

	<?php }?>
</div>
<!-- #page-wrapper -->