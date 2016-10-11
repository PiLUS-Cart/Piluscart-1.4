<?php if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!");
/**
 * File Modul users.php
 * mengelola business logic
 * pada fungsionalitas objek user
 * 
 * @package   PiLUS_CMS
 * @author    Maoelana Noermoehammad
 * @copyright 2014 kartatopia.com
 * @license   GPL version 3.0
 * @version   1.4.0
 * @since     Since Release 1.4
 *
 */

$action = isset($_GET['action']) ? htmlentities(strip_tags($_GET['action'])) : "";
$adminId = isset($_GET['userId']) ? abs((int)$_GET['userId']) : 0;
$sessionId = isset($_GET['sessionId']) ? $_GET['sessionId'] : "";
$userName = isset($_SESSION['adminLogin']) ? $_SESSION['adminLogin'] : "";
$admins = new Admin();
$myProfile = new MyProfile();
$accessLevel = Admin::accessLevel();

switch ($action)
{

	//tampil administrator
	default:

		if ( $accessLevel != 'superadmin')
		{
			showMyProfile();

		}
		else
		{
			listUsers();
		}

		break;

		//tambah pengguna -- hak akses superadmin
	case 'newUser':

		if ( $accessLevel != 'superadmin')
		{
			require( "../cabin/404.php" );
		}
		else
		{
			newUser();
		}

		break;

		//update pengguna 
	case 'editUser':

		$cleaned = $sanitasi -> sanitasi($adminId, 'sql');
		$current_user = $admins -> findById($cleaned, $sessionId);
		$current_id = $current_user -> ID;
		$current_session = $current_user -> admin_session;

		if (isset($adminId) && $adminId != $current_id )
		{
			require( "../cabin/404.php" );
		
		}
		elseif ( isset($sessionId) && $current_session != $sessionId )
		{
			require( "../cabin/404.php" );
		}
		else
		{
			if ( $accessLevel != 'superadmin')
			{
		
				updateMyProfil();
					
			}
			else
			{
				editUser(); // hak akses superadmin
			}
		
		}
		
		break;

		//hapus pengguna -- hak akses superadmin
	case 'deleteUser':

		if ( $accessLevel != 'superadmin')
		{
			require( "../cabin/404.php" );
		}
		else
		{
			deleteUser();
		}

		break;

		//update meta user
	case 'userMeta':

		$sanitized =  $sanitasi -> sanitasi($adminId, 'sql');
		$current_meta = $admins -> findMetaById($sanitized, $userName);
		$current_metaId = $current_meta -> admeta_id;
		
		if ( isset($current_metaId) && $current_metaId != $adminId)
		{
			require( "../cabin/404.php" );
		}
		elseif ( $accessLevel == 'superadmin') // hak akses superadmin
		{
			userMeta();
		}
		else 
		{
			profilMeta();
		}
	
		break;

}

// fungsi tampilkan staff dengan hak akses superadmin
function listUsers() {

	$views = array();

	$p = new Pagination();
	$limit = 10;
	$position = $p -> getPosition($limit);

	$data_user = Admin::getListUsers($position, $limit);

	$views['admins'] = $data_user['results'];
	$views['totalRows'] = $data_user['totalRows'];
	$views['position'] = $position;
	$views['pageTitle'] = "Semua Staff";

	//pagination
	$totalPage = $p -> totalPage($views['totalRows'], $limit);
	$pageLink = $p -> navPage($_GET['order'], $totalPage);
	$views['pageLink'] = $pageLink;


	if ( isset($_GET['error'])) {

		if ( $_GET['error'] == "userNotFound" ) $views['errorMessage'] = "Error: Pengguna tidak ditemukan";

	}

	if ( isset($_GET['status'])) {

		if ( $_GET['status'] == "userAdded") $views['statusMessage'] =  "Data Pengguna sudah disimpan dan telah dikirim ke email yang bersangkutan";
		if ( $_GET['status'] == "userUpdated") $views['statusMessage'] = "Data Pengguna sudah diupdate";
		if ( $_GET['status'] == "userDeleted") $views['statusMessage'] = "Pengguna telah dihapus";
	}

	require("user/list-users.php");

}

