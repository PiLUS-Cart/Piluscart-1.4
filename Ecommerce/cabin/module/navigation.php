<?php if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!");
/**
 * File Modul navigation.php
 * mengelola business logic
 * pada fungsionalitas objek menu
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
$menuId = isset($_GET['menuId']) ? abs((int)$_GET['menuId']) : 0;
$child_id = isset($_GET['child_id']) ? abs((int)$_GET['child_id']) : 0;
$menus = new Menu();
$menuChilds = new Menuchild();
$accessLevel = Admin::accessLevel();

if ( $accessLevel != 'superadmin' )
{
	include_once( "../cabin/404.php" );
}
else 
{

	switch ($action)
	{
		//tampilkan navigasi
		default:
	
			listMenus();
	
			break;
	
			//tambah Menu Utama
		case 'newMenu':
	
			addMenu();
	
			break;
	
			//update Menu Utama
		case 'editMenu':
	
			$cleaned = $sanitasi -> sanitasi($menuId, 'sql');
			$current_menu = $menus -> findById($cleaned);
			$current_id = $current_menu['menu_id'];
	
			if ( isset($menuId) && $current_id != $menuId )
			{
				require( "../cabin/404.php" );
			}
			else
			{
				updateMenu();
			}
	
			break;
	
			//hapus Menu Utama
		case 'deleteMenu':
	
			deleteMenu();
	
			break;
	
			//Tampilkan Sub Menu
		case 'listMenuChilds':
	
			listMenuChilds();
	
			break;
	
			//Tambah Sub Menu
		case 'newMenuChild':
	
			addMenuChild();
	
			break;
	
			//Update Sub Menu
		case 'editMenuChild':
	
			$cleaned_child = $sanitasi -> sanitasi($child_id, 'sql');
			$current_child = $menuChilds -> findById($cleaned_child);
			$current_child_id = $current_child['menu_child_id'];
	
			if ( isset($child_id) && $current_child_id != $child_id )
			{
				require( "../cabin/404.php" );
			}
			else
			{
				updateMenuChild();
			}
	
			break;
	
		case 'deleteMenuChild':
	
			deleteMenuChild();
	
			break;
	
	}
	
}

//fungsi tampil navigasi utama
function ListMenus()
{

	$views = array();

	$p = new Pagination;
	$limit = 10;
	$position = $p -> getPosition($limit);

	$data_menus = Menu::getMenus($position, $limit);


	$views['Menus'] = $data_menus['results'];
	$views['totalRows'] = $data_menus['totalRows'];
	$views['position'] = $position;
	$views['pageTitle'] = "Menu";

	//pagination
	$totalPage = $p -> totalPage($views['totalRows'], $limit);
	$pageLink = $p -> navPage($_GET['order'], $totalPage);
	$views['pageLink'] = $pageLink;

	if ( isset($_GET['error']))
	{

		if ( $_GET['error'] == "menuNotFound" ) $views['errorMessage'] = "Error: Menu tidak ditemukan";

	}

	if ( isset($_GET['status']))
	{

		if ( $_GET['status'] == "menuAdded") $views['statusMessage'] =  "Menu baru sudah disimpan";
		if ( $_GET['status'] == "menuUpdated") $views['statusMessage'] = "Menu sudah diupdate";
		if ( $_GET['status'] == "menuDeleted") $views['statusMessage'] = "Menu sudah dihapus";
	}

	require( "navigation/list-menus.php" );


}

//fungsi tambah navigasi
function addMenu()
{
	global $menus;

	$views = array();
	$views['pageTitle'] = "Tambah Menu";
	$views['formAction'] = "newMenu";

	if (isset($_POST['saveMenu']) && $_POST['saveMenu'] == 'Simpan')
	{
		$menu_name = preventInject($_POST['menu_label']);
		$menu_link = trim($_POST['menu_link']);
		$menu_role = trim($_POST['menu_role']);

		if (empty($menu_name))
		{
			$views['errorMessage'] = "Kolom yang bertanda asterik(*) harus diisi";
			require( "navigation/edit-menu.php" );
		}
		else
		{
			//cek nama menu
			if ($menus -> menuExists($menu_name) == true)
			{
				$views['errorMessage'] = "Nama menu sudah digunakan";
				require( "navigation/edit-menu.php" );
			}
		}

		if (empty($views['errorMessage']) == true)
		{
			$data = array(
					'menu_label' => $menu_name,
					'menu_link' => $menu_link,
					'menu_role' => $menu_role
			);

			$add_navigation = new Menu($data);
			$add_navigation -> createMenu();

			echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=navigation&status=menuAdded">';

			exit();

		}
	}
	else
	{
		$views['Menu'] = $menus;
		$views['menuRole'] = $views['Menu'] -> setMenu_RoleDropdown();

		require( "navigation/edit-menu.php" );
	}

}

//fungsi update navigasi
function updateMenu()
{
	global $menus;

	global $menuId;

	$views = array();
	$views['pageTitle'] = "Edit Menu";
	$views['formAction'] = "editMenu";


	if (isset($_POST['saveMenu']) && $_POST['saveMenu'] == 'Simpan')
	{
		$menu_name = preventInject($_POST['menu_label']);
		$menu_link = preventInject($_POST['menu_link']);
		$menu_role = trim($_POST['menu_role']);
		$menu_sort = preventInject($_POST['sort']);
		$menu_id = (int)$_POST['menu_id'];
			
		$data = array(
					
				'menu_id' => $menu_id,
				'menu_label' => $menu_name,
				'menu_link' => $menu_link,
				'menu_order' => $menu_sort,
				'menu_role' => $menu_role
		);
			
		$edit_navigation = new Menu($data);
		$edit_navigation -> updateMenu();
			
		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=navigation&status=menuUpdated">';
			
		exit();
	}
	else
	{
		$views['Menu'] = $menus -> getMenu($menuId);
		$views['sortMenu'] = $views['Menu'] -> getMenu_Order();
		$views['menuRole'] = $views['Menu'] -> setMenu_RoleDropdown();

		require( "navigation/edit-menu.php" );
	}
}


//fungsi hapus navigasi
function deleteMenu()
{
	global $menus;

	global $menuId;

	if (!$menu = $menus -> getMenu($menuId))
	{
		require( "../cabin/404.php" );
	}

	$data = array('menu_id' => $menuId);

	$hapus_menu = new Menu($data);
	$hapus_menu -> deleteMenu();

	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=navigation&status=menuDeleted">';

	exit();

}

//fungsi tampilkan sub menu
function listMenuChilds()
{


	$views = array();

	$p = new Pagination;
	$limit = 10;
	$position = $p ->getPosition($limit);

	$data_menuChild = Menuchild::getMenuChilds($position, $limit);

	$views['menuChilds'] = $data_menuChild['results'];
	$views['totalRows']  = $data_menuChild['totalRows'];
	$views['position']   = $position;
	$views['pageTitle']  = "Tambah Sub Menu";

	//pagination
	$totalPage = $p ->totalPage($views['totalRows'], $limit);
	$pageLink = $p -> navPage($_GET['order'], $totalPage);
	$views['pageLink'] = $pageLink;

	if ( isset($_GET['error']))
	{

		if ( $_GET['error'] == "menuChildNotFound" ) $views['errorMessage'] = "Error: Sub Menu tidak ditemukan";

	}

	if ( isset($_GET['status']))
	{

		if ( $_GET['status'] == "menuChildAdded") $views['statusMessage'] =  "Sub Menu baru sudah disimpan";
		if ( $_GET['status'] == "menuChildUpdated") $views['statusMessage'] = "Sub Menu sudah diupdate";
		if ( $_GET['status'] == "menuChildDeleted") $views['statusMessage'] = "Sub Menu sudah dihapus";
	}

	require('navigation/list-menuchilds.php');



}

//fungsi tambah sub menu
function addMenuChild()
{
	global $menus;

	global $menuChilds;

	$views = array();
	$views['pageTitle']  = "Tambah Sub Menu";
	$views['formAction'] = "newMenuChild";

	$subMenuRole = $menuChilds -> setMenuChild_Role();


	if (isset($_POST['saveMenu']) && $_POST['saveMenu'] == 'Simpan')
	{
		$menu_label = preventInject($_POST['menu_label']);
		$tautan = preventInject($_POST['menu_link']);
		$menu_parent = (int)$_POST['menu_parent'];
		$menu_child = (int) $_POST['menu_child'];
		$child_role = preventInject($_POST['child_role']);

		if (empty($menu_label))
		{
			$views['errorMessage'] = "Kolom yang bertanda asterik(*) harus diisi";
			require('navigation/edit-menuchild.php');
		}
		else
		{
			//cek sub menu
			if ($menuChilds -> menuChildExists($menu_label) == true)
			{
				$views['errorMessage'] = "Nama Sub Menu sudah digunakan";
				require( 'navigation/edit-menuchild.php' );
			}
		}

		if ($menu_parent == 0)
		{
			$data_menuChild = $menuChilds -> fetchMenuChild($menu_child);

			$parent = $data_menuChild['menu_parent_id'];

		}
		else
		{
			$parent = $menu_parent;
		}

		$data = array(

				'menu_child_label' => $menu_label,
				'menu_child_link' => $tautan,
				'menu_parent_id' => $parent,
				'menu_grand_child' => $menu_child,
				'menu_child_role' => $child_role
		);

		$add_submenu = new Menuchild($data);
		$add_submenu -> createMenuChild();

		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=navigation&action=listMenuChilds&status=menuChildAdded">';

		exit();
	}
	else
	{
		$views['parentId'] = $parent_id;
		$views['parentDropdown'] = $menus -> setMenu_DropDown();
		$views['menuChild'] = $menuChilds;
		$views['childDropdown'] = $views['menuChild'] -> setChild_Dropdown();
		$views['subMenuRole'] = $subMenuRole;

		require('navigation/edit-menuchild.php' );
		
	}

}

//fungsi update sub menu
function updateMenuChild()
{
	global $menus;

	global $menuChilds;

	global $child_id;

	$views = array();
	$views['pageTitle']  = "Edit Sub Menu";
	$views['formAction'] = "editMenuChild";

	if (isset($_POST['saveMenu']) && $_POST['saveMenu'] == 'Simpan')
	{
		$menu_child_id = abs((int)$_POST['menu_child_id']);
		$menu_label = preventInject($_POST['menu_label']);
		$tautan = preventInject($_POST['menu_link']);
		$menu_parent = (int)$_POST['menu_parent'];
		$menu_child = (int) $_POST['menu_child'];
		$child_role = preventInject($_POST['child_role']);
			
		if ($menu_parent == 0)
		{
			$data_menuChild = $menuChilds -> fetchMenuChild($menu_child);

			$parent = $data_menuChild['menu_parent_id'];

		}
		else
		{
			$parent = $menu_parent;
		}
			
		$data = array(
					
				'menu_child_id' => $menu_child_id,
				'menu_child_label' => $menu_label,
				'menu_child_link' => $tautan,
				'menu_parent_id' => $parent,
				'menu_grand_child' => $menu_child,
				'menu_child_role' => $child_role
		);
			
		$edit_submenu = new Menuchild($data);
		$edit_submenu -> updateMenuChild();
			
		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=navigation&action=listMenuChilds&status=menuChildUpdated">';
			
		exit();
			
	}
	else
	{
		$views['menuChild'] = $menuChilds -> getMenuChild($child_id);
		$views['parentDropdown'] = $menus -> setMenu_DropDown($views['menuChild'] -> getMenu_Parent_Id());
		$views['childDropdown'] = $views['menuChild'] -> setChild_Dropdown();
		$views['subMenuRole'] = $views['menuChild'] -> setMenuChild_Role();
			
		require('navigation/edit-menuchild.php');
	}

}

//fungsi hapus sub menu
function deleteMenuChild()
{
	global $menuChilds;

	global $child_id;

	if (!$menuChild = $menuChilds -> getMenuChild($child_id))
	{
		require('../cabin/404.php');
	}

	$data = array('menu_child_id' => $child_id);

	$hapus_submenu = new Menuchild($data);
	$hapus_submenu -> deleteMenuChild();

	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=navigation&action=listMenuChilds&status=menuChildDeleted">';

	exit();


}