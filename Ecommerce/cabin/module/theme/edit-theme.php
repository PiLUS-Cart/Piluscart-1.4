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

				<?php if ( isset($views['templateId']) && $views['templateId'] != 0 ) { ?>

				<a
					href="javascript:deleteTheme('<?php echo $views['templateId']; ?>', '<?php echo $views['Theme'] -> getTemplate_Name(); ?>' )"
					title="Uninstall template" class="btn btn-outline btn-danger"> <i
					class="fa fa-exclamation-circle fa-fw"></i> Uninstall
				</a>

				<?php } ?>
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
								action="index.php?module=themes&action=<?php echo $views['formAction']; ?>"
								role="form">
								<input type="hidden" name="theme_id"
									value="<?php if (isset($views['templateId'])) echo $views['templateId']; ?>">

								<!-- Nama Tema -->
								<div class="form-group">
									<label>*Nama Template</label> <input type="text"
										name="theme_name" class="form-control"
										placeholder="Template's title"
										value="<?php if (isset($views['Theme'])) echo htmlspecialchars($views['Theme'] -> getTemplate_Name()); ?>"
										required>
								</div>

								<!-- designer -->
								<div class="form-group">
									<label>*Desainer</label> <input type="text" name="designer"
										class="form-control" placeholder="Designer's Name"
										value="<?php if (isset($views['Theme'])) echo htmlspecialchars($views['Theme'] -> getTemplate_Designer()); ?>"
										required>
								</div>

								<!-- Theme/Folder -->
								<div class="form-group">
									<label>*Folder</label> <input type="text" name="folder"
										class="form-control" placeholder="content/themes/namafolder"
										value="<?php if (isset($views['Theme'])) echo $views['Theme'] -> getTemplate_Folder(); ?>"
										required>
									<p class="help-block">
										*Isikan folder sesuai dengan format : <b>content/themes/namafolder</b>
									</p>
								</div>

								<!-- short description -->
								<div class="form-group">
									<label>*Deskripsi</label>
									<textarea class="form-control" name="description" id="pilus" rows="3">
										<?php echo $views['Theme'] -> getTemplate_Desc(); ?>
									</textarea>
								</div>


								<input type="submit" class="btn btn-primary" name="saveTheme"
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
<script type="text/javascript">
  function deleteTheme(id, theme)
  {
	  if (confirm("Apakah anda yakin ingin menghapus template '" + theme + "'"))
	  {
	  	window.location.href = 'index.php?module=themes&action=deleteTheme&themeId=' + id;
	  }
  }
</script>
