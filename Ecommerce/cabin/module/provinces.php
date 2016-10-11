<?php if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!");
/**
 * File Modul provinces.php
 * mengelola business logic
 * pada fungsionalitas objek province
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
$province_id = isset($_GET['provinceId']) ? (int)$_GET['provinceId'] : 0;
$provinces = new Province();
$accessLevel = Admin::accessLevel();

if ( $accessLevel != 'superadmin' && $accessLevel != 'admin')
{
	include_once( "../cabin/404.php" );
}
else 
{
	switch ($action) {
	
	
		case 'newProvince': // tambah provinsi
	
			addProvince();
	
			break;
		  
		case 'editProvince': // edit provinsi
	
			$cleaned = $sanitasi -> sanitasi($province_id, 'sql');
			$current_province = $provinces -> findById($cleaned);
			$current_id = $current_province['province_id'];
	
			if ( isset($province_id) && $current_id != $province_id)
			{
				require( "../cabin/404.php" );
			}
			else
			{
				updateProvince();
			}
	
			break;
	
		case 'deleteProvince': // hapus provinsi
	
			deleteProvince();
	
			break;
	
		default:
				
			listProvinces(); // tampilkan semua provinsi
	
			break;
			
	}
	
}

// fungsi tambah provinsi
function addProvince() {
	
	global $provinces;
	
	$views = array();
	$views['pageTitle'] = "Tambah Provinsi";
	$views['formAction'] = "newProvince";
	
	if (isset($_POST['saveProvince']) && $_POST['saveProvince'] == 'Simpan')
	{
		if (empty($_POST['province_name']))
		{
			$views['errorMessage'] = "Kolom yang bertanda asterik(*) harus diisi";
			require ( "province/edit-province.php" );
		}
		
		if ( !isset($views['errorMessage']))
		{
			$data = array('province_name' => isset($_POST['province_name']) ? preg_replace('/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/', '', $_POST['province_name']) : '');
			
		    $add_province = new Province($data);
		    $add_province -> createProvince();
		    
		    echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=provinces&status=provinceAdded">';
		    
		    exit();
		    
		}
	}
	else 
	{
		$views['province'] = $provinces;
		require( "province/edit-province.php" );
	}
}

// fungsi edit provinsi
function updateProvince() {
	
	global $provinces, $province_id;
	
	$views = array();
	$views['pageTitle'] = "Edit Provinsi";
	$views['formAction'] = "editProvince";
	
	if (isset($_POST['saveProvince']) && $_POST['saveProvince'] == 'Simpan')
	{
		
		
		if (empty($_POST['province_name']))
		{
			$views['errorMessage'] = "Kolom yang bertanda asterik(*) harus diisi";
			require ( "province/edit-province.php" );
		}
	
		if ( !isset($views['errorMessage']))
		{
			$data = array(
			 
			 'province_id' => isset($_POST['province_id']) ? abs((int)$_POST['province_id']) : '',
			 'province_name' => isset($_POST['province_name']) ? preg_replace('/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/', '', $_POST['province_name']) : ''
					
			);
				
			$add_province = new Province($data);
			$add_province -> updateProvince();
	
			echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=provinces&status=provinceUpdated">';
	
			exit();
	
		}
	}
	else
	{
		$views['province'] = $provinces -> getProvinceById($province_id);
		require( "province/edit-province.php" );
	}
}

// fungsi hapus provinsi
function deleteProvince() {
	
	global $provinces, $province_id;
	
	if ( !$province = $provinces -> getProvinceById($province_id))
	{
		require( "../cabin/404.php" );
	}
	
	$data = array( 'province_id' => $province_id);
	$del_province = new Province($data);
	$del_province -> deleteProvince();
	
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=provinces&status=provinceDeleted">';
	
	exit();
	
}

// fungsi tampil provinsi
function listProvinces() {
	
	$views = array();
	
	$p = new Pagination();
	$limit = 10;
	$position = $p -> getPosition($limit);
	
	$data_province = Province::getListProvinces($position, $limit);
	
	$views['provinces'] = $data_province['results'];
	$views['totalRows'] = $data_province['totalRows'];
	$views['position']  = $position;
	$views['pageTitle'] = "Provinsi";
	
	// pagination
	$totalPage = $p -> totalPage($views['totalRows'], $limit);
	$pageLink = $p -> navPage($_GET['order'], $totalPage);
	$views['pageLink'] = $pageLink;
	
	if (isset($_GET['error']))
	{
		if ( $_GET['error'] == "provinceNotFound" ) $views['errorMessage'] = "Error: Provinsi tidak ditemukan";
	}
	
	if (isset($_GET['status']))
	{
		if ( $_GET['status'] == "provinceAdded") $views['statusMessage'] =   "Provinsi sudah disimpan";
		if ( $_GET['status'] == "provinceUpdated") $views['statusMessage'] = "Provinsi ksudah diupdate";
		if ( $_GET['status'] == "provinceDeleted") $views['statusMessage'] = "Provinsi sudah dihapus";
	}
	
	require( "province/list-provinces.php" );
	
}