<?php
/**
 * cabin/index.php
 * berfungsi sebagai front-controller
 * Back Store
 * 
 * @package   PiLUS_CMS
 * @author    Maoelana Noermoehammad
 * @copyright 2014 kartatopia.com
 * @license   GPL version 3.0
 * @version   1.4.0
 * @since     Since Release 1.4
 */

include_once('../core/plcore.php');

include_once('cabin-theme.php');

$pageTitle = isset($_GET['module']) ? htmlentities(strip_tags($_GET['module'])) : NULL;

if ($_SESSION['limit'] == 1) {

	if (!validateTimeLogIn()) {

		$_SESSION['limit'] = 0;
	}
}

if ($_SESSION['limit']== 0) {
	header('Location: login.php');
	exit;
} else {

	if (!isset($_SESSION['agent']) OR
		 ($_SESSION['agent'] != md5($_SERVER['HTTP_USER_AGENT']))) {
	
			header('Location: login.php');
			exit();

	} elseif (!$admins -> isLoggedIn() &&  $_SESSION['limit'] == 0 ) {
		
			header('Location: login.php');
			exit();
			
	} else {

		$userID = isset($_SESSION['adminID']) ? (int)$_SESSION['adminID'] : 0;
		$user_name = isset($_SESSION['adminLogin']) ? htmlentities($_SESSION['adminLogin']) : "";
		$user_fullname = isset($_SESSION['adminName']) ? htmlentities($_SESSION['adminName']) : "";
		$user_session = isset($_SESSION['adminSession']) ? htmlentities($_SESSION['adminSession']) : "";
		$user_level = isset($_SESSION['adminLevel']) ? htmlentities($_SESSION['adminLevel']) : "";

		$url_host = rtrim("http://".$_SERVER['HTTP_HOST'], "/").$_SERVER['PHP_SELF'];
		$clean_url_host = preg_replace("/\/cabin\/(index\.php$)/","",$url_host);
		$siteURL = $clean_url_host;

		$metaOptions = array();

		$data_option = $option -> getOptions();

		$metaOptions['siteOptions'] = $data_option['results'];

		foreach ( $metaOptions['siteOptions'] as $metaOption ) :

		$siteName = $metaOption -> getSite_Name();
		$description = $metaOption -> getMeta_Description();
		$keywords = $metaOption -> getMeta_Keywords();

		endforeach;

		// mendapatkan data inbox
		$countMessages = Inbox::countMessage();
		$data_pesan = Inbox::messageNotifications();
		$messages = $data_pesan['results'];


		// mendapatkan data komentar
		$countComments = PostComment::countComments();
		$data_komentar = PostComment::commentNotifications();
		$postComments = $data_komentar['results'];

		// mendapatkan data order
		$countOrders = Order::countOrders();
		$data_order = Order::orderNotifications();
		$orders = $data_order['results'];

		// mendapatkan data member
		$countMembers = Customer::countMembers();
		$data_member = Customer::memberNotifications();
		$members = $data_member['results'];

		// mendapatkan data notifikasi
		$countNotifications = Notification::countNotification();

		// mendapatkan jumlah item produk
		$countProducts = Product::countProducts();

		// mendapatkan data modul yang aktif -- Khusus superadmin
		$modulSetup = Module::setMenuModul();

		cabinHeader("$pageTitle\n" ."|\n" . $siteName . "\n-\n" . PACK_TITLE);

		include_once('cabin-menu.php');

		include_once('cabin-route.php');

		cabinFooter();

		ob_end_flush();

	}
}