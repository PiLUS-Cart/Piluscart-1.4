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

				<a href="index.php?module=users&action=newUser"
					class="btn btn-outline btn-success"> <i
					class="fa fa-plus-circle fa-fw"></i> Tambah Staff
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
					<?php  echo $totalRows; ?>
					 Staff<?php  echo ( $totalRows != 1 ) ? 's' : ''?>
					in Total
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>#</th>
									<th>Nama Lengkap</th>
									<th>Email</th>
									<th>Level</th>
									<th>Aktifasi</th>
									<th>Edit</th>
									<th>Hapus</th>

								</tr>
							</thead>
							<tbody>
								<?php 

								$no = $views['position'];
								foreach ($views['admins'] as $admin) :
									
								$activationKey = $admin -> getAdmin_Activation_Key();
								$no++
								?>
								<tr>

									<td><?php echo $no; ?></td>
									<td><?php echo htmlspecialchars($admin -> getAdmin_Fullname()); ?>
									</td>
									<td><?php echo htmlspecialchars($admin -> getAdmin_Email()); ?>
									</td>
									<td><?php echo htmlspecialchars($admin -> getAdmin_Level()); ?>
									</td>
									<td><?php if ( $activationKey != 'Yes') { 
										echo "Belum Diaktifkan";
									} else { echo htmlspecialchars(tgl_Lokal($admin -> getAdmin_Registered()));
                                    
									} ?>
									</td>

									<td><a
										href="index.php?module=users&action=editUser&userId=<?php echo $admin -> getId(); ?>&sessionId=<?php echo $admin -> getSession_Key(); ?>"
										class="btn btn-primary"> <i class="fa fa-pencil fa-fw"></i>
											Edit
									</a>
									</td>


									<td><a
										href="javascript:deleteAdmin('<?php echo $admin -> getId(); ?>', '<?php echo $admin -> getAdmin_Fullname(); ?>')"
										class="btn btn-danger"> <i class="fa fa-trash-o fa-fw"></i>
											Hapus
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
<!-- /#page-wrapper -->
<script type="text/javascript">
  function deleteAdmin(id, fullname)
  {
	  if (confirm("Apakah anda yakin ingin menghapus staff anda dengan nama  '" + fullname + "'"))
	  {
	  	window.location.href = 'index.php?module=users&action=deleteUser&userId=' + id;
	  }
  }
</script>
