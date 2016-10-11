<?php if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!");
/**
 * File Modul notification.php
 * mengelola business logic
 * pada fungsionalitas objek notification
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
$notificationId = isset($_GET['notificationId']) ? abs((int)$_GET['notificationId']) : 0;
$notifications =  new Notification();
$accessLevel = Admin::accessLevel();

if ( $accessLevel != 'superadmin' && $accessLevel != 'admin')
{
	include_once( "../cabin/404.php" );
}
else 
{
	switch ($action) {
	
		case 'viewNotification':
	
			$cleaned = $sanitasi -> sanitasi($notificationId, 'sql');
			$current_notifikasi = $notifications -> findById($cleaned);
			$current_id = $current_notifikasi['notify_id'];
	
			if (isset($notificationId) && $current_id != $notificationId )
			{
				require( "../cabin/404.php" );
			}
			else
			{
				viewNotification();
			}
		  
			break;
	
		case 'deleteNotification':
	
			deleteNotification();
	
			break;
	
		default:
	
			listNotifications();
				
			break;
	
	}
	
}

// fungsi detail notifikasi
function viewNotification() {
	
	global $notifications, $sanitasi, $notificationId;
	
	$views = array();
	$views['pageTitle'] = "detail notifikasi";
	
	if (isset($_POST['saveChange']) && $_POST['saveChange'] == 'Simpan')
	{
		$notification_id = isset($_POST['notify_id']) ? (int)$_POST['notify_id'] : 0;
		$status = isset($_POST['status']) ? preg_replace( '/[^01]/', '', $_POST['status'] ) : "";
		
		$data = array('notify_id' => $notification_id, 'status' => $status);
		
		$update_status = new Notification($data);
		$update_status -> updateStatus_Notification();
		
		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=notification&status=notificationUpdated">';
		
		exit();
		
	}
	else
	{
		$cleaned = $sanitasi -> sanitasi($notificationId, 'sql');
		$views['notification'] = $notifications -> getNotification($cleaned);
		require( "notifikasi/detail-notification.php" );
	}
	
}

// fungsi hapus notifikasi
function deleteNotification() {
	
	global $notifications, $notificationId;
	
	if ( !$notifikasi = $notifications -> getNotification($notificationId))
	{
		require( "../cabin/404.php" );
	}
	
	$data = array('notify_id' => $notificationId);
	$delete_notifikasi = new Notification($data);
	$delete_notifikasi -> deleteNotification();
	
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=notification&status=notificationDeleted">';
	
	exit();
	
}

// fungsi tampilkan semua notifikasi
function listNotifications() {
	
	$views = array();
	
	$p = new Pagination();
	$limit = 10;
	$position = $p -> getPosition($limit);
	
	$data_notification = Notification::getNotifications($position, $limit);
	
	$views['notifications'] = $data_notification['results'];
	$views['totalRows'] = $data_notification['totalRows'];
	$views['position'] = $position;
	$views['pageTitle'] = "Semua Notifikasi";
	
	//pagination
	$totalPage = $p ->totalPage($views['totalRows'], $limit);
	$pageLink = $p -> navPage($_GET['order'], $totalPage);
	$views['pageLink'] = $pageLink;
	
	if (isset($_GET['error']))
	{
		if ($_GET['error'] == "notificationNotFound") $views['errorMessage'] = "Error: Notifikasi tidak ditemukan";
	}
	
	if ( isset($_GET['status']))
	{
		if ( $_GET['status'] == "notificationUpdated") $views['statusMessage'] = "Status Notifikasi sudah diupdate";
		if ( $_GET['status'] == "notificationDeleted") $views['statusMessage'] = "Notifikasi sudah dihapus";
		
	}
	
	require( "notifikasi/list-notifications.php" );
	
}