<?php if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!");
/**
 * File Modul postimage.php
 * mengelola business logic
 * pada fungsionalitas objek post image
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
$image_id = isset($_GET['imageId']) ? abs((int)$_GET['imageId']) : 0;
$postImages = new Postimg;
$accessLevel = Admin::accessLevel();

if ( $accessLevel != 'superadmin' && $accessLevel != 'admin' && $accessLevel != 'editor' && $accessLevel != 'author' )
{
	include_once( "..//404.php" );
}
else 
{
	switch ($action) {
	
		default:
	
			listPostImages(); // tampilkan image post
	
			break;
	
			// Tambah image post baru
		case 'newPostImage':
	
			addPostImage();
	
			break;
	
			// Edit image post
		case 'editPostImage':
	
			$cleaned = $sanitasi -> sanitasi($image_id, 'sql');
			$current_img = $postImages -> findById($cleaned);
			$current_id = $current_img['ID'];
	
			if ( isset($image_id) && $current_id != $image_id)
			{
				require( "../cabin/404.php" );
			}
			else
			{
				updatePostImage();
			}
	
			break;
	
			// Hapus image post
		case 'deletePostImage':
	
			deletePostImage();
	
			break;
	}
	
}


//fungsi menampilkan gambar post
function listPostImages() {

	$views = array();

	$p = new Pagination;
	$limit = 10;
	$position = $p -> getPosition($limit);

	$data_banner = Postimg::getImages($position, $limit);

	$views['postimages'] = $data_banner['results'];
	$views['totalRows']  = $data_banner['totalRows'];
	$views['position']   = $position;
	$views['pageTitle']  = "Galeri Foto";

	//pagination
	$totalPage = $p -> totalPage($views['totalRows'], $limit);
	$pageLink = $p -> navPage($_GET['order'], $totalPage);
	$views['pageLink'] = $pageLink;

	if ( isset($_GET['error']))
	{

		if ( $_GET['error'] == "imageNotFound" ) $views['errorMessage'] = "Error: Gambar tidak ditemukan";

	}

	if ( isset($_GET['status']))
	{

		if ( $_GET['status'] == "imageAdded") $views['statusMessage'] =  "Gambar baru sudah disimpan";
		if ( $_GET['status'] == "imageUpdated") $views['statusMessage'] = "Gambar sudah diupdate";
		if ( $_GET['status'] == "imageDeleted") $views['statusMessage'] = "Gambar sudah dihapus";
	}

	require( "media/galleries.php" );

}

//fungsi tambah image post
function addPostImage() {

	global $postImages;

	$views = array();
	$views['pageTitle']  = "Tambah Galeri Foto";
	$views['formAction'] = "newPostImage";

	$max = 51200;

	if (isset($_POST['saveImage']) && $_POST['saveImage'] == 'Simpan')
	{
		$file_location = isset($_FILES['image']['tmp_name']) ? $_FILES['image']['tmp_name'] : '';
		$file_type = isset($_FILES['image']['type']) ? $_FILES['image']['type'] : '';
		$file_name = isset($_FILES['image']['name']) ? $_FILES['image']['name'] : '';

		$image_slug = makeSlug($_POST['caption']);

		if (empty($_POST['caption']) || empty($file_location))
		{
			$views['errorMessage'] = "Kolom yang bertanda asterik(*) harus diisi";
			require( "media/edit-gallery.php" );
		}
		else
		{
			if ($file_type != "image/jpeg" and $file_type != "image/pjpeg" and $file_type != "image/png" and $file_type != "image/gif" )
			{
				$views['errorMessage'] = "Tipe file yang anda upload salah";
				require( "media/edit-gallery.php" );
					
			}
			else
			{
				$path = '../content/uploads/images/';
				$path_thumb = '../content/uploads/images/thumbs/';

				$upload_image = new UploadImage($path);
				$upload_image -> setThumbDestination($path_thumb);
				$upload_image -> setMaxSize($max);
				$upload_image -> move();
				$names = $upload_image -> getFilenames();
					
				$data = array(

						'filename' => $names[0],
						'caption' => isset($_POST['caption']) ? preg_replace('/[^ \'\,\.\-a-zA-Z0-9]/', '', $_POST['caption']) : '',
						'slug' => $image_slug

				);

				$add_postImage = new Postimg($data);
				$add_postImage -> addImage();

				echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=postimage&status=imageAdded">';

				exit();
			}
		}
	}
	else
	{
		$views['postImages'] = $postImages;
		require( "media/edit-gallery.php" );
	}
}


//fungsi edit image post
function updatePostImage() {

	global $postImages;
	global $image_id;

	$views = array();
	$views['pageTitle']  = "Edit Gambar";
	$views['formAction'] = "editPostImage";

	$max = 51200;

	if (isset($_POST['saveImage']) && $_POST['saveImage'] == 'Simpan')
	{
		$file_location = isset($_FILES['image']['tmp_name']) ? $_FILES['image']['tmp_name'] : '';
		$file_type = isset($_FILES['image']['type']) ? $_FILES['image']['type'] : '';
		$file_name = isset($_FILES['image']['name']) ? $_FILES['image']['name'] : '';

		$image_slug = makeSlug($_POST['caption']);

		if (empty($file_location))
		{
			$data = array(
						
					'ID' => isset($_POST['image_id']) ? abs((int)$_POST['image_id']) : '',
					'caption' => isset($_POST['caption']) ? preg_replace('/[^ \'\,\.\-a-zA-Z0-9]/', '', $_POST['caption']) : '',
					'slug' => $image_slug
			);
				
			$edit_postimage = new Postimg($data);
			$edit_postimage -> updateImage();
				
			echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=postimage&status=imageUpdated">';
				
			exit();
		}
		else
		{
			if ( $file_type != "image/jpeg" and $file_type != "image/pjpeg" and $file_type != "image/png" and $file_type != "image/gif" )
			{
				$views['errorMessage'] = "Tipe file yang anda upload salah";
				require( "media/edit-gallery.php" );
					
			}
			else
			{
				$path = '../content/uploads/images/';
				$path_thumb = '../content/uploads/images/thumbs/';

				$upload_image = new UploadImage($path);
				$upload_image -> setThumbDestination($path_thumb);
				$upload_image -> setMaxSize($max);
				$upload_image -> move();
				$names = $upload_image -> getFilenames();

				$data = array(
							
						'ID' => isset($_POST['image_id']) ? abs((int)$_POST['image_id']) : '',
						'filename' => $names[0],
						'caption' => isset($_POST['caption']) ? preg_replace('/[^ \'\,\.\-a-zA-Z0-9]/', '', $_POST['caption']) : '',
						'slug' => $image_slug
				);
					
				$edit_postimage = new Postimg($data);
				$edit_postimage -> updateImage();

				echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=postimage&status=imageUpdated">';

				exit();

			}
		}
	}
	else
	{
		$views['postImages'] = $postImages -> getImage($image_id);
		$views['imagePath'] = $views['postImages'] -> getImage_Filename();

		require( "media/edit-gallery.php" );
	}
}

//fungsi hapus image post
function deletePostImage() {

	global $postImages;

	global $image_id;

	if (!$postimage = $postImages -> getImage($image_id))
	{
		require( "../cabin/404.php" );
	}

	$image_path = $postimage -> getImage_Filename();
	if ($image_path != '')
	{

		$data = array('ID' => $image_id);

		$hapus_postImage = new Postimg($data);
		$hapus_postImage -> deleteImage();

		unlink("../content/uploads/images/$image_path");
		unlink("../content/uploads/images/thumbs/$image_path");

		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=postimage&status=imageDeleted">';

		exit();
	}

}