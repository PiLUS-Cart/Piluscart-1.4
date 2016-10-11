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
				<?php  echo $views['pageTitle']; ?>

				<?php if (empty($views['userPhone']) && $views['userMetaId'] != 0 ) { ?>
				<a href="index.php?module=users&action=userMeta&userId=<?php echo $views['userId']; ?>&sessionId=<?php echo htmlspecialchars($views['sessionId']); ?>"
					class="btn btn-outline btn-warning"> Lengkapi Biodata </a>
				<?php }else{ ?>
				<a
					href="index.php?module=users&action=userMeta&userId=<?php echo $views['userId']; ?>&sessionId=<?php echo htmlspecialchars($views['sessionId']); ?>"
					class="btn btn-outline btn-success"> Lihat Detail</a>
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
					<?php  echo $views['pageTitle']; ?>

				</div>

				<div class="panel-body">
					<div class="row">
						<div class="col-lg-6">

							<form method="post"
								action="index.php?module=users&action=<?php echo $views['formAction']; ?>"
								role="form">
								<input type="hidden" name="admin_id" value="<?php if (isset($views['userId'])) echo htmlspecialchars($views['userId']); ?>" />
								<input type="hidden" name="session_id" value="<?php if ( isset($views['sessionId']))  echo htmlspecialchars($views['sessionId']); ?>" />
								
								<!-- user_login -->
								<div class="form-group">
									<label>Username*</label> <input type="text" name="admin_login" class="form-control" placeholder="username"
										value="<?php if (isset($views['User'])) echo htmlspecialchars($views['User']-> getAdmin_Username()); ?>"
										<?php if ($views['User'] -> getAdmin_Username() != '') echo "disabled" ?>>
								</div>
								<!-- user_fullname -->
								<div class="form-group">
									<label>Nama Lengkap*</label> <input type="text"
										name="admin_fullname" class="form-control"
										placeholder="fullname"
										value="<?php if (isset($views['User']))  echo htmlspecialchars($views['User'] -> getAdmin_Fullname()); ?>"
										required>
								</div>
								<!-- user_email -->
								<div class="form-group">
									<label>Alamat Surat Elektronik(E-mail)*</label> <input
										type="text" name="admin_email" class="form-control"
										placeholder="E-mail address"
										value="<?php if (isset($views['User']))  echo htmlspecialchars($views['User'] -> getAdmin_Email());  ?>"
										required>
								</div>
								<!-- user_pass -->
								<div class="form-group">
									<label>Kata Sandi*</label> <input type="password"
										name="admin_pass" class="form-control" placeholder="password">
								</div>
								<!-- confirm_user_pass -->
								<?php if (isset($views['User']) && !$email = $views['User'] -> getAdmin_Email()) { ?>
								<div class="form-group">
									<label>Ketik ulang kata sandi*</label> <input type="password"
										name="confirm_pass" class="form-control"
										placeholder="confirm password">
								</div>
								<?php } ?>

								<!-- user_level -->
								<div class="form-group">
									<?php echo $views['userLevel']; ?>
								</div>


								<div class="form-group">
									<label>Situs web/blog(jika ada)</label> <input type="text"
										name="admin_url" class="form-control"
										placeholder="example: www.yoursite.com"
										value="<?php if (isset($views['User'])) echo htmlspecialchars($views['User'] -> getAdmin_Url());  ?>">
								</div>

								<input type="submit" class="btn btn-primary" name="saveAdmin"
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
