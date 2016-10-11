<?php
/**
 * index.php
 * 
 * @package   PiLUS_CMS
 * @author    Maoelana Noermoehammad
 * @copyright 2014 kartatopia.com
 * @license   GPL version 3.0
 * @version   1.4.0
 * @since     Since Release 1.4
 */

include_once('core/plcore.php');
// current URL of the Page - redirects back to this URL
$currentURL = base64_encode($url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
// Meminta PiLUS untuk memanggil tema website yang aktif
$getThemeActived = $themes -> loadTheme();
if (!$getThemeActived) {
   // memanggil file maintenance.php
   require_once('maintenance.php');
} else {
   // memanggil template website toko online yang aktif
   require_once( $getThemeActived -> getTemplate_Folder() . '/theme.php' );
}