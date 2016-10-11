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
				Produk :
				<?php echo $views['pageTitle']; ?>
				<a
					href="index.php?module=products&action=newProduct&catId=<?php echo $views['cat_id']; ?>&productId=0"
					title="upload produk" class="btn btn-outline btn-success"> <i
					class="fa fa-cloud-upload fa-fw"></i> Upload Produk
				</a>
				<button type="button" class="btn btn-outline btn-warning"
					onClick="self.history.back();">
					<i class="fa fa-arrow-circle-left fa-fw"></i> Kembali
				</button>
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
					Kategori Produk :
					<?php echo  $views['pageTitle']; ?>
					- Jumlah:
					<?php  echo htmlspecialchars($totalRows); ?>
					Item
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>#</th>
									<th>Nama Produk</th>
									<th>Berat(Kg)</th>
									<th>Harga(Rp)</th>
									<th>Diskon(%)</th>
									<th>Stok</th>
									<th>Tgl.Masuk</th>
									<th>Edit</th>
									<th>Hapus</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$no = $views['position'];
									
								foreach ( $views['products'] as $product ) :
									
								$productName = htmlentities($product -> getProduct_Name());
								$harga = htmlentities(idrFormat($product -> getProduct_Price()));
								$tanggal = htmlentities(tgl_Lokal($product -> getProduct_Submited()));

								$no++;
								?>
								<tr>
									<td><?php echo htmlspecialchars($no); ?></td>
									<td><?php echo htmlspecialchars($productName); ?></td>
									<td><?php echo htmlspecialchars($product -> getProduct_weight()); ?>
									</td>
									<td><?php echo htmlspecialchars($harga); ?>
									</td>
									<td><?php echo htmlspecialchars($product -> getProduct_Discount()); ?>
									</td>
									<td><?php echo htmlspecialchars($product -> getProduct_Stock()); ?>
									</td>
									<td><?php echo htmlspecialchars($tanggal); ?></td>

									<td><a
										href="index.php?module=products&action=editProduct&catId=<?php echo (int)$product -> getProduct_CatId(); ?>&amp;productId=<?php echo (int)$product -> getId(); ?>"
										title="Edit" class="btn btn-primary"> <i
											class="fa fa-pencil fa-fw"></i> Edit
									</a>
									</td>

									<td><a
										href="javascript:deleteProduct('<?php echo $product -> getId(); ?>', '<?php echo $product -> getProduct_CatId(); ?>', '<?php echo $productName; ?>', '<?php echo $product -> getProduct_Image(); ?>')"
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
						<span><?php if ($totalRows > 10) echo $views['pageLink']; ?></span>
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
  function deleteProduct(id, cat_id, product, filename)
  {
	  if (confirm("Apakah anda yakin ingin menghapus '" + product + "'?"))
	  {
	  	window.location.href = 'index.php?module=products&action=deleteProduct&catId='+ cat_id + '&productId=' + id + '&filename=' + filename;
	  }
  }
</script>
