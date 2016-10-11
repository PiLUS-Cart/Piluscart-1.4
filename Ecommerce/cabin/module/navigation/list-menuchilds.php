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
				<a href="index.php?module=navigation&action=newMenuChild&child_id=0"
					class="btn btn-outline btn-success"> <i
					class="fa fa-plus-circle fa-fw"></i> Tambah Sub Menu
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

	<?php } ?>


	<div class="row">

		<div class="col-lg-12">

			<div class="panel panel-default">

				<div class="panel-heading">
					<?php  echo htmlspecialchars($totalRows); ?>
					Sub Menu
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
									<th>Sub Menu</th>
									<th>Menu Utama</th>
									<th>Link Submenu</th>
									<th>Hak Akses</th>
									<th>Edit</th>
									<th>Hapus</th>
								</tr>
							</thead>
							<tbody>
								<?php 

								$no = $views['position'];
									
								foreach ($views['menuChilds'] as $menuChild):
									
								$no++;
								?>
								<tr>
									<td><?php echo htmlspecialchars($no); ?></td>
									<td><?php echo htmlspecialchars($menuChild -> getMenu_Child_Label());  ?>
									</td>
									<td><?php echo htmlspecialchars($menuChild -> getMenu_Parent()); ?>
									</td>
									<td><?php echo htmlspecialchars($menuChild -> getMenu_Child_Link()); ?>
									</td>
									<td><?php echo htmlspecialchars($menuChild -> getMenu_Child_Role()); ?>
									</td>
									<td><a
										href="index.php?module=navigation&action=editMenuChild&child_id=<?php echo $menuChild -> getMenu_Child_Id(); ?> "
										title="Edit" class="btn btn-primary"> <i
											class="fa fa-pencil fa-fw"></i> Edit
									</a>
									</td>

									<td><a
										href="javascript:deleteMenuChild('<?php echo $menuChild -> getMenu_Child_Id(); ?>', '<?php echo $menuChild -> getMenu_Child_Label(); ?>')"
										title="Hapus" class="btn btn-danger"> <i
											class="fa fa-trash-o fa-fw"></i> Hapus
									</a>
									</td>
								</tr>

								<?php endforeach; ?>
							</tbody>
						</table>
						<!-- /table-responsive -->
					</div>

                     <div class="pagination">
						<span> 
						<?php if ($totalRows > 10) echo $views['pageLink']; ?>
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
<!-- #page-wrapper -->
<script type="text/javascript">
  function deleteMenuChild(id, menuChild)
  {
	  if (confirm("Apakah anda yakin ingin menghapus '" + menuChild + "'"))
	  {
	  	window.location.href = 'index.php?module=navigation&action=deleteMenuChild&child_id=' + id;
	  }
  }
</script>
