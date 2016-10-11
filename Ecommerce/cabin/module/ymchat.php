<?php if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!");
/**
 * File Modul ymchat.php
 * mengelola business logic
 * pada fungsionalitas objek ymchat
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
$csId = isset($_GET['ymId']) ? abs((int)$_GET['ymId']) : 0;
$ymchat = new YMChat();
$accessLevel = Admin::accessLevel();

if ( $accessLevel != 'superadmin' )
{
	include_once( "../cabin/404.php" );
}
else 
{
	
	switch ($action) {
	
		//tambah ymchat
		case 'newCS':
	
			addYMChat();
	
			break;
	
			//update ymchat
		case 'editCS':
	
			$cleaned = $sanitasi -> sanitasi($csId, 'sql');
			$current_ym = $ymchat -> findById($cleaned);
			$current_ymid = $current_ym['ymchat_id'];
	
			if (isset($csId) && $current_ymid != $csId )
			{
				require( "../cabin/404.php" );
			}
			else
			{
				updateYMChat();
			}
	
			break;
	
			//hapus ymchat
		case 'deleteCS':
	
			deleteYMChat();
	
			break;
	
			//tampilkan ymchat
		default :
	
			listYMChat();
	
			break;
	
	}
	
}

//fungsi tampil ymchat
function listYMChat()
{
	$views = array();
	
	$p = new Pagination();
	$limit = 10;
	$position = $p -> getPosition($limit);

	$data_ymchat = YMChat::getYMChats($position, $limit);

	$views['cs'] = $data_ymchat['results'];
	$views['totalRows'] = $data_ymchat['totalRows'];
	$views['position'] = $position;
	$views['pageTitle'] = "YM Chat";
	

	//pagination
	$totalPage = $p -> totalPage($views['totalRows'], $limit);
	$pageLink = $p -> navPage($_GET['order'], $totalPage);
	$views['pageLink'] = $pageLink;


	if ( isset($_GET['error']))
	{

		if ( $_GET['error'] == "customerServiceNotFound" ) $views['errorMessage'] = "Error: Banner tidak ditemukan";

	}

	if ( isset($_GET['status']))
	{

		if ( $_GET['status'] == "customerServiceAdded") $views['statusMessage'] =   "Customer service baru sudah disimpan";
		if ( $_GET['status'] == "customerServiceUpdated") $views['statusMessage'] = "Customer service sudah diupdate";
		if ( $_GET['status'] == "customerServiceDeleted") $views['statusMessage'] = "Customer service sudah dihapus";
	}

	require( "ymchat/list-cs.php" );
	
}

//fungsi tambah ymchat
function addYMChat()
{

	global $ymchat;

	$views = array();
	$views['pageTitle'] = "Tambah YM Chat";
	$views['formAction'] = "newCS";

	if (isset($_POST['saveCS']) && $_POST['saveCS'] == 'Simpan')
	{
		extract($_POST);

		if (empty($cs_name) OR empty($open_id))
		{
			$views['errorMessage'] = "Kolom yang bertanda asterik(*) harus diisi";
			require( "ymchat/edit-cs.php" );
		}

		if ($ymchat -> nameExists($cs_name) == true)
		{
			$views['errorMessage'] = "Nama operator YM support sudah digunakan. Silahkan gunakan nama lain";
			require( "ymchat/edit-cs.php" );
		}

		if ($ymchat -> openIdExists($open_id) == true )
		{
			$views['errorMessage'] = "yahoo ID sudah digunakan. Silahkan gunakan yahoo ID lain";
			require( "ymchat/edit-cs.php" );
		}

		if (!isset($views['errorMessage']))
		{
			$data = array(
						
					'name' => $cs_name,
					'openID' => $open_id
			);
				
			$new_cs = new YMChat($data);
			$new_cs -> createYMChat();
				
			echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=ymchat&status=customerServiceAdded">';

			exit();
		}

	}
	else
	{
		
		$views['ymchat'] = $ymchat;
		require( "ymchat/edit-cs.php" );

	}

}

//fungsi edit ymchat
function updateYMChat()
{
	global $ymchat, $csId;

	$views = array();
	$views['pageTitle'] = "Edit YM Chat";
	$views['formAction'] = "editCS";

	if (isset($_POST['saveCS']) && $_POST['saveCS'] == 'Simpan')
	{
		extract($_POST);

		if (empty($cs_name) OR empty($open_id))
		{
			$views['errorMessage'] = "Kolom yang bertanda asterik(*) harus diisi";
			require( "ymchat/edit-cs.php" );
		}

		if (!isset($views['errorMessage']))
		{
			$data = array(

					'ymchat_id' => $cs_id,
					'name' => $cs_name,
					'openID' => $openID
			);

			$edit_cs = new YMChat($data);
			$edit_cs -> updateYMChat();

			echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=ymchat&status=customerServiceUpdated">';

			exit();
		}

	}
	else
	{
		$views['cs'] = $ymchat -> getYMChatById($csId);
		
		require( "ymchat/edit-cs.php" );
	}

}

// fungsi hapus ymchat
function deleteYMChat()
{
	
	global $ymchat, $csId;

	if ( !$cs = $ymchat -> getYMChatById($csId))
	{
		require( "../cabin/404.php" );
	}
	
	$data = array('ymchat_id', $csId);
	
	$hapus_cs = new YMChat();
	$hapus_cs -> deleteYMChat();
	
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=ymchat&status=customerServiceDeleted">';
	
	exit();
	
}