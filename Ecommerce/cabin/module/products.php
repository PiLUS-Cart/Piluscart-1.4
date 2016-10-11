<?php if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!");
/**
 * File products.php
 * mengelola business logic
 * pada fungsionalitas objek product
 * dan product category
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
$prodcat_id = isset($_GET['catId']) ? abs((int)$_GET['catId']) : 0;
$product_id = isset($_GET['productId']) ? abs((int)$_GET['productId']) : 0;
$products = new Product();
$prodcats = new Prodcat();
$accessLevel = Admin::accessLevel();

if ( $accessLevel != 'superadmin' && $accessLevel != 'admin') {
	include_once('../cabin/404.php');
} else {
	
	switch ($action) {
	// tampil kategori produk
	default:
		listProduct_Categories();
		break;
	// tambah kategori produk
	case 'newProdCat':
		addProduct_Category();
		break;
	// update kategori produk
	case 'editProdCat':
		$cleaned = $sanitasi -> sanitasi($prodcat_id, 'sql');
		$current_prodcat = $prodcats -> findById($cleaned);
		$current_prodcatId = $current_prodcat['ID'];
		
		if ( isset($prodcat_id) && $current_prodcatId != $prodcat_id) { 
			require('../cabin/404.php');
		} else {
				updateProduct_Category();
		} 
		break; 
	// hapus kategori produk
	case 'deleteProdCat':
		deleteProduct_Category();
		break;
	// tambah produk
	case 'newProduct':
		addProduct();
		break;
	// update produk
	case 'editProduct':
		$sanitized = $sanitasi -> sanitasi($product_id, 'sql');
		$current_product = $products -> findById($sanitized);
		$current_product_id = $current_product['ID'];
		
		if ( isset($product_id) && $current_product_id != $product_id ) {
			require('../cabin/404.php');
		} else {
			updateProduct();
		}
		break;
	// hapus produk
	case 'deleteProduct':
		deleteProduct();
		break;
	// tampil produk
	case 'listProducts':
		$cleanup = $sanitasi -> sanitasi($prodcat_id, 'sql');
		$current_prodcat = $prodcats -> findById($cleanup);
		$current_prodcatId = $current_prodcat['ID'];
		if ( isset($prodcat_id) && $current_prodcatId != $prodcat_id ) {
				require('../cabin/404.php');
		} else {
			listProducts();
		}
		break;
	
	}
	
}

// tampilkan semua kategori produk
function listProduct_Categories()
{
	
global $countProducts;

$views = array();

$p = new Pagination;
$limit = 10;
$position = $p -> getPosition($limit);
$data_prodcat = Prodcat::getProduct_Categories($position, $limit);

$views['prodcats'] = $data_prodcat['results'];
$views['totalRows'] = $data_prodcat['totalRows'];
$views['position'] = $position;
$views['pageTitle'] = "Kategori Produk";
$views['countProducts'] = $countProducts;

//pagination
$totalPage = $p -> totalPage($views['totalRows'], $limit);
$pageLink = $p -> navPage($_GET['order'], $totalPage);
$views['pageLink'] = $pageLink;

if (isset($_GET['error'])) {
	if ($_GET['error'] == "prodcatNotFound") $views['errorMessage'] = "Error: Kategori Produk tidak ditemukan";
}

if ( isset($_GET['status'])) {
	if ( $_GET['status'] == "prodcatAdded") $views['statusMessage'] =  "Kategori Produk sudah disimpan";
	if ( $_GET['status'] == "prodcatUpdated") $views['statusMessage'] = "Kategori Produk sudah diupdate";
	if ( $_GET['status'] == "prodcatDeleted") $views['statusMessage'] = "Kategori Produk sudah dihapus";
}

require('product/list-prodcats.php');

}

// tambah kategori produk
function addProduct_Category()
{

global $prodcats;

$views = array();
$views['pageTitle'] = "Tambah Kategori Produk";
$views['formAction'] = "newProdCat";

if (isset($_POST['saveProdcat']) && $_POST['saveProdcat'] == 'Simpan') { 

	$fileLocation = isset($_FILES['image']['tmp_name']) ? $_FILES['image']['tmp_name'] : '';
	$fileType = isset($_FILES['image']['type']) ? $_FILES['image']['type'] : '';
    $fileName = isset($_FILES['image']['name']) ? $_FILES['image']['name'] : '';
    $fileSize = isset($_FILES['image']['size']) ? $_FILES['image']['size'] : '';
	$newFileName = renameFileImage($fileName);
	$uniqueFilename = rand(000000, 999999) . $newFileName;
	
	$MAX_FILE_SIZE = 50000; // 50kb
	
	if (strlen($fileName) < 1) { 
		$views['errorMessage'] = "maaf, anda belum memilih gambar yang akan diupload";
		require('product/edit-prodcat.php');
	} 
	// cek format gambar
	$formatGambar = array("image/jpeg", "image/jpg", "image/gif", "image/png");
	if (!in_array($fileType, $formatGambar)) { 
		$views['errorMessage'] = "Tipe File yang anda upload salah"; 
		require('product/edit-prodcat.php');
	}
	// cek ukuran gambar	
	if ( $fileSize > $MAX_FILE_SIZE) { 
		$views['errorMessage'] = "Ukuran file yang diupload terlalu besar";
		require('product/edit-prodcat.php');
	} 
	// cek pengisian kolom
	if (empty($_POST['cat_name']) || empty($_POST['description']) || empty($_POST['active'])) {
		
		$views['errorMessage'] = "Kolom yang bertanda asterik(*) harus diisi";
		require('product/edit-prodcat.php');
		
	}
	// jika tidak ditemukan error maka upload gambar dan proses ke database
	if(empty($views['errorMessage']) == 'true') {
			
			uploadProdcat($uniqueFilename);
			
			$data = array(
			
					'product_cat' => isset($_POST['cat_name']) ? preg_replace('/[^ \'\,\.\-a-zA-Z0-9]/', '', $_POST['cat_name']) : '',
					'description' => isset($_POST['description']) ? preventInject($_POST['description']) : '',
					'actived' => isset($_POST['active']) ? preg_replace('/[^YN]/', '', $_POST['active']) : '',
					'cat_image' => $uniqueFilename,
					'slug' => isset($_POST['cat_name']) ? makeSlug($_POST['cat_name']) : ''
			);
				
			$add_prodcat = new Prodcat($data);
			$add_prodcat -> createProdcat();
				
			echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=products&status=prodcatAdded">';
				
			exit();
			
		}
		
	} else {
		
		$views['Prodcat'] = $prodcats;
			
		require('product/edit-prodcat.php');
		
	}
	
}

// edit kategori produk
function updateProduct_Category()
{
	global $prodcats;

	global $prodcat_id;

	$views = array();

	$views['pageTitle'] = "Edit Kategori Produk";
	$views['formAction'] = "editProdCat";

	if (isset($_POST['saveProdcat']) && $_POST['saveProdcat'] == 'Simpan') {
		
		$fileLocation = isset($_FILES['image']['tmp_name']) ? $_FILES['image']['tmp_name'] : '';
		$fileType = isset($_FILES['image']['type']) ? $_FILES['image']['type'] : '';
		$fileName = isset($_FILES['image']['name']) ? $_FILES['image']['name'] : '';
		$fileSize = isset($_FILES['image']['size']) ? $_FILES['image']['size'] : '';
		$newFileName = renameFileImage($fileName);
		$uniqueFilename = rand(000000, 999999) . $newFileName;
		
		$MAX_FILE_SIZE = 50000; // 50kb
		
		
		if (empty($fileLocation)) {
			$data = array(
					'ID' => isset($_POST['prodcat_id']) ? abs((int)$_POST['prodcat_id']) : '',
					'product_cat' => isset($_POST['cat_name']) ? filter_input(INPUT_POST, 'cat_name', FILTER_SANITIZE_STRING) : '',
					'description' => isset($_POST['description']) ? filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '',
					'actived' => isset($_POST['active']) ? preg_replace( '/[^YN]/', '', $_POST['active'] ) : '',
					'slug' => makeSlug($_POST['cat_name'])
			);

			$edit_prodcat = new Prodcat($data);
			$edit_prodcat -> updateProdcat();

			echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=products&status=prodcatUpdated">';

			exit();
			
		} else {
			
			// cek format gambar
			$formatGambar = array("image/jpeg", "image/jpg", "image/gif", "image/png");
			if (!in_array($fileType, $formatGambar)) {
				$views['errorMessage'] = "Tipe File yang anda upload salah";
				require('product/edit-prodcat.php');
			}
			// cek ukuran gambar
			if ( $fileSize > $MAX_FILE_SIZE) {
				$views['errorMessage'] = "Ukuran file yang diupload terlalu besar";
				require('product/edit-prodcat.php');
				
			} else {
				
				uploadProdcat($uniqueFilename);
					
				$data = array(
						'ID' => isset($_POST['prodcat_id']) ? abs((int)$_POST['prodcat_id']) : '',
						'product_cat' => isset($_POST['cat_name']) ? filter_input(INPUT_POST, 'cat_name', FILTER_SANITIZE_STRING) : '',
						'description' => isset($_POST['description']) ? filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '',
						'actived' => isset($_POST['active']) ? preg_replace( '/[^YN]/', '', $_POST['active'] ) : '',
						'cat_image' => $uniqueFilename,
						'slug' => makeSlug($_POST['cat_name'])
				);
					
				$edit_prodcat = new Prodcat($data);
				$edit_prodcat -> updateProdcat();
					
				echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=products&status=prodcatUpdated">';
					
				exit();

			}

		} 
		
	} else {
		
		$views['Prodcat'] = $prodcats -> getProduct_category($prodcat_id);
		$views['activedProdcat'] = $views['Prodcat'] -> getProdcat_Status();
		$views['ProdcatImg'] = $views['Prodcat'] -> getProdcat_Image();
			
		require('product/edit-prodcat.php');
		
	}

}

// hapus kategori produk
function deleteProduct_Category()
{
	global $prodcats;

	global $prodcat_id;


	if (!$prodcat = $prodcats -> getProduct_category($prodcat_id)) {
		require('../cabin/404.php');
	}

	$prodcat_image = $prodcat -> getProdcat_Image();
	if ($prodcat_image != '') {
		$data = array('ID' => $prodcat_id);
			
		$hapus_prodcat = new Prodcat($data);
		$hapus_prodcat -> deleteProdcat();
			
		unlink("../content/uploads/products/$prodcat_image");
		unlink("../content/uploads/products/thumbs/thumb_$prodcat_image");
			
		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=products&status=prodcatDeleted">';

		exit();

	}

}


// tampilkan semua produk
function listProducts() 
{
    global $prodcats, $prodcat_id;

	$views = array();

	$p = new ProductPaging;
	$limit = 10;
	$position = $p -> getPosition($limit);

	$data_products = Product::getProducts($prodcat_id, $position, $limit);


	$views['products']  = $data_products['results'];
	$views['totalRows'] = $data_products['totalRows'];
	$views['position']  = $position;
	$views['cat_id'] = $prodcat_id;


	// pagination
	$totalPage = $p -> totalPage($views['totalRows'], $limit);
	$pageLink = $p -> navPage($_GET['order'], $views['cat_id'], $totalPage);
	$views['pageLink'] = $pageLink;


	if (isset($_GET['error'])) {
		if ($_GET['error'] == "productNotFound") $views['errorMessage'] = "Error: Produk tidak ditemukan";

	}

	if ( isset($_GET['status'])) {

		if ( $_GET['status'] == "productAdded") $views['statusMessage']   = "Produk baru sudah disimpan";
		if ( $_GET['status'] == "productUpdated") $views['statusMessage'] = "Produk sudah diupdate";
		if ( $_GET['status'] == "productDeleted") $views['statusMessage'] = "Produk sudah dihapus";
	}

	if ( isset($prodcat_id)) {
		$getProdcat = $prodcats -> getProduct_category($prodcat_id);
		$views['pageTitle'] = $getProdcat -> getProdcat_Name();
	}
	require('product/list-products.php');
}


// tambah produk
function addProduct()
{

	global $prodcats, $products, $prodcat_id;

	$views = array();

	$views['pageTitle']  = "Upload Produk";
	$views['formAction'] = "newProduct";


	if (isset($_POST['saveProduct']) && $_POST['saveProduct'] == 'Simpan') {
		$fileLocation = isset($_FILES['image']['tmp_name']) ? $_FILES['image']['tmp_name'] : '';
		$fileType = isset($_FILES['image']['type']) ? $_FILES['image']['type'] : '';
		$fileName = isset($_FILES['image']['name']) ? $_FILES['image']['name'] : '';
		$fileSize = isset($_FILES['image']['size']) ? $_FILES['image']['size'] : '';
		$newFileName = renameFileImage($fileName);
		$uniqueFilename = rand(1,99).$newFileName;

		$product_slug = makeSlug($_POST['product_name']);
		
		$MAX_FILE_SIZE = 50000; // 50kb
		
		if (strlen($fileName) < 1) {
			$views['errorMessage'] = "maaf, anda belum memilih gambar yang akan diupload";
			require('product/edit-prodcat.php');
		}
		// cek format gambar
		$formatGambar = array("image/jpeg", "image/jpg", "image/gif", "image/png");
		if (!in_array($fileType, $formatGambar)) {
			$views['errorMessage'] = "Tipe File yang anda upload salah";
			require('product/edit-prodcat.php');
		}
		// cek ukuran gambar
		if ( $fileSize > $MAX_FILE_SIZE) {
			$views['errorMessage'] = "Ukuran file yang diupload terlalu besar";
			require('product/edit-prodcat.php');
		}
		// cek pengisian kolom
		if (empty($_POST['product_name']) || empty($_POST['price']) || empty($_POST['description'])) {
			$views['errorMessage'] = "Kolom yang bertanda asterik(*) harus diisi";
			require('product/edit-product.php');
		}
		// jika tidak ditemukan error
		if (empty($views['errorMessage']) == 'true') {
			
			uploadProductImage($uniqueFilename);
			
			$data = array(
			
					'product_catId' => isset($_POST['cat_id']) ? abs((int)$_POST['cat_id']) : '',
					'product_name' => isset($_POST['product_name']) ? preg_replace('/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/', '', $_POST['product_name']) : '',
					'slug' => $product_slug,
					'description' => isset($_POST['description']) ? preventInject($_POST['description']) : '',
					'price' => filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_INT),
					'stock' => filter_input(INPUT_POST, 'stock', FILTER_SANITIZE_NUMBER_INT),
					'weight' => isset($_POST['weight']) ? trim(strip_tags($_POST['weight'])) : '',
					'date_submited' => date('Y-m-d'),
					'discount' => filter_input(INPUT_POST, 'discount', FILTER_SANITIZE_NUMBER_INT),
					'image' => $uniqueFilename
			);
			
			$add_product = new Product($data);
			$add_product -> createProduct();
			
			header("Location: index.php?module=products&action=listProducts&catId=$_POST[cat_id]&status=productAdded");
				
		}

	} else {
		
		$views['cat_Dropdown'] = $prodcats -> getProdcat_Dropdown($prodcat_id);
		$views['Product'] = $products;
		require('product/edit-product.php');
	}

}

// edit produk
function updateProduct()
{
	global $prodcats, $products, $product_id, $prodcat_id;

	$views = array();

	$views['pageTitle'] = "Edit Produk";
	$views['formAction'] = "editProduct";


	if (isset($_POST['saveProduct']) && $_POST['saveProduct'] == 'Simpan') {

		$fileLocation = isset($_FILES['image']['tmp_name']) ? $_FILES['image']['tmp_name'] : '';
		$fileType = isset($_FILES['image']['type']) ? $_FILES['image']['type'] : '';
		$fileName = isset($_FILES['image']['name']) ? $_FILES['image']['name'] : '';
		$fileSize = isset($_FILES['image']['size']) ? $_FILES['image']['size'] : '';
		$newFileName = renameFileImage($fileName);
		$uniqueFilename = rand(1,99).$newFileName;
		
		$MAX_FILE_SIZE = 50000; // 50kb
		
		$product_slug = makeSlug($_POST['product_name']);

		if (empty($fileLocation)) {
			
			$data = array(
						
					'ID' => isset($_POST['product_id']) ? abs((int)$_POST['product_id']) : '',
					'product_catId' => isset($_POST['cat_id']) ? abs((int)$_POST['cat_id']) : '',
					'product_name' => isset($_POST['product_name']) ? preg_replace('/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/', '', $_POST['product_name']) : '',
					'slug' => $product_slug,
					'description' => isset($_POST['description']) ? preventInject($_POST['description']) : '',
					'price' => filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_INT),
					'stock' => filter_input(INPUT_POST, 'stock', FILTER_SANITIZE_NUMBER_INT),
					'weight' => isset($_POST['weight']) ? trim(strip_tags($_POST['weight'])) : '',
					'date_submited' => date('Y-m-d'),
					'discount' => filter_input(INPUT_POST, 'discount', FILTER_SANITIZE_NUMBER_INT),

			);
				
			$edit_product = new Product($data);
			$edit_product -> updateProduct();
				
			header("Location: index.php?module=products&action=listProducts&catId=$_POST[cat_id]&status=productAdded");

		} else {
				
			// cek format gambar
			$formatGambar = array("image/jpeg", "image/jpg", "image/gif", "image/png");
			if (!in_array($fileType, $formatGambar)) {
				$views['errorMessage'] = "Tipe File yang anda upload salah";
				require('product/edit-product.php');
			}
			// cek ukuran gambar
			if ( $fileSize > $MAX_FILE_SIZE) {
				$views['errorMessage'] = "Ukuran file yang diupload terlalu besar";
				require('product/edit-product.php');
			
			} else {

				uploadProductImage($uniqueFilename);

				$data = array(

						'ID' => isset($_POST['product_id']) ? abs((int)$_POST['product_id']) : '',
						'product_catId' => isset($_POST['cat_id']) ? abs((int)$_POST['cat_id']) : '',
						'product_name' => isset($_POST['product_name']) ? preg_replace('/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/', '', $_POST['product_name']) : '',
						'slug' => $product_slug,
						'description' => isset($_POST['description']) ? preventInject($_POST['description']) : '',
						'price' => filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_INT),
						'stock' => filter_input(INPUT_POST, 'stock', FILTER_SANITIZE_NUMBER_INT),
						'weight' => isset($_POST['weight']) ? trim(strip_tags($_POST['weight'])) : '',
						'date_submited' => date('Y-m-d'),
						'discount' => filter_input(INPUT_POST, 'discount', FILTER_SANITIZE_NUMBER_INT),
						'image' => $uniqueFilename);

				$edit_product = new Product($data);
				$edit_product -> updateProduct();
					
				header("Location: index.php?module=products&action=listProducts&catId=$_POST[cat_id]&status=productAdded");

			}
			
		}
		
	} else {
		
		$views['Product'] = $products -> getProduct($product_id);
		$views['cat_Dropdown'] = $prodcats -> getProdcat_Dropdown($prodcat_id);
		$views['productImg'] = $views['Product'] -> getProduct_Image();
		require('product/edit-product.php');
		
	}
	
}

// hapus produk
function deleteProduct()
{

	global $products;

	global $product_id;


	if (!$product = $products -> getProduct($product_id)) {

		require('../cabin/404.php');
		
	}

	$cat_id = (int) $product -> getProduct_CatId();
	$product_image = $product -> getProduct_Image();
	if ($product_image != '') {
		
		$data = array('ID' => $product_id);
		$hapus_product = new Product($data);
		$hapus_product -> deleteProduct();

		unlink("../content/uploads/products/$product_image");
		unlink("../content/uploads/products/thumbs/thumb$product_image");

		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=products&action=listProducts&catId='.$cat_id.'&status=productDeleted">';
			
		exit();
			
	}
	
}