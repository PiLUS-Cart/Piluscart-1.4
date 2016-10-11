<?php if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!");
/**
 * File Modul report.php
 * mengelola business logic
 * pada fungsionalitas objek report
 * 
 * @package   PiLUS_CMS
 * @author    Maoelana Noermoehammad
 * @copyright 2014 kartatopia.com
 * @license   GPL version 3.0
 * @version   1.4.0
 * @since     Since Release 1.4
 *
 */

$action = isset($_GET['action']) ? htmlspecialchars(strip_tags($_GET['action'])) : "";
$accessLevel = Admin::accessLevel();
$dbh = new Pldb;
$report = new Report();
$option = new Option();

if ( $accessLevel != 'superadmin' && $accessLevel != 'admin')
{
	include_once( "../cabin/404.php" );
}
else 
{
	switch ($action) {
	
		// laporan hari ini
		case 'todayReport':
	
			todayReport(); // fungsi laporan hari ini
	
			break;
	
		default:
	
			// tampil form buat laporan
			formReport();
	
			break;
			
	}
	
}

// fungsi laporan hari ini
function todayReport() {

	global $dbh, $report;
	
	$views = array();
	$views['pageTitle'] = "Laporan penjualan hari ini";
	
	$tglSekarang = date('Y-m-d');
	$data_harian = $report -> getReportToday($tglSekarang);
	
	$views['latestReports'] = $data_harian['results'];
	$views['totalRows'] = $data_harian['totalRows'];
	
	if ( $report -> cekTransaksiHarian($tglSekarang) > 0)
	{
		require('report/latest-report.php');
	}
	else 
	{
		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=report&error=reportTodayNotFound">';
			
		exit();
	}
}

// fungsi tampil form laporan
function formReport() {

	$views = array();

	$views['pageTitle'] = "Laporan penjualan";
	$views['formAction'] = "todayReport";
	

	if (isset($_GET['error']))
	{
		if ($_GET['error'] == "reportTodayNotFound") $views['errorMessage'] = "Error: Laporan Hari ini tidak ditemukan";

		if ($_GET['error'] == "reportPeriodNotFound") $views['errorMessage'] = "Error: Laporan Periode tidak ditemukan";
	}

	require( "report/set-reports.php" );
}
