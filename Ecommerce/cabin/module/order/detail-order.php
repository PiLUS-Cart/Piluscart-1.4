<?php

if (!defined('PILUS_SHOP'))
{
	header("Location: ../../../cabin/403.php");
	exit;
}

$tanggal = tgl_Lokal($views['Pesan'] -> getDateOrder());
?>

<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">
				<?php if (isset($views['pageTitle']))  echo $views['pageTitle']; ?>

			</h1>
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->
	<?php
	if (isset ( $views ['errorMessage'] )) {
		?>

	<div class="alert alert-danger alert-dismissable">
		<button type="button" class="close" data-dismiss="alert"
			aria-hidden="true">&times;</button>
		<?php echo $views['errorMessage']; ?>
	</div>

	
	<?php
	}
	if (isset ( $views ['statusMessage'] )) {
		?>

	<div class="alert alert-success alert-dismissable">
		<button type="button" class="close" data-dismiss="alert"
			aria-hidden="true">&times;</button>
		<?php echo $views['statusMessage']; ?>
	</div>

	<?php }?>
	<!-- /.row -->

	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
				<?php if (isset($views['pageTitle'])) echo $views['pageTitle']; ?>
				</div>
				<!-- .panel-heading -->

				<div class="panel-body">
					<div class="row">
						<div class="col-lg-8">
							<form method="post" action="index.php?module=orders&action=<?php echo $views['formAction']; ?>" role="form">
								<input type="hidden" name="order_id" value="<?php if (isset($views['Pesan'])) echo (int)$views['Pesan'] -> getOrderId(); ?>">
								<div class="table-responsive">
									<table class="table table-striped table-bPesaned table-hover">

										<tr>
											<td>Nomer Pemesanan</td>
											<td> : <?php if (isset($views['Pesan'])) echo htmlspecialchars($views['Pesan'] -> getOrderId()); ?> </td>
										</tr>
										<tr>
											<td>Tanggal Pemesanan</td>
											<td> : <?php echo $tanggal; ?></td>
										</tr>
										<tr>
											<td>Jam Pemesanan</td>
											<td> : <?php if (isset($views['Pesan'])) echo htmlspecialchars($views['Pesan'] -> getTimeOrder()); ?></td>
										</tr>
										<tr>
											 <?php if (isset($views['statusPesan'])) echo $views['statusPesan']; ?>
										</tr>
                                        <tr>
                                           <td colspan="4" align="right">
                                          <input type="submit" class="btn btn-primary" name="ubah" value="Konfirmasi">
                                           
								      <button type="button" class="btn btn-danger"
									onClick="self.history.back();">Batal</button></td>
                                        </tr>
									</table>
								</div>
							</form>
						</div>
					</div>

					<!-- tampilkan rincian produk yang diPesan -->
					<table class="table table-striped table-bPesaned table-hover">
						<thead>
							<tr>
								<th>Nama Produk</th>
								<th>Berat(gram)</th>
								<th>Jumlah</th>
								<th>Harga Satuan</th>
								<th>Sub Total</th>
							</tr>
						</thead>

						<tbody>
							   
							    <?php
											
									foreach ( $views ['detailPesanan'] as $detail_Pesan ) :
												
										$product_discount = $detail_Pesan->getProductDiscount();
										$product_price = $detail_Pesan->getProductPrice();
										$product_quantity = $detail_Pesan->getQuantity();
										$product_weight = $detail_Pesan->getProductWeight();
												
										// formula for counting subtotal and total
										$disc = ($product_discount / 100) * $product_price;
										$hargadisc = number_format ( ($product_price - $disc), 0, ',', '.' );
										$subtotal = ($product_price - $disc) * $product_quantity;
												
										$total = $total + $subtotal;
										$subtotal_rupiah = idrFormat ( $subtotal );
										$total_rupiah = idrFormat ( $total );
										$harga = idrFormat ( $product_price );
												
										$subtotal_berat = $product_weight * $product_quantity; // total berat per item produk
										$total_berat = $total_berat + $subtotal_berat; // grand total berat semua produk yang dibeli
												
									?>
							<tr>
							
								<td><?php echo htmlspecialchars($detail_Pesan -> getProductName()); ?></td>
								<td><?php echo htmlspecialchars(weightConverter($product_weight)); ?></td>
								<td><?php echo htmlspecialchars($product_quantity); ?></td>
								<td><?php echo htmlspecialchars($harga); ?></td>
								<td><?php echo htmlspecialchars($subtotal_rupiah); ?></td>
								
							</tr>
							
							     <?php endforeach; ?>
							  
							   
							   <?php
										
										$shipping_cost_base = $views ['ongkos']-> getOngkir();
								
										$shipping_cost = $shipping_cost_base * $total_berat;
										
										if ( $shipping_cost < $shipping_cost_base )
										{
											$grandtotal = $total + ($shipping_cost_base);
										}
										else
										{
											$grandtotal = $total + $shipping_cost;
										}
										
										
										$shipping_cost_rupiah = idrFormat ( $shipping_cost );
			
										$shippingcost_base_rupiah = idrFormat ( $shipping_cost_base );
										
										$grandtotal_rupiah = idrFormat ( $grandtotal )?>

							
							<tr>
								<td colspan="4" align="right">Diskon :</td>
								<td><b><?php echo htmlspecialchars($product_discount);  ?> %</b></td>
							</tr>										   
							<tr>
								<td colspan="4" align="right">Total Rp. :</td>
								<td><b><?php echo $total_rupiah;  ?></b></td>
							</tr>
							<tr>
								<td colspan="4" align="right">Ongkos Kirim Rp. :</td>
								<td><b><?php echo $shippingcost_base_rupiah;  ?></b> /Kg</td>
							</tr>
							<tr>
								<td colspan="4" align="right">Total Berat :</td>
								<td><b><?php echo weightConverter($total_berat);  ?></b> gram</td>
							</tr>
							<tr>
								<td colspan="4" align="right">Total Ongkos Kirim Rp. :</td>
								<td><b><?php if ( $shipping_cost < $shipping_cost_base) { echo $shippingcost_base_rupiah; }else{ echo $shipping_cost_rupiah; } ?></b></td>
							</tr>
							<tr>
								<td colspan="4" align="right">Grand Total Rp.:</td>
								<td><b><?php echo $grandtotal_rupiah;  ?></b></td>
							</tr>
						</tbody>
					</table>

					<!-- Tampilkan data kustomer -->
					<table class="table table-striped table-bPesaned table-hover">
						<tr>
							<th colspan=2>Data Kustomer</th>
						</tr>
						<tr>
							<td>Nama Kustomer</td>
							<td>: <?php echo htmlspecialchars($views['Pesan'] -> getCustomerFullname()); ?></td>
						</tr>
						<tr>
							<td>Alamat Pengiriman</td>
							<td>: <?php echo htmlspecialchars($views['Pesan'] -> getCustomerAddress()); ?> </td>
						</tr>
						<tr>
							<td>No.Telpon/HP</td>
							<td>: <?php echo htmlspecialchars($views['Pesan'] -> getCustomerPhone()); ?></td>
						</tr>
						<tr>
							<td>Email</td>
							<td>: <?php echo htmlspecialchars($views['Pesan'] -> getCustomerEmail()); ?></td>
						</tr>
					</table>

				</div>
				<!-- /.panel-body -->
			</div>
		</div>

	</div>
	<!-- /.row -->

</div>
<!-- /#page-wrapper -->