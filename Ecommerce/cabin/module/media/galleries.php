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
				<a href="index.php?module=postimage&action=newPostImage"
					class="btn btn-outline btn-success"> <i
					class="fa fa-plus-circle fa-fw"></i> Tambah Galeri Foto
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
					<?php echo $views['totalRows']?>
					Picture
					<?php echo ( $views['totalRows'] != 1 ) ? 's' : '' ?>
					in total.
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>#</th>
									<th>Gambar</th>
									<th>Filename</th>
									<th>Caption</th>
									<th>Edit</th>
									<th>Hapus</th>
								</tr>
							</thead>
							<tbody>
								<?php 

								$rowCount = $views['position'];
									
								foreach ( $views['postimages'] as $postimage ) :
									
								$rowCount++;
								?>
								<tr>
									<td><?php echo htmlspecialchars($rowCount); ?></td>
									<?php 
									//set up image
									$image = '../content/uploads/images/'. $postimage -> getImage_Filename();
									if (!is_file($image))
									{
										$image = '../content/uploads/images/nophoto.jpg';
											
									}

									$image_thumb = '../content/uploads/images/thumbs/'. $postimage -> getImage_Filename();
									if (!is_file($image_thumb))
									{
										$image_thumb = '../content/uploads/images/thumbs/nophoto.jpg';
											
									}


									?>
									<td><a href="<?php echo $image; ?>"><img alt=""
											src="<?php echo $image_thumb; ?>"> </a></td>
									<td><?php echo htmlspecialchars($postimage ->getImage_Filename()); ?>
									</td>
									<td><?php echo htmlspecialchars($postimage -> getImage_Caption()); ?>
									</td>

									<td><a
										href="index.php?module=postimage&action=editPostImage&imageId=<?php echo $postimage -> getId(); ?> "
										title="Edit">
											<button type="button" class="btn btn-primary">
												<i class="fa fa-pencil fa-fw"></i> Edit
											</button>
									</a>
									</td>
									<td><a
										href="javascript:deletePostImage('<?php echo $postimage ->  getId(); ?>', '<?php echo $postimage -> getImage_Filename(); ?>')"
										title="Hapus">
											<button type="button" class="btn btn-danger">
												<i class="fa fa-trash-o fa-fw"></i> Hapus
											</button>
									</a>
									</td>

								</tr>
								<?php  endforeach; ?>


							</tbody>
						</table>

					</div>
					<!-- /table-responsive -->


					<div class="pagination">
						<span> <?php if ($totalRows > 10) echo $views['pageLink']; ?>
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
<script type="text/javascript">
  function deletePostImage(id, image)
  {
	  if (confirm("Apakah anda yakin ingin menghapus file '" + image + "'"))
	  {
	  	window.location.href = 'index.php?module=postimage&action=deletePostImage&imageId=' + id + '&filename=' + image;
	  }
  }
</script>
