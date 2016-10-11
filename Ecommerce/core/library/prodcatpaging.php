<?php if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!");
/**
 * Kelas ProductPaging
 * product category page pagination
 * used in front end page
 * 
 * @package   PiLUS_CMS
 * @author    Maoelana Noermoehammad
 * @copyright 2014 kartatopia.com
 * @license   GPL version 3.0
 * @version   1.4.0
 * @since     Since Release 1.4
 *
 */

class ProdCatPaging 
{

	function getPosition($batas) 
	{
		if(empty($_GET['halaman-kategori']))
		{
			$posisi=0;
			$_GET['halaman-kategori']=1;
		}
		else
		{
			
			$posisi = ($_GET['halaman-kategori']-1) * $batas;
		}
		return $posisi;
	}

	// Fungsi untuk menghitung total halaman
	function totalPage($jmldata, $batas)
	{
		$jmlhalaman = ceil($jmldata/$batas);
		return $jmlhalaman;
	}

	// Fungsi untuk link halaman 1,2,3
	function navPage($halaman_aktif, $jmlhalaman)
	{
		$link_halaman = "";

		// Link ke halaman pertama (first) dan sebelumnya (prev)
		if($halaman_aktif > 1){
			$prev = $halaman_aktif-1;
			$link_halaman .= "<a href=halaman-kategori-$_GET[catslug]-1  class='nextprev'>Awal</a>
			<a href=halaman-kategori-$_GET[catslug]-$prev  class='nextprev'>Kembali</a>";
		}
		else{
			$link_halaman .= "<span class='nextprev'>Awal</span>
					<span class='nextprev'>Kembali</span>";
		}

		// Link halaman 1,2,3, ...
		$angka = ($halaman_aktif > 3 ? " ... " : " ");
		for ($i=$halaman_aktif-2; $i<$halaman_aktif; $i++){
			if ($i < 1)
				continue;
			$angka .= "<a href=halaman-kategori-$_GET[catslug]-$i>$i</a>";
		}
		$angka .= " <span class='current'><b>$halaman_aktif</b></span>";
			
		for($i=$halaman_aktif+1; $i<($halaman_aktif+3); $i++){
			if($i > $jmlhalaman)
				break;
			$angka .= "<a href=halaman-kategori-$_GET[catslug]-$i>$i</a>";
		}
		$angka .= ($halaman_aktif+2<$jmlhalaman ? "<span class='nextprev'>...</span><a href=halaman-kategori-$_GET[catslug]-$jmlhalaman>$jmlhalaman</a>" : " ");

		$link_halaman .= "$angka";

		// Link ke halaman berikutnya (Lanjut) dan terakhir (Akhir)
		if($halaman_aktif < $jmlhalaman){
			$next = $halaman_aktif+1;
			$link_halaman .= " <a href=halaman-kategori-$_GET[catslug]-$next class='nextprev' >Lanjut</a>
			<a href=halaman-kategori-$_GET[catslug]-$jmlhalaman class='nextprev'>Akhir</a>";
		}
		else{
			$link_halaman .= "<span class='nextprev'>Lanjut</span>
					<span class='nextprev'>Akhir</span>";
		}
		return $link_halaman;
	}
}