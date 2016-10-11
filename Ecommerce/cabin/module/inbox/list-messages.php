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
					Message
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
									<th>Pengirim</th>
									<th>Subjek</th>
									<th>Tanggal</th>
									<th>Hapus</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$no = $views['position'];

								foreach ($views['messages'] as $message ) :
								$no++;
								?>
								<tr>
									<td><?php echo htmlspecialchars($no); ?></td>
									<td><?php echo htmlspecialchars($message -> getSender()); ?>
									</td>
									<td><a
										href="index.php?module=inbox&action=replyMessage&messageId=<?php echo htmlspecialchars($message -> getInbox_Id()); ?>"><?php echo htmlspecialchars($message -> getSubject()); ?>
									</a></td>
									<td><?php echo htmlspecialchars(tgl_Lokal($message -> getDate_Sent())); ?>
									</td>
									<td><a
										href="javascript:deleteMessage('<?php echo $message -> getInbox_Id(); ?>', '<?php echo $message -> getSender(); ?> ')"
										title="hapus pesan" class="btn btn-danger"><i
											class="fa fa-trash-o fa-fw"></i> Hapus </a>
									</td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
						<!-- /table-responsive -->
					</div>


					<div class="pagination">
						<span> <?php if ( $totalRows > 10) echo $views['pageLink']; ?>
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
  function deleteMessage(id, sender)
  {
	  if (confirm("Apakah anda yakin ingin menghapus pesan dari '" + sender + "'"))
	  {
	  	window.location.href = 'index.php?module=inbox&action=deleteMessage&messageId=' + id;
	  }
  }
</script>
