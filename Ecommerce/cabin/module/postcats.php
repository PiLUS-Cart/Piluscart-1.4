<?php if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!");
/**
 * File Modul postcats.php
 * mengelola business logic
 * pada fungsionalitas objek post category
 * @package   PiLUS_CMS
 * @author    Maoelana Noermoehammad
 * @copyright 2014 kartatopia.com
 * @license   GPL version 3.0
 * @version   1.4.0
 * @since     Since Release 1.4
 *
 */

$action = isset($_GET['action']) ? htmlentities(strip_tags($_GET['action'])) : "";
$catId = isset($_GET['catId']) ? abs((int)$_GET['catId']) : 0;
$postCats = new Postcat();
$accessLevel = Admin::accessLevel();

if ( $accessLevel != 'superadmin' && $accessLevel != 'admin' && $accessLevel != 'editor' )
{
	include_once( "../cabin/404.php" );
}
else 
{
	switch ($action) {
	
		// Tambah kategori tulisan
		case 'newPostCat':
	
			addPostCat();
	
			break;
	
			// edit kategori tulisan
		case 'editPostCat':
	
			$cleaned = $sanitasi ->sanitasi($catId, 'sql');
			$current_postcat = $postCats -> findById($cleaned);
			$current_id = $current_postcat['ID'];
	
			if ( isset($catId) && $current_id != $catId )
			{
				require( "../cabin/404.php" );
			}
			else
			{
				updatePostCat();
			}
	
			break;
	
			// hapus tulisan
		case 'deletePostCat':
	
			deletePostCat();
	
			break;
	
			// tampilkan kategori tulisan
		default:
	
			listPostCats();
	
			break;
	}
	
}

// fungsi tambah kategori post
function addPostCat() {

	global $postCats;

	$views = array();
	$views['pageTitle'] = "Tambah Kategori Tulisan";
	$views['formAction'] = "newPostCat";

	if (isset($_POST['saveCat']) && $_POST['saveCat'] == 'Simpan')
	{

		extract($_POST);


		if (empty($catTitle) || empty($postDesc) || empty($active))
		{
			$views['errorMessage'] = "Kolom yang bertanda asterik(*) harus diisi";
			require( "post/edit-postcat.php" );
		}

		if (!isset($views['errorMessage']))
		{
			$postCatSlug = makeSlug($catTitle);

			$data = array(

					'postCat_name' => isset($catTitle) ? preg_replace('/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/', '', $catTitle) : '',
					'slug' => $postCatSlug,
					'description' => $postDesc,
					'actived' => $active

			);

			$new_postcat = new Postcat($data);
			$new_postcat -> createPostcat();

			echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=postcats&status=postcatAdded">';

			exit();
		}
	}
	else
	{
		$views['postcat'] = $postCats;
		require( "post/edit-postcat.php" );
	}
}

// fungsi edit kategori post
function updatePostCat() {

	global $postCats, $catId;

	$views = array();
	$views['pageTitle'] = "Edit Kategori Tulisan";
	$views['formAction']= "editPostCat";

	if (isset($_POST['saveCat']) && $_POST['saveCat'] == 'Simpan')
	{
		extract($_POST);

		if ($catTitle == '')
		{
			$views['errorMessage'] = "Tolong kolom nama kategori diisi";
			require( "post/edit-postcat.php" );
		}

		if ($postDesc == '')
		{
			$views['errorMessage'] = "Tolong kolom deskripsi diisi";
			require( "post/edit-postcat.php" );
		}

		if (!isset($views['errorMessage']))
		{
			$data = array(

					'ID' => isset($_POST['catId']) ? abs((int)$_POST['catId']) : '',
					'postCat_name' => $catTitle,
					'slug' => makeSlug($catTitle),
					'description' => $postDesc,
					'actived' => isset($_POST['active']) ? preg_replace('/[^YN]/', '', $_POST['active'] ) : ''
			);

			$editPostcat = new Postcat($data);
			$editPostcat -> updatePostcat();

			echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=postcats&status=postcatUpdated">';

			exit();

		}
	}
	else
	{
		$views['postcat'] = $postCats -> getPost_Category($catId);
		$views['activedPostcat'] = $views['postcat'] -> getPostcat_Status();

		require( "post/edit-postcat.php" );
	}

}

// fungsi hapus kategori post
function deletePostCat() {

	global $postCats, $catId;

	if (!$postcat = $postCats -> getPost_Category($catId))
	{
		require( "../cabin/404.php" );
	}

	$data = array('ID' => $catId);
	$del_postcat = new Postcat($data);
	$del_postcat -> deletePostcat();

	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=postcats&status=postcatDeleted">';

	exit();
	
}

// fungsi menampilkan semua kategori post
function listPostCats() {

	$views = array();

	$p = new Pagination();
	$limit = 10;
	$position = $p -> getPosition($limit);

	$data_postcat = Postcat::getPost_Categories($position, $limit);

	$views['postcats'] = $data_postcat['results'];
	$views['totalRows']= $data_postcat['totalRows'];
	$views['position'] = $position;
	$views['pageTitle']= "Kategori Tulisan";

	// pagination
	$totalPage = $p -> totalPage($views['totalRows'], $limit);
	$pageLink = $p -> navPage($_GET['order'], $totalPage);
	$views['pageLink'] = $pageLink;

	if (isset($_GET['error']))
	{
		if ($_GET['error'] == "postcatNotFound") $views['errorMessage'] = "Error: Kategori tulisan tidak ditemukan";
	}

	if ( isset($_GET['status']))
	{

		if ( $_GET['status'] == "postcatAdded")   $views['statusMessage'] = "Kategori tulisan sudah disimpan";
		if ( $_GET['status'] == "postcatUpdated") $views['statusMessage'] = "Kategori tulisan sudah diupdate";
		if ( $_GET['status'] == "postcatDeleted") $views['statusMessage'] = "Kategori tulisan sudah dihapus";
	}

	require( "post/list-postcats.php" );

}