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
					<?php  echo htmlspecialchars($totalRows) .  "\nNotifikasi"; ?>
				</div>
				<!-- /.panel-heading -->

				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>#</th>
									<th>Judul</th>
									<th>Tanggal</th>
									<th>Waktu</th>
									<th>Status</th>
									<th>Hapus</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$no = $views['position'];
									
								foreach ( $views['notifications'] as $notification ) :
								$no++;
								?>
								<tr>
									<td><?php echo htmlspecialchars($no); ?></td>
									<td>
									  <a href="index.php?module=notification&action=viewNotification&notificationId=<?php echo $notification -> getNotifyId(); ?>"><?php echo htmlspecialchars($notification -> getNotifyTitle()); ?></a>
									</td>
									<td>
									  <?php echo htmlspecialchars(tgl_Lokal($notification -> getDateSubmited())); ?>
									</td>
									<td>
									   <?php echo htmlspecialchars($notification -> getTimeSubmited()); ?>
									</td>
									<td>
									   <?php if ( htmlspecialchars($notification -> getNotifyStatus() == 0 )) { echo  "Belum dibaca"; }else { echo  "sudah dibaca";} ?>
									</td>
									<td>
									   <a href="javascript:deleteNotification('<?php echo $notification-> getNotifyId(); ?>', '<?php echo $notification -> getNotifyTitle(); ?>')" title="Hapus" class="btn btn-danger"> 
									   <i class="fa fa-trash-o fa-fw"></i> Hapus</a>
									</td>
									
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
					<!-- /table-responsive -->
					<div class="pagination">
						<span> <?php if ($totalRows > 10 ) echo $views['pageLink']; ?>
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
  function deleteNotification(id, notification)
  {
	  if (confirm("Apakah anda yakin ingin menghapus notifikasi '" + notification + "' ?"))
	  {
	  	window.location.href = 'index.php?module=notification&action=deleteNotification&notificationId=' + id;
	  }
  }
</script>