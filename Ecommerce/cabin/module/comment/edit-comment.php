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

				

				<?php if ( isset($views['admin_id']) && $views['admin_id'] == 0 ) { ?>
				<a
					href="index.php?module=comments&action=replyComment&commentId=<?php echo $views['comment_id']; ?>&replyId=<?php echo $views['reply_id']; ?>"
					title="Balas Komentar" class="btn btn-outline btn-success"> <i
					class="fa fa-mail-reply fa-fw"></i> Reply Comment
				</a>
				<?php }else {  ?>

                 <a
					href="index.php?module=comments&action=editReply&commentId=<?php echo $views['comment_id']; ?>&replyId=<?php echo $views['reply_id']; ?>"
					title="Balas Komentar" class="btn btn-outline btn-primary"> <i
					class="fa fa-mail-reply fa-fw"></i> View Reply
				</a>
				
				<?php } ?>
			</h1>

		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->
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


	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<?php if (isset($views['pageTitle'])) echo $views['pageTitle']; ?>
				</div>
				<!-- #panel-heading -->

				<div class="panel-body">
					<div class="row">
						<div class="col-lg-6">

							<form method="post"
								action="index.php?module=comments&action=<?php echo $views['formAction']; ?>"
								role="form">
								<input type="hidden" name="comment_id"
									value="<?php if (isset($views['comment_id'])) echo $views['comment_id'];  ?>">
								<input type="hidden" name="post_id"
									value="<?php if (isset($views['comment'])) echo $views['comment'] -> getPost_Id(); ?>">

								<!-- nama komentar -->
								<div class="form-group">
									<label>Nama </label> <input type="text" name="nama_komentar"
										class="form-control"
										value="<?php if (isset($views['comment'])) echo htmlspecialchars($views['comment'] -> getFullname());  ?>">
								</div>

								<!-- URL -->
								<div class="form-group">
									<label>Website </label> <input type="text" name="url"
										class="form-control"
										value="<?php if (isset($views['comment'])) echo htmlspecialchars($views['comment'] -> getUrl());  ?>">
								</div>

								<!-- isi komentar -->
								<div class="form-group">
									<label>Isi Komentar</label>
									<textarea class="form-control" name="isi_komentar" id="pilus" rows="3"
										maxlength="500">
										<?php echo $views['comment'] -> getComment();  ?>
									</textarea>
								</div>

								<!-- Actived -->
								<div class="form-group">
									<label>Diaktifkan</label> <label class="radio-inline"> <input
										type="radio" name="active" id="optionsRadiosInline1" value="Y"
										<?php if (isset($views['activedComment']) && $views['activedComment'] == 'Y') echo 'checked="checked"'; ?>>
										Ya
									</label> <label class="radio-inline"> <input type="radio"
										name="active" id="optionsRadiosInline1" value="N"
										<?php if (isset($views['activedComment']) && $views['activedComment'] == 'N') echo 'checked="checked"'; ?>>
										Tidak
									</label>
								</div>
								<input type="submit" class="btn btn-primary" name="saveComment"
									value="Simpan" />

								<button type="button" class="btn btn-danger"
									onClick="self.history.back();">Batal</button>


							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php }?>
</div>
<!-- /#page-wrapper -->