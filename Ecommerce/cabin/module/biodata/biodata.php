<?php

if (!defined('PILUS_SHOP'))
{
	header("Location: ../../../cabin/403.php");
	exit;
}

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

				<div class="panel-heading"></div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>#</th>
									<th>Nama Pengguna</th>
									<th>Nama Lengkap</th>
									<th>Email</th>
									<th>Level</th>
									<th>Edit</th>

								</tr>
							</thead>
							<tbody>

								<tr>
									<td></td>
									<td><?php if (isset($views['username'])) echo htmlspecialchars($views['username']); ?>
									</td>
									<td><?php if (isset($views['fullname'])) echo htmlspecialchars($views['fullname']); ?>
									</td>
									<td><?php if (isset($views['Email'])) echo htmlspecialchars($views['Email']); ?>
									</td>
									<td><?php if (isset($views['level'])) echo htmlspecialchars($views['level']); ?>
									</td>

									<td><a
										href="index.php?module=users&action=editUser&userId=<?php if (isset($views['userId'])) echo $views['userId']; ?>&sessionId=<?php if (isset($views['user_session'])) echo $views['user_session']; ?>"
										class="btn btn-primary"> <i class="fa fa-pencil fa-fw"></i>
											Edit
									</a>
									</td>

								</tr>

							</tbody>
						</table>
						<!-- /table-responsive -->

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
