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
				<a href="index.php?module=postcats&action=newPostCat&catId=0"
					class="btn btn-outline btn-success"> <i
					class="fa fa-plus-circle fa-fw"></i> Tambah Kategori Tulisan
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

	<?php }?>
	<?php 
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
					<?php  echo htmlspecialchars($totalRows) .  "\nKategori Tulisan"; ?>
				</div>
				<!-- /.panel-heading -->

				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>#</th>
									<th>Nama Kategori</th>
									<th>Deskripsi</th>
									<th>Diaktifkan</th>
									<th>Edit</th>
									<th>Hapus</th>

								</tr>
							</thead>
							<tbody>
								<?php 
								$no = $views['position'];
									
								foreach ( $views['postcats'] as $postcat) :
								$no++;
								?>
								<tr>
									<td><?php echo $no; ?></td>
									<td><?php echo htmlspecialchars($postcat -> getPostcat_Name()); ?>
									</td>
									<td><?php echo html_entity_decode($postcat -> getPostcat_Desc()); ?>
									</td>
									<td><?php echo htmlspecialchars($postcat -> getPostcat_Status()); ?>
									</td>

									<td><a
										href="index.php?module=postcats&action=editPostCat&catId=<?php echo $postcat-> getId(); ?> "
										title="Edit" class="btn btn-primary"> <i
											class="fa fa-pencil fa-fw"></i> Edit
									</a>
									</td>

									<td><a
										href="javascript:deletePostcat('<?php echo $postcat-> getId(); ?>', '<?php echo $postcat-> getPostcat_Name();  ?>')"
										title="Hapus" class="btn btn-danger"> <i
											class="fa fa-trash-o fa-fw"></i> Hapus
									</a>
									</td>

								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
					<!-- /table-responsive -->
					<div class="pagination">
						<span> 
						<?php if ( $totalRows > 10) echo $views['pageLink']; ?>
						</span>
					</div>

				</div>
			</div>
		</div>
	</div>
	<!-- /.row -->
</div>
<!-- #page-wrapper -->
<script type="text/javascript">
  function deletePostcat(id, postcat)
  {
	  if (confirm("Apakah anda yakin ingin menghapus kategori '" + postcat+ "' ?"))
	  {
	  	window.location.href = 'index.php?module=postcats&action=deletePostCat&catId=' + id;
	  }
  }
</script>