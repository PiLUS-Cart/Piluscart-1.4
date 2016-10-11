<?php if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!");
/**
 * File Modul couries.php
 * mengelola business logic
 * pada fungsionalitas objek jasa kirim
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
$courierId = isset($_GET['courierId']) ? abs((int)$_GET['courierId']) : 0;
$shipping = new Shipping();
$accessLevel = Admin::accessLevel();

if ( $accessLevel != 'superadmin' && $accessLevel != 'admin') {
	
	include_once('../cabin/404.php');
	
} else {
	
	switch ($action) {
	
		case 'newCourier':
	
			addCourier();
	
			break;
	
		case 'editCourier':
	
			$cleaned = $sanitasi -> sanitasi($courierId, 'sql');
			$current_courier = $shipping -> findById($cleaned);
			$current_id = $current_courier['shipping_id'];
	
			if (isset($courierId) && $current_id != $courierId) {
				
				require('../cabin/404.php');
				
			} else {
				
				updateCourier();
				
			}
	
			break;
	
		case 'deleteCourier':
	
			deleteCourier();
	
			break;
	
		default:
	
			listCouriers();
				
			break;
	
	}
	
}

// tambah jasa kirim
function addCourier() 
{

	global $shipping;

	$views = array();
	$views['pageTitle'] = "Tambah Jasa Pengiriman";
	$views['formAction']= "newCourier";

	if (isset($_POST['saveCourier']) && $_POST['saveCourier'] == 'Simpan') {
		
		$file_location = isset($_FILES['image']['tmp_name']) ? $_FILES['image']['tmp_name'] : '';
		$file_type = isset($_FILES['image']['type']) ? $_FILES['image']['type'] : '';
		$file_name = isset($_FILES['image']['name']) ? $_FILES['image']['name'] : '';

		if (empty($_POST['jasa_kirim'])) {
			
			$views['errorMessage'] = "Kolom yang bertanda asterik(*) harus diisi";
			require('courier/edit-courier.php');
			
		} else {
			
			if (!empty($file_location)) {
				
				if ($file_type != "image/jpeg" and $file_type != "image/pjpeg") {
					
					$views['errorMessage'] = "Tipe file yang anda upload salah";
					require('courier/edit-courier.php');
					
				} else {
					
					uploadBanner($file_name);
					
					$data = array(
					
							'shipping_name' => isset($_POST['jasa_kirim']) ? preg_replace('/[^ \'\,\.\-a-zA-Z0-9]/', '', $_POST['jasa_kirim']) : '',
							'shipping_logo' => $file_name,
					);
					
					$add_courier = new Shipping($data);
					$add_courier -> createShipping();
					
					echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=courier&status=courierAdded">';
					
					exit();
						
				}
								
			} else {
				
				$data = array('shipping_name' => isset($_POST['jasa_kirim']) ? preg_replace('/[^ \'\,\.\-a-zA-Z0-9]/', '', $_POST['jasa_kirim']) : '');

				$add_courier = new Shipping($data);
				$add_courier -> createShipping();

				echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=courier&status=courierAdded">';

				exit();
				
			}
			
		}
		
	} else {
		
		$views['courier'] = $shipping;
		require('courier/edit-courier.php');
		
	}
	
}

// edit jasa kirim
function updateCourier() 
{

	global $shipping, $courierId;

	$views = array();
	$views['pageTitle'] = "Edit jasa kirim";
	$views['formAction'] = "editCourier";

	if (isset($_POST['saveCourier']) && $_POST['saveCourier'] == 'Simpan') {
		
		$file_location = isset($_FILES['image']['tmp_name']) ? $_FILES['image']['tmp_name'] : '';
		$file_type  = isset($_FILES['image']['type']) ? $_FILES['image']['type'] : '';
		$file_name  = isset($_FILES['image']['name']) ? $_FILES['image']['name'] : '';

		if (empty($file_location)) {
			
			$data = array('shipping_name' => isset($_POST['jasa_kirim']) ? preg_replace('/[^ \'\,\.\-a-zA-Z0-9]/', '', $_POST['jasa_kirim']) : '' );
				
			$edit_courier = new Shipping($data);
			$edit_courier -> updateShipping();
				
			echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=courier&status=courierUpdated">';
				
			exit();
				
		} else {
			
			if ($file_type != "image/jpeg"  and $file_type != "image/pjpeg") {
				
				$views['errorMessage'] = "Tipe file yang anda upload salah";
				require('courier/edit-courier.php');
					
			} else {

				uploadBanner($file_name);

				$data = array(

						'shipping_name' => isset($_POST['jasa_kirim']) ? preg_replace('/[^ \'\,\.\-a-zA-Z0-9]/', '', $_POST['jasa_kirim']) : '',
						'shipping_logo' => $file_name,
						'shipping_id' => (int)$_POST['courier_id']);

				$edit_courier = new Shipping($data);
				$edit_courier -> updateShipping();

				echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=courier&status=courierUpdated">';
					
				exit();
				
			}
				
		}
		
	} else {
		
		$views['courier'] = $shipping -> getShippingById($courierId);
		$views['imagePath'] = $views['courier'] -> getShippingLogo();

		require('courier/edit-courier.php');
		
	}

}

// hapus jasa kirim
function deleteCourier() 
{

	global $shipping, $courierId;

	if (!$courier = $shipping -> getShippingById($courierId)) {
		
		require('../cabin/404.php');
		
	}

	$shipping_image = $courier -> getShippingLogo();
	if ($shipping_image != '') {
		
		$data = array('shipping_id' => $courierId);
		$delete_courier = new Shipping($data);
		$delete_courier -> deleteShipping();

		unlink("../content/uploads/images/$shipping_image");
		unlink("../content/uploads/images/thumbs/thumb_$shipping_image");

		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=courier&status=courierDeleted">';

		exit();
		
	} else {
		
		$data = array('shipping_id' => $courierId);
		$delete_courier = new Shipping($data);
		$delete_courier -> deleteShipping();
		
		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=courier&status=courierDeleted">';
		exit();

	}

}

// menampilkan jasa kirim
function listCouriers() 
{

	$views = array();
	$views['pageTitle'] = "Jasa Pengiriman";

	$p = new Pagination();
	$limit = 10;
	$position = $p -> getPosition($limit);

	$data_courier = Shipping::getListShipping($position, $limit);

	$views['couriers'] = $data_courier['results'];
	$views['totalRows'] = $data_courier['totalRows'];
	$views['position'] = $position;

	//pagination
	$totalPage = $p ->totalPage($views['totalRows'], $limit);
	$pageLink = $p ->navPage($_GET['order'], $totalPage);
	$views['pageLink'] = $pageLink;

	if ( isset($_GET['error'])) {

		if ( $_GET['error'] == "courierNotFound" ) $views['errorMessage'] = "Error: Jasa Kirim tidak ditemukan";

	}

	if ( isset($_GET['status'])) {

		if ( $_GET['status'] == "courierAdded") $views['statusMessage'] =  "Jasa kirim baru sudah disimpan";
		if ( $_GET['status'] == "courierUpdated") $views['statusMessage'] = "Jasa kirim sudah diupdate";
		if ( $_GET['status'] == "courierDeleted") $views['statusMessage'] = "Jasa kirim sudah dihapus";
	
	}

	require('courier/list-courier.php');
	
}