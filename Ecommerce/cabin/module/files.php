<?php if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!");
/**
 * File Modul files.php
 * mengelola business logic
 * pada fungsionalitas objek download
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
$fileId = isset($_GET['fileId']) ? abs((int)$_GET['fileId']) : 0;
$downloads = new Download();
$accessLevel = Admin::accessLevel();

if ( $accessLevel != 'superadmin' && $accessLevel != 'admin' && $accessLevel != 'editor')
{
	include_once('../cabin/404.php');
}
else 
{
	switch ($action) {
	
		//tampilkan file download
		default:
	
			listFiles();
	
			break;
	
			//tambah file download
		case 'newFile':
	
			addFile();
	
			break;
	
			//update file download
		case 'editFile':
	
			$cleaned = $sanitasi -> sanitasi($fileId, 'sql');
			$current_file = $downloads -> findById($cleaned);
			$current_id = $current_file['download_id'];
	
			if ( isset($fileId) && $current_id != $fileId )
			{
				require('../cabin/404.php');
			}
			else
			{
				updateFile();
			}
	
			break;
	
			//hapus file download
		case 'deleteFile':
	
			deleteFile();
	
			break;
	}
	
}

//fungsi tampil file download
function listFiles() {
	
	$views = array();

	$p = new Pagination;
	$limit = 10;
	$position = $p -> getPosition($limit);

	$file = Download::getDownloads($position, $limit);

	$views['files']     = $file['results'];
	$views['totalRows'] = $file['totalRows'];
	$views['position']  = $position;
	$views['pageTitle'] = "Katalog Produk";

	//pagination
	$totalPage = $p -> totalPage($views['totalRows'], $limit);
	$pageLink = $p -> navPage($_GET['order'], $totalPage);
	$views['pageLink'] = $pageLink;

	if (isset($_GET['error']))
	{
		if ( $_GET['error'] == "fileNotFound" ) $views['errorMessage'] = "Error: File download tidak ditemukan";
	}

	if (isset($_GET['status']))
	{
		if ( $_GET['status'] == "fileAdded") $views['statusMessage'] =   "File download baru sudah disimpan";
		if ( $_GET['status'] == "fileUpdated") $views['statusMessage'] = "File download sudah diupdate";
		if ( $_GET['status'] == "fileDeleted") $views['statusMessage'] = "File download sudah dihapus";
	}

	require('files/list-files.php');

}

//fungsi tambah file download
function addFile() {
	
	global $downloads;

	$views = array();
	$views['pageTitle'] = "Tambah Katalog Produk";
	$views['formAction'] = "newFile";

	if (isset($_POST['saveFile']) && $_POST['saveFile'] == 'Simpan')
	{
		$file_location = isset($_FILES['fdoc']['tmp_name']) ? $_FILES['fdoc']['tmp_name'] : '';
		$file_name = isset($_FILES['fdoc']['name']) ? $_FILES['fdoc']['name'] : '';

		$tgl_sekarang = date('Ymd');


		if (empty($file_location) || empty($_POST['title']))
		{
			$views['errorMessage'] = "Kolom yang bertanda asterik(*) harus diisi";
			require('files/edit-file.php');

		}
		else
		{
			$file_extension = strtolower(substr(strrchr($file_name,"."),1));

			switch ($file_extension)
			{
					
				case "pdf": $ctype="application/pdf"; break;
				case "exe": $ctype="application/octet-stream"; break;
				case "zip": $ctype="application/zip"; break;
				case "rar": $ctype="application/rar"; break;
				case "doc": $ctype="application/msword"; break;
				case "xls": $ctype="application/vnd.ms-excel"; break;
				case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
				case "gif": $ctype="image/gif"; break;
				case "png": $ctype="image/png"; break;
				case "jpeg":
				case "jpg": $ctype="image/jpg"; break;
				default: $ctype="application/force-download";
					
			}

			if ($file_extension == 'php')
			{
				$views['errorMessage'] = "Upload Gagal, Tipe File Dilarang!";
				require('files/edit-file.php');
			}
			else {
					
				uploadFile();
					
				$data = array(
							
						'title' => isset($_POST['title']) ? preg_replace('/[^ \'\,\.\-a-zA-Z0-9]/', '', $_POST['title']) : '',
						'filename' => $file_name,
						'date_uploaded' => $tgl_sekarang,
						'slug' => makeSlug($_POST['title'])
				);
					
				$file_download = new Download($data);
				$file_download -> createDownload();
					
				echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=files&status=fileAdded">';
					
				exit();
			}
		}
	}
	else
	{
		$views['Files'] = $downloads;
		require( "files/edit-file.php" );
	}
}

//fungsi update file dowload
function updateFile() {
	
	global $downloads;

	global $fileId;

	$views = array();
	$views['pageTitle'] = "Edit File Download";
	$views['formAction'] = "editFile";

	if (isset($_POST['saveFile']) && $_POST['saveFile'] == 'Simpan')
	{
		$file_location = isset($_FILES['fdoc']['tmp_name']) ? $_FILES['fdoc']['tmp_name'] : '';
		$file_name = isset($_FILES['fdoc']['name']) ? $_FILES['fdoc']['name'] : '';

		if (empty($file_location))
		{
			$data = array(

					'download_id' => isset($_POST['file_id']) ? abs((int)$_POST['file_id']) : '',
					'title' => isset($_POST['title']) ? preg_replace('/[^ \'\,\.\-a-zA-Z0-9]/', '', $_POST['title']) : '',
					'slug' => makeSlug($_POST['title'])
			);

			$file_download = new Download($data);
			$file_download -> updateDownload();

			echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=files&status=fileUpdated">';

			exit();
		}
		else
		{
			$file_extension = strtolower(substr(strrchr($file_name,"."),1));

			switch ($file_extension)
			{
					
				case "pdf": $ctype="application/pdf"; break;
				case "exe": $ctype="application/octet-stream"; break;
				case "zip": $ctype="application/zip"; break;
				case "rar": $ctype="application/rar"; break;
				case "doc": $ctype="application/msword"; break;
				case "xls": $ctype="application/vnd.ms-excel"; break;
				case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
				case "gif": $ctype="image/gif"; break;
				case "png": $ctype="image/png"; break;
				case "jpeg":
				case "jpg": $ctype="image/jpg"; break;
				default: $ctype="application/force-download";
					
			}

			if ($file_extension == 'php')
			{
				$views['errorMessage'] = "Upload Gagal, Tipe File Dilarang!";
				require( "files/edit-file.php" );
			}
			else {
					
				uploadFile();
					
				$data = array(

						'download_id' => isset($_POST['file_id']) ? abs((int)$_POST['file_id']) : '',
						'title' => isset($_POST['title']) ? preg_replace('/[^ \'\,\.\-a-zA-Z0-9]/', '', $_POST['title']) : '',
						'filename' => $file_name,
						'slug' => makeSlug($_POST['slug'])
				);
					
				$file_download = new Download($data);
				$file_download -> updateDownload();

				echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=files&status=fileUpdated">';
					
				exit();

			}

		}

	}
	else
	{
		$views['Files'] = $downloads -> getDownload($fileId);
		$views['filePath'] = $views['Files'] -> getDownload_Filename();

		require( "files/edit-file.php" );
	}

}

//fungsi hapus file download
function deleteFile() {
	
	global $downloads, $fileId;


	if (!$file = $downloads -> getDownload($fileId))
	{
		require( "../cabin/404.php" );
	}

	$getFilename = $file -> getDownload_Filename();
	if ($getFilename != '')
	{
		$data = array('download_id' => $fileId, 'filename'=>$getFilename);

		$hapus_download = new Download($data);
		$hapus_download -> deleteDownload();

		unlink("../content/uploads/files/$getFilename");

		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=files&status=fileDeleted">';
			
		exit();
	}
}