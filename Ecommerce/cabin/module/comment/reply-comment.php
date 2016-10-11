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

				<?php if ( isset($views['reply_id']) && $views['reply_id'] != 0 ) { ?>

				<a
					href="javascript:deleteReply('<?php echo $views['reply_id']; ?>', '<?php echo $views['sender']; ?>' )"
					title="Hapus Reply">

					<button type="button" class="btn btn-outline btn-danger">
						<i class="fa fa-exclamation-circle fa-fw"></i> Hapus Reply
					</button>
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
	;

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

							<form method="post" action="index.php?module=comments&action=<?php echo $views['formAction']; ?>"
								role="form">
								<input type="hidden" name="reply_id" value="<?php if (isset($views['reply_id'])) echo $views['reply_id']; ?>">
								<input type="hidden" name="comment_id" value="<?php if (isset($views['replyComment'])) echo $views['replyComment'] -> getComment_Id(); ?>">
							
								<!-- isi komentar -->
								<div class="form-group">
									<div class="well">
									    <h4>Komentar dari <?php echo $views['replyComment'] -> getCommentator_Fullname(); ?></h4>
									    <?php echo html_entity_decode($views['replyComment'] -> getPost_Comment()); ?>
									</div>
								</div>

								<!-- Balas Komentar -->
								<div class="form-group">
									<label>*Reply</label>
									<textarea class="form-control" name="balas_komentar" id="pilus" rows="3"
										maxlength="500">
										<?php if (isset($views['replyComment'])) echo $views['replyComment'] -> getReply(); ?>
									</textarea>
								</div>

								<!-- Actived -->
								<div class="form-group">
									<label>*Diaktifkan</label> <label class="radio-inline"> <input
										type="radio" name="active" id="optionsRadiosInline1" value="Y"
										<?php if (isset($views['activedReply']) && $views['activedReply'] == 'Y') echo 'checked="checked"'; ?>>
										Ya
									</label> <label class="radio-inline"> <input type="radio"
										name="active" id="optionsRadiosInline1" value="N"
										<?php if (isset($views['activedReply']) && $views['activedReply'] == 'N') echo 'checked="checked"'; ?>>
										Tidak
									</label>
								</div>

								<input type="submit" class="btn btn-primary" name="saveReply" value="Simpan" />

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
<script type="text/javascript">
  function deleteReply(replyId, nama)
  {
	  if (confirm("Apakah anda yakin ingin menghapus reply ke '" + nama + "'"))
	  {
	  	window.location.href = 'index.php?module=comments&action=deleteReply&replyId=' + replyId;
	  }
  }
</script>