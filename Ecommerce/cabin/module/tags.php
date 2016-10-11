<?php if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!");
/**
 * File Modul tags.php
 * mengelola business logic
 * pada fungsionalitas objek tag
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
$tagId = isset($_GET['tagId']) ? (int)$_GET['tagId'] : 0;
$accessLevel = Admin::accessLevel();
$tags = new Tag();

if ( $accessLevel != 'superadmin' && $accessLevel != 'admin' && $accessLevel != 'editor' )
{
	include_once( "../cabin/404.php" );
}
else 
{
	
	switch ($action) {
	
		case 'newTag':
			 
			addTag();
	
			break;
	
		case 'editTag':
	
			$cleaned = $sanitasi -> sanitasi($tagId, 'sql');
			$current_tag = $tags -> findById($cleaned);
			$current_id = $current_tag['tag_id'];
	
			if (isset($tagId) && $current_id != $tagId )
			{
				require( "../cabin/404.php" );
					
			}
			else
			{
				updateTag();
			}
	
			break;
	
		case 'deleteTag':
	
			deleteTag();
	
			break;
	
		default:
	
			listTags();
	
			break;
			
	}
	
}

// fungsi tambah label/tag
function addTag() {
	
	global $tags;
	
	$views = array();
	$views['pageTitle'] = "Tambah Label";
	$views['formAction'] = "newTag";
	
	if (isset($_POST['saveTag']) && $_POST['saveTag'] == 'Simpan')
	{
		if ( empty($_POST['tag_name']))
		{
			$views['errorMessage'] = "Kolom yang bertanda asterik(*) harus diisi";
			require( "tag/edit-tag.php" );
		}
		
		if (strlen($_POST['tag_name']) > 100)
		{
			$views['errorMessage'] = "Label harus kurang dari atau sama dengan 100 karakter";
			require( "tag/edit-tag.php" );
		}
		
		if ( !isset($views['errorMessage']))
		{
			 $tag_slug = makeSlug($_POST['tag_name']);
			 
			 $data = array(
			 		
			 		'tag' => isset($_POST['tag_name']) ? preg_replace('/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/', '', $_POST['tag_name']) : '',
			 		'slug' => $tag_slug
			 );
			 
			 $new_tag = new Tag($data);
			 $new_tag -> createTag();
			 
			 echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=tags&status=tagAdded">';
			 
			 exit();
			 
		}
	}
	else 
	{
		$views['tag'] = $tags;
		require( "tag/edit-tag.php" );
	}
	
}

// fungsi edit label/tag
function updateTag() {
	
	global $tags, $tagId;
	
	$views = array();
	$views['pageTitle'] = "Edit Label";
	$views['formAction'] = "editTag";
	
	if (isset($_POST['saveTag']) && $_POST['saveTag'] == 'Simpan')
	{
		if ( empty($_POST['tag_name']))
		{
			$views['errorMessage'] = "Kolom yang bertanda asterik(*) harus diisi";
			require( "tag/edit-tag.php" );
		}
		
		if (strlen($_POST['tag_name']) > 100)
		{
			$views['errorMessage'] = "Label harus kurang dari atau sama dengan 100 karakter";
			require( "tag/edit-tag.php" );
		}
			
		if ( !isset($views['errorMessage']))
		{
			$tag_slug = makeSlug($_POST['tag_name']);
			
			$data = array(
					
					'tag_id' => isset($_POST['tag_id']) ? abs((int)$_POST['tag_id']) : '',
					'tag' => isset($_POST['tag_name']) ? preg_replace('/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/', '', $_POST['tag_name']) : '',
					'slug' => $tag_slug
			);
			
			$edit_tag = new Tag($data);
			$edit_tag -> updateTag();
			
			echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=tags&status=tagUpdated">';
			
			exit();
			
		}
	}
	else 
	{
		$views['tag'] = $tags -> getTag($tagId);
		require( "tag/edit-tag.php" );
	}
	
}

// fungsi hapus label/tag
function deleteTag() {
	
	global  $tags, $tagId;
	
	if ( !$tag = $tags -> getTag($tagId))
	{
		require( "../cabin/404.php" );
	}
	
	$data = array( 'tag_id' => $tagId );
	$del_tag = new Tag($data);
	$del_tag -> deleteTag();
	
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=tags&status=tagDeleted">';
		
	exit();
	
}

// fungsi tampilkan semua label/tag
function listTags() {
	
	$views = array();
	
	$p = new Pagination();
	$limit = 10;
	$position = $p -> getPosition($limit);
	
	$data_tags = Tag::getTags($position, $limit);
	
	$views['tags'] = $data_tags['results'];
	$views['totalRows'] = $data_tags['totalRows'];
	$views['position'] = $position;
	$views['pageTitle'] = "Semua Label";
	
	//pagination
	$totalPage = $p -> totalPage($views['totalRows'], $limit);
	$pageLink = $p -> navPage($_GET['order'], $totalPage);
	$views['pageLink'] = $pageLink;
	
	if (isset($_GET['error']))
	{
		if ($_GET['error'] == "tagNotFound") $views['errorMessage'] = "Error: Label tidak ditemukan";
	}
	
	if ( isset($_GET['status']))
	{
	
		if ( $_GET['status'] == "tagAdded")   $views['statusMessage'] = "Label sudah disimpan";
		if ( $_GET['status'] == "tagUpdated") $views['statusMessage'] = "Label sudah diupdate";
		if ( $_GET['status'] == "tagDeleted") $views['statusMessage'] = "Label sudah dihapus";
	}
	
	require( "tag/list-tags.php" );
	
}