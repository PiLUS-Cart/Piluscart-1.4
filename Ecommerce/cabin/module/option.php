<?php if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!");
/**
 * File Modul setting.php
 * mengelola business logic
 * pada fungsionalitas objek setting
 * 
 * @package   PiLUS_CMS
 * @author    Maoelana Noermoehammad
 * @copyright 2014 kartatopia.com
 * @license   GPL version 3.0
 * @version   1.4.0
 * @since     Since Release 1.4
 *
 */

$action = isset( $_GET['action'] ) ? htmlentities(strip_tags($_GET['action'])) : "";
$optionId = isset($_GET['optionId']) ? abs((int)$_GET['optionId']) : 0;
$options = new Option();
$accessLevel = Admin::accessLevel();

if ( $accessLevel != 'superadmin' ) {
	
	include_once( "../cabin/404.php" );
	
} else  {
	switch ($action) {
	
		//tampilkan opsi
		default:
	
			listOption();
	
			break;
				
			//set opsi
		case 'setOption':
	
			$dbh = new Pldb;
			$cekId = "SELECT option_id FROM pl_option";
			$sth = $dbh -> query($cekId);
			$ketemu = $sth -> rowCount();
	
			if ( $ketemu < 1) {
				setOption();
	
			} else {
				require( "../cabin/404.php" );
			}
	
			break;
	
			//update opsi
		case 'editOption':
	
			$cleaned = $sanitasi -> sanitasi($optionId, 'sql');
			$current_option = $options -> findById($cleaned);
			$current_id = $current_option['option_id'];
	
			if ( isset($optionId) && $current_id != $optionId ) {
	
				require( "../cabin/404.php" );
				
			} else {
				
				updateOption();
				
			}
	
			break;
	
	}
	
}

// fungsi tampil option
function listOption()
{
	$views = array();

	$views['pageTitle'] = "Pengaturan";

	$data_option = Option::getOptions();

	$views['options'] = $data_option['results'];

	foreach ($views['options'] as $opsi) :

	$sitename = $opsi -> getSite_Name();

	endforeach;

	$views['siteName'] = $sitename;

	if ( isset($_GET['error'])) {

		if ( $_GET['error'] == "optionNotFound" ) $views['errorMessage'] = "Error: Banner tidak ditemukan";

	}

	if ( isset($_GET['status'])) {

		if ( $_GET['status'] == "optionAdded") $views['statusMessage'] =   "Option baru sudah disimpan";
		if ( $_GET['status'] == "optionUpdated") $views['statusMessage'] = "Option sudah diupdate";
		if ( $_GET['status'] == "optionDeleted") $views['statusMessage'] = "Option sudah dihapus";
	}

	require( "option/siteoption.php" );
}

