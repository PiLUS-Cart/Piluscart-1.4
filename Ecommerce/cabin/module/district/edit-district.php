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
								action="index.php?module=districts&action=<?php echo $views['formAction']; ?>"
								role="form">
								<input type="hidden" name="district_id" class="form-control"
									value="<?php if (isset($views['District'])) echo $views['District'] -> getDistrict_Id(); ?>">

								<!-- District name -->
								<div class="form-group">
									<label>*Nama Kota/Kabupaten</label><input type="text" name="district_name"
										class="form-control" placeholder="Nama kota atau kabupaten"
										value="<?php if (isset($views['District'])) echo $views['District'] -> getDistrict_Name(); ?>"
										required>
								</div>
                                
                                <!-- kabupaten / kota -->
                                <div class="form-group">
                                   <?php if (isset($views['province_Dropdown'])) echo $views['province_Dropdown']; ?>
                                </div>
								
								<!-- Jasa Pengiriman -->
								<div class="form-group">
									<?php if (isset($views['shipping'])) echo $views['shipping']; ?>
								</div>

                                <!-- #shipping cost -->
								<div class="form-group">
									<label>*Ongkos Kirim</label> <input type="text"
										name="ship_cost" class="form-control"
										placeholder="biaya pengiriman"
										value="<?php if (isset($views['District'])) echo $views['District'] -> getShipping_Cost(); ?> "
										required>
								</div>
                                
								<input type="submit" class="btn btn-primary" name="saveDistrict"
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