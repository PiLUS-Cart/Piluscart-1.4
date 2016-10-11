<?php if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!");
/**
 * File Modul customers.php
 * mengelola business logic
 * pada fungsionalitas objek kustomer
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
$customerId = isset($_GET['customerId']) ? abs((int)$_GET['customerId']) : 0;
$sessionId = isset($_GET['sessionId']) ? htmlentities(strip_tags($_GET['sessionId'])) : "";
$customers = new Customer();
$district = new District();
$shipping = new Shipping();
$accessLevel = Admin::accessLevel();

if ( $accessLevel != 'superadmin' ) 
{
	include_once('../cabin/404.php' );
}
else 
{
	switch ($action) {
	
		case 'newCustomer' :
	
			newCustomer();
	
			break;
	
		case 'editCustomer' :
	
			$cleaned = $sanitasi -> sanitasi($customerId, 'sql');
			$current_customer = $customers -> findById($cleaned, $sessionId);
			$current_id = $current_customer -> ID;
			$current_session = $current_customer -> customer_session;
	
			if (isset($customerId) && $current_id != $customerId)
			{
				require('../cabin/404.php');
			}
			else
			{
				editCustomer();
			}
	
			break;
	
		case 'deleteCustomer' :
	
			deleteCustomer();
	
			break;
	
		default:
				
			listCustomers();
	
			break;
	}	
}

// fungsi tampilkan kustomer
function listCustomers() {
	
	$views = array();
	
	$p = new Pagination();
	$limit = 10;
	$position = $p -> getPosition($limit);
	
	$data_kustomer = Customer::getListCustomers($position, $limit);
	
	$views['customers'] = $data_kustomer['results'];
	$views['totalRows'] = $data_kustomer['totalRows'];
	$views['position'] = $position;
	$views['pageTitle'] = "Semua Kustomer";
	
	//pagination
	$totalPage = $p -> totalPage($views['totalRows'], $limit);
	$pageLink = $p -> navPage($_GET['order'], $totalPage);
	$views['pageLink'] = $pageLink;
	
	if ( isset($_GET['error'])) {
	
		if ( $_GET['error'] == "customerNotFound" ) $views['errorMessage'] = "Error: Kustomer tidak ditemukan";
	
	}
	
	if ( isset($_GET['status'])) {
		
		if ( $_GET['status'] == "customerAdded") $views['statusMessage'] = "Kustomer baru sudah ditambahkan";
		if ( $_GET['status'] == "customerUpdated") $views['statusMessage'] = "Kustomer sudah diupdate";
		if ( $_GET['status'] == "customerDeleted") $views['statusMessage'] = "Kustomer telah dihapus";
		
	}
	
	require('customer/list-customers.php');
	
}

// fungsi tambah kustomer
function newCustomer() {
	
	global $customers, $option, $district, $shipping;
	
	$views = array();
	$views['pageTitle'] = "Tambah Kustomer";
	$views['formAction'] = "newCustomer";
	
	if (isset($_POST['saveCustomer']) && $_POST['saveCustomer'] == 'Simpan')
	{
		$customer_fullname = preventInject($_POST['fullname']);
		$customer_email = preventInject($_POST['email']);
		$customer_address = preventInject($_POST['address']);
		$customer_phone = isset($_POST['phone']) ? str_replace(array(' ', '-', '(', ')'), '', $_POST['phone']) : "";
		$customer_district = (int)$_POST['kab_id'];
		$customer_shipping = (int)$_POST['shipping'];
		$customer_type = preventInject($_POST['customer_type']);
		$password = isset($_POST['password']) ? $_POST['password'] : '';
		$confirmed = isset($_POST['confirmed']) ? $_POST['confirmed'] : '';
		
		if (empty($customer_fullname) || empty($customer_email) || empty($customer_address)
			|| empty($customer_phone) || empty($customer_district) || empty($customer_shipping) || empty($customer_type))
		{
			$views['errorMessage'] = "Semua Kolom harus diisi!";
			require('customer/edit-customer.php');
		}
		else 
		{
			if (!preg_match('/^[0-9]{10,13}$/', $customer_phone))
			{
				$views['errorMessage'] = "Nomor Telepon tidak valid!";
				require('customer/edit-customer.php');
			}
			
			// cek email
			if ( $customers -> emailExists($customer_email) == true)
			{
				
				$views['errorMessage'] = "Email sudah dipakai, Silahkan gunakan alamat e-mail yang lain!";
				require('customer/edit-customer.php');
				
			}
			elseif (strlen($customer_email)) {
				
				if (is_valid_email_address(trim($customer_email)) == 0) {
					$views['errorMessage'] = "Penulisan E-mail tidak valid!";
					require('customer/edit-customer.php');
				}
				
			}
			// cek password
			elseif (!isset($password) || !isset($confirmed) || !$password || !$confirmed || $password != $confirmed)
			{
				$views['errorMessage'] = "Password yang anda ketik tidak sama";
				require('customer/edit-customer.php');
			}
			elseif ( strlen($password) < 6)
			{
				$views['errorMessage'] = "Password tidak boleh kurang dari 6 karakter";
				require('customer/edit-customer.php');
			}
			elseif ( strlen($confirmed) < 6)
			{
				$views['errorMessage'] = "Password tidak boleh kurang dari 6 karakter";
				require('customer/edit-customer.php');
			}
			
		}
		
		if ( empty($views['errorMessage']) === true)
		{
			
			$data = array(
			
					'fullname' => $customer_fullname,
					'email' => $customer_email,
					'password' =>  $password,
					'address' => $customer_address,
					'phone' => $customer_phone,
					'district_id' => $customer_district,
					'shipping_id' => $customer_shipping,
					'customer_type' => $customer_type, 
					'customer_session' => $password, 
					'date_registered' => date("Y-m-d"),
					'time_registered' => date("H:i:s")
					
			);
			
			$tambah_kustomer = new Customer($data);
			$tambah_kustomer -> addCustomer();
			
			// Mengambil data pemilik toko
			$metaowner = '';
				
			$data_owner = $option -> getOptions();
				
			$metaowner = $data_owner['results'];
				
			foreach ( $metaowner as $owner )
			{
				
				$namaToko = $owner -> getSite_Name();
			
			}
				
			$kepada = safeEmail($customer_email);
			$subyek = "Join for Premium Customer As Member - Free of charge!";
			$pesan = "<html>
			<body>
			Jika anda tidak pernah meminta menjadi Member di $namaToko, silahkan untuk menghiraukan email ini.<br />
			Tetapi jika anda memang yang meminta pesan informasi ini, berikut data profil anda: <br><br>
			<b>Nama Lengkap:</b> $customer_fullname<br />
			<b>Email:</b> $customer_email <br />
			<b>password:</b> $password <br />
			<b>Alamat:</b> $customer_address<br />
			<b>Telepon:</b> $customer_phone<br />
			<b>Tipe Kustomer:</b> $customer_type<br />
			Anda dapat masuk ke halaman member dengan mengklik tautan (link) di bawah ini :<br /><br />
			<a href=".PL_DIR."member-login>Log In Member</a><br /><br />
			Terima kasih,<br />
			<b>Tim Pengembang Pilus Open Source E-commerce Software</b>
			</body>
			</html>";
			
			$kirim_pesan = new Mailer();
			
			$kirim_pesan -> setSendText(false);
			$kirim_pesan -> setSendTo($kepada);
			$kirim_pesan -> setFrom($namaToko);
			$kirim_pesan -> setSubject($subyek);
			$kirim_pesan -> setHTMLBody($pesan);
			
			$kirim_pesan -> send();
			
			echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=customers&status=customerAdded">';
			
			exit();
			
		}
	}
	else 
	{
		
		$views['Customer'] = $customers;
		$views['district'] = $district -> setDistrict_Dropdown();
		$views['shipping'] = $shipping -> setShippingDropDown();
		
		require('customer/edit-customer.php');
		
	}
}

// fungsi edit kustomer
function editCustomer() {
	
	global $customers, $district, $shipping, $customerId, $sessionId;
	
	$views = array();
	$views['pageTitle'] = "Edit Kustomer";
	$views['formAction'] = "editCustomer";
	
	if (isset($_POST['saveCustomer']) && $_POST['saveCustomer'] == 'Simpan')
	{
		
		$customer_id = (int)$_POST['customer_id'];
		$customer_fullname = preventInject($_POST['fullname']);
		$customer_email = preventInject($_POST['email']);
		$customer_address = preventInject($_POST['address']);
		$customer_phone = isset($_POST['phone']) ? str_replace(array(' ', '-', '(', ')'), '', $_POST['phone']) : "";
		$customer_district = (int)$_POST['kab_id'];
		$customer_shipping = (int)$_POST['shipping'];
		$customer_type = preventInject($_POST['customer_type']);
		
		if (!preg_match('/^[0-9]{10,13}$/', $customer_phone))
		{
			$views['errorMessage'] = "Nomor Telepon tidak valid!";
			require('customer/edit-customer.php');
		}
		elseif (empty($_POST['password']))
		{
			$data = array(
					
					'ID' => $customer_id,
					'fullname' => $customer_fullname,
					'email' => $customer_email,
					'address' => $customer_address,
					'phone' => $customer_phone,
					'district_id' => $customer_district,
					'shipping_id' => $customer_shipping,
					'customer_type' => $customer_type,
			);
			
			$edit_kustomer =  new Customer($data);
			$edit_kustomer -> updateCustomer();
			
			echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=customers&status=customerUpdated">';
			
			exit();
			
		} else {
			
			$data = array(
					
					'ID' => $customer_id,
					'fullname' => $customer_fullname,
					'email' => $customer_email,
					'password' => isset($_POST['password']) ? preg_replace('/[^ \-\_a-zA-Z0-9]/', '', $_POST['password']) : '',
					'address' => $customer_address,
					'phone' => $customer_phone,
					'district_id' => $customer_district,
					'shipping_id' => $customer_shipping,
					'customer_type' => $customer_type
			);
			
			$edit_kustomer =  new Customer($data);
			$edit_kustomer -> updateCustomer();
				
			echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=customers&status=customerUpdated">';
				
			exit();
			
		}
		
	}
	else 
	{
		
		// get customer's record by their Ids and session
		$views['Customer'] = $customers -> getCustomerBySession($sessionId);
		$views['customerId'] = $views['Customer'] -> getId();
		$views['sessionId'] = $views['Customer'] -> getCustomer_Session();
		// get district and shipping record and match it with data customer
		$views['district'] = $district -> setDistrict_Dropdown($views['Customer'] -> getDistrictId());
		$views['shipping'] = $shipping -> setShippingDropDown($views['Customer'] -> getShippingId());
		
		require('customer/edit-customer.php');
		
		
	}
	
}

// fungsi hapus kustomer
function deleteCustomer() {
	
	global $customers, $customerId;
	
	if ( !$kustomer = $customers -> getCustomerById($customerId)) {
		
		require('../cabin/404.php');
	}
	
	$data = array('ID' => $customerId);
	
	$hapus_customer = new Customer($data);
	$hapus_customer -> deleteCustomer();
	
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=customers&status=customerDeleted">';
	
	exit();
	
}
