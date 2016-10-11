<?php if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!");
/**
 * File Modul posts.php
 * mengelola business logic
 * pada fungsionalitas objek post
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
$postId = isset($_GET['postId']) ? abs((int)$_GET['postId']) : 0;
$accessLevel = Admin::accessLevel();
$posts = new Post();
$postImg = new Postimg();
$postCat = new Postcat();
$Label = new Tag();

if ( $accessLevel != 'superadmin' && $accessLevel != 'admin' && $accessLevel != 'editor' && $accessLevel != 'author' && $accessLevel != 'contributor')
{
	include_once( "../cabin/404.php" );
}
else 
{

	switch ($action) {
	
		// Tambah Tulisan
		case 'newPost' :
	
			addPost();
	
			break;
	
			// Edit Tulisan
		case 'editPost':
	
			$cleaned = $sanitasi -> sanitasi($postId, 'sql');
			$current_post = $posts -> seekById($cleaned, "blog");
			$current_pid = $current_post['ID'];
	
			if (isset($postId) && $current_pid != $postId)
			{
				require( "../cabin/404.php" );
			}
			elseif ( $accessLevel != 'superadmin' && $accessLevel != 'admin')
			{
				updatePost_ByStaff();
			}
			else
			{
				updatePost();
			}
	
			break;
	
			// Hapus Tulisan
		case 'deletePost':
	
			deletePost();
	
			break;
	
			// Tampil Tulisan
		default:
	
			if ( $accessLevel != 'superadmin' && $accessLevel != 'admin')
			{
				listPost_ByStaff();
			}
			else
			{
				listPosts();
			}
			
			break;
			
	}
	
}

// Fungsi Tambah Tulisan
function addPost() 
{

	global $posts, $postImg, $postCat, $userID, $Label;

	$views = array();
	$views['pageTitle'] = "Tambah Tulisan";
	$views['formAction']= "newPost";

	//Set max size file uploaded
	$max=512000;

	if (isset($_POST['savePost']) && $_POST['savePost'] == 'Simpan') {
		
		extract($_POST);

		$tgl_sekarang = date("Ymd");

		if (empty($title) OR empty($content) OR empty($catID)) {
			
			$views['errorMessage'] = "Kolom yang bertanda asterik(*) harus diisi";
			require( "post/edit-post.php" );
		}


		if (!isset($views['errorMessage'])) {
			
			if ( isset($upload_new)) {
				
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

					$image_id = $row['imageId'];
						
					if ( isset($image_id))
					{
						
						$data = array(
									
								'post_image' => $image_id,
								'post_cat' => $catID,
								'post_author' => $userID,
								'post_date' => $tgl_sekarang,
								'post_title' => isset($title) ? preg_replace('/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/', '', $title) : '',
								'post_slug' => makeSlug($title),
								'post_content' => isset($content) ? preventInject($content) : '',
								'post_status' => $post_status,
								'comment_status' => $comment_status,
								'post_tag' => isset($_POST['slug']) ? implode(',', $_POST['slug']) : ''
								
						);

						$add_post = new Post($data);
						if ( $add_post -> createPost())
						{
							$updateTagged = $Label -> updateTagCounted($_POST['slug']);
						}
						
						echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=posts&status=postAdded">';
							
						exit();

					}
					
				}
				
			} else {
				
				$data = array(
							
						'post_image' => $image_id,
						'post_cat' => $catID,
						'post_author' => $userID,
						'post_date' => $tgl_sekarang,
						'post_title' => isset($title) ? preg_replace('/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/', '', $title) : '',
						'post_slug' => makeSlug($title),
						'post_content' => isset($content) ? preventInject($content) : '',
						'post_status' => $post_status,
						'comment_status' => $comment_status,
						'post_tag' => isset($_POST['slug']) ? implode(',', $_POST['slug']) : '');

				$add_post = new Post($data);
				
				if ( $add_post -> createPost())
				{
					$updateTagged = $Label -> updateTagCounted($_POST['slug']);
				}
				echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=posts&status=postAdded">';
					
				exit();

			}
		}
		
	}
	else
	{
		$views['post'] = $posts;
		$views['postImg'] = $postImg -> setPostImg_Dropdown();
		$views['postCat'] = $postCat -> setPostcat_Dropdown();
		$views['postStatus'] = $views['post'] -> postStatus_Dropdown();
		$views['commentStatus'] = $views['post'] -> commentStatus_Dropdown();
		$views['Label'] = $Label -> setCheckBoxes($_POST['slug']);

		require( "post/edit-post.php" );
	}

}

// Fungsi Edit Tulisan
function updatePost() {

	global $posts, $postImg, $postCat, $Label, $postId, $sanitasi;

	$views = array();
	$views['pageTitle'] = "Edit Tulisan";
	$views['formAction'] = "editPost";

	//Set max size file uploaded
	$max=512000;

	if (isset($_POST['savePost']) && $_POST['savePost'] == 'Simpan')
	{
		extract($_POST);

		$post_id = $sanitasi -> sanitasi($post_id,'sql');

		if ( $post_id == '')
		{
			$views['errorMessage'] = "ID tulisan ini tidak ditemukan";
			require( "page/edit-page.php" );
		}

		if (empty($title) || empty($content))
		{
			$views['errorMessage'] = "Kolom yang bertanda asterik(*) harus diisi";
			require( "page/edit-page.php" );

		}

		if (!isset($views['errorMessage']))
		{
			if ( !isset($upload_new))
			{
				$data = array(
							
						'ID' => $post_id,
						'post_image' => $image_id,
						'post_cat' => $catID,
						'post_title' => isset($title) ? preg_replace('/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/', '', $title) : '',
						'post_slug' => makeSlug($title),
						'post_content' => isset($content) ? preventInject($content) : '',
						'post_status' => $post_status,
						'comment_status' => $comment_status, 
						'post_tag' => isset($_POST['slug']) ? implode(',', $_POST['slug']) : ''
				);

				$edit_post = new Post($data);
				$edit_post -> updatePost();

				echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=posts&status=postUpdated">';
					
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
									
								'ID' => $post_id,
								'post_image' => $image_id,
								'post_cat' => $catID,
								'post_title' => isset($title) ? preg_replace('/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/', '', $title) : '',
								'post_slug' => makeSlug($title),
								'post_content' => isset($content) ? preventInject($content) : '',
								'post_status' => $post_status,
								'comment_status' => $comment_status,
								'post_tag' => isset($_POST['slug']) ? implode(',', $_POST['slug']) : ''
						);
							
						$edit_post = new Post($data);
						$edit_post -> updatePost();

						echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=posts&status=postUpdated">';
							
						exit();

					}
				}
			}
		}

	}
	else
	{
		$data_articles = '';
		$data_articles = $posts -> findPostById($postId);
		$views['post'] = $data_articles;
		$views['postStatus'] = $views['post'] -> postStatus_Dropdown();
		$views['commentStatus'] = $views['post'] -> commentStatus_Dropdown();
		$views['postImg'] = $postImg -> setPostImg_Dropdown($views['post'] -> getPost_Image());
		$views['postCat'] = $postCat -> setPostcat_Dropdown($views['post'] -> getPost_Cat());
		$views['imagePath'] = $views['post'] -> getPostImg_Filename();
		$views['Label'] = $Label -> getCheckBoxes('slug', 'tag', $views['post'] -> getPost_Tag());
		
		require( "post/edit-post.php" );
	}
}

// fungsi update tulisan oleh staff selain admin
function updatePost_ByStaff() {
	
	global $posts, $userID, $postImg, $postCat, $Label, $postId, $sanitasi;
	
	$views = array();
	$views['pageTitle'] = "Edit Tulisan";
	$views['formAction'] = "editPost";
	
	//Set max size file uploaded
	$max=512000;
	
	if (isset($_POST['savePost']) && $_POST['savePost'] == 'Simpan')
	{
		extract($_POST);
	
		$post_id = $sanitasi -> sanitasi($post_id,'sql');
	
		if ( $post_id == '')
		{
			$views['errorMessage'] = "ID tulisan ini tidak ditemukan";
			require( "page/edit-page.php" );
		}
	
		if (empty($title) || empty($content))
		{
			$views['errorMessage'] = "Kolom yang bertanda asterik(*) harus diisi";
			require( "page/edit-page.php" );
	
		}
	
		if (!isset($views['errorMessage']))
		{
			if ( !isset($upload_new))
			{
				$data = array(
							
						'ID' => $post_id,
						'post_image' => $image_id,
						'post_cat' => $catID,
						'post_title' => isset($title) ? preg_replace('/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/', '', $title) : '',
						'post_slug' => makeSlug($title),
						'post_content' => isset($content) ? preventInject($content) : '',
						'post_status' => $post_status,
						'comment_status' => $comment_status,
						'post_tag' => isset($_POST['slug']) ? implode(',', $_POST['slug']) : ''
				);
	
				$edit_post = new Post($data);
				$edit_post -> updatePost();
	
				echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=posts&status=postUpdated">';
					
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
									
								'ID' => $post_id,
								'post_image' => $image_id,
								'post_cat' => $catID,
								'post_title' => isset($title) ? preg_replace('/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/', '', $title) : '',
								'post_slug' => makeSlug($title),
								'post_content' => isset($content) ? preventInject($content) : '',
								'post_status' => $post_status,
								'comment_status' => $comment_status,
								'post_tag' => isset($_POST['slug']) ? implode(',', $_POST['slug']) : ''
						);
							
						$edit_post = new Post($data);
						$edit_post -> updatePost();
	
						echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=posts&status=postUpdated">';
							
						exit();
	
					}
				}
			}
		}
	
	}
	else
	{
		$views['post'] = $posts -> findPostByStaff($postId, $userID);
		$views['postStatus'] = $views['post'] -> postStatus_Dropdown();
		$views['commentStatus'] = $views['post'] -> commentStatus_Dropdown();
		$views['postImg'] = $postImg -> setPostImg_Dropdown($views['post'] -> getPost_Image());
		$views['postCat'] = $postCat -> setPostcat_Dropdown($views['post'] -> getPost_Cat());
		$views['imagePath'] = $views['post'] -> getPostImg_Filename();
		$views['Label'] = $Label -> getCheckBoxes('slug', 'tag', $views['post'] -> getPost_Tag());
		require( "post/edit-post.php" );
	}
}

// fungsi hapus tulisan
function deletePost() {

	global $posts, $postId;

	if (!$post = $posts -> findPostById($postId))
	{
		require( "../cabin/404.php" );
	}

	$data = array('ID'=> $postId);

	$hapus_tulisan = new Post($data);
	$hapus_tulisan -> deletePost();

	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=posts&status=postDeleted">';

	exit();

}

// fungsi menampilkan semua tulisan
function listPosts() {

	global $postCat;

	$views = array();

	$p = new Pagination();
	$limit = 10;
	$position = $p -> getPosition($limit);

	$data_post = Post::findPosts($position, $limit);


	$views['posts'] = $data_post['results'];
	$views['totalRows'] = $data_post['totalRows'];
	$views['pageTitle'] = "Tulisan";
	$views['position'] = $position;

	//pagination
	$totalPage = $p -> totalPage($views['totalRows'], $limit);
	$pageLink = $p -> navPage($_GET['order'], $totalPage);
	$views['pageLink'] = $pageLink;

	if (isset($_GET['error']))
	{
		if ($_GET['error'] == "postNotFound") $views['errorMessage'] = "Error: Tulisan tidak ditemukan";

	}

	if ( isset($_GET['status']))
	{

		if ( $_GET['status'] == "postAdded") $views['statusMessage']   = "Tulisan baru sudah disimpan";
		if ( $_GET['status'] == "postUpdated") $views['statusMessage'] = "Tulisan sudah diupdate";
		if ( $_GET['status'] == "postDeleted") $views['statusMessage'] = "Tulisan sudah dihapus";
	}


	require( "post/list-posts.php" );

}

// fungsi menampilkan semua tulisan
function listPost_ByStaff() {
	
	global $postCat;
	
	$views = array();
	
	$p = new Pagination();
	$limit = 10;
	$position = $p -> getPosition($limit);
	
	$data_post = Post::findPosts_ByStaff($position, $limit);
	
	
	$views['posts'] = $data_post['results'];
	$views['totalRows'] = $data_post['totalRows'];
	$views['pageTitle'] = "Tulisan";
	$views['position'] = $position;
	
	//pagination
	$totalPage = $p -> totalPage($views['totalRows'], $limit);
	$pageLink = $p -> navPage($_GET['order'], $totalPage);
	$views['pageLink'] = $pageLink;
	
	if (isset($_GET['error']))
	{
		if ($_GET['error'] == "postNotFound") $views['errorMessage'] = "Error: Tulisan tidak ditemukan";
	
	}
	
	if ( isset($_GET['status']))
	{
	
		if ( $_GET['status'] == "postAdded") $views['statusMessage']   = "Tulisan baru sudah disimpan";
		if ( $_GET['status'] == "postUpdated") $views['statusMessage'] = "Tulisan sudah diupdate";
		if ( $_GET['status'] == "postDeleted") $views['statusMessage'] = "Tulisan sudah dihapus";
	}
	
	
	require( "post/list-posts.php" );
}