//fungsi set option
function setOption()
{
	global $options;

	$views = array();
	$views['pageTitle'] = "Set Pengaturan";
	$views['formAction'] = "setOption";

	if (isset($_POST['saveOption']) && $_POST['saveOption'] == 'Simpan') {
		
		$file_location = isset($_FILES['image']['tmp_name']) ? $_FILES['image']['tmp_name'] : '';
		$file_type = isset($_FILES['image']['type']) ? $_FILES['image']['type'] : '';
		$file_name = isset($_FILES['image']['name']) ? $_FILES['image']['name'] : '';

		$phone = str_replace(array(' ', '-', '(', ')'), '', $_POST['phone']);
		if (!preg_match('/^[0-9]{10,13}$/', $phone)) {
			
			$views['errorMessage'] = "Nomor Telepon tidak valid!";
			require( "option/edit-siteoption.php" );
			
		} 
		$faxNumber = str_replace(array(' ', '-', '(', ')'), '', $_POST['fax']);
		if (!preg_match('/^[0-9]{10,13}$/', $faxNumber)) {
			  
			$views['errorMessage'] = "Nomor Faksimile tidak valid!";
			require( "option/edit-siteoption.php" );
			
		} elseif (!empty($file_location)) {
			
			if ($file_type != 'image/png') {
				$views['errorMessage'] = "Tipe file yang anda upload salah";
				require( "option/edit-siteoption.php" );
				
			} else {
				
				uploadFavicon($file_name);
				$data = array(
							
						'site_name' => isset($_POST['site_name']) ? preg_replace('/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/', '', $_POST['site_name']) : '',
						'meta_description' => isset($_POST['meta_desc']) ? preg_replace('/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/', '', $_POST['meta_desc']) : '',
						'meta_keywords' => isset($_POST['meta_key']) ? preg_replace('/[^ \'\,\.\-a-zA-Z0-9]/', '', $_POST['meta_key']) : '',
						'tagline' => isset($_POST['tagline']) ? preg_replace('/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/', '', $_POST['tagline']) : '',
						'shop_address' => isset($_POST['shop_address']) ? preventInject($_POST['shop_address']) : '',
						'owner_email' => isset($_POST['email']) ? preg_replace('/[^ \@\.\-\_a-zA-Z0-9]/', '', $_POST['email']) : '',
						'nomor_rekening' => preventInject($_POST['no_rekening']),
						'nomor_telpon' => $phone,
						'nomor_fax' => $faxNumber,
						'instagram' => isset($_POST['instagram']) ? preg_replace('/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/', '', $_POST['instagram']) : '',
						'twitter' => isset($_POST['twitter']) ? preg_replace('/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/', '', $_POST['twitter']) : '',
						'facebook' => isset($_POST['facebook']) ? preg_replace('/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/', '', $_POST['facebook']) : '',
						'pin_bb' => preventInject($_POST['pin_bb']),
						'favicon' => $file_name
				);
					
				$setting = new Option($data);
				$setting -> createOption();
					
				echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=option&status=optionAdded">';
					
				exit();

			}

		} else {
			
			$data = array(

					'site_name' => isset($_POST['site_name']) ? preg_replace('/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/', '', $_POST['site_name']) : '',
					'meta_description' => isset($_POST['meta_desc']) ? preg_replace('/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/', '', $_POST['meta_desc']) : '',
					'meta_keywords' => isset($_POST['meta_key']) ? preg_replace('/[^ \'\,\.\-a-zA-Z0-9]/', '', $_POST['meta_key']) : '',
					'tagline' => isset($_POST['tagline']) ? preg_replace('/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/', '', $_POST['tagline']) : '',
					'shop_address' => isset($_POST['shop_address']) ? preventInject($_POST['shop_address']) : '',
					'owner_email' => isset($_POST['email']) ? preg_replace('/[^ \@\.\-\_a-zA-Z0-9]/', '', $_POST['email']) : '',
					'nomor_rekening' => preventInject($_POST['no_rekening']),
					'nomor_telpon' => $phone,
					'nomor_fax' => $faxNumber,
					'instagram' => isset($_POST['instagram']) ? preg_replace('/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/', '', $_POST['instagram']) : '',
					'twitter' => isset($_POST['twitter']) ? preg_replace('/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/', '', $_POST['twitter']) : '',
					'facebook' => isset($_POST['facebook']) ? preg_replace('/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/', '', $_POST['facebook']) : '',
					'pin_bb' => preventInject($_POST['pin_bb'])

			);

			$setting = new Option($data);
			$setting -> createOption();

			echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=option&status=optionAdded">';

			exit();
		}
		
	} else {
		
		$views['Option'] = $options;
		require( "option/edit-siteoption.php" );
	}

}

