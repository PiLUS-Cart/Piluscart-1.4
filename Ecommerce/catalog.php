<?php
/**
 * catalog.php
 * merespon permintaan
 * download file katalog produk
 *
 * @package   PiLUS_CMS
 * @author    Maoelana Noermoehammad
 * @copyright 2014 kartatopia.com
 * @license   GPL version 3.0
 * @version   1.4.0
 * @since     Since Release 1.4
 */

include_once('core/plcore.php');

$direktori = 'content/uploads/files/';

$filename = isset($_GET['filename']) ? $_GET['filename'] : '';

if (empty($filename)) directPage();

if (is_readable($direktori.$filename)) {
  
$fileExtension = strtolower(substr(strrchr($filename, "."), 1));
  
switch ($fileExtension) {
  case "pdf": $ctype="application/pdf"; 
      break; 
  case "exe": $ctype="application/octet-stream"; 
      break;
  case "zip": $ctype="application/zip"; 
      break;
  case "rar": $ctype="application/rar"; 
      break;
  case "doc": $ctype="application/msword"; 
      break;
  case "xls": $ctype="application/vnd.ms-excel"; 
      break;
  case "ppt": $ctype="application/vnd.ms-powerpoint"; 
      break;
  case "gif": $ctype="image/gif"; 
      break;
  case "png": $ctype="image/png"; 
      break;
  case "jpeg":
  case "jpg": $ctype="image/jpg"; 
      break;
  default: $ctype="application/force-download";
}

if ($fileExtension == 'php' ) {
  
  echo '<blockquote><h3>Terjadi Kesalahan!</h3>
		Jenis file yang anda unduh salah.
		 </blockquote>';
  exit();
  
} else {
$fileDownloaded = new Download();
$fileDownloaded -> updateHits($filename);
  header("Content-Type: octet/stream");
  header("Pragma: private");
  header("Expires: 0");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("Cache-Control: private", false);
  header("Content-Type:$ctype");
  header("Content-Disposition: attachment;filename=\"".basename($filename)."\";");
  header("Content-Transfer-Encoding: binary");
  header("Content-Length: ".filesize($direktori.$filename));
  readfile("$direktori$filename");
  exit();		
}

} else {
  echo '<h3>Access forbidden!</h3>
  <p>Maaf, file yang Anda download sudah
  tidak tersedia atau filenya sudah dihapus</p>';
  exit();
}