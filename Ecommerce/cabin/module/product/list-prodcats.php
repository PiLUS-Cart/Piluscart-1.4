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
				<a href="index.php?module=products&action=newProdCat"
					title="tambah kategori" class="btn btn-outline btn-success"> <i
					class="fa fa-plus-circle fa-fw"></i> Tambah Kategori Produk
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
					Terdapat
					<?php  echo htmlspecialchars($totalRows) .  "\nKategori Produk"; ?>
					- dengan total produk
					<?php echo $views['countProducts']; ?>
					item
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>#</th>
									<th>Gambar</th>
									<th>Kategori</th>
									<th>Aktif</th>
									<th>Produk</th>
									<th>Edit</th>
									<th>Hapus</th>
								</tr>
							</thead>
							<tbody>
								<?php 

								$no = $views['position'];

								foreach ( $views['prodcats'] as $prodcat) :

								$prodcatName = htmlentities($prodcat-> getProdcat_Name());
								$prodcatStatus = htmlentities($prodcat -> getProdcat_Status());
								$no++;
								?>
								<tr>
									<td><?php echo htmlspecialchars($no); ?></td>
									<?php 
									//set up Image
									$image = '../content/uploads/products/' . $prodcat -> getProdcat_Image();
									if (!is_file($image))
									{
										$image = '../content/uploads/products/nophoto.jpg';
									}

									$image_thumb = '../content/uploads/products/thumbs/thumb_' . $prodcat -> getProdcat_Image();
									if (!is_file($image_thumb))
									{
										$image_thumb = '../content/uploads/products/thumbs/nophoto.jpg';
									}
									?>
									<td><a href="<?php echo $image; ?>"><img alt=""
											src="<?php echo $image_thumb; ?>"> </a>
									</td>
									<td><?php echo $prodcatName; ?></td>
									<td><?php echo htmlspecialchars($prodcat -> getProdcat_Status()); ?>
									</td>
									
									<td><a href="index.php?module=products&action=listProducts&catId=<?php echo (int)$prodcat -> getId() ?>"
										title="Upload Produk" class="btn btn-success"><i
											class="fa fa-folder-open fa-fw"></i> Buka</a></td>

									<td><a
										href="index.php?module=products&action=editProdCat&catId=<?php echo $prodcat-> getId(); ?>"
										title="Edit Kategori" class="btn btn-primary"> <i
											class="fa fa-pencil fa-fw"></i> Edit
									</a>
									</td>

									<td><a
										href="javascript:deleteProdCat('<?php echo (int) $prodcat-> getId(); ?>', '<?php echo $prodcat-> getProdcat_Name();  ?>', '<?php echo $prodcat -> getProdcat_Image();  ?>')"
										title="Hapus kategori" class="btn btn-danger"> <i
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
						<?php if ( $totalRows > 10) echo $views['pageLink']; ?>
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
  function deleteProdCat(id, prodcat, filename)
  {
	  if (confirm("Apakah anda yakin ingin menghapus '" + prodcat + "'"))
	  {
	  	window.location.href = 'index.php?module=products&action=deleteProdCat&catId=' + id + '&filename=' + filename;
	  }
  }
</script>
