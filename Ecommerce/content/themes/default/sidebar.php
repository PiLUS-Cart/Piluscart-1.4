<?php 
/**
 * this is a sidebar theme of pilus cms
 * File sidebar.php
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
	
if (!$logged_in = Customer::isMemberLoggedIn()) {
	
	// sidebar blog
	
	if ( isset($_GET['content'])  &&  $_GET['content'] != 'detailpage'
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
		 &&  $_GET['content'] != 'sendtestimony') {
		
	?>
	
	<div id="sidebar">
	
	<?php 
	
	echo blog_categories();
	echo banner_advertising("pl_banner");
		
	?>
	
	</div>
	<!-- END of sidebar blog -->
	
	<?php 
	} else {
	
		// sidebar toko
	?>
	
	<div id="sidebar">
	
	      <?php 
	         
	      	echo product_categories();
	      	echo side_ShoppingCart();
	      	echo customer_service();
	      	echo katalog_product();
	      	echo banner_advertising("pl_banner");
	     
	      ?>
	      
	  <!-- END of sidebar toko -->
	</div> 

<?php 
	 }
} else {
	
	if ( isset($_GET['content'])  &&  $_GET['content'] != 'detailpage'
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
			&&  $_GET['content'] != 'sendtestimony') {
		// sidebar blog
		?>
		<div id="sidebar">
		
		<?php 
		
		echo blog_categories();
		
			
		?>
		
		</div>
		<!-- END of sidebar -->
		
		<?php 
		} else {
		
			// sidebar toko
		?>
<div id="sidebar">
		
		      <?php 
		         
		        echo dataMember();
		      	echo product_categories();
		      	echo side_ShoppingCart();
		      	echo customer_service();
		      	echo katalog_product();
		     
		      ?>
		      
</div>
		
<?php } }?>