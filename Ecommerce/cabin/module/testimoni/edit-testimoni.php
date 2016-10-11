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
								action="index.php?module=testimoni&action=<?php echo $views['formAction']; ?>"
								role="form">
								<input type="hidden" name="testi_id"
									value="<?php if (isset($views['testi'])) echo htmlspecialchars($views['testi'] -> getTestimoni_Id()); ?>">
								<input type="hidden" name="customer_id"
									value="<?php if (isset($views['testi'])) echo htmlspecialchars($views['testi'] -> getCustomer_Id()); ?>">

								<!-- customer_name -->
								<div class="form-group">
									<label>Nama Kustomer</label> <input type="text"
										name="customer_name" class="form-control" placeholder=""
										value="<?php if (isset($views['testi'])) echo htmlspecialchars($views['testi'] -> getCustomer_Fullname());  ?>"
										required>
								</div>

								<!-- Testimoni -->
								<div class="form-group">
									<label>Testimoni</label>
									<textarea class="form-control" name="testi" id="pilus" rows="3">
										<?php if (isset($views['testi'])) echo $views['testi'] -> getTestimoni_Content(); ?>
									</textarea>
								</div>


								<!-- Actived -->
								<div class="form-group">
									<label>*Diaktifkan</label> <label class="radio-inline"> <input
										type="radio" name="active" id="optionsRadiosInline1" value="Y"
										<?php if (isset($views['activedTesti']) && $views['activedTesti'] == 'Y') echo 'checked="checked"';  ?>>
										Ya
									</label> <label class="radio-inline"> <input type="radio"
										name="active" id="optionsRadiosInline2" value="N"
										<?php if (isset($views['activedTesti']) && $views['activedTesti'] == 'N') echo 'checked="checked"';  ?>>
										Tidak
									</label>
								</div>


								<input type="submit" class="btn btn-primary" name="saveTesti"
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
