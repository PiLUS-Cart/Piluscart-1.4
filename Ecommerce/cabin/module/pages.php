<?php if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!");
/**
 * File Modul pages.php
 * mengelola business logic
 * pada fungsionalitas objek page
 * dan page management
 * 
 * @package   PiLUS_CMS
 * @author    Maoelana Noermoehammad
 * @copyright 2014 kartatopia.com
 * @license   GPL version 3.0
 * @version   1.4.0
 * @since     Since Release 1.4
 *
 */

$action = isset($_GET['action']) ? htmlspecialchars(strip_tags($_GET['action'])) : " ";
$type = isset($_GET['type']) ? htmlspecialchars($_GET['type']) : " ";
$pageId = isset($_GET['pageId']) ? abs((int)$_GET['pageId']) : 0;
$posts = new Post();
$postImg = new Postimg();
$accessLevel = Admin::accessLevel();

if ( $accessLevel != 'superadmin' && $accessLevel != 'admin')
{
	include_once( "../cabin/404.php" );
}
else 
{
	switch ($action) {
	
		// menampilkan semua halaman
		default:
			listPages();
			break;
	
			// Tambah Halaman
		case 'newPage':
	
			addPage();
	
			break;
	
			//Edit Halaman
		case 'editPage':
	
			$cleaned = $sanitasi -> sanitasi($pageId, 'sql');
			$current_page = $posts -> seekById($cleaned, $type);
			$current_pid = $current_page['ID'];
	
			if ( isset( $pageId) && $current_pid != $pageId)
			{
				require( "../cabin/404.php" );
			}
			else
			{
				updatePage();
			}
	
			break;
	
			//Hapus Halaman
		case 'deletePage':
	
			deletePage();
	
			break;
	
	}	
	
}

// fungsi tampil halaman
function listPages() {

	$views = array();

	$p = new Pagination;
	$limit = 10;
	$position = $p -> getPosition($limit);

	$postType = 'page';
	$data_page = Post::findPages($postType, $position, $limit);

	$views['pages'] = $data_page['results'];
	$views['totalRows'] = $data_page['totalRows'];
	$views['pageTitle'] = "Halaman";
	$views['position'] = $position;

	//pagination
	$totalPage = $p -> totalPage($views['totalRows'], $limit);
	$pageLink = $p -> navPage($_GET['order'], $totalPage);
	$views['pageLink'] = $pageLink;

	if (isset($_GET['error']))
	{
		if ($_GET['error'] == "pageNotFound") $views['errorMessage'] = "Error: Halaman tidak ditemukan";

	}

	if ( isset($_GET['status']))
	{

		if ( $_GET['status'] == "pageAdded") $views['statusMessage']   = "Halaman baru sudah disimpan";
		if ( $_GET['status'] == "pageUpdated") $views['statusMessage'] = "Halaman sudah diupdate";
		if ( $_GET['status'] == "pageDeleted") $views['statusMessage'] = "Halaman sudah dihapus";
	}

	require( "page/list-pages.php" );

}

// fungsi tambah halaman
function addPage() {

	global $posts;

	global $postImg;

	global $userID;

	global $sanitasi;

	$views = array();
	$views['pageTitle'] = "Tambah Halaman";
	$views['formAction']= "newPage";

	$postType = "page";

	//Set max size file uploaded
	$max=512000;


	if (isset($_POST['savePage']) && $_POST['savePage'] == 'Simpan')
	{

		extract($_POST);

		$tgl_sekarang = date("Ymd");

		$title = $sanitasi -> sanitasi($title, 'xss');

		if (empty($title) OR empty($content))
		{
			$views['errorMessage'] = "Kolom yang bertanda asterik(*) harus diisi";
			require( "page/edit-page.php" );

		}

		if ( !isset($views['errorMessage']))
		{
			if ( isset($upload_new))
			{
					
				$path = "../content/uploads/images/";
				$path_thumb = "../content/uploads/images/thumbs/";
					
				$upload_image = new UploadImage($path);
				$upload_image -> setThumbDestination($path_thumb);
					
				$upload_image -> setMaxSize($max);
				$upload_image -> move();
					
				$names = $upload_image -> getFilenames();
					
				if ( $names )
				{
					$new_image = array(

							'filename' => $names[0],
							'caption' => isset($caption) ? preg_replace('/[^ \'\,\.\-a-zA-Z0-9]/', '', $caption) : '',
							'slug' => isset($caption) ? makeSlug($caption) : ''
					);

					$add_image = new Postimg($new_image);
					$row = $add_image -> addImage();

					$image_id = (int)$row['imageId'];

					if ( isset($image_id))
					{
						$new_page = array(
									
								'post_image' => $image_id,
								'post_author' => $userID,
								'post_date' => $tgl_sekarang,
								'post_title' => $title,
								'post_slug' => makeSlug($title),
								'post_content' => isset($content) ? preventInject($content) : '',
								'post_status' => $post_status,
								'post_type' => $postType,
								'comment_status' => $comment_status
						);
							
						$add_page = new Post($new_page);
						$row = $add_page -> createPage();
							
						echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=pages&status=pageAdded">';
							
						exit();
					}

				}
					
			}
			else
			{
				$new_page = array(
							
						'post_image' => $image_id,
						'post_author' => $userID,
						'post_date' => $tgl_sekarang,
						'post_title' => isset($title) ? preg_replace('/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/', '', $title) : '',
						'post_slug' => makeSlug($title),
						'post_content' => isset($content) ? preventInject($content) : '',
						'post_status' => $post_status,
						'post_type' => $postType,
						'comment_status' => $comment_status
				);
					
					
				$add_page = new Post($new_page);
				$row = $add_page -> createPage();
					
				echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=pages&status=pageAdded">';
					
				exit();
			}
		}
	}
	else
	{

		$views['page'] = $posts;
		$views['postStatus'] = $views['page'] -> postStatus_Dropdown();
		$views['commentStatus'] = $views['page'] -> commentStatus_Dropdown();
		$views['postImg'] = $postImg -> setPostImg_Dropdown();
		require( "page/edit-page.php" );
			
	}

}

