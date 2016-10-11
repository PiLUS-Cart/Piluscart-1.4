<?php
/**
 * activate.php
 * berperan untuk mengaktifkan pengguna 
 * halaman back store 
 * 
 * @package   PiLUS_CMS
 * @author    Maoelana Noermoehammad
 * @copyright 2014 kartatopia.com
 * @license   GPL version 3.0
 * @version   1.4.0
 * @since     Since Release 1.4
 * 
 */

include_once( '../core/plcore.php');

$admin_activationKey = isset($_GET['key']) ? trim($_GET['key']) : '';

if (empty($admin_activationKey)) {
	directPage();
} else {
	$admins -> activateAdmin($admin_activationKey);
}