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
				<a href="index.php?module=courier&action=newCourier"
					class="btn btn-outline btn-success"><i
					class="fa fa-plus-circle fa-fw"></i> Tambah jasa kirim </a>
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
					Courier
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
									<th>Logo</th>
									<th>Jasa Pengiriman</th>
									<th>Edit</th>
									<th>Hapus</th>

								</tr>
							</thead>
							<tbody>
								<?php 
								$no = $views['position'];

								foreach ( $views['couriers'] as $courier) {

                                       $courierName = htmlentities($courier -> getShippingName());
                                       $no++;
                                       ?>
								<tr>
									<td><?php echo htmlspecialchars($no); ?></td>
									<?php 
									//set up the image
									$image = '../content/uploads/images/'.$courier -> getShippingLogo();
									if (!is_file($image))
									{
										$image = '../content/uploads/images/nophoto.jpg';

									}

									$image_thumb = '../content/uploads/images/thumbs/thumb_'. $courier -> getShippingLogo();
									if (!is_file($image_thumb))
									{
										$image_thumb = '../content/uploads/images/thumbs/nophoto.jpg';

									}


									?>

									<td><a href="<?php echo $image; ?>"><img alt=""
											src="<?php echo $image_thumb; ?>"> </a></td>
									<td><?php echo $courierName; ?></td>

									<td><a
										href="index.php?module=courier&action=editCourier&courierId=<?php echo $courier -> getShippingId();  ?> "
										title="Edit" class="btn btn-primary"> <i
											class="fa fa-pencil fa-fw"></i> Edit
									</a>
									</td>
									<td><a
										href="javascript:deleteCourier('<?php echo $courier ->  getShippingId(); ?>', '<?php echo $courier -> getShippingLogo(); ?>')"
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
  function deleteBanner(id, courier)
  {
	  if (confirm("Apakah anda yakin ingin menghapus '" + courier + "'"))
	  {
	  	window.location.href = 'index.php?module=courier&action=deleteCourier&courierId=' + id + '&filename=' + courier;
	  }
  }
</script>