// fungsi update option
function updateOption()
{
	global $options, $optionId;

	$views = array();

	$views['pageTitle'] = "Edit Pengaturan";
	$views['formAction'] = "editOption";

	if (isset($_POST['saveOption']) && $_POST['saveOption'] == 'Simpan') {
		
		$file_location = isset($_FILES['image']['tmp_name']) ? $_FILES['image']['tmp_name'] : '';
		$file_type = isset($_FILES['image']['type']) ? $_FILES['image']['type'] : '';
		$file_name = isset($_FILES['image']['name']) ? $_FILES['image']['name'] : '';
		$faxNumber = str_replace(array(' ', '-', '(', ')'), '', $_POST['fax']);
		$phone = str_replace(array(' ', '-', '(', ')'), '', $_POST['phone']);
		
		if (!preg_match('/^[0-9]{10,13}$/', $phone)) {
			
			$views['errorMessage'] = "Nomor Telepon tidak valid!";
			require( "option/edit-siteoption.php" );
			
		} elseif (empty($file_location)) {
			
			$data = array(

					'option_id' => isset($_POST['option_id']) ? abs((int)$_POST['option_id']) : '',
					'site_name' => isset($_POST['site_name']) ? preg_replace('/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/', '', $_POST['site_name']) : '',
					'meta_description' => isset($_POST['meta_desc']) ? preg_replace('/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/', '', $_POST['meta_desc']) : '',
					'meta_keywords' => isset($_POST['meta_key']) ? preg_replace('/[^ \'\,\.\-a-zA-Z0-9]/', '', $_POST['meta_key']) : '',
					'tagline' => isset($_POST['tagline']) ? preg_replace('/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/', '', $_POST['tagline']) : '',
					'shop_address' => isset($_POST['shop_address']) ? preventInject($_POST['shop_address']) : '',
					'owner_email' => isset($_POST['email']) ? preg_replace('/[^ \@\.\-\_a-zA-Z0-9]/', '', $_POST['email']) : '',
					'nomor_rekening' => preventInject($_POST['no_rekening']),
					'nomor_telpon' => $phone,
					'nomor_fax' => $faxNumber,
					'instagram' => isset($_POST['instagram']) ? preg_replace('/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/', '', $_POST['instagram']) : '',
					'twitter' => isset($_POST['twitter']) ? preg_replace('/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/', '', $_POST['twitter']) : '',
					'facebook' => isset($_POST['facebook']) ? preg_replace('/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/', '', $_POST['facebook']) : '',
					'pin_bb' => preventInject($_POST['pin_bb'])
						
			);

			$setting = new Option($data);
			$setting -> updateOption();

			echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=option&status=optionUpdated">';

			exit();

		}
		else
		{
			if ($file_type != "image/png")
			{
				$views['errorMessage'] = "Tipe file yang anda upload salah";
				require( "option/edit-siteoption.php" );

			}
			else
			{
				$opsi = '';
				$opsi = $options -> findById($optionId);
				if ($opsi['favicon'] != '')
				{
					$data = array('option_id' => $optionId);

					$hapus_option = new Option($data);
					$hapus_option -> deleteOption();

					unlink("../content/uploads/images/$opsi[favicon]");
					unlink("../content/uploads/images/thumbs/thumb_$opsi[favicon]");

				}

				uploadFavicon($file_name);

				$data = array(
							
						'option_id' => isset($_POST['option_id']) ? abs((int)$_POST['option_id']) : '',
						'site_name' => isset($_POST['site_name']) ? preg_replace('/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/', '', $_POST['site_name']) : '',
						'meta_description' => isset($_POST['meta_desc']) ? preg_replace('/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/', '', $_POST['meta_desc']) : '',
						'meta_keywords' => isset($_POST['meta_key']) ? preg_replace('/[^ \'\,\.\-a-zA-Z0-9]/', '', $_POST['meta_key']) : '',
						'tagline' => isset($_POST['tagline']) ? preg_replace('/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/', '', $_POST['tagline']) : '',
						'shop_address' => isset($_POST['shop_address']) ? preventInject($_POST['shop_address']) : '',
						'owner_email' => isset($_POST['email']) ? preg_replace('/[^ \@\.\-\_a-zA-Z0-9]/', '', $_POST['email']) : '',
						'nomor_rekening' => preventInject($_POST['no_rekening']),
						'nomor_telpon' => $phone,
						'nomor_fax' => $faxNumber,
						'instagram' => isset($_POST['instagram']) ? preg_replace('/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/', '', $_POST['instagram']) : '',
						'twitter' => isset($_POST['twitter']) ? preg_replace('/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/', '', $_POST['twitter']) : '',
						'facebook' => isset($_POST['facebook']) ? preg_replace('/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/', '', $_POST['facebook']) : '',
						'pin_bb' => preventInject($_POST['pin_bb']),
						'favicon' => $file_name
				);
					
				$setting = new Option($data);
				$setting -> updateOption();

				echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=option&status=optionUpdated">';

				exit();

			}
		}
	}
	else
	{
		$views['Option'] = $options -> getOption($optionId);
		$views['favicon'] = $views['Option'] -> getFavicon();

		require( "option/edit-siteoption.php" );
	}

}

//fungsi hapus option
function deleteOption()
{
	global $options;

	global $optionId;

	if (!$option = $options -> getOption($optionId)) {
		
		require( "../cabin/404.php" );
	}

	$favicon_img = $option -> getFavicon();
	if ($favicon_img != '') {
		
		$data = array('option_id' => $optionId);

		$hapus_option = new Option($data);
		$hapus_option -> deleteOption();

		unlink("../content/uploads/images/$favicon_img");
		unlink("../content/uploads/images/thumbs/thumb_$favicon_img");

	} else {
		
		$data = array('option_id' => $optionId);

		$hapus_option = new Option($data);
		$hapus_option -> deleteOption();
		
		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=option&status=optionDeleted">';
		
		exit();
		
	}

}