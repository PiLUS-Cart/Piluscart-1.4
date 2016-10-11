<?php if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!");
/**
 * File Modul Orders.php
 * mengelola business logic
 * pada fungsionalitas objek order
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
$orderId = isset($_GET['orderId']) ? abs((int)$_GET['orderId']) : 0;
$orders = new Order();
$accessLevel = Admin::accessLevel();

if ( $accessLevel != 'superadmin' && $accessLevel != 'admin')
{
	include_once( "../cabin/404.php" );
}
else 
{
	switch ($action) {
	
		// Tampilkan detail pesanan
		case 'detailOrder':
	
			$cleaned = $sanitasi -> sanitasi($orderId, 'sql');
			$current_order = $orders -> findById($cleaned);
			$current_id = $current_order['orders_id'];
	
			if ( isset($orderId) && $current_id != $orderId)
			{
				require( "../cabin/404.php" );
			}
			else
			{
				detailOrder();
			}
	
			break;
	
		default:
	
			listOrder(); // Menampilkan semua Pesanan
	
			break;
	
	}
	
}

// fungsi menampilkan order
function listOrder() {

	$views = array();
	$views['pageTitle'] = "Order";
	
	$p = new Pagination();
	$limit = 10;
	$position = $p -> getPosition($limit);
	
	$data_order = Order::getOrders($position, $limit);
	
	$views['orders'] = $data_order['results'];
	$views['totalRows'] = $data_order['totalRows'];
	$views['position'] = $position;
	
	// pagination
	$totalPage = $p -> totalPage($views['totalRows'], $limit);
	$pageLink = $p -> navPage($_GET['order'], $totalPage);
	$views['pageLink'] = $pageLink;
	
	require( "order/list-orders.php" );
}

// fungsi detail order
function detailOrder() {

	global $orders, $orderId, $option;
	
	$dbh = new Pldb;
	
	$views = array();
	$views['pageTitle'] = "Detail Order";
	$views['formAction'] = "detailOrder";

	if (isset($_POST['ubah']) && $_POST['ubah'] == 'Konfirmasi')
	{
	    if (isset($_POST['status_order']) && $_POST['status_order'] == 'Lunas')
	    {
	    	$data_order = array('orders_id' => isset($_POST['order_id']) ? (int)$_POST['order_id'] : "");
	    	
	    	$updateOrder = new Order($data_order);
	    	
	    	$tambahStock = $updateOrder -> kurangiStock();
	    	
	    	$tambahBestSeller = $updateOrder -> tambahBestSeller();
	    	
	    	$data_status = array(
	    			
	    			'orders_id' => isset($_POST['order_id']) ? (int)$_POST['order_id'] : '',
	    			'status' => isset($_POST['status_order']) ? $_POST['status_order'] : ''
	    	);
	    	
	    	$updateStatus = new Order($data_status);
	    	
	    	if ( $updateStatus -> updateStatusOrder() == 1)
	    	{
	    	
	    		// Mengambil data pemilik toko
	    		
	    		$data_owner = $option -> getOptions();
	    		
	    		$managers = $data_owner['results'];
	    		
	    		foreach ( $managers as $manager )
	    		{
	    			
	    			$owner_email = $manager -> getOwnerEmail();
	    			$no_rekening = $manager -> getNoRekening();
	    			$nomor_telp = $manager -> getNoTelpon();
	    			$pinBB = $manager -> getPinBB();
	    			$namaToko = $manager -> getSite_Name();
	    			$alamat_toko = $manager -> getShopAddress();
	    			
	    		}
	    		
	    		// Mengambil data kustomer
	    		$data_kustomer = $orders -> getOrder($orderId);
	    		$nama_kustomer = htmlentities($data_kustomer -> getCustomerFullname());
	    		$email_kustomer = htmlentities($data_kustomer -> getCustomerEmail());
	    		$alamat_kustomer = htmlentities($data_kustomer -> getCustomerAddress());
	    		$telp_kustomer = htmlentities($data_kustomer -> getCustomerPhone());
	    		
	    		$kepada = safeEmail($email_kustomer);
	    		$subyek = "Pembayaran sudah Lunas!";
	    		$pesan = "<html>
	    		<body>
	    		<p>Pemberitahuan ini kami kirimkan karena anda telah melunasi Pembayaran di toko online $namaToko.<br />
	    		 Barang yang anda pesan akan segera kami kirimkan ke alamat berikut ini: <br><br>
	    		<b>Nama kustomer     :</b> $nama_kustomer <br />
	    		<b>Alamat pengiriman :</b> $alamat_kustomer<br />
	    		<b>No.Telepon/Hp     :</b> $telp_kustomer<br /><br />
	    		
	    		<b>Catatan:</b>Pastikan data alamat pengiriman anda valid!.<br/>
	    		Segera sampaikan informasi kepada kami jika terdapat perubahan pada alamat pengiriman anda.</p><br /><br />
	    		
	    		Terima kasih,<br />
	    		<b>$namaToko</b><br />
	    		
	    		$alamat_toko<br />
	    		$nomor_telp<br />
	    		$pinBB<br />
	    		$owner_email<br />
	    		
	    		</body>
	    		</html>";
	    		
	    		$kirim_pesan = new Mailer();
	    		
	    		$kirim_pesan -> setSendText(false);
	    		$kirim_pesan -> setSendTo($kepada);
	    		$kirim_pesan -> setFrom($namaToko);
	    		$kirim_pesan -> setSubject($subyek);
	    		$kirim_pesan -> setHTMLBody($pesan);
	    		 
	    		if ( $kirim_pesan -> send())
	    		{
	    			echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=orders&status=orderUpdated">';
	    			 
	    			exit();
	    			
	    		}
	    		
	    	}
	    	
	    }
	    elseif (isset($_POST['status_order']) && $_POST['status_order'] == 'Batal')
	    {
	    	$data_order = array('orders_id' => isset($_POST['order_id']) ? (int)$_POST['order_id'] : "");
	    	
	    	$updateOrder = new Order($data_order);
	    	
	    	$kurangiStok = $updateOrder -> tambahStock();
	    	
	    	$kurangiBestSeller = $updateOrder -> kurangiBestSeller();
	    	
	    	$data_status = array(
	    	
	    			'orders_id' => isset($_POST['order_id']) ? (int)$_POST['order_id'] : '',
	    			'status' => isset($_POST['status_order']) ? $_POST['status_order'] : ''
	    	);
	    	
	    	$updateStatus = new Order($data_status);
	    	$updateStatus -> updateStatusOrder();
	    	
	    	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=orders&status=orderUpdated">';
	    	
	    	exit();
	    	
	    }
	    else {
	    	
	    	$data_status = array(
	    	
	    			'orders_id' => isset($_POST['order_id']) ? (int)$_POST['order_id'] : '',
	    			'status' => isset($_POST['status_order']) ? $_POST['status_order'] : ''
	    	);
	    	
	    	$updateStatus = new Order($data_status);
	    	$updateStatus -> updateStatusOrder();
	    	
	    	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=orders">';
	    	
	    	exit();
	    }
	}
	else 
	{
		$views['Pesan'] = $orders -> getOrder($orderId);
		$views['statusPesan'] = $orders -> statusDropDown($views['Pesan'] -> getOrderId());
		$detail_order = Order::getDetailOrder($orderId);
		$views['detailPesanan'] = $detail_order['results'];
		$views['ongkos'] = $orders -> getShippingCost($views['Pesan'] -> getOrderId());
		
		require( "order/detail-order.php" );
	}
	
}