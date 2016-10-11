<?php if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!");
/**
 * File Modul messages.php
 * mengelola business logic
 * pada fungsionalitas objek message-inbox
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
$messageId = isset($_GET['messageId']) ? abs((int)$_GET['messageId']) : 0;
$messages = new Inbox();
$mailer = new Mailer();
$accessLevel = Admin::accessLevel();

if ( $accessLevel != 'superadmin' && $accessLevel != 'admin' )
{
	include_once( "../cabin/404.php" );
}
else 
{
	switch ($action)
	{
		default:
	
			listMessages();
	
			break;
	
		case 'replyMessage':
	
			$cleaned = $sanitasi -> sanitasi($messageId, 'sql');
			$current_message = $messages -> findById($cleaned);
			$current_id = $current_message['inbox_id'];
	
			if ( isset($messageId) && $current_id != $messageId )
			{
				require( "../cabin/404.php" );
			}
			else
			{
				replyMessage();
			}
	
			break;
	
		case 'deleteMessage':
	
			deleteMessage();
	
			break;
	}	
}

//fungsi tampil pesan
function listMessages() {

	$views = array();

	$p = new Pagination;
	$limit = 10;
	$position = $p -> getPosition($limit);

	$data_message = Inbox::getMessages($position, $limit);

	$views['messages'] = $data_message['results'];
	$views['totalRows']= $data_message['totalRows'];
	$views['position'] = $position;
	$views['pageTitle'] = "Pesan";

	//pagination
	$totalPage = $p -> totalPage($views['totalRows'], $limit);
	$pageLink = $p -> navPage($_GET['order'], $totalPage);
	$views['pageLink'] = $pageLink;

	if (isset($_GET['error']))
	{
		if ( $_GET['error'] == "messageNotFound" ) $views['errorMessage'] = "Error: pesan tidak ditemukan ";
	}

	if (isset($_GET['status']))
	{
		if ( $_GET['status'] == "messageSent" ) $views['statusMessage'] = "Pesan berhasil dikirim ke tujuan";
		if ( $_GET['status'] == "messageDeleted" ) $views['statusMessage'] = "Pesan berhasil dihapus";
	}

	require( "inbox/list-messages.php" );

}

//fungsi balas pesan
function replyMessage() {

	global $messages, $messageId, $option;

	$views = array();
	$views['pageTitle'] = "Reply E-mail";
	$views['formAction'] = "replyMessage";
	
	if (isset($_POST['send']) && $_POST['send'] == 'Kirim')
	{
	
		$email = (isset($_POST['email']) ? preg_replace('/[^ \@\.\-\_a-zA-Z0-9]/', '', $_POST['email']) : '');
		$subjek = (isset($_POST['subjek']) ? preg_replace('/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/', '', $_POST['subjek']) : '');
		$pesan = $_POST['pesan'];
		
		//Mengambil data pemilik toko
		$metaowner = '';
		
		$data_owner = $option -> getOptions();
		
		$metaowner = $data_owner['results'];
		
		foreach ( $metaowner as $owner )
		{
			$namaToko = $owner -> getSite_Name();
		}
		
		$kirim_pesan = $messages -> replyMessage($email, $subjek, $pesan, $namaToko);
			
		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=inbox&status=messageSent">';
		exit;
	}
	else 
	{
		$views['Message'] = $messages -> readMessage($messageId);
		
		require( "inbox/read-message.php" );
		
	}

}

//fungsi hapus pesan
function deleteMessage() {

	global $messages, $messageId;

	if ( !$message = $messages -> readMessage($messageId))
	{
		require('../cabin/404.php');
	}

	$data = array('inbox_id' => $messageId);

	$hapus_pesan = new Inbox($data);
	$hapus_pesan -> deleteMessage();

	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=inbox&status=messageDeleted">';
	exit;

}