// fungsi menampilkan record data pengguna selain superadmin
function showMyProfile()
{
	global $admins, $userName;

	$views = array();

	$data_profil = MyProfile::getBiodata($userName);

	$views['pageTitle'] = "Biodata";
	$views['userId'] = $data_profil['ID'];
	$views['username'] = $data_profil['admin_login'];
	$views['fullname'] = $data_profil['admin_fullname'];
	$views['Email'] = $data_profil['admin_email'];
	$views['level'] = $data_profil['admin_level'];
	$views['user_session'] = $data_profil['admin_session'];


	if ( isset($_GET['error'])) {

		if ( $_GET['error'] == "userNotFound" ) $views['errorMessage'] = "Error: Pengguna tidak ditemukan";

	}

	if ( isset($_GET['status'])) {

		if ( $_GET['status'] == "userUpdated") $views['statusMessage'] = "Data Pengguna sudah diupdate";
		if ( $_GET['status'] == "userDeleted") $views['statusMessage'] = "Pengguna telah dihapus";
	}
	require( "biodata/biodata.php");
}

// fungsi tambah pengguna dengan hak akses superadmin
function newUser() {

	global $admins, $option;

	$views = array();

	$views['pageTitle'] = "Tambah Staff";
	$views['formAction'] = "newUser";

	$userLevel = $admins -> getLevel_dropDown();

	if (isset($_POST['saveAdmin']) && $_POST['saveAdmin'] == 'Simpan') {
		
		$admin_login = preventInject($_POST['admin_login']);
		$admin_fullname = preventInject($_POST['admin_fullname']);
		$admin_email = trim($_POST['admin_email']);
		$admin_pass = preventInject($_POST['admin_pass']);
		$confirm_pass = preventInject($_POST['confirm_pass']);
		$admin_registered = date("Ymd");
		$admin_activation_key = createActivationKey($admin_email);
		$admin_level = trim($_POST['admin_level']);
		$admin_session = trim(md5($_POST['admin_pass']));
		$admin_url = validhttp($_POST['admin_url']);

		if (empty($admin_login) || empty($admin_fullname) || empty($admin_email) || empty($admin_pass) || empty($confirm_pass))
		{
			$views['errorMessage'] = "Kolom dengan tanda asterik(*) harus diisi";
			require( 'user/edit-user.php' );
		}
		else
		{
			
			//cek username
			if ($admins -> usernameExists($admin_login) == true)
			{
				$views['errorMessage'] = "Nama pengguna sudah terpakai";
				require('user/edit-user.php');
			}

			if (!ctype_alnum($admin_login))
			{
				$views['errorMessage'] = "Ketikkan nama pengguna hanya menggunakan angka dan huruf";
				require('user/edit-user.php');
			}

			//cek fullname
			if (!preg_match('/^[A-Z \'.-]{2,90}$/i', $admin_fullname))
			{
				$views['errorMessage'] = "Ketikkan nama lengkap hanya menggunakan huruf";
				require('user/edit-user.php');
			}

				
			//cek Email
			if (strlen($admin_email))
			{
				if (is_valid_email_address(trim($admin_email)) == 0) { 
					$views['errorMessage'] = "Penulisan E-mail tidak valid";
					require('user/edit-user.php');
				}
				
			}

			elseif ($admins -> emailExists($admin_email) == true)
			{
				$views['errorMessage'] = "Alamat E-mail sudah dipakai";
				require('user/edit-user.php');
			}
				
			//Cek password
			elseif (!isset($admin_pass) || !isset($confirm_pass) || !$admin_pass || !$confirm_pass || $admin_pass != $confirm_pass)
			{
				$views['errorMessage'] = "Masukkan konfirmasi password sesuai dengan password anda";
				require('user/edit-user.php');
			}

			elseif (strlen($admin_pass) < 6)
			{
				$views['errorMessage'] = 'Kata sandi tidak boleh kurang dari 6 karakter';
				require( "user/edit-user.php" );
			}

			elseif (strlen($confirm_pass) < 6)
			{
				$views['errorMessage'] = 'Kata sandi konfirmasi tidak boleh kurang dari 6 karakter';
				require( "user/edit-user.php" );
			}

		}

		if (empty($views['errorMessage']) === true)
		{
			
			$data = array(

					'admin_login' => $admin_login,
					'admin_fullname' => $admin_fullname,
					'admin_email' => $admin_email,
					'admin_pass' => $admin_pass,
					'admin_registered' => $admin_registered,
					'admin_activation_key' => $admin_activation_key,
					'admin_level' => $admin_level,
					'admin_session' => $admin_session,
					'admin_url' => $admin_url
					
			);

			$admin_baru = new Admin($data);
				
			$admin_baru -> createAdmin();
			
			// Mengambil data pemilik toko
			$metaowner = '';
			
			$data_owner = $option -> getOptions();
			
			$metaowner = $data_owner['results'];
			
			foreach ( $metaowner as $owner )
			{
				$namaToko = $owner -> getSite_Name();
			}
			
			$kepada = safeEmail($admin_email);
			$subyek = "Join for The best Crew in Town!";
			$pesan = "<html>
					<body>
					Jika anda tidak pernah meminta menjadi crew di $namaToko, silahkan untuk menghiraukan email ini.<br />
					Tetapi jika anda memang yang meminta pesan informasi ini, berikut data profil anda: <br><br>
					<b>username:</b> $admin_login <br />
					<b>password:</b> $admin_pass <br />
					Aktikan segera akun crew tersebut dengan mengklik tautan (link) di bawah ini :<br /><br />
					<a href=".PL_CABIN."activate.php?key=$admin_activation_key >Aktifkan Akun Saya</a><br /><br />
					Terima kasih,<br />
					<b>$namaToko</b>
					</body>
					</html>";
				
			$kirim_pesan = new Mailer();
				
			$kirim_pesan -> setSendText(false);
			$kirim_pesan -> setSendTo($kepada);
			$kirim_pesan -> setFrom($namaToko);
			$kirim_pesan -> setSubject($subyek);
			$kirim_pesan -> setHTMLBody($pesan);
            /*
			if ( $kirim_pesan -> send())
			{
				
				echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=users&status=userAdded">';
				
				exit();
				
			}
			*/
				
		}

	}
	else
	{
		
		$views['User'] = $admins;
		$views[ 'userLevel'] = $userLevel;

		require( "user/edit-user.php" );
		
	}
}

