<?php

if (!defined('PILUS_SHOP'))
{
	header("Location: ../../../cabin/403.php");
	exit;
}

$totalRows = isset($views['totalRows']) ? htmlspecialchars($views['totalRows']) : '';

$dbh = new Pldb;
?>


<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">

			<h1 class="page-header">
				<?php echo $views['pageTitle']; ?>
				<a href="index.php?module=posts&action=newPost&postId=0"
					class="btn btn-outline btn-success"> <i
					class="fa fa-plus-circle fa-fw"></i> Tambah Tulisan
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
					<?php  echo $totalRows . "\nTulisan"; ?>
				</div>
				<!-- /.panel-heading -->

				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>#</th>
									<th>Judul</th>
									<th>Penulis</th>
									<th>Kategori</th>
									<th>Tgl.Posting</th>
								</tr>
							</thead>
							<tbody>
								<?php 

								$no = $views['position'];
									
								foreach ( $views['posts'] as $post ) :
									
								$no++;
									
								?>
								<tr>
									<td><?php echo htmlspecialchars($no); ?></td>
									<td>
									<a href="index.php?module=posts&action=editPost&postId=<?php echo $post -> getId(); ?>"><?php echo htmlspecialchars($post -> getPost_Title()); ?>
									</a>
									</td>
									<td><?php echo htmlspecialchars($post -> getAuthor_Username()); ?>
									</td>

									<td><?php  echo htmlspecialchars($post -> getPostCat_Name()); ?>
									</td>
									<td><?php echo htmlspecialchars(tgl_Lokal($post -> getPost_Date())); ?>
									</td>

								</tr>

								<?php endforeach; ?>
							</tbody>
						</table>
					</div>

					<!-- /table-responsive -->
					<div class="pagination">
						<span> <?php if ( $totalRows > 10) echo $views['pageLink']; ?>
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
  function deletePost(id, post)
  {
	  if (confirm("Apakah anda yakin ingin menghapus tulisan '" + post + "'"))
	  {
	  	window.location.href = 'index.php?module=posts&action=deletePost&postId=' + id;
	  }
  }
</script>
