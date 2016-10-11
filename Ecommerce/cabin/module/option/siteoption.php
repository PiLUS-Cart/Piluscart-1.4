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
				<?php if (empty($views['siteName'])) : ?>
				<a href="index.php?module=option&action=setOption"
					class="btn btn-outline btn-success"><i class="fa fa-wrench fa-fw"></i>
					Set Pengaturan </a>
				<?php  endif; ?>
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
									<th>Nama Website</th>
									<th>Deskripsi</th>
									<th>Kata kunci</th>
									<th>Favicon</th>
									<th>Aksi</th>
								</tr>
							</thead>
							<tbody>
								<?php 
			
								foreach ($views['options'] as $option):
									
								?>
								<tr>

									<td><?php echo htmlspecialchars($option -> getSite_Name()); ?>
									</td>
									<td><?php echo htmlspecialchars($option-> getMeta_Description()); ?>
									</td>
									<td><?php echo htmlspecialchars($option -> getMeta_Keywords()); ?>
									</td>

									<?php 
									//set up images
									$favicon = '../content/uploads/images/' . $option -> getFavicon();

									if (!is_file($favicon)) :

									$favicon = '../content/uploads/images/thumbs/nophoto.jpg';
									endif;


									?>
									<td><a href="<?php echo $favicon; ?>"><img
											alt="<?php echo PACK_CODENAME; ?>"
											src="<?php echo $favicon; ?>"> </a>
									</td>

									<td><a
										href="index.php?module=option&action=editOption&optionId=<?php echo $option -> getOption_Id(); ?>"
										title="Edit" class="btn btn-primary"> <i
											class="fa fa-pencil fa-fw"></i> Edit
									</a>
									</td>
								</tr>

								<?php endforeach; ?>
							</tbody>
						</table>
					</div>

				</div>
			</div>
		</div>
	</div>
	<!-- /.row -->

</div>
<!-- /#page-wrapper -->
