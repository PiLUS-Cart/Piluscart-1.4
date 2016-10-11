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
								action="index.php?module=inbox&action=<?php echo $views['formAction']; ?>"
								role="form">

								<!-- Kepada -->
								<div class="form-group">
									<label> Kepada: </label> <input type="text"
										class="form-control" name="email"
										value="<?php if (isset($views['Message'])) echo htmlspecialchars($views['Message'] -> getEmail()); ?>">
								</div>

								<!-- Subjek -->
								<div class="form-group">
									<label> Subjek: </label> <input type="text"
										class="form-control" name="subjek"
										value="Re: <?php if (isset($views['Message'])) echo htmlspecialchars($views['Message'] -> getSubject()); ?>">
								</div>

								<!-- Pesan -->
								<div class="form-group">
									<label>Pesan: </label>
									<textarea name="pesan" class="form-control" rows="10"
										id="pilus" maxlength="100000">
										<?php echo "<br><br><br>-------------------------------------------------------------------------------\n\n<br><br>".$views['Message'] -> getSender(). " menulis pesan:\n\n<br><br>".$views['Message'] -> getMessage(); ?>
	                          </textarea>
								</div>

								<input type="submit" class="btn btn-primary" name="send"
									value="Kirim" />

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
