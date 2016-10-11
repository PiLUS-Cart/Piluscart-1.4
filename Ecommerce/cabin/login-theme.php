<?php
/**
 * File login-theme.php
 * berfungsi sebagai fungsi template
 * Halaman Login - Header dan Footer
 * Back Store - administrator web
 *
 * @package   PiLUS_CMS
 * @author    Maoelana Noermoehammad
 * @copyright 2014 kartatopia.com
 * @license   GPL version 3.0
 * @version   1.4.0
 * @since     Since Release 1.4
 *
 */

function loginHeader($pageTitle)
{
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title><?php echo $pageTitle; ?></title>

<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes"> 

<!-- Core CSS - Include with every page -->
<link href="../cabin/css/bootstrap.min.css" rel="stylesheet">
<link href="../cabin/font-awesome/css/font-awesome.css"
	rel="stylesheet">
<!-- Page-Level Plugin CSS - Dashboard -->
<!-- Page-Level Plugin CSS - Tables -->
<link href="../cabin/css/plugins/dataTables/dataTables.bootstrap.css"
	rel="stylesheet">
<link href="../cabin/css/plugins/morris/morris-0.4.3.min.css"
	rel="stylesheet">
<link href="../cabin/css/plugins/timeline/timeline.css"
	rel="stylesheet">
<link href="../cabin/css/sb-admin.css" rel="stylesheet">
<!-- Icon -->
<link rel="icon" href="../cabin/img/faviconnew.png" type="image/x-icon" />

</head>
<body OnLoad="document.login.username.focus();" >


<?php 
}


function loginFooter()
{
?>

	<!-- Core Scripts - Include with every page -->
	<script src="../cabin/js/jquery-1.10.2.js"></script>
	<script src="../cabin/js/bootstrap.min.js"></script>
	<script src="../cabin/js/plugins/metisMenu/jquery.metisMenu.js"></script>

	<!-- SB Admin Scripts - Include with every page -->
	<script src="../cabin/js/sb-admin.js"></script>
	<script src="../cabin/js/checklogin.js"></script>

</body>
</html>


<?php 	
}
?>