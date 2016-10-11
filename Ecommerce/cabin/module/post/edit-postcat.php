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
								action="index.php?module=postcats&action=<?php echo $views['formAction']; ?>"
								role="form">
								<input type="hidden" name="catId"
									value="<?php echo htmlspecialchars($views['postcat'] -> getId()); ?>">
								<!-- postCat_name -->
								<div class="form-group">
									<label>*Nama Kategori</label> <input type="text"
										name="catTitle" class="form-control"
										value="<?php echo htmlspecialchars($views['postcat'] -> getPostcat_Name()); ?>">
								</div>

								<!-- description -->
								<div class="form-group">
									<label>*Deskripsi</label>
									<textarea class="form-control" name="postDesc" id="pilus" rows="3"
										maxlength="500">
										<?php if (isset($views['postcat'])) echo $views['postcat'] -> getPostcat_Desc(); ?>
									</textarea>
								</div>

								<!-- Actived -->
								<div class="form-group">
									<label>*Diaktifkan</label> <label class="radio-inline"> <input
										type="radio" name="active" id="optionsRadiosInline1" value="Y"
										<?php if (isset($views['activedPostcat']) && $views['activedPostcat'] == 'Y') echo 'checked="checked"'; ?>>
										Ya
									</label> <label class="radio-inline"> <input type="radio"
										name="active" id="optionsRadiosInline1" value="N"
										<?php if (isset($views['activedPostcat']) && $views['activedPostcat'] == 'N') echo 'checked="checked"'; ?>>
										Tidak
									</label>
								</div>
								
								<input type="submit" class="btn btn-primary" name="saveCat"
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
<!-- /#page-wrapper -->
