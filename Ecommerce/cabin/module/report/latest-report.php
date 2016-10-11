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
				
				<button type="button" class="btn btn-outline btn-success" 
				onclick="window.location.href='module/report/laporan-harian.php'">
				<i class="fa fa-print fa-fw"></i> Cetak Laporan</button>
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
					Jumlah yang terjual : <?php echo $totalRows; ?> item
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>#</th>
									<th>Faktur</th>
									<th>Nama Produk</th>
									<th>Qty</th>
									<th>Harga</th>
									<th>Sub Total</th>
									
								</tr>
							</thead>
							<tbody>
								<?php 
								  $no = 1;
								  
								  foreach ( $views['latestReports'] as $latestReport) :
								  
								   $quantityharga = rupiah($latestReport -> getOrderQuantity() * $latestReport -> getProductPrice());
								   $hargarp = rupiah($latestReport -> getProductPrice());
								   $faktur = $latestReport -> getFaktur();
								   
								   $total = $total + ($latestReport -> getOrderQuantity() * $latestReport -> getProductPrice());
								   $totqu = $totqu + $latestReport -> getOrderQuantity();
								   
								   $no++;
								?>
								  <tr>
								      <td><?php echo htmlspecialchars($no); ?></td>
								      <td><?php echo htmlspecialchars($latestReport -> getFaktur()); ?></td>
								      <td><?php echo htmlspecialchars($latestReport -> getProductName()); ?></td>
								      <td><?php echo htmlspecialchars($latestReport -> getOrderQuantity()); ?></td>
								      <td><?php echo htmlspecialchars($hargarp); ?></td>
								      <td><?php echo htmlspecialchars($quantityharga); ?></td>
								  </tr>
								<?php endforeach; ?>
							</tbody>
						</table>

					</div>
					<!-- /table-responsive -->

					<div class="pagination">
					
					 
					   Total keseluruhan : Rp. <?php echo rupiah($total); ?> <br>
					   Jumlah keseluruhan yang terjual : <?php echo htmlspecialchars($totqu);  ?> item
					
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
