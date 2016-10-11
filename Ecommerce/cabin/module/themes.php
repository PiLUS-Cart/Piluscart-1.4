<?php if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!");
/**
 * File Modul themes.php
 * mengelola business logic
 * pada fungsionalitas objek themes
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
$themeId = isset($_GET['themeId']) ? abs((int)$_GET['themeId']) : 0;
$themes = new Template();
$accessLevel = Admin::accessLevel();

if ( $accessLevel != 'superadmin' )
{
	include_once( "../cabin/404.php" );
	
}
else 
{
	
	switch ($action) {
	
		// Tampilkan template
		default:
	
			listThemes();
	
			break;
	
			// tambah template baru
		case 'newTheme':
	
			addTheme();
	
			break;
	
		case 'installTheme':
	
			setupTheme();
	
			break;
	
			// update template
		case 'editTheme':
	
			$cleaned = $sanitasi -> sanitasi($themeId, 'sql');
			$current_theme = $themes -> findById($cleaned);
			$current_id = $current_theme['ID'];
	
			if ( isset($themeId) && $current_id != $themeId )
			{
				require( "../cabin/404.php" );
			}
			else
			{
				updateTheme();
			}
	
			break;
	
			// aktifkan template
		case 'activateTheme':
	
			activateTheme();
	
			break;
	
			// hapus template
		case 'deleteTheme' :
	
			deleteTheme();
	
			break;
	
	}	
	
}

// fungsi tampil tema
function listThemes()
{
	$views = array();

	$p = new Pagination;
	$limit = 10;
	$position = $p -> getPosition($limit);

	$data_themes = Template::getTemplates($position, $limit);

	$views['themes']   = $data_themes['results'];
	$views['totalRows'] = $data_themes['totalRows'];
	$views['position']  = $position;
	$views['pageTitle'] = "Template";

	//pagination
	$totalPage = $p -> totalPage($views['totalRows'], $limit);
	$pageLink = $p -> navPage($_GET['order'], $totalPage);
	$views['pageLink'] = $pageLink;

	if (isset($_GET['error']))
	{
		if ( $_GET['error'] == "themeNotFound" ) $views['errorMessage'] = "Error: Template tidak ditemukan";
	}

	if (isset($_GET['status']))
	{
		if ( $_GET['status'] == "themeAdded") $views['statusMessage'] =   "Template baru sudah disimpan";
		if ( $_GET['status'] == "themeInstalled") $views['statusMessage'] =   "Proses instalasi template berhasil, aktifkan template terlebih dahulu untuk melihat hasilnya";
		if ( $_GET['status'] == "themeUpdated") $views['statusMessage'] = "Template sudah diupdate";
		if ( $_GET['status'] == "themeActivated") $views['statusMessage'] = "Template sudah diaktifkan";
		if ( $_GET['status'] == "themeDeleted") $views['statusMessage'] = "Template sudah dihapus";
	}

	require('theme/list-themes.php');

}

// fungsi tambah template
function addTheme()
{
	global $themes, $sanitasi;

	$views = array();
	$views['pageTitle']  = "Tambah Template";
	$views['formAction'] = "newTheme";

	if (isset($_POST['saveTheme']) && $_POST['saveTheme'] == 'Simpan')
	{
		$theme_name = $_POST['theme_name'];
		$designer = $_POST['designer'];
		$deskripsi = isset($_POST['description']) ? preventInject($_POST['description']) : '';

		if (empty($theme_name) || empty($designer) || empty($_POST['folder']) || empty($deskripsi))
		{
			$views['errorMessage'] = "Kolom yang bertanda asterik(*) harus diisi";
			require( "theme/edit-theme.php" );
		}
		else
		{
				
			if ($themes -> themeExists($theme_name) == true)
			{
				$views['errorMessage'] = "Nama Tema sudah digunakan";
				require( "theme/edit-theme.php" );
			}
		}

		if (empty($views['errorMessage']) == true)
		{
				
			$data = array(
						
					'template_name' => isset($theme_name) ? preg_replace('/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/', '', $theme_name) : '',
					'short_desc' => $deskripsi,
					'designed_by' => isset($designer) ? preg_replace('/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/', '', $designer) : '',
					'folder' => $_POST['folder']
			);
				
			$add_theme = new Template($data);
			$add_theme -> insertTemplate();
				
			echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=themes&status=themeAdded">';
				
			exit();

		}
	}
	else
	{
		$views['Theme'] = $themes;
		require( "theme/edit-theme.php" );
	}
}

// fungsi instalasi template
function setupTheme() {

	global $themes;

	$views = array();
	$views['pageTitle']  = "Install Template";
	$views['formAction'] = "installTheme";

	if (isset($_POST['saveTheme']) && $_POST['saveTheme'] == 'Install')
	{
		$file_name = $_FILES['zip_file']['name'] ;
		$file_location =  $_FILES['zip_file']['tmp_name'];
		$file_type =  $_FILES['zip_file']['type'];
		$template = current(explode(".", $file_name));
		$pecah = explode(".", $file_name);
		$ekstensi = $pecah[1];
		$deskripsi = isset($_POST['description']) ? preventInject($_POST['description']) : '';

		$accepted_types = array('application/zip', 'application/x-zip-compressed', 'multipart/x-zip', 'application/x-compressed');
		foreach($accepted_types as $mime_type) {
			if($mime_type == $type) {
				$okay = true;
				break;
			}
		}

		$validasi = strtolower($ekstensi) == 'zip' ? true : false;

		$folder = "content/themes/$template";

		if ( !$validasi)
		{
			$views['errorMessage'] = "Instalasi template gagal, Pastikan file yang di Upload bertipe *.zip";
			require( 'theme/install-theme.php' );
		}
		elseif (empty($deskripsi) OR empty($file_location))
		{
			$views['errorMessage'] = "Maaf, Kolom yang bertanda asterik(*) harus diisi";
			require( 'theme/install-theme.php');
		}
		elseif (file_exists( "../content/themes/$template" ))
		{
			$views['errorMessage'] = "Maaf, Anda telah menginstall template ini sebelumnya. Silahkan Upload Template lain";
			require( "theme/install-theme.php" );
		}
		else
		{
				
				
			$install_theme = uploadTheme($file_name);
				
			$data = array(
						
					'template_name' => $template,
					'short_desc' => $deskripsi,
					'designed_by' => $template,
					'folder' => $folder
			);
				
			$setup_theme = new Template($data);
			$setup_theme -> insertTemplate();
				
			echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=themes&status=themeInstalled">';

			exit();
				
		}

	}
	else
	{
		$views['Theme'] = $themes;
		require( "theme/install-theme.php" );
	}

}

// fungsi update template
function updateTheme()
{
	global $themes, $themeId;

	$views = array();
	$views['pageTitle']  = "Edit Tema";
	$views['formAction'] = "editTheme";

	if (isset($_POST['saveTheme']) && $_POST['saveTheme'] == 'Simpan')
	{

		$theme_name = isset($_POST['theme_name']) ? preg_replace('/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/', '', $_POST['theme_name']) : '';
		$designer = isset($_POST['designer']) ? preg_replace('/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/', '', $_POST['designer']) : '';
		$deskripsi = isset($_POST['description']) ? preventInject($_POST['description']) : '';
		$theme_id = abs((int)$_POST['theme_id']);

		$data = array(
				'ID'=> $theme_id,
				'template_name' => $theme_name,
				'short_desc' => $deskripsi,
				'designed_by' => $designer,
				'folder' => $_POST['folder']);

		$edit_theme = new Template($data);
		$edit_theme -> updateTemplate();

		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=themes&status=themeUpdated">';

		exit();

	}
	else
	{
		
		$views['Theme'] = $themes -> getTemplate($themeId);
		$views['templateId'] = $views['Theme'] -> getId();
		$views['folderPath'] = $views['Theme'] -> getTemplate_Folder();
		require( "theme/edit-theme.php" );
		
	}

}

// fungsi aktifasi template
function activateTheme()
{
	
	global $themes, $themeId;

	$data = array('ID' => $themeId);

	$activate_theme = new Template($data);
	$activate_theme -> activateTheme();

	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=themes&status=themeActivated">';

	exit();

}

// fungsi hapus template
function deleteTheme() {

	global $themes, $themeId;

	if ( !$theme = $themes -> getTemplate($themeId))
	{
		require('../cabin/404.php');
	}

	$templateFolder = $theme -> getTemplate_Folder();
	if ( $templateFolder != '' )
	{
		$data = array('ID' => $themeId);
		$pathFolder = "../$templateFolder/";
		deleteDir($pathFolder);
	}
	else
	{
		$data = array('ID' => $themeId);
	}

	$hapus_theme = new Template($data);
	$hapus_theme -> deleteTheme();

	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=themes&status=themeDeleted">';

	exit();

}