<?php if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!");
/**
 * File Modul dashboard.php
 * mengelola business logic
 * pada fungsionalitas objek dashboard
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
$accessLevel = Admin::accessLevel();
$statistics = new Statistics();
$articles = new Post();

switch ($action) {
	
	default:
		
		if ( $accessLevel != 'superadmin' && $accessLevel != 'admin') {
			
			welcomeCrew();
			
		} else {
			
			welcomeAdmin();
			
		}
		
		break;

}

// fungsi halaman utama staff
function welcomeCrew() 
{
	
	global $articles;
	
	$dbh = new Pldb;
	
	$views = array();
	
	$views['pageTitle'] = "Dashboard";
	$views['new_articles'] = "Tulisan Terbaru";
	$views['visitor_stat'] = "Statistik Pengunjung";
	
	// Tulisan terbaru
	$data_artikel = Post::findPosts_ByStaff(0, 10);
	$views['articles'] = $data_artikel['results'];
	$views['totalRows'] = $data_artikel['totalRows'];
	
	require('dashboard/homeuser.php');
	
}

// fungsi halaman utama admin
function welcomeAdmin() 
{
	
	global $statistics, $articles, $countMessages, $countComments, $countOrders, $countProducts;

	$dbh = new Pldb;
	
	$views = array();
	$views['pageTitle'] = "Dashboard";
	$views['stats'] = "Statistik Pengunjung";
	$views['statistic_summary'] = "Rangkuman Statistik Pengunjung";
	$views['new_post'] = "Tulisan Terbaru";
	$views['countMessages'] = $countMessages;
	$views['countComments'] = $countComments;
	$views['countOrders'] = $countOrders;
	$views['countProducts'] = $countProducts;
	
	// Rangkuman Statistik Pengunjung
	$views['visitor_today'] = $statistics -> setVisitorToday(date("Y-m-d"));
	$views['total_visitor'] = $statistics -> setTotalVisitor();
	$hits_hari_ini = $statistics -> setHitsToday(date("Y-m-d"));
	$views['hits_hari_ini'] = $hits_hari_ini['hitstoday'];
	$views['total_hits'] = $statistics -> setTotalHits();
	
	// Tulisan terbaru
	$data_artikel = $articles -> findPosts(0, 3);
	$views['articles'] = $data_artikel['results'];
	$views['totalRows'] = $data_artikel['totalRows'];
	
	require('dashboard/home.php');
	
}