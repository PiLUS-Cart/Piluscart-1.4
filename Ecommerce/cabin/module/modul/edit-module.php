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

				<?php if (isset($views['id_module']) && $views['id_module'] != 0 ) { ?>

				<a
					href="javascript:deleteModule('<?php echo $views['id_module']; ?>', '<?php echo $views['Module'] -> getModule_Name(); ?>')"
					title="Uninstall Modul" class="btn btn-outline btn-danger"> <i
					class="fa fa-exclamation-circle fa-fw"></i> Uninstall
				</a>

				<?php } ?>

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
								action="index.php?module=modules&action=<?php echo $views['formAction']; ?>"
								role="form">
								<input type="hidden" name="module_id"
									value="<?php if (isset($views['id_module'])) echo $views['id_module']; ?>">
								<!-- Nama Modul-->
								<div class="form-group">
									<label>*Nama Modul</label> <input type="text"
										name="module_name" class="form-control"
										placeholder="Module Name"
										value="<?php  echo htmlspecialchars($views['Module']-> getModule_Name()); ?>">
								</div>
								<!-- Tautan -->
								<div class="form-group">
									<label>Tautan</label> <input type="text" name="module_link"
										class="form-control" placeholder="Module Link"
										value="<?php  echo htmlspecialchars($views['Module'] -> getModule_Link()); ?>">
								</div>
								<!-- deskripsi/keterangan -->
								<div class="form-group">
									<label>*Keterangan </label>
									<textarea class="form-control" name="description" rows="3" id="pilus" maxlength="500" >
										<?php if (isset($views['Module'])) echo $views['Module'] -> getModule_Description(); ?>
									</textarea>
								</div>

								<!--Role Level -->
								<div class="form-group">
									<?php if (isset($views['roleLevel'])) echo $views['roleLevel']; ?>
								</div>

								<!-- Urutan/Sort -->
								<?php if (!empty($views['sortModule'])) { ?>
								<div class="form-group">
									<label>Urutan</label> <input type="text" class="form-control"
										name="sort"
										value="<?php if (isset($views['Module'])) echo htmlspecialchars($views['Module'] -> getModule_Sort());  ?>" />
								</div>
								<?php }?>

								<input type="submit" class="btn btn-primary" name="saveModule"
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
<script type="text/javascript">
  function deleteModule(id, modul)
  {
	  if (confirm("Apakah anda yakin ingin menghapus modul '" + modul + "'"))
	  {
	  	window.location.href = 'index.php?module=modules&action=deleteModule&moduleId=' + id;
	  }
  }
</script>
