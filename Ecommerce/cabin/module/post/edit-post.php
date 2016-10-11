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
				<?php if (isset($views['pageTitle'])) echo $views['pageTitle']; ?>

				<?php if (isset($views['post']) && $views['post'] -> getId() != 0 ) { ?>
				<a
					href="javascript:deletePost('<?php echo $views['post'] -> getId(); ?>', '<?php echo $views['post'] -> getPost_Title();?>' )"
					title="Hapus Tulisan" class="btn btn-outline btn-danger"> <i
					class="fa fa-exclamation-circle fa-fw"></i> Hapus Tulisan
				</a>

				<?php } ?>
			</h1>
		</div>
		<!-- /.col-lg-12 -->
	</div>

	<?php 
	if (isset($views['errorMessage'])) { ?>

	<div class="alert alert-danger ">
		<h4>Error!</h4>
		<p>
			<?php echo $views['errorMessage']; ?>
			<button type="button" class="btn btn-danger"
				onClick="self.history.back();">Ulangi</button>
		</p>
	</div>

	<?php } else { ?>

	<!-- /.row -->
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<?php if (isset($views['pageTitle'])) echo $views['pageTitle']; ?>
				</div>

				<div class="panel-body">
					<div class="row">
						<div class="col-lg-6">

							<form method="post"
								action="index.php?module=posts&action=<?php echo $views['formAction']; ?>"
								role="form" enctype="multipart/form-data">

								<input type="hidden" name="post_id"
									value="<?php if (isset($views['post'])) echo htmlspecialchars($views['post'] -> getId()); ?>">
								<input type="hidden" name="post_type"
									value="<?php if (isset($views['post'])) echo htmlspecialchars($views['post'] -> getPost_Type());  ?>">
								<!-- post title -->
								<div class="form-group">
									<label>*Judul</label> <input type="text" class="form-control"
										placeholder="page's title" name="title"
										value="<?php if (isset($views['post'])) echo htmlspecialchars($views['post'] -> getPost_Title());?> ">
								</div>

								<!-- Kategori Tulisan -->
								<div class="form-group">
									<?php if (isset($views['postCat'])) echo $views['postCat']; ?>
								</div>

								<!--  gambar terkini -->
								<?php if (!empty($views['imagePath'])) { ?>
								<div class="form-group">
									<label>*Gambar saat ini: </label>
									<?php 
									$image_thumb = '../content/uploads/images/thumbs/' . $views['imagePath'];
									if (!is_file($image_thumb)) :
									$image_thumb = '../content/uploads/images/thumbs/nophoto.jpg';
									endif;
									?>
									<br> <img alt="" src="<?php echo $image_thumb; ?>">
								</div>
								<?php } ?>

								<!-- Image for page -->
								<?php if (isset($views['postImg'])) { ?>

								<div class="form-group">

									<?php echo $views['postImg']; ?>


									<p id="allowUpload">
										<label>Upload Gambar :</label> <input type="checkbox"
											name="upload_new" id="upload_new">
									</p>

									<p class="optional">
										<label> Select Image :</label> <input type="file" name="image"
											id="image"> <br> <label>Caption</label> <input type="text"
											class="form-control" placeholder="picture's caption"
											name="caption" id="caption" value="">

									</p>


								</div>

								<?php } ?>

								<!-- description -->
								<div class="form-group">
									<label>*Isi Tulisan</label>
									<textarea class="form-control" id="pilus" name="content"
										rows="10" maxlength="100000">
										<?php if (isset($views['post'])) echo $views['post'] -> getPost_Content(); ?> </textarea>
								</div>
								
								<!-- post_tag -->
								<div class="form-group">
								    <?php if ( isset($views['Label']) ) echo $views['Label']; ?>
								</div>

								<!-- post status -->
								<div class="form-group">
									<?php echo $views['postStatus']; ?>
								</div>

								<!-- Comment status -->
								<div class="form-group">
									<?php echo $views['commentStatus']; ?>
								</div>

								<input type="submit" class="btn btn-primary" name="savePost"
									value="Simpan" />

								<button type="button" class="btn btn-danger"
									onClick="self.history.back();">Batal</button>

							</form>
						</div>
						<!-- /.col-lg-6 (nested) -->
						<div class="col-lg-6"></div>
						<!-- /.col-lg-6 (nested) -->
					</div>
					<!-- /.row (nested) -->
				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<?php } ?>
	<!-- /.row -->
</div>
<!-- #Page-Wrapper -->
<script type="text/javascript">
  function deletePost(id, post)
  {
	  if (confirm("Apakah anda yakin ingin menghapus tulisan dengan judul '" + post + "'"))
	  {
	  	window.location.href = 'index.php?module=posts&action=deletePost&postId=' + id;
	  }
  }
</script>