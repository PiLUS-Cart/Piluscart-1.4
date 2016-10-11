<?php
/**
 * File theme.php 
 * default template
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
	
$_SESSION['allow_access'] = true;

// statCollector();

$metaOptions = "";

$data_option = $option->getOptions();

$metaOptions = $data_option ['results'];

foreach ( $metaOptions as $m => $metaOption ) :
	
	$siteName = $metaOption->getSite_Name();
	$description = $metaOption->getMeta_Description();
	$keywords = $metaOption->getMeta_Keywords();
	$tagline = $metaOption->getTagline();
	$favicon = $metaOption -> getFavicon();
	
endforeach;

require_once ($getThemeActived->getTemplate_Folder() . '/widget.php');


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="id" lang="id">
<head>
<title><?php include_once 'title.php';  ?></title>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="robots" content="index, follow" />
<meta name="keywords" content="<?php echo $keywords; ?>" />
<meta name="description" content="<?php echo $description; ?>" />
<meta http-equiv="imagetoolbar" content="no" />
<meta name="language" content="Indonesia" />
<meta name="revisit-after" content="2" />
<meta name="webcrawlers" content="all" />
<meta name="rating" content="general" />
<meta name="spiders" content="all" />
<meta name="generator" content="<?php echo PACK_TITLE . "-" . PACK_VERSION; ?>" />

<link rel="alternate" type="text/xml" title="<?php echo $siteName . " | " . $keywords; ?>"  href="content/feed" />
<link href="<?php echo $getThemeActived -> getTemplate_Folder() . "/"; ?>css/templatemo_style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="<?php echo $getThemeActived -> getTemplate_Folder() . "/"; ?>css/ddsmoothmenu.css" />
<link rel="shortcut icon" href="content/uploads/images/<?php echo $favicon; ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo $getThemeActived -> getTemplate_Folder() . "/"; ?>facebox/facebox.css" />

<!-- Begin Slider best seller -->
<link rel="stylesheet" type="text/css"
			href="<?php echo $getThemeActived -> getTemplate_Folder() . "/"; ?>css/slide.css" />
		

</head>

<body id="subpage">

	<div id="templatemo_wrapper">
		<div id="templatemo_header">
			<div id="site_title">
				<h1>
					<a href="<?php echo PL_DIR; ?>" title="<?php echo $keywords.",".$tagline; ?>"><?php echo $tagline;?></a>
				</h1>
			</div>

			<div id="header_right">
				<ul id="language">
				     <?php if ( !$checkLogin = Customer::isMemberLoggedIn()) { ?>
					<li> <a href="member-login" title="Log In Member"><img
							src="content/themes/default/images/site_admin.png"
							alt="PiLUS CMS, CMS buatan lokal, CMS Indonesia" /></a></li>

                     <?php }else { ?>
                     <li> <a href="member-logout" title="Log Out Member"><img
							src="content/themes/default/images/power_off.png"
							alt="PiLUS CMS, CMS buatan lokal, CMS Indonesia" /></a></li>
                     
                     <?php } ?>
                     <li>
                     <a href="shopping-basket" title="keranjang belanja">
                     <img src="content/themes/default/images/cart.png" alt="Keranjang Belanja" /></a></li>
                     
				</ul>
				<div class="cleaner"></div>
				<div id="templatemo_search">
				  <?php 
				  
				  // cari artikel
				  
				  if (isset($_GET['content'])  &&  $_GET['content'] != 'detailpage' 
	                 &&  $_GET['content'] != 'productdetail'  
	                 &&  $_GET['content'] != 'prodcatdetail' 
	                 &&  $_GET['content'] != 'searchproduct'
	                 &&  $_GET['content'] != 'contactform' 
	                 &&  $_GET['content'] != 'basket'
	                 &&  $_GET['content'] != 'checkout'
	                 &&  $_GET['content'] != 'savetransaction'
	                 &&  $_GET['content'] != 'membertransaction'
	                 &&  $_GET['content'] != 'testimoni'
	                 &&  $_GET['content'] != 'daftarmember'
				  	 &&  $_GET['content'] != 'memberlogin'
				  	 &&  $_GET['content'] != 'forgetpassword'
				  	 &&  $_GET['content'] != 'recoverpassword'
				  	 &&  $_GET['content'] != 'editprofile'
				  	 &&  $_GET['content'] != 'changepass'
				  	 &&  $_GET['content'] != 'shophistory'
				  	 &&  $_GET['content'] != 'sendtestimony')
				  
				  {
				  	  	
				 ?>
				 
					<form action="search-article" method="post">
						<input type="text" value="Cari Artikel" name="keyword" id="field"
							title="keyword" onfocus="clearText(this)"
							onblur="clearText(this)" class="txt_field" /> <input
							type="submit" name="Search" value="" alt="Cari Produk"
							id="searchbutton" title="Search" class="sub_btn" />
					</form>
					
				<?php }else {  // cari produk  ?>
				
				  <form action="search-product" method="post" >
						<input type="text" value="Cari Produk" name="keyword" id="field"
							title="keyword" onfocus="clearText(this)"
							onblur="clearText(this)" class="txt_field" /> <input
							type="submit" name="Search" value="" alt="Cari Produk" id="searchbutton" title="Search" class="sub_btn" />
					</form>
					
				<?php } ?>
				</div>
			</div>
			<!-- END -->
		</div>
		<!-- END of header -->

		<div id="templatemo_menu" class="ddsmoothmenu">
			<ul>
				<?php echo set_MenuFront(); //set menu front end ?>
			</ul>
			<br style="clear: left" />
		</div>
		<!-- end of templatemo_menu -->

		<div class="cleaner h20"></div>
		<div id="templatemo_main_top"></div>
		<div id="templatemo_main">
		
			<?php if (!isset($_GET['content']) || $_GET['content'] == '') { ?>

			<!--#product_slider -->
			<div id="product_slider">
				<div id="SlideItMoo_outer">
					<div id="SlideItMoo_inner">
						<div id="SlideItMoo_items">

							<?php echo product_slider();  ?>

						</div>
					</div>
				</div>

				<div class="cleaner"></div>

			</div>

			<?php } ?>

			<!-- /#product_slider -->

			<?php
			
			// Memanggil tema sidebar
			include_once ($getThemeActived->getTemplate_Folder() . '/sidebar.php');
					
			?>

			<div id="content">
				<?php
				// Memanggil konten website
				include_once ($getThemeActived->getTemplate_Folder() . '/content.php');
				?>
			</div>
			<!-- END of content -->
			<div class="cleaner"></div>
		</div>
		<!-- END of main -->

		<div id="templatemo_footer">

			<!-- Widget Footer -->

			<?php echo setup_widget('postcat', 'footer'); ?>
			<?php echo setup_widget('staticpage', 'footer'); ?>
			<?php echo setup_widget('productcategories', 'footer'); ?>
			<?php echo setup_widget('customer', 'footer'); ?>
			<?php echo setup_widget('aboutus', 'footer-right'); ?>


			<div class="cleaner h40"></div>
			<center>
				Copyright &copy;
				<?php
				
				$starYear = 2016;
				$thisYear = date ( "Y" );
				if ($starYear == $thisYear) {
					echo $starYear;
				} else {
					echo "{$starYear}&#8211; {$thisYear}";
				}
				?>
				<?php echo $siteName; ?>
				| Powered by <a href="http://www.getpilus.com/"
					target="_blank">PiLUS</a>
			</center>
		</div>
		<!-- END of footer -->

	</div>
	<!-- /# end of templatemo_wrapper -->
	
<script type="text/javascript" src="<?php echo $getThemeActived -> getTemplate_Folder() . "/"; ?>js/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="<?php echo $getThemeActived -> getTemplate_Folder() . "/"; ?>js/ddsmoothmenu.js">
/***********************************************
* Smooth Navigational Menu- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
***********************************************/
</script>

