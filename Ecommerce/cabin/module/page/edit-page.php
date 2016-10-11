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
								action="index.php?module=pages&action=<?php echo $views['formAction']; ?>"
								role="form" enctype="multipart/form-data">

								<input type="hidden" name="page_id"
									value="<?php if (isset($views['page'])) echo $views['page'] -> getId(); ?>">
								<input type="hidden" name="post_type"
									value="<?php if ( isset($views['page'])) echo htmlspecialchars($views['page'] -> getPost_Type()); ?>">

								<!-- post title -->
								<div class="form-group">
									<label>*Judul</label> <input type="text" class="form-control"
										placeholder="page's title" name="title"
										value="<?php if (isset($views['page'])) echo htmlspecialchars($views['page'] -> getPost_Title()); ?> ">
								</div>

								<!-- gambar terkini -->
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
									<label>*Isi Halaman</label>
									<textarea class="form-control" id="pilus" name="content"
										rows="10" maxlength="100000">
										<?php if (isset($views['page'])) echo $views['page'] -> getPost_Content(); ?> </textarea>
								</div>

								<!-- post status -->
								<div class="form-group">
									<?php echo $views['postStatus']; ?>
								</div>

								<!-- Comment status -->
								<div class="form-group">
									<?php echo $views['commentStatus']; ?>
								</div>

								<input type="submit" class="btn btn-primary" name="savePage"
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
