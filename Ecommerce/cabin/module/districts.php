<?php if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!");
/**
 * File Modul districts.php
 * mengelola business logic
 * pada fungsionalitas objek district
 * dan objek shipping
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
$districtId = isset($_GET['districtId']) ? (int)$_GET['districtId'] : 0;
$districts = new District();
$shipping = new Shipping();
$provinces = new Province();
$accessLevel = Admin::accessLevel();

if ( $accessLevel != 'superadmin' && $accessLevel != 'admin')
{
	include_once('../cabin/404.php');
}
else 
{
  
	switch ($action) {
	
		//tampilkan semua Kota Kabupaten
		default:
	
			listDistricts();
	
			break;
	
			//tambah Kota Kabupaten
		case 'newDistrict':
	
			addDistrict();
	
			break;
	
			//update Kota Kabupaten
		case 'editDistrict':
	
			$cleaned = $sanitasi -> sanitasi($districtId, 'sql');
			$current_district = $districts -> findById($cleaned);
			$current_id = $current_district['district_id'];
	
			if ( isset($districtId) && $current_id != $districtId )
			{
				require('../cabin/404.php');
			}
			else
			{
				updateDistrict();
			}
	
			break;
	
			//hapus Kota Kabupaten
		case 'deleteDistrict':
	
			deleteDistrict();
	
			break;
	}
	
}

//fungsi tampil Kota Kabupaten
function listDistricts() {
	$views = array();

	$p = new Pagination;
	$limit = 10;
	$position = $p -> getPosition($limit);

	$data_district = District::getDistricts($position, $limit);

	$views['districts'] = $data_district['results'];
	$views['totalRows'] = $data_district['totalRows'];
	$views['position']  = $position;
	$views['pageTitle'] = "Kota / Kabupaten";

	//pagination
	$totalPage = $p -> totalPage($views['totalRows'], $limit);
	$pageLink = $p -> navPage($_GET['order'], $totalPage);
	$views['pageLink'] = $pageLink;

	if (isset($_GET['error']))
	{
		if ( $_GET['error'] == "districtNotFound" ) $views['errorMessage'] = "Error: kota / kabupaten tidak ditemukan";
	}

	if (isset($_GET['status']))
	{
		if ( $_GET['status'] == "districtAdded") $views['statusMessage'] =   "kota / kabupaten baru sudah disimpan";
		if ( $_GET['status'] == "districtUpdated") $views['statusMessage'] = "kota / kabupaten sudah diupdate";
		if ( $_GET['status'] == "districtDeleted") $views['statusMessage'] = "kota / kabupaten sudah dihapus";
	}

	require('district/list-districts.php');

}

//fungsi tambah Kota Kabupaten
function addDistrict() {
	
	global $districts, $shipping, $provinces;

	$views = array();
	$views['pageTitle'] = "Tambah Kota/Kabupaten";
	$views['formAction'] = "newDistrict";

	if (isset($_POST['saveDistrict']) && $_POST['saveDistrict'] == 'Simpan')
	{
		$District_name = preventInject($_POST['district_name']);
		$province_id = abs((int)$_POST['prov_id']);
		$ongkos_kirim = preventInject($_POST['ship_cost']);
		$shipping_id = abs((int)$_POST['shipping']);

		if (empty($District_name) || empty($ongkos_kirim))
		{
			$views['errorMessage'] = "Kolom yang bertanda asterik(*) harus diisi";
			require('district/edit-district.php');

		}

		if ($shipping_id == 0 || $province_id == 0)
		{

			$views['errorMessage'] = "jasa pengiriman atau nama provinsi belum anda pilih. Silahkan memilih nama provinsi atau jasa pengiriman yang tersedia.";
			require('district/edit-district.php');
					
		}
		else
		{
			if ($districts -> districtExists($District_name) == true)
			{
				$views['errorMessage'] = "Nama kota atau kabupaten sudah digunakan";
				require('district/edit-district.php');
			}

			if (!ctype_xdigit($ongkos_kirim))
			{
				$views['errorMessage'] = "masukkan ongkos kirim hanya dengan angka";
				require( 'district/edit-district.php' );
			}
		}

		if (empty($views['errorMessage']) == true)
		{
			$data = array(
					
					'province_id' => $province_id,
					'shipping_id' => $shipping_id,
					'district_name' => $District_name,
					'shipping_cost' => $ongkos_kirim
					
			);

			$new_District = new District($data);
			$new_District -> createDistrict();

			echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=districts&status=districtAdded">';
				
			exit();
		}
	}
	else
	{
		$views['District'] = $districts;
		$views['province_Dropdown'] = $provinces -> setProvince_Dropdown();
		$views['shipping'] = $shipping -> setShippingDropDown();
		
		require( "district/edit-district.php" );
		
	}

}

//fungsi edit Kota Kabupaten
function updateDistrict() {
	
	global $districts, $provinces, $shipping, $districtId;

	$views = array();

	$views['pageTitle'] = "Edit Kota/Kabupaten";
	$views['formAction'] = "editDistrict";


	if (isset($_POST['saveDistrict']) && $_POST['saveDistrict'] == 'Simpan')
	{
		$District_id = abs((int)$_POST['district_id']);
		$province_id = abs((int)$_POST['prov_id']);
		$shipping_id = abs((int)$_POST['shipping']);
		$District_name = preventInject($_POST['district_name']);
		$ongkos_kirim = preventInject($_POST['ship_cost']);


		$data = array(
				'district_id' => $District_id,
				'province_id' => $province_id,
				'shipping_id' => $shipping_id,
				'district_name' => $District_name,
				'shipping_cost' => $ongkos_kirim);

		$edit_District = new District($data);
		$edit_District -> updateDistrict();

		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=districts&status=districtUpdated">';

		exit();
	}
	else
	{
		$views['District'] = $districts -> getDistrictById($districtId);
		$views['province_Dropdown'] = $provinces -> setProvince_Dropdown($views['District'] -> getProvince_Id());
		$views['shipping'] = $shipping -> setShippingDropDown($views['District'] -> getShipping_Id());

		require('district/edit-district.php');
		
	}
}

//fungsi hapus Kota Kabupaten
function deleteDistrict() {

	global $districts, $districtId;

	if (!$District = $districts -> getDistrictById($districtId))
	{
		require ('../cabin/404.php');

	}

	$data = array('district_id' => $districtId);

	$del_District = new District($data);
	$del_District -> deleteDistrict();

	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=districts&status=districtDeleted">';

	exit();

}