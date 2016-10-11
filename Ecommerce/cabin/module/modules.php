<?php if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!");
/**
 * File Modul modules.php
 * mengelola business logic
 * pada fungsionalitas objek module
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
$moduleId = isset($_GET['moduleId']) ? abs((int)$_GET['moduleId']) : 0;
$modules = new Module();
$accessLevel = Admin::accessLevel();

if ( $accessLevel != 'superadmin')
{
	include_once('../cabin/404.php');
}
else 
{
	switch ($action)
	{
		//tampilkan modul
		default:
	
			listModules();
	
			break;
	
			//tambah modul
		case 'newModule':
	
			addModule();
	
			break;
	
			//update modul
		case 'editModule':
	
			$cleaned = $sanitasi -> sanitasi($moduleId, 'sql');
			$current_module = $modules -> findById($cleaned);
			$current_id = $current_module['module_id'];
	
			if ( isset($moduleId) && $current_id != $moduleId )
			{
				require( "../cabin/404.php" );
			}
			else
			{
				updateModule();
			}
	
			break;
	
			//mengaktifkan modul
		case 'activateModul':
	
			activateModul();
	
			break;
	
			//non-aktif kan modul
		case 'deactivateModul':
	
			deactivateModul();
	
			break;
	
		case 'installModule':
	
			setupModul();
	
			break;
	
			//hapus modul
		case 'deleteModule':
	
			deleteModule();
	
			break;
	}
	
}

//fungsi tampil Modul
function listModules() {

	$views = array();

	$p = new Pagination;
	$limit = 10;
	$position = $p -> getPosition($limit);

	$data_module = Module::getModules($position, $limit);

	$views['modules'] = $data_module['results'];
	$views['totalRows'] = $data_module['totalRows'];
	$views['position'] = $position;
	$views['pageTitle'] = "Modul";

	//pagination
	$totalPage = $p -> totalPage($views['totalRows'], $limit);
	$pageLink = $p -> navPage($_GET['order'], $totalPage);
	$views['pageLink'] = $pageLink;

	if ( isset($_GET['error']))
	{
		if ( $_GET['error'] == "moduleNotFound" ) $views['errorMessage'] = "Error: Modul tidak ditemukan";
		if ( $_GET['error'] == "tableNotFound" ) $views['errorMessage'] = "Error: Tabel Modul tidak ditemukan";

	}

	if ( isset($_GET['status']))
	{

		if ( $_GET['status'] == "moduleAdded") $views['statusMessage'] =  "Modul baru sudah disimpan";
		if ( $_GET['status'] == "moduleInstalled") $views['statusMessage'] =  "Modul baru sudah diinstall";
		if ( $_GET['status'] == "moduleUpdated") $views['statusMessage'] = "Modul sudah diupdate";
		if ( $_GET['status'] == "moduleActivated") $views['statusMessage'] = "Modul sudah diaktifkan";
		if ( $_GET['status'] == "moduleDeactivated") $views['statusMessage'] = "Modul sudah dinonaktifkan";
		if ( $_GET['status'] == "moduleDeleted") $views['statusMessage'] = "Modul sudah dihapus";
	}

	require( "modul/list-modules.php" );

}

//fungsi tambah modul
function addModule() {

	global $modules, $sanitasi;

	$views = array();
	$views['pageTitle'] = "Tambah Modul";
	$views['formAction'] = "newModule";

	$roleLevel = $modules -> getRole_DropDown();


	if (isset($_POST['saveModule']) && $_POST['saveModule'] == 'Simpan')
	{

		$module_name = $_POST['module_name'];
		$module_link = $_POST['module_link'];
		$description = preventInject($_POST['description']);
		$module_level = $_POST['role_level'];


		if (empty($module_name) OR empty($description) )
		{
			$views['errorMessage'] = "Kolom yang bertanda asterik(*) harus diisi";
			require( "modul/edit-module.php" );
		}
		else
		{
			//cek module name
			if ($modules -> moduleExists($module_name) == true)
			{
				$views['errorMessage'] = "Nama modul sudah digunakan";
				require( "modul/edit-module.php" );
			}
		}

		if (empty($views['errorMessage']) == true)
		{
			$data = array(

					'module_name' => isset($module_name) ? preg_replace('/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/', '', $module_name) : '',
					'link' => $module_link,
					'description' => $description,
					'role_level' => $module_level
			);

			$modul_baru = new Module($data);
			$modul_baru -> createModule();

			echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=modules&status=moduleAdded">';
				
			exit();
		}

	}
	else
	{
		$views['Module'] = $modules;
		$views['roleLevel'] = $roleLevel;

		require( "modul/edit-module.php" );
	}
}

//fungsi intall modul
function setupModul() {

	global $modules;

	$views =  array();
	$views['pageTitle'] = "Install Modul";
	$views['formAction']= "installModule";

	$roleLevel = $modules -> getRole_DropDown();

	if (isset($_POST['saveModul']) && $_POST['saveModul'] == 'Install')
	{
		$file_name = $_FILES['zip_file']['name'] ;
		$file_location =  $_FILES['zip_file']['tmp_name'];
		$file_type =  $_FILES['zip_file']['type'];

		$modul = current(explode(".", $file_name));
		$pecah = explode(".", $file_name);
		$ekstensi = $pecah[1];
		$deskripsi = isset($_POST['description']) ? preventInject($_POST['description']) : '';
		$module_level = htmlspecialchars($_POST['role_level']);

		$accepted_types = array('application/zip', 'application/x-zip-compressed', 'multipart/x-zip', 'application/x-compressed');
		foreach($accepted_types as $mime_type) {
			if($mime_type == $type) {
				$okay = true;
				break;
			}
		}

		$validasi = strtolower($ekstensi) == 'zip' ? true : false;

		$tautan = "?module=$modul";


		if ( !$validasi)
		{
			$views['errorMessage'] = "Instalasi modul gagal, Pastikan file yang di Upload bertipe *.zip";
			require( "modul/install-module.php" );
		}
		elseif (empty($deskripsi) OR empty($file_location))
		{
			$views['errorMessage'] = "Maaf, Kolom yang bertanda asterik(*) harus diisi";
			require( "modul/install-module.php" );
		}
		elseif (file_exists( "../cabin/module/$modul.php"))
		{
			$views['errorMessage'] = "Maaf, Anda telah menginstall modul ini sebelumnya. Silahkan Upload Modul lain";
			require( "modul/install-module.php" );
		}
		else
		{
			$install_module = uploadModul($file_name, $file_location);
				
			$data = array(
						
					'module_name' => $modul,
					'link' => $tautan,
					'description' => $deskripsi,
					'role_level' => $module_level
			);
				
			$setup_modul = new Module($data);
			$setup_modul -> createModule();
				
				
			echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=modules&status=moduleInstalled">';
				
			exit();
		}
	}
	else
	{
		$views['Module'] = $modules;
		$views['roleLevel'] = $roleLevel;
		require( "modul/install-module.php" );
	}
}

//fungsi update modul
function updateModule() {

	global $modules;

	global $moduleId;

	$views = array();

	$views['formAction'] = "editModule";
	$views['pageTitle'] = "Edit Modul";

	if (isset($_POST['saveModule']) && $_POST['saveModule'] == 'Simpan')
	{

		$module_name = preventInject($_POST['module_name']);
		$module_link = preventInject($_POST['module_link']);
		$description = preventInject($_POST['description']);
		$module_level = htmlspecialchars($_POST['role_level']);
		$actived = htmlspecialchars($_POST['status']);
		$urutan = abs((int)$_POST['sort']);
		$module_id = (int)$_POST['module_id'];

		$data = array(

				'module_id' => $module_id,
				'module_name' => $module_name,
				'link' => $module_link,
				'description' => $description,
				'role_level' => $module_level,
				'actived' => $actived,
				'sort' => $urutan
		);

		$edit_module = new Module($data);
		$edit_module -> updateModule();

		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=modules&status=moduleUpdated">';
			
		exit();

	}

	else {

		$views['Module'] = $modules -> getModule($moduleId);
		$views['sortModule'] = $views['Module'] -> getModule_Sort();
		$views['roleLevel'] = $views['Module'] -> getRole_DropDown();
		$views['id_module'] = $views['Module'] -> getModule_Id();
		require( "modul/edit-module.php" );
	}

}

//fungsi aktifasi modul
//pembuatan modul tabel di database jika modul support basis data
function activateModul() {

	global $modules, $moduleId;

	$dbh = new Pldb;

	if ( !$module = $modules -> getModule($moduleId))
	{
		require( "../cabin/404.php" );
	}

	$sql_filename = $module -> getModule_Name();
	$file_path = '../cabin/module/'.$sql_filename.'.sql'; //nama tabel modul = nama modul yang diupload


	if (file_exists($file_path))
	{
		$sql_contents = file_get_contents($file_path);
		$sql_contents = explode(";", $sql_contents);

		foreach ( $sql_contents as $query )
		{
			$result = '';
			$result = $dbh -> query($query);

			if (!$result)
			{
				unlink($file_path);
				header("Location: index.php?module=modules&error=tableNotFound");
				 
			}
			else
			{
				$data = array('module_id' => $moduleId);
				 
				$activate_modul =  new Module($data);
				$activate_modul -> activateModul();
				 
				header("Location: index.php?module=modules&status=moduleActivated");
				unlink($file_path);
			}

		}
	  
	}
	else
	{

		$data = array('module_id' => $moduleId);
			
		$activate_modul =  new Module($data);
		$activate_modul -> activateModul();

		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=modules&status=moduleActivated">';
			
		exit();

	}

}

//fungsi deaktifasi modul
function deactivateModul() {

	global $modules, $moduleId;

	if ( !$module = $modules -> getModule($moduleId))
	{
		require( "../cabin/404.php" );
	}

	$data = array('module_id' => $moduleId);

	$activate_modul =  new Module($data);
	$activate_modul -> deactivateModul();

	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=modules&status=moduleDeactivated">';

	exit();

}

//fungsi hapus modul
function deleteModule() {

	global $modules, $moduleId;

	if ( !$module = $modules -> getModule($moduleId))
	{
		require( "../cabin/404.php" );
	}

	$moduleName = $module -> getModule_Name();
	$moduleLink = $module -> getModule_Link();
	if ( $moduleLink != '#')
	{
		$data = array('module_id' => $moduleId);
		$pathModul = "../cabin/module/$moduleName";
		deleteDir($pathModul);
		unlink("../cabin/module/$moduleName.php");

	}
	else
	{
		$data = array('module_id' => $moduleId);
	}

	$hapus_modul = new Module($data);
	$hapus_modul -> deleteModule();

	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=modules&status=moduleDeleted">';

	exit();

}