<?php

include_once '../../../core/plcore.php';

if (!defined('PILUS_SHOP'))
{
	header("Location: ../../../cabin/403.php");
	exit;
}

$accessLevel =  Admin::accessLevel();
$option = new Option();
$pdf = new Cezpdf();
$dbh = new Pldb;

if ( $accessLevel != 'superadmin' && $accessLevel != 'admin')
{
	include_once( "../../../cabin/404.php" );
}
else {

$metaData = array();

$options = $option -> getOptions();

$metaData['options'] = $options['results'];

foreach ( $metaData['options'] as $opt)
{
	$siteName = $opt -> getSite_Name();
}
//set margin dan font
$pdf -> ezSetCmMargins(3, 3, 3, 3);
$pdf -> selectFont('../../../cabin/fonts/Courier.afm');

$all = $pdf -> openObject();

//Judul Header
$pdf -> addText(180, 820, 16, '<b>Laporan Penjualan Harian</b>');
$pdf -> addText(200, 800, 14, $siteName);

//garis atas untuk header
$pdf -> line(10, 795,  578, 795);

//garis bawah untuk footer
$pdf -> line(10, 50, 578, 50);

//Teks kiri bawah
$pdf -> addText(30,34,8,'Dicetak tanggal:' . date( 'd-m-Y, H:i:s'));

$pdf -> closeObject();

//Menampilkan Object di semua halaman
$pdf -> addObject($all, 'all');

$tglSekarang = date('Y-m-d');


//Query untuk merelasikan kedua tabel di filter berdasarkan tanggal

$sql = "SELECT o.orders_id AS faktur, DATE_FORMAT(o.date_order, '%d-%m-%Y') AS tanggal,
		p.product_name, p.price, od.quantity
		FROM pl_orders AS o
		INNER JOIN pl_orders_detail AS od ON o.orders_id = od.orders_id
		INNER JOIN pl_product AS p ON od.product_id = p.ID
		WHERE o.status='Lunas' AND o.date_order='$tglSekarang'";

$sth = $dbh -> query($sql);
$jml = $sth -> rowCount();

if ( $jml > 0)
{
	$i = 1;
		
	while ($r = $sth -> fetch(PDO::FETCH_ASSOC)) {

		$quantityharga = rupiah($r['quantity'] * $r['price']);
		$hargarp = rupiah($r['price']);
		$faktur = $r['faktur'];

		$data[$i] = array(


				'<b>No</b>'=> $i,
				'<b>Faktur</b>' => $faktur,
				'<b>Nama Produk</b>' => $r['product_name'],
				'<b>Qty</b>' => $r['quantity'],
				'<b>Harga</b>' => $hargarp,
				'<b>Sub Total</b>' => $quantityharga
		);

		$total = $total+($r['quantity']*$r['price']);
		$totqu = $totqu + $r['quantity'];
		$i++;
	}

	$pdf -> ezTable($data, '', '', '');

	$tot = rupiah($total);

	$pdf -> ezText("\n\nTotal keseluruhan : Rp. {$tot}");
	$pdf -> ezText("\njumlah yang terjual : {$jml} unit");
	$pdf -> ezText("Jumlah keseluruhan yang terjual : {$totqu} unit");

	//Penomoran Halaman
	$pdf -> ezStartPageNumbers(320, 15, 8);
	$pdf -> ezStream();
}
else
{
	
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=report&error=reportTodayNotFound">';
	
	exit();
		
}
}