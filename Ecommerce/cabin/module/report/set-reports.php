<?php

if (!defined('PILUS_SHOP'))
{
	header("Location: ../../../cabin/403.php");
	exit;
}

$tgl_skrg     = date("d");
$bln_sekarang = date("m");
$thn_sekarang = date("Y");

?>

<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">
				<?php if (isset($views['pageTitle']))  echo $views['pageTitle']; ?>
				 
				<a href="index.php?module=report&action=<?php echo $views['formAction'] ?>"
					title="Laporan penjualan hari ini" class="btn btn-outline btn-success"><i
					class="fa fa-file-text fa-fw"></i> Laporan penjualan sekarang
				</a>
				
			</h1>
		</div>
		<!-- /.col-lg-12 -->
	</div>
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
	<!-- /.row -->

	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<?php  echo $views['pageTitle']; ?>
					  Per Periode
				</div>
				<!-- .panel-heading -->

				<div class="panel-body">
					<div class="row">
						<div class="col-lg-6">
							<form method="post" action="module/report/laporan-periodik.php"
								role="form">

								<!-- Dari Tanggal -->
								<div class="form-group">
									<label>Tanggal Mulai</label><br>
									<?php 


									echo comboBox_Tanggal(1,31,'tgl_mulai',$tgl_skrg);
									echo comboBox_NamaBulan(1,12,'bln_mulai',$bln_sekarang);
									echo comboBox_Tahun(2000,$thn_sekarang,'thn_mulai',$thn_sekarang);
									?>
								</div>

								<!-- s/d tanggal -->
								<div class="form-group">
									<label>Tanggal Selesai</label><br>
									<?php 


									echo comboBox_Tanggal(1,31,'tgl_selesai',$tgl_skrg);
									echo comboBox_NamaBulan(1,12,'bln_selesai',$bln_sekarang);
									echo comboBox_Tahun(2000,$thn_sekarang,'thn_selesai',$thn_sekarang);

									?>
								</div>

								<div class="form-group">
									<i class="fa fa-file-text fa-fw"></i> <label>Buat Laporan Penjualan:</label><br>
									<input type="submit" class="btn btn-primary" name="proses"
										value="Proses" />
									<button type="button" class="btn btn-danger"
										onClick="self.history.back();">Batal</button>

								</div>


							</form>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>

</div>
<!-- #page-wrapper -->