// fungsi update biodata staff dengan hak akses superadmin
function editUser() {

	global $admins, $adminId, $sessionId;

	$views = array();

	$views['formAction'] = "editUser";
	$views['pageTitle'] = "Biodata Staff";

	if (isset($_POST['saveAdmin']) && $_POST['saveAdmin'] == 'Simpan')
	{

		$admin_session = $_POST['session_id'];
		$admin_fullname = preventInject($_POST['admin_fullname']);
		$admin_email = preventInject($_POST['admin_email']);
		$admin_level = trim($_POST['admin_level']);
		$admin_url = validhttp($_POST['admin_url']);
			
		if (empty($_POST['admin_pass'])) {

			$data = array(

					'admin_fullname' => $admin_fullname,
					'admin_email' => $admin_email,
					'admin_level' => $admin_level,
					'admin_session' => $admin_session,
					'admin_url' => $admin_url

			);

			$edit_pengguna = new Admin($data);
			$edit_pengguna -> updateAdmin();
			
			echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=users&status=userUpdated">';

			exit();

		}
		else
		{
			
			$data = array(

					'admin_fullname' => $admin_fullname,
					'admin_email' => $admin_email,
					'admin_pass' => isset($_POST['admin_pass']) ? preg_replace('/[^ \-\_a-zA-Z0-9]/', '', $_POST['admin_pass']) : '',
					'admin_level' => $admin_level,
					'admin_session' => $admin_session,
					'admin_url' => $admin_url

			);


			$edit_pengguna = new Admin($data);
			$edit_pengguna -> updateAdmin();

			echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=users&status=userUpdated">';

			exit();

		}
			
	}
	else
	{
		$getUserMeta = '';
		$getUserMeta = $admins -> fetchUserMeta($adminId, $sessionId);
		$views['userPhone'] = $getUserMeta -> getAdmeta_Phone();
		$views['userMetaId']= $getUserMeta -> getAdmeta_Id();
		// get user's record by their session
		$views['User'] = $admins -> getUserBySession($sessionId); 
		$views['userId'] = $views['User'] -> getId();
		$views['sessionId'] = $views['User'] -> getSession_Key();
		$views['userLevel'] = $views['User'] -> getLevel_dropDown();

		require( "user/edit-user.php" );
		
	}

}