// fungsi update halaman
function updatePage() {

	global $posts;

	global $postImg;

	global $pageId;

	global $type;

	global $sanitasi;

	$views =  array();
	$views['pageTitle'] = "Edit Halaman";
	$views['formAction']= "editPage";

	//Set max size file uploaded
	$max=512000;

	if (isset($_POST['savePage']) && $_POST['savePage'] == 'Simpan')
	{

		extract($_POST);

		$page_id = $sanitasi -> sanitasi($page_id, 'sql');
		$title = $sanitasi -> sanitasi($_POST['title'], 'xss');


		if (empty($title) OR empty($content))
		{
			$views['errorMessage'] = "Kolom yang bertanda asterik(*) harus diisi";
			require( "page/edit-page.php" );

		}


		if ( !isset($views['errorMessage']))
		{
			if ( !isset($upload_new))
			{
				$data = array(
							
						'ID' => $page_id,
						'post_image' => $image_id,
						'post_title' => $title,
						'post_slug' => makeSlug($title),
						'post_content' => isset($content) ? preventInject($content) : '',
						'post_status' => $post_status,
						'post_type' => $post_type,
						'comment_status' => $comment_status
				);
					
				$edit_page = new Post($data);
				$edit_page -> updatePage();
					
				echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=pages&status=pageUpdated">';
					
				exit();
			}
			else
			{
				$path = "../content/uploads/images/";
				$path_thumb = "../content/uploads/images/thumbs/";
					
				$upload_image = new UploadImage($path);
				$upload_image -> setThumbDestination($path_thumb);
					
				$upload_image -> setMaxSize($max);
				$upload_image -> move();
					
				$names = $upload_image -> getFilenames();
					
				if ( $names )
				{
					$new_image = array(

							'filename' => $names[0],
							'caption' => isset($caption) ? preg_replace('/[^ \'\,\.\-a-zA-Z0-9]/', '', $caption) : '',
							'slug' => isset($caption) ? makeSlug($caption) : ''
					);

					$add_image = new Postimg($new_image);
					$row = $add_image -> addImage();

					$image_id = (int)$row['imageId'];

					if ( $image_id )
					{
						$data = array(
									
								'ID' => $page_id,
								'post_image' => $image_id,
								'post_title' => $title,
								'post_slug' => makeSlug($title),
								'post_content' => isset($content) ? preventInject($content) : '',
								'post_status' => $post_status,
								'post_type' => $post_type,
								'comment_status' => $comment_status
						);
							
						$edit_page = new Post($data);
						$edit_page -> updatePage();
							
						echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=pages&status=pageUpdated">';
							
						exit();
					}
				}
			}

		}

	}
	else
	{
		$views['page'] = $posts -> findPageById($pageId, $type);
		$views['postStatus'] = $views['page'] -> postStatus_Dropdown();
		$views['commentStatus'] = $views['page'] -> commentStatus_Dropdown();
		$views['postImg'] = $postImg -> setPostImg_Dropdown($views['page'] -> getPost_Image());
		$views['imagePath'] = $views['page'] -> getPostImg_Filename();
		require( "page/edit-page.php" );
	}
}

// fungsi hapus halaman
function deletePage() {

	global $posts;

	global $postImg;

	global $pageId;

	global $type;


	if ( !$page = $posts -> findPageById($pageId, $type))
	{
		require( "../cabin/404.php" );
	}

	$data = array('ID' => $pageId);

	$hapus_halaman = new Post($data);
	$hapus_halaman -> deletePage($type);

	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=pages&status=pageDeleted">';

	exit();

}