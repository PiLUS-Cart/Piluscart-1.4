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
				<a href="index.php?module=files&action=newFile"
					class="btn btn-outline btn-success"> <i
					class="fa fa-plus-circle fa-fw"></i> Tambah Katalog Produk
				</a>
			</h1>

		</div>
		<!-- /.col-lg-12 -->


	</div>
	<!-- #row -->
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
					<?php echo htmlspecialchars($totalRows); ?>
					File
					<?php echo ( $totalRows != 1 ) ? 's' : '' ?>
					in total.
				</div>
				<!-- /.panel-heading -->

				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>#</th>
									<th>Judul</th>
									<th>Nama File</th>
									<th>Tgl.Posting</th>
									<th>Edit</th>
									<th>Hapus</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$no = $views['position'];
								foreach ($views['files'] as $file) {


                              $no++;
                              ?>
								<tr>
									<td><?php echo htmlspecialchars($no); ?></td>
									<td><?php echo htmlspecialchars($file -> getDownload_Title()); ?>
									</td>
									<td><?php echo $file -> getDownload_Filename(); ?>
									</td>
									<td><?php echo htmlspecialchars(tgl_Lokal($file -> getDate_Uploaded()));  ?>
									</td>

									<td><a
										href="index.php?module=files&action=editFile&fileId=<?php echo $file-> getDownload_Id(); ?> "
										title="Edit" class="btn btn-primary"> <i
											class="fa fa-pencil fa-fw"></i> Edit
									</a>
									</td>

									<td><a
										href="javascript:deleteFile('<?php echo $file ->  getDownload_Id(); ?>', '<?php echo $file -> getDownload_Filename(); ?>')"
										title="Hapus" class="btn btn-danger"> <i
											class="fa fa-trash-o fa-fw"></i> Hapus
									</a>
									</td>

								</tr>

								<?php } ?>
							</tbody>
						</table>
					</div>
					<!-- /table-responsive -->
					<div class="pagination">
						<span> <?php if ($totalRows > 10) echo $views['pageLink']; ?>
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
  function deleteFile(id, file)
  {
	  if (confirm("Apakah anda yakin ingin menghapus '" + file + "'"))
	  {
	  	window.location.href = 'index.php?module=files&action=deleteFile&fileId=' + id + '&filename=' + file;
	  }
  }
</script>
