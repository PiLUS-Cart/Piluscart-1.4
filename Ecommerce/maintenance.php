<?php
/**
 * maintenance.php
 * Jika tidak ada template yang aktif
 * maka file maintenance.php yang akan dipanggil
 * 
 * @package   PiLUS_CMS
 * @author    Maoelana Noermoehammad
 * @copyright 2014 kartatopia.com
 * @license   GPL version 3.0
 * @version   1.4.0
 * @since     Since Release 1.4
 * 
 */

include_once ('core/plcore.php');

//Meminta PiLUS untuk memanggil tema website yang aktif
$getThemeActived = $themes -> loadTheme();

if (!$getThemeActived) 
{
	echo "<h1>Toko Online saat ini tidak aktif</h1>";
}
else {
	
	directPage();
}