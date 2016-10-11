<?php if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!");
/**
 * File Modul banners.php
 * mengelola business logic
 * pada fungsionalitas objek banner
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
$bannerId = isset($_GET['bannerId']) ? abs((int)$_GET['bannerId']) : 0;
$banners = new Banner();
$accessLevel = Admin::accessLevel();

if ( $accessLevel != 'superadmin') {
	
	include_once('../cabin/404.php');
	
} else {
	
	switch ($action) {
	
		//tampilkan banner
		default:
	
			listBanners();
	
			break;
	
			//tambah banner
		case 'newBanner':
	
			addBanner();
	
			break;
	
		 //update banner
		case 'editBanner':
	
			$cleaned = $sanitasi -> sanitasi($bannerId, 'sql');
			$current_banner = $banners -> findById($cleaned);
			$current_id = $current_banner['banner_id'];
	
			if (isset($bannerId) && $current_id != $bannerId) {
				require('../cabin/404.php');
			} else {
				updateBanner();
			}
	
			break;
	
			//hapus banner
		case 'deleteBanner':
	
			deleteBanner();
	
			break;
	
	}
	
}

// Menampilkan semua banner
function listBanners() 
{

	$views = array();

	$p = new Pagination;
	$limit = 10;
	$position = $p -> getPosition($limit);

	$data_banner = Banner::getBanners($position, $limit);

	$views['banners'] = $data_banner['results'];
	$views['totalRows'] = $data_banner['totalRows'];
	$views['position'] = $position;
	$views['pageTitle'] = "Banner";

	//pagination
	$totalPage = $p ->totalPage($views['totalRows'], $limit);
	$pageLink = $p ->navPage($_GET['order'], $totalPage);
	$views['pageLink'] = $pageLink;

	if ( isset($_GET['error'])) {

		if ( $_GET['error'] == "bannerNotFound" ) $views['errorMessage'] = "Error: Banner tidak ditemukan";

	}

	if ( isset($_GET['status'])) {

		if ( $_GET['status'] == "bannerAdded") $views['statusMessage'] =  "Banner baru sudah disimpan";
		if ( $_GET['status'] == "bannerUpdated") $views['statusMessage'] = "Banner sudah diupdate";
		if ( $_GET['status'] == "bannerDeleted") $views['statusMessage'] = "Banner sudah dihapus";
	}

	require('banner/list-banners.php');

}



// Menambahkan banner
function addBanner() 
{

	global $banners;

	$views = array();
	$views['pageTitle'] = "Tambah Banner";
	$views['formAction'] = "newBanner";


	if (isset($_POST['saveBanner']) && $_POST['saveBanner'] == 'Simpan') {
		
		$file_location = isset($_FILES['image']['tmp_name']) ? $_FILES['image']['tmp_name'] : '';
		$file_type = isset($_FILES['image']['type']) ? $_FILES['image']['type'] : '';
		$file_name = isset($_FILES['image']['name']) ? $_FILES['image']['name'] : '';
			
			
		if (empty($_POST['banner_label']) 
			|| empty($_POST['banner_url']) || empty($file_location)) {
			
			$views['errorMessage'] = "Kolom yang bertanda asterik(*) harus diisi";
			require('banner/edit-banner.php');
			
		} else {
			
			if ($file_type != "image/jpeg" and $file_type != "image/pjpeg") {
				
				$views['errorMessage'] = "Tipe file yang anda upload salah";
				require('banner/edit-banner.php');
			
			} else {
				
				uploadBanner($file_name);
					
				$data = array(

						'title' => isset($_POST['banner_label']) ? preg_replace('/[^ \'\,\.\-a-zA-Z0-9]/', '', $_POST['banner_label']) : '',
						'url' => isset($_POST['banner_url']) ? validhttp($_POST['banner_url']) : '',
						'image' => $file_name,
						'uploadedOn' => date('Ymd'));

				$add_banner = new Banner($data);
				$add_banner -> createBanner();

				echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=banners&status=bannerAdded">';

				exit();

			}
				
		}
			
	} else {
		
		$views['Banner'] = $banners;
		require('banner/edit-banner.php');
		
	}

}

// edit banner
function updateBanner() 
{

	global $banners, $bannerId;

	$views = array();

	$views['formAction'] = "editBanner";
	$views['pageTitle'] = "Edit Banner";

	if (isset($_POST['saveBanner']) && $_POST['saveBanner'] == 'Simpan') {
		
		$file_location = isset($_FILES['image']['tmp_name']) ? $_FILES['image']['tmp_name'] : '';
		$file_type  = isset($_FILES['image']['type']) ? $_FILES['image']['type'] : '';
		$file_name  = isset($_FILES['image']['name']) ? $_FILES['image']['name'] : '';
			
		if (empty($file_location)) {
			
			$data = array(

					'banner_id' => isset($_POST['banner_id']) ? abs((int)$_POST['banner_id']) : '',
					'title' => isset($_POST['banner_label']) ? preg_replace('/[^ \'\,\.\-a-zA-Z0-9]/', '', $_POST['banner_label']) : '',
					'url' => isset($_POST['banner_url']) ? validhttp($_POST['banner_url']) : '');
			
			$update_banner = new Banner($data);
			$update_banner -> updateBanner();

			echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=banners&status=bannerUpdated">';
				
			exit();

		} else {
			
			if ($file_type != "image/jpeg"  and $file_type != "image/pjpeg") {
				
				$views['errorMessage'] = "Tipe file yang anda upload salah";
				require('banner/edit-banner.php');

			} else {
				
				uploadBanner($file_name);
					
				$data = array(
							
						'banner_id' => isset($_POST['banner_id']) ? abs((int)$_POST['banner_id']) : '',
						'title' => isset($_POST['banner_label']) ? preg_replace('/[^ \'\,\.\-a-zA-Z0-9]/', '', $_POST['banner_label']) : '',
						'url' => isset($_POST['banner_url']) ? validhttp($_POST['banner_url']) : '',
						'image' => $file_name);
					
				$update_banner = new Banner($data);
				$update_banner -> updateBanner();
					
				echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=banners&status=bannerUpdated">';

				exit();
					
			}

		}
		
	} else {
		
		$views['Banner'] = $banners -> getBanner($bannerId);
		$views['imagePath'] = $views['Banner'] -> getBanner_Image();
			
		require('banner/edit-banner.php');
		
	}

}

// hapus banner
function deleteBanner() 
{

	global $banners, $sanitasi, $bannerId;


	if (!$banner = $banners -> getBanner($bannerId)) {
		
		require('../cabin/404.php');
		
	}

	$banner_image = $banner -> getBanner_Image();
	if ($banner_image != '') {
			
		if (($bannerId = (int)$bannerId)) {
			
			$sanitizeId = $sanitasi -> sanitasi($bannerId, 'sql');
			
			$data = array('banner_id' => $sanitizeId);
				
			$hapus_banner = new Banner($data);
			$hapus_banner -> deleteBanner();
				
			unlink("../content/uploads/images/$banner_image");
			unlink("../content/uploads/images/thumbs/thumb_$banner_image");
				
			echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=banners&status=bannerDeleted">';
			
			exit();
				
		}
			
	}

}