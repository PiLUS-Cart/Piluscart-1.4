<?php

if (!defined('PILUS_SHOP'))
{
	header("Location: ../../../cabin/403.php");
	exit;
}

$totalRows = isset($views['totalRows']) ? htmlspecialchars($views['totalRows']) : '';

?>

<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">

			<h1 class="page-header">
				<?php echo $views['pageTitle']; ?>
				<a href="index.php?module=themes&action=newTheme"
					title="tambah tema" class="btn btn-outline btn-success"> <i
					class="fa fa-plus-circle fa-fw"></i> Tambah Template
				</a> <a href="index.php?module=themes&action=installTheme"
					title="install tema" class="btn btn-outline btn-success"> <i
					class="fa fa-cloud-upload fa-fw"></i> Install Template
				</a>

			</h1>

		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->


	<?php 
   if (isset($views['errorMessage'])) { ?>

	<div class="alert alert-danger alert-dismissable">
		<button type="button" class="close" data-dismiss="alert"
			aria-hidden="true">&times;</button>
		<?php echo $views['errorMessage']; ?>
	</div>
 <?php 
   }
   
   if ( isset( $views['statusMessage'] ) ) { ?>

	<div class="alert alert-success alert-dismissable">
		<button type="button" class="close" data-dismiss="alert"
			aria-hidden="true">&times;</button>
		<?php echo $views['statusMessage']; ?>
	</div>

	<?php }?>


	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">

				<div class="panel-heading">
					<?php echo $views['totalRows']?>
					Theme
					<?php echo ( $views['totalRows'] != 1 ) ? 's' : '' ?>
					in total.
				</div>
				<!-- /.panel-heading -->

				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>#</th>
									<th>Template</th>
									<th>Desainer</th>
									<th>Folder</th>
									<th>*Status</th>
									<th>Edit</th>
									<th>Non-aktif/Aktif</th>
								</tr>
							</thead>
							<tbody>
								<?php 

								$no = $views['position'];

								foreach ($views['themes'] as $theme) :
								$no++;
								?>
								<tr>
									<td><?php echo htmlspecialchars($no); ?>
									</td>
									<td><?php echo htmlspecialchars($theme -> getTemplate_Name()); ?>
									</td>
									<td><?php echo htmlspecialchars($theme -> getTemplate_Designer()); ?>
									</td>
									<td><?php echo htmlspecialchars($theme -> getTemplate_Folder()); ?>
									</td>
									<td><?php echo htmlspecialchars($theme -> getTemplate_Status()); ?>
									</td>
								
									<td><a
										href="index.php?module=themes&action=editTheme&themeId=<?php echo $theme -> getId(); ?> "
										title="Edit" class="btn btn-primary"> <i
											class="fa fa-pencil fa-fw"></i> Edit
									</a>
									</td>

									<td>
									 <?php if ($theme -> getTemplate_Status() == 'Y') { ?>
									<a
										href="javascript:nonactivateTheme('<?php echo $theme -> getId(); ?>', '<?php echo $theme -> getTemplate_Name(); ?>')"
										title="Aktifkan Template" class="btn btn-warning"> <i
											class="fa fa-check fa-fw"></i> Non-Aktifkan
									</a>
									<?php } else { ?>
									  
									  <a
										href="javascript:activateTheme('<?php echo $theme -> getId(); ?>', '<?php echo $theme -> getTemplate_Name(); ?>')"
										title="Aktifkan Template" class="btn btn-success"> <i
											class="fa fa-check fa-fw"></i> Aktifkan
									</a>
									
									<?php } ?>
									</td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
						<p class="help-block">
							*Y = Tema website yang aktif <br> *N = Tema website yang non
							aktif
						</p>
					</div>
					<!-- /table-responsive -->
					<div class="pagination">
						<span> 
						<?php if ($totalRows > 10) echo $views['pageLink']; ?>
						</span>
					</div>

				</div>
			</div>
		</div>
	</div>
	<!-- /.row -->
</div>
<!-- /#page-wrapper -->

<script type="text/javascript">
  function activateTheme(id, theme)
  {
	  if (confirm("Apakah anda yakin ingin mengaktifkan '" + theme + "'"))
	  {
	  	window.location.href = 'index.php?module=themes&action=activateTheme&themeId=' + id;
	  }
  }
</script>
<script type="text/javascript">
  function nonactivateTheme(id, theme)
  {
	  if (confirm("Apakah anda yakin ingin menon-aktifkan '" + theme + "'"))
	  {
	  	window.location.href = 'index.php?module=themes&action=activateTheme&themeId=' + id;
	  }
  }
</script>
