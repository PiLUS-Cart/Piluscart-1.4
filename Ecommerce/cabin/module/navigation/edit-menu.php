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
								action="index.php?module=navigation&action=<?php echo $views['formAction']; ?>"
								role="form">
								<input type="hidden" name="menu_id"
									value="<?php echo htmlspecialchars($views['Menu'] -> getMenu_Id()); ?>" />

								<!-- Nama Modul-->
								<div class="form-group">
									<label>*Nama Menu</label> <input type="text" name="menu_label"
										class="form-control" placeholder="Menu Name"
										value="<?php  echo htmlspecialchars($views['Menu']-> getMenu_Label()); ?>">
								</div>

								<!-- Tautan -->
								<div class="form-group">
									<label>Tautan</label> <input type="text" name="menu_link"
										class="form-control" placeholder="Menu Link"
										value="<?php  echo htmlspecialchars($views['Menu'] -> getMenu_Link()); ?>">
								</div>


								<!--Role Level -->
								<div class="form-group">
									<?php  echo $views['menuRole']; ?>
								</div>

								<?php if (!empty($views['sortMenu'])) { ?>
								<div class="form-group">
									<label>Urutan</label> <input type="text" class="form-control"
										name="sort"
										value="<?php  echo htmlspecialchars($views['Menu'] -> getMenu_Order());  ?>" />
								</div>
								<?php }?>



								<input type="submit" class="btn btn-primary" name="saveMenu"
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
