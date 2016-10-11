<?php if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!");
/**
 * File Modul comments.php
 * mengelola business logic
 * pada fungsionalitas comment
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
$commentId = isset($_GET['commentId']) ? abs((int)$_GET['commentId']) : 0;
$replyId = isset($_GET['replyId']) ? abs((int)$_GET['replyId']) : 0;
$comments =  new PostComment();
$replyComment = new ReplyComment();
$accessLevel = Admin::accessLevel();

if ( $accessLevel != 'superadmin' && $accessLevel != 'admin' 
	&& $accessLevel != 'editor' && $accessLevel != 'author') {
			
	include_once('../cabin/404.php');
	
} else {
	
	switch ($action) {
	
		case 'editComment':
				
			$cleaned = $sanitasi -> sanitasi($commentId, 'sql');
			$current_comment = $comments -> findById($cleaned);
			$current_id = $current_comment['comment_id'];
	
			if (isset($commentId) && $commentId != $current_id) {
				
				require('../cabin/404.php');
				
			} else {
				
				updateComment();
				
			}
	
			break;
	
		case 'deleteComment':
	
			deleteComment();
	
			break;
	
		case 'replyComment':
	
			$sanitized = $sanitasi -> sanitasi($replyId, 'sql');
			$current_reply = $replyComment -> findById($sanitized);
			$current_reply_id = $current_reply['reply_id'];
			
			if (isset($replyId) && $replyId != $current_reply_id) {
				
				require('../cabin/404.php');
				
			} else {
				
				replyComment();
				
			}
			
			break;
	
		case 'editReply':
	
			$sanitized = $sanitasi -> sanitasi($replyId, 'sql');
			$current_reply = $replyComment -> findById($sanitized);
			$current_reply_id = $current_reply['reply_id'];
				
			if (isset($replyId) && $replyId != $current_reply_id) {
				
				require('../cabin/404.php');
				
			} else {
				
				updateReply();
				
			}
			
			break;
	
		case 'deleteReply':
	
			deleteReply();
	
			break;
	
		default:
	
			listComments();
	
			break;
			
	}    	
	
}

// Menampilkan komentar tulisan
function listComments() 
{

	$views = array();

	$p = new Pagination();
	$limit = 10;
	$position = $p -> getPosition($limit);

	$data_comment = PostComment::getListComments($position, $limit);

	$views['comments'] = $data_comment['results'];
	$views['totalRows']= $data_comment['totalRows'];
	$views['position'] = $position;
	$views['pageTitle'] = "Komentar";

	//pagination
	$totalPage = $p ->totalPage($views['totalRows'], $limit);
	$pageLink = $p -> navPage($_GET['order'], $totalPage);
	$views['pageLink'] = $pageLink;

	if (isset($_GET['error'])) {
		
		if ($_GET['error'] == "commentNotFound") $views['errorMessage'] = "Error: Komentar tidak ditemukan";
		
	}

	if ( isset($_GET['status'])) {
		
		if ( $_GET['status'] == "commentUpdated") $views['statusMessage'] = "Komentar sudah diupdate";
		if ( $_GET['status'] == "commentDeleted") $views['statusMessage'] = "Komentar sudah dihapus";
		if ( $_GET['status'] == "commentReplied") $views['statusMessage'] = "Komentar sudah dibalas";
		if ( $_GET['status'] == "replyUpdated") $views['statusMessage'] = "Reply sudah diupdate";
		if ( $_GET['status'] == "replyDeleted") $views['statusMessage'] = "Reply sudah dihapus";
		
	}

	require('comment/list-comments.php');
	
}

// edit komentar
function updateComment() 
{

	global $comments, $replyComment, $commentId;

	$views = array();
	$views['pageTitle'] = "Edit Komentar";
	$views['formAction'] = "editComment";

	if (isset($_POST['saveComment']) && $_POST['saveComment'] == 'Simpan') {
		
		extract($_POST);

		if ( empty($nama_komentar) || empty($isi_komentar)) {
			
			$views['errorMessage'] = "Kolom yang bertanda asterik(*) harus diisi";
			require('comment/edit-comment.php');
			
		}

		if (!isset($views['errorMessage'])) {
			
			$data = array(

					'comment_id' => abs((int)$comment_id),
					'post_id' => (int)$post_id,
					'fullname' => isset($nama_komentar) ? preventInject($nama_komentar) : '',
					'url' => isset($url) ? validHttp($url) : '',
					'comment' => preventInject($isi_komentar),
					'actived' => isset($_POST['active']) ? preg_replace('/[^YN]/', '', $_POST['active']) : '');
				
			$edit_komentar = new PostComment($data);
			$edit_komentar -> updateComment();
				
			echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=comments&status=commentUpdated">';
				
			exit();
			
		}
		
	} else {
		
		$komentar = '';
		$komentar = $comments -> getCommentById($commentId);
		$views['comment'] = $komentar;
		$views['comment_id'] = $views['comment'] -> getComment_Id();
		$views['activedComment'] = $views['comment'] -> getComment_Status();
		
		// reply comment
		$balas_komentar = '';
		$balas_komentar = $replyComment -> setReplyId($commentId);
		$views['replyComment'] = $balas_komentar;
		$views['reply_id'] = $views['replyComment'] -> reply_id;
		$views['admin_id'] = $views['replyComment'] -> admin_id;

		require('comment/edit-comment.php');
		
	}

}

// hapus komentar
function deleteComment() 
{

	global $comments, $sanitasi, $commentId;

	if (!$comment = $comments -> getCommentById($commentId)) {
		
		require('../cabin/404.php');
	}

	if ($commentId = (int)$commentId) {
		
		$sanitizeId = $sanitasi -> sanitasi($commentId, 'sql');
		
		$data = array( 'comment_id' => $sanitizeId);
		
		$hapus_komentar = new PostComment($data);
		$hapus_komentar -> deleteComment();
		
		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=comments&status=commentDeleted">';
		
		exit();
	}
	
}

// reply comment
function replyComment() 
{

	global $replyComment, $userID, $commentId, $replyId;

	$views = array();
	$views['pageTitle'] = "Reply Comment";
	$views['formAction'] = "replyComment";

	if (isset($_POST['saveReply']) && $_POST['saveReply'] == 'Simpan') {
		
		extract($_POST);

		$tgl_sekarang = date("Ymd");

		if (empty($balas_komentar) OR empty($active)) {
			
			$views['errorMessage'] = "Kolom yang bertanda asterik(*) harus diisi";
			require('comment/reply-comment.php');
			
		}

		if (!isset($views['errorMessage'])) {
			
			$data = array(
					
					'reply_id' => isset($reply_id) ? (int)$reply_id : '',	
					'comment_id' => isset($comment_id) ? abs((int)$comment_id) : '',
					'admin_id' => (int)$userID,
					'reply' => preventInject($balas_komentar),
					'date_created' => $tgl_sekarang,
					'actived' => isset($_POST['active']) ? preg_replace('/[^YN]/', '', $_POST['active']) : '');
				
			$reply_comment = new replyComment($data);
			$reply_comment -> updateReply();
				
			echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=comments&status=commentReplied">';

			exit();
				
		}
		
	} else {
		
		$balas_komentar = '';
		$balas_komentar = $replyComment -> setReplyId($commentId);
		$views['reply_id'] = $balas_komentar -> reply_id;
		$views['replyComment'] = $replyComment -> findReply($views['reply_id']);
		$views['activedReply'] = $views['replyComment'] -> getActived();
		$views['sender'] = $views['replyComment'] -> getCommentator_Fullname();
		
		require('comment/reply-comment.php');
		
	}

}

//fungsi view reply
function updateReply() 
{

	global $replyComment, $userID, $replyId, $commentId;

	$views = array();
	$views['pageTitle'] = "Edit Reply";
	$views['formAction'] = "editReply";

	if (isset($_POST['saveReply']) && $_POST['saveReply'] == 'Simpan') {
		
		extract($_POST);
		
		$tgl_sekarang = date("Ymd");

		if (empty($balas_komentar) OR empty($active)) {
			
			$views['errorMessage'] = "Kolom yang bertanda asterik(*) harus diisi";
			require('comment/reply-comment.php');
			
		}

		if (!isset($views['errorMessage'])) {
			
			$data = array(
						
					'reply_id' => isset($reply_id) ? (int)$reply_id : '',
					'comment_id' => isset($comment_id) ? abs((int)$comment_id) : '',
					'admin_id' => (int)$userID,
					'reply' => preventInject($balas_komentar),
					'date_created' => $tgl_sekarang,
					'actived' => isset($_POST['active']) ? preg_replace('/[^YN]/', '', $_POST['active']) : '');
			
			$reply_comment = new replyComment($data);
			$reply_comment -> updateReply();
				
			echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=comments&status=replyUpdated">';
				
			exit();
			
		}
		
	} else {
		
		$balas_komentar = '';
		$balas_komentar = $replyComment -> setReplyId($commentId);
		$views['reply_id'] = $balas_komentar -> reply_id;
		$views['replyComment'] = $replyComment -> findReply($views['reply_id']);
		$views['activedReply'] = $views['replyComment'] -> getActived();
		$views['sender'] = $views['replyComment'] -> getCommentator_Fullname();
		
		require('comment/reply-comment.php');
		
	}

}

//fungsi hapus reply
function deleteReply() {

	global $replyComment, $replyId;

	if (!$reply = $replyComment -> findReply($replyId)) {
		
		require('../cabin/404.php');
	}

	$data = array('reply_id' => $replyId);

	$hapus_reply = new ReplyComment($data);
	$hapus_reply -> deleteReply();

	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=comments&status=replyDeleted">';

	exit();

}