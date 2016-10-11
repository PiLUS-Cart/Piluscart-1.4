<?php
/**
 * resetPassword.php
 * mendapatkan kata sandi baru
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
include_once('login-theme.php');

$pageTitle = "Reset Password";

$errors = array();

$dbc = new Pldb;

if (isset($_POST['reset']) && $_POST['reset'] == 'reset_pass')
{
	$admin_email = preventInject($_POST['admin_email']);

	$data_reset = array('admin_email' => $admin_email);

	if (empty($admin_email))
	{
		$errors[] = 'Kolom E-mail harus diisi';
	}
	else
	{

		// cek validasi Email
		
		if (!preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/', $admin_email))
		{
			$errors[] = 'penulisan E-mail tidak valid';
		}

		if ($admins -> emailExists($admin_email) == false)
		{
			$errors[] = 'E-mail tidak terdaftar';
		}
	}


	if (empty($errors) === true)
	{
		// create token
		$tempToken = md5(uniqid(rand(),true));

		// update record on table pl_admin
		$sql = "UPDATE pl_admin SET admin_reset_key = :admin_reset_key,
			   admin_resetComplete = 'No' WHERE admin_email = :admin_email";

		$stmt = $dbc -> prepare($sql);
		$stmt -> bindValue(":admin_reset_key", $tempToken);
		$stmt -> bindValue(":admin_email", $admin_email);

		try {
			
			$stmt -> execute();

			if ($row = $stmt -> rowCount() == 1)
			{
				// send an Email
				$to = safeEmail($admin_email);
				$subject = "Password Reset";
				$message = "<html>
						<body>
						Jika anda tidak pernah meminta pesan informasi tentang lupa password, silahkan untuk menghiraukan email ini.<br />
						Tetapi jika anda memang yang meminta pesan informasi ini, maka silahkan untuk mengklik tautan (link) di bawah ini :<br /><br />
						<a href=".PL_CABIN."recoverPassword.php?tempToken=$tempToken >Recover Password</a><br /><br />
						Terima Kasih,<br />
						<b>Tim Pengembang Pilus Open Source E-commerce Software</b>
						</body>
						</html>";


				$headers  = "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
				$headers .= "From: <".PL_SITEEMAIL.">\r\n";
				$headers .= "Reply-To: ".PL_SITEEMAIL."";

				mail($to, $subject, $message, $headers);

				// redirect to reset password's page
				header("Location: " . PL_CABIN . "resetPassword.php?status=reset");
				exit;
			}
			
		} catch (PDOException $e) {

			LogError::newMessage($e);
			LogError::customErrorMessage();
		}
	}
}

$metaOptions = '';

$data_option = $option -> getOptions();

$metaOptions = $data_option['results'];

foreach ( $metaOptions as $metaOption ) :

$siteName = $metaOption -> getSite_Name();
$description = $metaOption -> getMeta_Description();
$keywords = $metaOption -> getMeta_Keywords();

endforeach;

loginHeader( $siteName . "\n|". "\n$pageTitle" );
?>
<div class="container">
		<div class="row">
			<div class="col-md-4 col-md-offset-4">
				<div class="login-panel panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Masukkan Alamat E-mail</h3>
					</div>
					<div class="panel-body"
						OnLoad="document.reset.admin_email.focus();">
						<?php if ( empty($errors) == false ) { ?>
						<div class="alert alert-danger alert-dismissable">
							<button type="button" class="close" data-dismiss="alert"
								aria-hidden="true">&times;</button>
							<?php echo implode('<div></div>', $errors); ?>
							.
						</div>
						<?php } ?>


						<form method="post" action="resetPassword.php"
							onSubmit="return validasi(this)" role="form" autocomplete="off">
							<fieldset>
								<div class="form-group">
									<input class="form-control" placeholder="E-mail"
										name="admin_email" type="text" autofocus required>
								</div>

								<!-- Change this to a button or input when using this as a form -->

								<input type="hidden" name="reset" value="reset_pass" />
								<button type="submit" class="btn btn-primary btn-lg btn-block">
									Dapatkan kata sandi baru</button>
							</fieldset>
						</form>
					</div>

				</div>
				<?php if ( isset($_GET['status']) ) { ?>
				<div class="alert alert-success alert-dismissable">
					<button type="button" class="close" data-dismiss="alert"
						aria-hidden="true">&times;</button>
					Kata sandi sudah di<strong><?php echo $_GET['status']; ?> </strong>.
					cek Email anda!
				</div>
				<?php } ?>
				<a href="<?php echo PL_CABIN; ?>">Masuk ke Akun ?</a>
			</div>

		</div>

	</div>
<?php loginFooter(); ?>