//fungsi update biodata dengan hak akses selain superadmin
function updateMyProfil() {

	global $myProfile, $adminId, $sessionId, $userName;

	$views = array();
	$views['pageTitle'] = "Biodata";
	$views['formAction']= "editUser";
	
	if (isset($_POST['saveProfil']) && $_POST['saveProfil'] == 'Simpan')
	{
		$admin_session = $_POST['sesi_id'];
		$admin_fullname = preventInject($_POST['admin_fullname']);
		$admin_email = preventInject($_POST['admin_email']);
		$admin_url = validhttp($_POST['admin_url']);


		if ($admin_fullname == '' OR $admin_email == '')
		{
			$views['errorMessage'] = "Kolom nama lengkap dan email harus diisi";
			require( "biodata/edit-biodata.php" );
		}

		if (empty($admin_pass))
		{
			$data = array(

					'admin_fullname' => $admin_fullname,
					'admin_email' => $admin_email,
					'admin_session' => $admin_session,
					'admin_url' => $admin_url
			);

		}
		else
		{
			$data = array(

					'admin_fullname' => $admin_fullname,
					'admin_email' => $admin_email,
					'admin_pass' => isset($admin_pass) ? preg_replace('/[^ \-\_a-zA-Z0-9]/', '', $admin_pass) : '',
					'admin_session' => $admin_session,
					'admin_url' => validHttp($admin_url)
			);


		}

		$edit_profil = new MyProfile($data);
		$edit_profil -> updateMyProfile();

		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=users&status=userUpdated">';

	}
	else
	{
		$biodata = $myProfile -> getBiodata($userName);
		$views['user_id'] = $biodata['ID'];
		$views['user_sesi'] = $biodata['admin_session'];
		$views['user_name'] = $biodata['admin_login'];
		$views['user_fullname'] = $biodata['admin_fullname'];
		$views['user_email'] = $biodata['admin_email'];
		$views['user_url'] = $biodata['admin_url'];

		$myMetaProfile = $myProfile -> findPhone($biodata['ID']);
		$views['user_meta_phone'] = $myMetaProfile -> admeta_phone;
		$views['user_meta_id'] = $myMetaProfile -> admeta_id;
		
		require( "biodata/edit-biodata.php" );
		
	}

}

//fungsi hapus pengguna dengan hak akses superadmin
function deleteUser() {

	global $admins, $adminId;
	
	if ( !$user = $admins -> getUserById($adminId))
	{
		require( "../cabin/404.php" );
	}
	
	$data = array('ID' => $adminId);
	
	$hapus_admin = new Admin($data);
	$hapus_admin -> deleteAdmin();
	
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=users&status=userDeleted">';
	
	exit();
	
}

// fungsi update usermeta dengan hak akses superadmin
function userMeta() {

	global $admins, $adminId, $sessionId;

	$views = array();
	$views['pageTitle'] = "Biodata Staff";
	$views['formAction']= "userMeta";

	if (isset($_POST['saveAdmin']) && $_POST['saveAdmin'] == 'Simpan')
	{
		
		
		$file_location = isset($_FILES['image']['tmp_name']) ? $_FILES['image']['tmp_name'] : '';
		$file_type = isset($_FILES['image']['type']) ? $_FILES['image']['type'] : '';
		$file_name = isset($_FILES['image']['name']) ? $_FILES['image']['name'] : '';

		$phone = isset($_POST['phone']) ? str_replace(array(' ', '-', '(', ')'), '', $_POST['phone']) : "";
		
		if (!preg_match('/^[0-9]{10,13}$/', $phone))
		{
			$views['errorMessage'] = "Nomor Telepon tidak valid!";
			require( "user/metauser.php" );
		}
		elseif (empty($file_location))
		{
				
			$data = array(

					'admeta_id' => isset($_POST['admeta_id']) ? (int)$_POST['admeta_id'] : '',
					'admeta_address' => isset($_POST['address']) ? preventInject($_POST['address']) : '',
					'admeta_gender' => isset($_POST['gender']) ? preg_replace('/[^LP]/', '', $_POST['gender']) : '',
					'admeta_borndate' => isset($_POST['tanggal']) ? tgl_ind_to_eng($_POST['tanggal']) : '',
					'admeta_phone' => $phone,
					'admeta_bio' => isset($_POST['bio']) ? preventInject($_POST['bio']) : ''
			);

			$edit_userMeta = new Admin($data);
			$edit_userMeta -> updateUserMeta();


			echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=users&status=userUpdated">';

			exit();

		}
		else
		{
			if ($file_type != "image/jpeg"  and $file_type != "image/pjpeg"  )
			{
				$views['errorMessage'] = "Tipe file atau ukuran file anda salah";
				require( "user/metauser.php" );
					
			}
			else
			{

				uploadAvatar($file_name);

				$data = array(


						'admeta_id' => isset($_POST['admeta_id']) ? (int)$_POST['admeta_id'] : '',
						'admeta_address' => isset($_POST['address']) ? preg_replace('/[^ \'\,\.\-a-zA-Z0-9]/', '', $_POST['address']) : '',
						'admeta_gender' => isset($_POST['gender']) ? preg_replace('/[^LP]/', '', $_POST['gender']) : '',
						'admeta_borndate' => isset($_POST['tanggal']) ? tgl_ind_to_eng($_POST['tanggal']) : '',
						'admeta_phone' => $phone,
						'admeta_bio' => isset($_POST['bio']) ? preg_replace('/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/', '', $_POST['bio']) : '',
						'admeta_avatar' => $file_name
				);

				$edit_userMeta = new Admin($data);
				$edit_userMeta -> updateUserMeta();


				echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=users&status=userUpdated">';
					
				exit();

			}
		}
	}
	else
	{
		$user_meta = '';
		if (!$user_meta = $admins -> fetchUserMeta($adminId, $sessionId))
		{
			require( "../cabin/404.php" );
		}
		else 
		{
			$views['userMeta'] = $user_meta;
			$views['admin_id'] = $views['userMeta'] -> getId();
			$views['admeta_id'] = $views['userMeta'] -> getAdmeta_Id();
			$views['gender'] = $views['userMeta'] -> getAdmeta_Gender();
			$views['imagePath'] = $views['userMeta'] -> getAdmeta_Avatar();
			
			require( "user/metauser.php" );
		}
		
	}

}

