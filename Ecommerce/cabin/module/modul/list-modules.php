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
				<a href="index.php?module=modules&action=newModule"
					class="btn btn-outline btn-success" title="Tambah Moduls"> <i
					class="fa fa-plus-circle fa-fw"></i> Tambah Modul
				</a> <a href="index.php?module=modules&action=installModule"
					title="install modul" class="btn btn-outline btn-success"> <i
					class="fa fa-cloud-upload fa-fw"></i> Install Modul
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
					<?php  echo htmlspecialchars($totalRows); ?>
					Module
					<?php  echo ( $totalRows != 1 ) ? 's' : ''?>
					in Total
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>#</th>
									<th>Nama Modul</th>
									<th>Diaktikan</th>
									<th>Level</th>
									<th>Edit</th>
									<th>Aktifkan</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$no = $views['position'];

								foreach ( $views['modules'] as $module ) {

                                       $moduleName = htmlentities($module -> getModule_Name());
                                       $no++;
                                       ?>
								<tr>
									<td><?php echo htmlspecialchars($no); ?></td>
									<td><?php echo $moduleName; ?></td>
									<td><?php echo htmlspecialchars($module -> getModule_Actived()); ?>
									</td>
									<td><?php echo htmlspecialchars($module -> getModule_RoleLevel());  ?>
									</td>

									<td><a
										href="index.php?module=modules&action=editModule&moduleId=<?php echo $module -> getModule_Id(); ?> "
										title="Edit" class="btn btn-primary"> <i
											class="fa fa-pencil fa-fw"></i> Edit
									</a>
									</td>

									<td><?php if ($module -> getModule_Actived() == 'N') { ?> <a
										href="javascript:activateModul('<?php echo $module -> getModule_Id(); ?>', '<?php echo $module -> getModule_Name(); ?>')"
										title="Aktifkan" class="btn btn-warning"> <i
											class="fa fa-check fa-fw"></i> Aktifkan
									</a> <?php } else { ?> <a
										href="javascript:deactivateModul('<?php echo $module -> getModule_Id(); ?>', '<?php echo $module -> getModule_Name(); ?>')"
										title="Aktifkan" class="btn btn-danger"> <i
											class="fa fa-times-circle fa-fw"></i> Non aktifkan
									</a> <?php } ?>
									</td>
								</tr>

								<?php } ?>
							</tbody>
						</table>
						<!-- /table-responsive -->
					</div>

					<div class="pagination">
						<span> <?php if ($totalRows > 10) echo $views['pageLink']; ?>
						</span>
					</div>

				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->
</div>
<!-- /#page-wrapper -->
<script type="text/javascript">
  function activateModul(id, module)
  {
	  if (confirm("Apakah anda yakin ingin mengaktifkan modul '" + module + "'"))
	  {
	  	window.location.href = 'index.php?module=modules&action=activateModul&moduleId=' + id;
	  }
  }

  function deactivateModul(id, module)
  {
	  if (confirm("Apakah anda yakin ingin non-aktifkan modul '" + module + "'"))
	  {
	  	window.location.href = 'index.php?module=modules&action=deactivateModul&moduleId=' + id;
	  }
  }
</script>
