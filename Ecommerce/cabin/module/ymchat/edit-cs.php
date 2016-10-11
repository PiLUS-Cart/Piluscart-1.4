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
				<!-- .panel-heading -->

				<div class="panel-body">
					<div class="row">
						<div class="col-lg-6">
							<form method="post"
								action="index.php?module=ymchat&action=<?php echo $views['formAction']; ?>"
								role="form">
								<input type="hidden" name="cs_id"
									value="<?php if (isset($views['cs'])) echo $views['cs'] -> getYMChatId(); ?>">
								<!-- Nama -->
								<div class="form-group">
									<label>*Nama</label> <input type="text" name="cs_name"
										class="form-control" placeholder="customer service name"
										value="<?php if (isset($views['cs'])) echo htmlspecialchars($views['cs'] -> getCustomerCare()); ?>">
								</div>

								<!-- OpenID -->
								<div class="form-group">
									<label>*Yahoo ID</label> <input type="text" name="open_id"
										class="form-control" placeholder="your yahoo ID"
										value="<?php if (isset($views['cs'])) echo htmlspecialchars($views['cs'] -> getOpenId()); ?>">
								</div>
								<input type="submit" class="btn btn-primary" name="saveCS"
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

	<?php } ?>
</div>
<!-- /#page-wrapper -->
