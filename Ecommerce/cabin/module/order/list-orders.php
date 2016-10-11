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
					Order
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
									<th>Nama Kustomer</th>
									<th>Status</th>
									<th>Tgl.Order</th>
									<th>Jam</th>
									<th>Detail</th>

								</tr>
							</thead>
							<tbody>
								<?php 

								$no = $views['position'];
									
								foreach ( $views['orders'] as $Order) :
									
								$customer_name = htmlentities($Order -> getCustomerFullname());
								$no++;
								?>
								<tr>

									<td><?php echo htmlspecialchars($no); ?></td>
									<td><?php echo htmlspecialchars($customer_name); ?></td>
									<td><?php echo htmlspecialchars($Order -> getOrderStatus()); ?></td>
									<td><?php echo htmlspecialchars(tgl_Lokal($Order -> getDateOrder())); ?>
									</td>
									<td><?php echo htmlspecialchars($Order -> getTimeOrder()); ?></td>
									<td><a
										href="index.php?module=orders&action=detailOrder&orderId=<?php echo $Order -> getOrderId(); ?>"
										title="Detail" class="btn btn-primary"> <i
											class="fa fa-check fa-fw"></i> Detail
									</a>
									</td>
								</tr>
								<?php  endforeach; ?>

							</tbody>
						</table>

					</div>
					<!-- /table-responsive -->


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
