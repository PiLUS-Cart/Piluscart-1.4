<?php if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!");
/**
 * File Modul testimoni.php
 * mengelola business logic
 * pada fungsionalitas objek testimoni
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
$testimoni_id = isset( $_GET['testimoniId']) ? (int)$_GET['testimoniId'] : 0;
$testimony = new Testimoni();
$accessLevel = Admin::accessLevel();

if ( $accessLevel != 'superadmin' )
{
	include_once( "../cabin/404.php" );
}
else 
{
	
	switch ($action) {
	
		//tampilkan semua testimoni
		default:
	
			listTestimonies();
	
			break;
	
			//Edit Testimoni
		case 'editTestimoni':
	
			$cleaned = $sanitasi -> sanitasi($testimoni_id, 'sql');
			$current_testi = $testimony -> findById($cleaned);
			$current_id = $current_testi['testimoni_id'];
	
			if ( isset($testimoni_id) && $current_id != $testimoni_id )
			{
				require( "../cabin/404.php" );
			}
			else
			{
				updateTestimoni();
			}
	
			break;
	
			//Hapus Testimoni
		case 'deleteTestimoni':
	
			deleteTestimoni();
	
			break;
	
	}	
	
}

// fungsi edit testimoni
function updateTestimoni() {

	global $testimony;

	global $testimoni_id;

	$views = array();
	$views['pageTitle'] = "Edit Testimoni";
	$views['formAction']= "editTestimoni";


	if (isset($_POST['saveTesti']) && $_POST['saveTesti'] == 'Simpan')
	{
		extract($_POST);


		if ($testi == '')
		{
			$views['errorMessage'] = "Tolong Kolom Testimoni diisi";
			require( "testimoni/edit-testimoni.php" );
		}

		if (!isset($views['errorMessage']))
		{
			$data = array(

					'testimoni_id' => abs((int)$testi_id),
					'customer_id' => abs((int)$customer_id),
					'testimoni' => isset($testi) ? preg_replace('/[^ \'\,\.\-a-zA-Z0-9]/', '', $testi) : '',
					'actived' => isset($active) ? preg_replace('/[^YN]/', '', $active) : ''
			);

			$edit_testi = new Testimoni($data);
			$edit_testi -> updateTestimoni();

			echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=testimoni&status=testimoniUpdated">';

			exit();

		}

	}
	else
	{
		$views['testi'] = $testimony -> getTestimoni($testimoni_id);
		$views['activedTesti'] = $views['testi'] -> getTestimoni_Status();
		require( "testimoni/edit-testimoni.php" );
	}
}

//fungsi hapus testimoni
function deleteTestimoni() {

	global $testimony;

	global $testimoni_id;

	if (!$testimoni = $testimony -> getTestimoni($testimoni_id))
	{
		require("../cabin/404.php" );
	}

	$data = array('testimoni_id' => $testimoni_id);

	$del_testi = new Testimoni($data);
	$del_testi -> deleteTestimoni();

	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=testimoni&status=testimoniDeleted">';

	exit();
}

//fungsi tampil testimoni
function listTestimonies() {

	$views = array();

	$p = new Pagination;
	$limit = 10;
	$position = $p -> getPosition($limit);

	$data_testimoni = Testimoni::getTestimonies($position, $limit);

	$views['testimonies'] = $data_testimoni['results'];
	$views['totalRows'] = $data_testimoni['totalRows'];
	$views['position'] = $position;
	$views['pageTitle'] = "Testimoni";

	//pagination
	$totalPage = $p -> totalPage($views['totalRows'], $limit);
	$pageLink = $p -> navPage($_GET['orde'], $totalPage);
	$views['pageLink'] = $pageLink;

	if (isset($_GET['error']))
	{
		if ( $_GET['error'] == "testimoniNotFound" ) $views['errorMessage'] = "Error: Testimoni tidak ditemukan";
	}

	if (isset($_GET['status']))
	{
		if ( $_GET['status'] == "testimoniUpdated") $views['statusMessage'] = "Testimoni sudah diupdate";
		if ( $_GET['status'] == "testimoniDeleted") $views['statusMessage'] = "Testimoni sudah dihapus";
	}

	require( "testimoni/list-testimonies.php" );
}