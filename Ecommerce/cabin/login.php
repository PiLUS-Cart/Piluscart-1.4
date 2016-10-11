<?php
/**
 * File login.php
 * berfungsi sebagai halaman login
 * Back Store - administrator web
 *
 * @package   PiLUS_CMS
 * @subpackage Authorization
 * @author    Maoelana Noermoehammad
 * @copyright 2014 kartatopia.com
 * @license   GPL version 3.0
 * @version   1.4.0
 * @since     Since Release 1.4
 */

include_once('../core/plcore.php');
include_once('login-theme.php');

$errors = array();

$loginFormSubmitted = isset($_POST['Log-In']);

if (empty($loginFormSubmitted) == false) {
	
	$userName = trim($_POST['username']);
	$passWord = trim($_POST['password']);
	
	$badCSRF = true; // check CSRF
	
    if (!isset($_POST['csrf']) || !isset($_SESSION['CSRF']) || empty($_POST['csrf']) 
    	|| $_POST['csrf'] !== $_SESSION['CSRF'])
    {
    	$errors['errorMessage'] = 'Sorry, there was a security issue';
    	
    	$badCSRF = true;
    }
	elseif (empty($userName) || empty($passWord))
	{
		$errors['errorMessage'] = 'Semua kolom harus diisi';

	}
	elseif ($admins -> usernameExists($userName) == false)
	{
		$errors['errorMessage'] = 'nama pengguna tidak terdaftar';

	}
	elseif ($admins -> cekStatusToken($userName) == false)
	{
		$errors['errorMessage'] = 'Akun belum diaktifkan';

	}
	elseif ( !ctype_alnum($userName) or !ctype_alnum($passWord))
	{
		$errors['errorMessage'] = 'Maaf, data tidak valid.';

	}
	else
	{
		if (strlen($passWord) < 6)
		{
			$errors['errorMessage'] = 'password anda salah!';

		}

		$login = $admins -> login($userName, $passWord);

		if ($login == false)
		{
			$errors['errorMessage'] = 'periksa kembali password anda!';

		}
		else
		{
		
			$badCSRF = false;
			unset($_SESSION['CSRF']);
			
			$_SESSION['KCFINDER']=array();
				
			$_SESSION['KCFINDER']['disabled'] = false;
				
			$_SESSION['KCFINDER']['uploadURL'] = "../pictures";
				
			$_SESSION['KCFINDER']['uploadDir'] = "";
			
			// time limit for accessing administrator page
			$_SESSION['limit'] = 1;
			timeKeeper();
			
			$old_session = session_id();

			session_regenerate_id(true); // destroying the old session id and creating a new one

			$new_session = session_id();

			$update_sesi = $admins -> updateAdminSession($new_session, $userName);
			
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

loginHeader( $siteName . "\n|". "\nLog In" );
?>
<div class="container">
	<div class="row">
		<div class="col-md-4 col-md-offset-4">
			<div class="login-panel panel panel-default">
				<div class="panel-heading">
				   <h3 class="panel-title">
				   <img alt="logo pilus" src="../cabin/img/iconlogopilus.png">
				   </h3>
				</div>
				<div class="panel-body">
					<?php 
					if (isset($errors['errorMessage'])) {

   echo '<div class="alert alert-danger alert-dismissable">' . $errors['errorMessage'] . '</div>';

	}

	if (isset($_GET['status']) && $_GET['status'] == 'ganti'){

     echo '<div class="alert alert-info alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Kata sandi sudah di' . $_GET['status'] . '. Silahkan masuk!</div>';

	}elseif (isset($_GET['status']) && $_GET['status'] == 'aktif')
	{
		echo '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Akun sudah di' . $_GET['status'] . 'kan. Silahkan masuk!</div>';
	}

	?>
					<form name="formlogin" method="post" action="login.php"
						onSubmit="return validasi(this)" role="form" autocomplete="off">
						<fieldset>
							<div class="form-group">
								<input class="form-control" placeholder="nama pengguna"
									name="username" type="text" required autofocus maxlength="60">
							</div>
							<div class="form-group">
								<input class="form-control" placeholder="Kata sandi"
									name="password" type="password" required maxlength="32" autocomplete="off" />
							</div>

							

    <?php 
    // create token for prevent CSRF
    $key= 'PiLu5!@#$%^&*0nLinEShoP';
    $CSRF = sha1(mt_rand(1,1000000) . $key); 
    $_SESSION['CSRF'] = $CSRF;
    ?>
     <input type="hidden" name="csrf" value="<?php echo $CSRF; ?>"/>
     <input type="submit" name="Log-In" class="btn btn-primary btn-lg btn-block" value="Masuk" />

						</fieldset>
					</form>
				</div>
			</div>

			<a href="resetPassword.php">lupa password ?</a>


		</div>
	</div>
</div>
<!-- Core Scripts - Include with every page -->

<?php 

loginFooter();
?>