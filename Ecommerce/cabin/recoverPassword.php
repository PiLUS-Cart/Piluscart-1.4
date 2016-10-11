<?php
/**
 * recoverPassword.php
 * berperan untuk mengatur 
 * kembali password yang hilang
 * 
 * @package   PiLUS_CMS
 * @author    Maoelana Noermoehammad
 * @copyright 2014 kartatopia.com
 * @license   GPL version 3.0
 * @version   1.4.0
 * @since     Since Release 1.4
 * 
 */

include_once('../core/plcore.php');

$pageTitle = "Ganti Kata Sandi";

$errors = array();

$tempToken = isset($_GET['tempToken']) ? htmlentities(strip_tags($_GET['tempToken'])) : '';

$dbc = new Pldb;

$stmt = $dbc -> prepare("SELECT admin_reset_key, admin_resetComplete
		                FROM pl_admin WHERE admin_reset_key = :token");

$stmt -> execute(array(":token" => $tempToken));
$row = $stmt -> fetch(PDO::FETCH_ASSOC);

if (empty($row['admin_reset_key'])) {
	
	$stop = 'Token tidak valid.cek email anda!';
	
} elseif ($row['admin_resetComplete'] == 'Yes') {
	
	$stop = 'Password anda sudah diubah.';
}

if (isset($_POST['action']) && $_POST['action'] == 'recover pass') {
	
	$password = preventInject($_POST['pass1']);
	$confirmPass = preventInject($_POST['pass2']);

	if (empty($password) || empty($confirmPass)) {
		
		$errors[] = 'semua kolom harus diisi';
		
	}else {
		
		if (strlen($password) < 6) {
			
			$errors[] = 'Password terlalu pendek';
		}
		if (strlen($confirmPass)< 6) {
			
			$errors[] = 'Password terlalu pendek';
		}
		if ($password != $confirmPass) {
				
			$errors[] = 'Password tidak cocok';
		}

	}

	if (empty($errors) == true) {
		

		$admins -> recoverPass($password, $row['admin_reset_key']);

	}
	
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?php echo $pageTitle; ?> | PiLUS</title>
<!-- Core CSS - Include with every page -->
<link href="<?php echo PL_DIR; ?>cabin/css/bootstrap.min.css"
	rel="stylesheet">
<link
	href="<?php echo PL_DIR; ?>cabin/font-awesome/css/font-awesome.css"
	rel="stylesheet">
<!-- Page-Level Plugin CSS - Dashboard -->
<!-- Page-Level Plugin CSS - Tables -->
<link
	href="<?php echo PL_DIR; ?>cabin/css/plugins/dataTables/dataTables.bootstrap.css"
	rel="stylesheet">
<link
	href="<?php echo PL_DIR; ?>cabin/css/plugins/morris/morris-0.4.3.min.css"
	rel="stylesheet">
<link
	href="<?php echo PL_DIR; ?>cabin/css/plugins/timeline/timeline.css"
	rel="stylesheet">
<link href="<?php echo PL_DIR; ?>cabin/css/sb-admin.css"
	rel="stylesheet">

</head>
<body OnLoad="document.recover.email.focus();">
	<div class="container">
		<div class="row">
			<div class="col-md-4 col-md-offset-4">
				<div class="login-panel panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Masukkan Kata Sandi baru</h3>
					</div>
					<div class="panel-body" OnLoad="document.recover.pass1.focus();">
						<?php if ( empty($errors) == false ) { ?>
						<div class="alert alert-danger ">

							<?php echo implode('<div></div>', $errors); ?>

						</div>
						<?php } ?>


						<?php if (isset($stop)) {
							?>
						<div class="alert alert-danger">

							<?php echo $stop; ?>
							<script type="text/javascript">function leave() {  window.location = "<?php echo PL_DIR; ?>";} setTimeout("leave()", 3640);</script>
							
						</div>
						<?php  } else { ?>

						<form method="post" action="" onSubmit="return validasi(this)"
							role="form" autocomplete="off">
							<fieldset>
								<div class="form-group">
									<input class="form-control" placeholder="kata sandi"
										name="pass1" type="password" required>
								</div>
								<div class="form-group">
									<input class="form-control"
										placeholder="ketik ulang kata sandi" name="pass2"
										type="password" autofocus required>
								</div>

								<input type="hidden" name="action" value="recover pass" />
								<button type="submit" class="btn btn-primary btn-lg btn-block">Ganti
									Kata Sandi</button>
							</fieldset>
						</form>
					</div>

				</div>
				<?php if ( isset($_GET['status']) ) { ?>
				<div class="alert alert-success alert-dismissable">
					<button type="button" class="close" data-dismiss="alert"
						aria-hidden="true">&times;</button>
					Kata sandi berhasil di<strong><?php echo $_GET['status']; ?> </strong>
				</div>
				<a href="<?php echo PL_cabin; ?> ">Masuk ke Akun ?</a>
				<?php } 
}?>
			</div>

		</div>

	</div>

	<script type="text/javascript">
function validasi(form){
if (form.pass1.value == ""){
alert("Anda belum mengisikan kata sandi");
form.pass1.focus();
return (false);
}
     
if (form.pass2.value == ""){
alert("Anda belum mengisikan Kata sandi konfirmasi");
form.pass2.focus();
return (false);
}
return (true);
}
</script>
	<!-- Core Scripts - Include with every page -->
	<script src="<?php echo PL_DIR; ?>cabin/js/jquery-1.10.2.js"></script>
	<script src="<?php echo PL_DIR; ?>cabin/js/bootstrap.min.js"></script>
	<script
		src="<?php echo PL_DIR; ?>cabin/js/plugins/metisMenu/jquery.metisMenu.js"></script>

	<!-- SB Admin Scripts - Include with every page -->
	<script src="<?php echo PL_DIR; ?>cabin/js/sb-admin.js"></script>
</body>
</html>