// fungsi update usermeta dengan hak akses selain superadmin
function profilMeta()
{
	global $myProfile, $adminId, $sessionId, $userName;

	$views = array();
	$views['pageTitle'] = "Biodata";
	$views['formAction']= "userMeta";

	if (isset($_POST['saveProfil']) && $_POST['saveProfil'] == 'Simpan')
	{
		$file_location = isset($_FILES['image']['tmp_name']) ? $_FILES['image']['tmp_name'] : '';
		$file_type = isset($_FILES['image']['type']) ? $_FILES['image']['type'] : '';
		$file_name = isset($_FILES['image']['name']) ? $_FILES['image']['name'] : '';

		$phone = str_replace(array(' ', '-', '(', ')'), '', $_POST['phone']);
		if (!preg_match('/^[0-9]{10,13}$/', $phone))
		{
			$views['errorMessage'] = "Nomor Telepon tidak valid!";
			require( "biodata/metabiodata.php" );
		}
		elseif (empty($file_location))
		{
			$data = array(
						
					'admeta_id' => isset($_POST['admeta_id']) ? (int)$_POST['admeta_id'] : '',
					'admeta_address' => isset($_POST['address']) ? preventInject($_POST['address']) : '',
					'admeta_gender' => isset($_POST['gender']) ? preg_replace('/[^LP]/', '', $_POST['gender']) : '',
					'admeta_borndate' => isset($_POST['tanggal']) ? tgl_ind_to_eng($_POST['tanggal']) : '',
					'admeta_phone' => $phone,
					'admeta_bio' => isset($_POST['bio']) ? preventInject($_POST['bio']) : ''
			);
				
			$edit_biodata = new MyProfile($data);
			$edit_biodata -> updateMyMetaProfile();
				
			echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=users&status=userUpdated">';

			exit();
		}
		else
		{
			uploadAvatar($file_name);
				
			$data = array(
						
						
					'admeta_id' => isset($_POST['admeta_id']) ? (int)$_POST['admeta_id'] : '',
					'admeta_address' => isset($_POST['address']) ? preg_replace('/[^ \'\,\.\-a-zA-Z0-9]/', '', $_POST['address']) : '',
					'admeta_gender' => isset($_POST['gender']) ? preg_replace('/[^LP]/', '', $_POST['gender']) : '',
					'admeta_borndate' => isset($_POST['tanggal']) ? tgl_ind_to_eng($_POST['tanggal']) : '',
					'admeta_phone' => $phone,
					'admeta_bio' => isset($_POST['bio']) ? preg_replace('/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/', '', $_POST['bio']) : '',
					'admeta_avatar' => $file_name
			);
				
			$edit_biodata = new MyProfile($data);
			$edit_biodata -> updateMyMetaProfile();
				
			echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=users&status=userUpdated">';
				
			exit();

		}
	}
	else
	{
		$profilMeta = '';
		if ( !$profilMeta = $myProfile -> getMyProfile($adminId, $sessionId, $userName))
		{
			require( "../cabin/404.php" );
		}
		else 
		{
			
			$views['profilMeta'] = $profilMeta;
			$views['myMetaId'] = $views['profilMeta'] -> getAdmeta_Id();
			$views['myGender'] = $views['profilMeta'] -> getAdmeta_Gender();
			$views['imagePath']= $views['profilMeta'] -> getAdmeta_Avatar();
			
			require( "biodata/metabiodata.php" );
				
		}
		
		
	}

}