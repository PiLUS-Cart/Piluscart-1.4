<?php
/**
 * File cabin-theme.php
 * berfungsi sebagai fungsi template
 * Header dan Footer
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

if (!defined('PILUS_SHOP')) header("Location: 403.php"); 

//fungsi display admin header
function cabinHeader($pageTitle = NULL)
{

?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title><?php if (isset($pageTitle)) echo $pageTitle; ?>
</title>

<!-- Bootstrap Core CSS -->
<link href="../cabin/css/bootstrap.min.css" rel="stylesheet">
<!-- MetisMenu CSS -->
<link href="../cabin/css/plugins/metisMenu/metisMenu.min.css"
	rel="stylesheet">
<!-- Icon -->
<link rel="icon" href="../cabin/img/faviconnew.png" type="image/x-icon" />

<!-- font awesome -->
<link href="../cabin/font-awesome/css/font-awesome.min.css"
	rel="stylesheet" type="text/css">


<link href="../cabin/css/sb-admin.css" rel="stylesheet">

<!-- wysiwyg editor-->
<script src="../cabin/wysiwyg/tiny_mce/tiny_mce.js" type="text/javascript"></script>
<script src="../cabin/wysiwyg/tiny_mce/tiny_pilus.js" type="text/javascript"></script>

 <!-- chart -->	
    <script type="text/javascript" src="../cabin/js/jquery.min.js"></script>
    <script type="text/javascript" src="../cabin/js/jquery.fusioncharts.js"></script>
    
<!-- Kalender -->
<link rel="stylesheet" href="../cabin/kalender/calendar.css" type="text/css">
<script type="text/javascript" src="../cabin/kalender/calendar.js"></script>
<script type="text/javascript" src="../cabin/kalender/calendar2.js"></script>

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="../cabin/js/html5shiv.js"></script>
<script src="../cabin/js/respond.min.js"></script>
<![endif]-->



</head>
<body>

	<div id="wrapper">

<?php 

}

// footer
function cabinFooter()
{
	?>

	</div>
	<!-- /#wrapper -->

    <!-- Jquery -->
    <script src="../cabin/js/jquery.js"></script>
    
    <!-- Checklogin -->
	<script src="../cabin/js/checklogin.js"></script>

	<!-- Toggle Fields -->
	<script src="../cabin/js/toggle_fields.js"></script>

	<script src="../cabin/js/bootstrap.min.js"></script>

	<script src="../cabin/js/plugins/metisMenu/metisMenu.min.js"></script>


	<!-- SB Admin Scripts - Include with every page -->
	<script src="../cabin/js/sb-admin.js"></script>


</body>
</html>
<?php 
}