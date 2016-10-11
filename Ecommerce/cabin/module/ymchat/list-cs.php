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

				<a class="btn btn-outline btn-success"
					href="index.php?module=ymchat&action=newCS"> <i
					class="fa fa-plus-circle fa-fw"></i> Tambah YM Chat
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
					<?php  echo htmlspecialchars($totalRows); ?>
					Customer Service
				</div>
				<!-- /.panel-heading -->

				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>#</th>
									<th>Nama</th>
									<th>Yahoo ID</th>
									<th>Edit</th>
									<th>Hapus</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$no = $views['position'];

								foreach ( $views['cs'] as $cs) :
								$no++;
								?>
								<tr>
									<td><?php echo $no; ?></td>
									<td><?php echo htmlspecialchars($cs -> getCustomerCare()); ?></td>
									<td><?php echo htmlspecialchars($cs -> getOpenId()); ?>
									</td>

									<td><a
										href="index.php?module=ymchat&action=editCS&ymId=<?php echo $cs -> getYMChatId(); ?> "
										title="Edit" class="btn btn-primary"> <i
											class="fa fa-pencil fa-fw"></i> Edit
									</a>
									</td>

									<td><a
										href="javascript:deleteYM('<?php echo $cs -> getYMChatId(); ?>', '<?php echo $cs -> getCustomerCare(); ?>')"
										title="Hapus" class="btn btn-danger"> <i
											class="fa fa-trash-o fa-fw"></i> Hapus
									</a>
									</td>
								</tr>
								<?php endforeach; ?>
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
			<!-- /.panel-default -->
		</div>
	</div>
	<!-- /.row -->
</div>
<!-- /#page-wrapper -->

<script type="text/javascript">
  function deleteYM(id, name)
  {
	  if (confirm("Apakah anda yakin ingin menghapus '" + name + "'"))
	  {
	  	window.location.href = 'index.php?module=ymchat&action=deleteCS&ymId=' + id;
	  }
  }
</script>