<script type="text/javascript">

ddsmoothmenu.init({
	mainmenuid: "templatemo_menu", //menu DIV id
	orientation: 'h', // Horizontal or vertical menu: Set to "h" or "v"
	classname: 'ddsmoothmenu', // class added to menu's outer DIV
	//customtheme: ["#1c5a80", "#18374a"],
	contentsource: "markup" // "markup" or ["container_id", "path_to_menu_file"]
});

function clearText(field)
{
    if (field.defaultValue == field.value) field.value = '';
    else if (field.value == '') field.value = field.defaultValue;
}
</script>

<script type="text/javascript" src="<?php echo $getThemeActived -> getTemplate_Folder() . "/"; ?>facebox/facebox.js"></script>
<script type="text/javascript">
jQuery(document).ready(function($) {
    $('a[rel*=facebox]').facebox(); 
  });
</script>

<script type="text/javascript" src="<?php echo $getThemeActived -> getTemplate_Folder() . "/"; ?>scripts/mootools-1.2.1-core.js"></script>
<script type="text/javascript" src="<?php echo $getThemeActived -> getTemplate_Folder() . "/"; ?>scripts/mootools-1.2-more.js"></script>
<script type="text/javascript" src="<?php echo $getThemeActived -> getTemplate_Folder() . "/"; ?>scripts/slideitmoo-1.1.js"></script>
<script type="text/javascript">
window.addEvents({
'domready': function(){
/* thumbnails example , div containers */
		new SlideItMoo({
					overallContainer: 'SlideItMoo_outer',
					elementScrolled: 'SlideItMoo_inner',
					thumbsContainer: 'SlideItMoo_items',		
					itemsVisible: 5,
					elemsSlide: 2,
					duration: 200,
					itemsSelector: '.SlideItMoo_element',
					itemWidth: 171,
					showControls:1});
	},
	
});
</script>
	
	<script type="text/javascript">

   /* fungsi validasi form checkout kustomer baru */
   function validasi(form){
	  if (form.name.value == ""){
	    alert("Anda belum mengisikan Nama.");
	    form.name.focus();
	    return (false);
	  }    
	  if (form.address.value == ""){
	    alert("Anda belum mengisikan Alamat.");
	    form.address.focus();
	    return (false);
	  }
	  if (form.phone.value == ""){
	    alert("Anda belum mengisikan Telpon.");
	    form.phone.focus();
	    return (false);
	  }
	  if (form.email.value == ""){
	    alert("Anda belum mengisikan E-mail.");
	    form.email.focus();
	    return (false);
	  }
	  if (form.password.value == ""){
		  alert("Anda belum mengisikan Password.");
		  form.password.focus();
		  return (false);
	  }
	  if (form.confirmed.value == ""){
		  alert("Anda belum mengetik ulang password!.");
		  form.confirmed.focus();
		  return (false);
	   }
	  if (form.shipping.value == 0){
	    alert("Anda belum memilih jasa pengiriman.");
	    form.shipping.focus();
	    return (false);
	  }
	
	  return (true);
	}

    /* fungsi validasi form checkout member  */
	function validasiMember(member){
	  if (member.email.value == ""){
	    alert("Anda belum mengisikan E-mail.");
	    member.email.focus();
	    return (false);
	  }
	  if (member.password.value == ""){
	    alert("Anda belum mengisikan Password.");
	    member.password.focus();
	    return (false);
	  }
	  return (true);
	}

    /* fungsi validasi form hubungi kami */
	function validateContactForm(contact){
      if ( contact.nama.value == ""){
          alert("Anda belum mengisikan nama");
          contact.nama.focus();
          return (false);
      }
      if ( contact.email.value == ""){
          alert("Anda belum mengisikan E-mail ");
          contact.email.focus();
          return (false);      
      }
      if ( contact.subjek.value == ""){
          alert("Anda belum mengisikan subjek");
          contact.subjek.focus();
          return (false);
      }
      if ( contact.pesan.value == ""){
          alert("Anda belum mengisikan pesan");
          contact.pesan.focus();
          return (false);
      }
      return (true);
	}

	 /* fungsi validasi form komentar */
	function validateCommentForm(comment) {
      if ( comment.nama_komentar.value == ""){
          alert("Anda belum mengisikan nama");
          comment.nama_komentar.focus();
          return (false);
      }
      if ( comment.isi_komentar.value == ""){
          alert("Anda belum mengisikan komentar");
          comment.isi_komentar.focus();
          return (false);
      }
      if ( comment.captcha.value == ""){
          alert("Anda belum menjawab pertanyaan yang diberikan !");
          comment.captcha.focus();
          return (false);
      }
      return (true);
	}
	
	/* fungsi validasi form kirim testimoni */
	function validateTestimoniForm(testimoni){
      
      if ( testimoni.testi.value == ""){
          alert("Anda belum mengisikan testimoni !");
          testimoni.testi.focus();
          return (false);
      }
      return (true);
	}

	/* fungsi validasi register member */
	function validateRegister(regMember){
		if (regMember.nama_lengkap.value == "") {
            alert("Anda belum mengisikan nama lengkap!");
            regMember.nama_lengkap.focus();
            return (false);
		}
		if (regMember.email.value == "") {
            alert("Anda belum mengisikan E-mail!");
            regMember.email.focus();
            return (false);
		}

		return (true);
	}

	 /* fungsi validasi lupa kata sandi */
	function validateForgetPassword(forgetPass){
	      
	      if ( forgetPass.email.value == ""){
	          alert("Anda belum mengisikan E-mail ");
	          forgetPass.email.focus();
	          return (false);      
	      }
	      
	      return (true);
	}


	/* fungsi validasi ganti password */
	function validateChangePassword(gantiPassword){
	      
	      if ( gantiPassword.password.value == ""){
	          alert("Anda belum mengisikan Kata Sandi !");
	          gantiPassword.password.focus();
	          return (false);      
	      }

	      if ( gantiPassword.confirmed.value == ""){
	          alert("Anda belum mengetik ulang Kata Sandi !");
	          gantiPassword.confirmed.focus();
	          return (false);      
	      }
	      
	      return (true);
	}

	/* fungsi validasi edit member */
	function validateEditMember(editMember){
		if (editMember.fullname.value == "") {
            alert("Anda belum mengisikan nama lengkap!");
            editMember.fullname.focus();
            return (false);
		}
		if (editMember.email.value == "") {
            alert("Anda belum mengisikan E-mail!");
            editMember.email.focus();
            return (false);
		}
		if (editMember.address.value == "") {
            alert("Anda belum mengisikan Alamat!");
            editMember.address.focus();
            return (false);
		}
		if (editMember.phone.value == "" ) {
            alert("Anda belum mengisikan nomor telepon! ");
            editMember.phone.focus();
            return (false);
		}
		
		
		return (true);
	}
	
	function harusangka(jumlah){
	  var karakter = (jumlah.which) ? jumlah.which : event.keyCode;
	  if (karakter > 31 && (karakter < 48 || karakter > 57))
	    return false;
	  return true;
	}


</script>
	

</body>
</html>