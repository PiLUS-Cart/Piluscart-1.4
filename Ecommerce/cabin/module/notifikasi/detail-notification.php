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
   
  <?php	} else { ?>


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
								action="index.php?module=notification&action=<?php echo $views['formAction']; ?>"
								role="form">
								<input type="hidden" name="notify_id"
									value="<?php if (isset($views['notification'])) echo htmlspecialchars($views['notification'] -> getNotifyId()); ?>">
								
								<!--Judul -->
								<div class="form-group">
									<label>*Judul</label> <input type="text" class="form-control" id="disabledInput"
										value="<?php if (isset($views['notification'])) echo htmlspecialchars($views['notification'] -> getNotifyTitle()); ?>" disabled>
								</div>

								<div class="form-group">
								    <label>*Isi Notifikasi</label>
									<textarea class="form-control" id="pilus" rows="3"
										maxlength="500">
										<?php if (isset($views['notification'])) echo $views['notification'] -> getNotifyContent(); ?>
									</textarea>
								</div>
								
								<div class="form-group">
									 <label>Status Notifikasi</label>
                                         <div class="radio">
                                            <label>
                                            <input type="radio" name="status" id="optionsRadios1" value="0" <?php if ($views['notification'] -> getNotifyStatus() == '0') echo 'checked="checked"'; ?>>Belum dibaca
                                            </label>
                                          </div>
                                       <div class="radio">
                                           <label>
                                           <input type="radio" name="status" id="optionsRadios2" value="1" <?php if ($views['notification'] -> getNotifyStatus() == '1') echo 'checked="checked"'; ?>>Sudah dibaca
                                           </label>
                                        </div>    
								</div>
								<input type="submit" class="btn btn-primary" name="saveChange"
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