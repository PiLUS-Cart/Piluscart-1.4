<?php 
/**
 * File studio-menu.php
 * file ini berfungsi sebagai menu
 * halaman administrtor web
 *
 */

if (!defined('PILUS_SHOP')) header("Location: 403.php");

?>

<!-- Navigation -->
<nav class="navbar navbar-default navbar-static-top" role="navigation"
	style="margin-bottom: 0">
	<div class="navbar-header">
		<button type="button" class="navbar-toggle" data-toggle="collapse"
			data-target=".navbar-collapse">
			<span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span>
			<span class="icon-bar"></span> <span class="icon-bar"></span>
		</button>
		<a class="navbar-brand" href="http://www.getpilus.com/" title="Free Open Source Ecommerce Solution"><?php echo PACK_TITLE . "\n" . PACK_VERSION . "\n" . PACK_CODENAME; ?></a>
	</div>
	<!-- /.navbar-header -->

	<ul class="nav navbar-top-links navbar-right">
	       
	       <!-- Pesan Masuk -->
	       <?php if ( isset($user_level) && $user_level == 'superadmin' OR $user_level == 'admin')  { ?> 
	    <li class="dropdown">
	         <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-envelope fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-messages">
                    
                    <?php if (isset($messages)) { 
                    
                          foreach ( $messages as $message ) : 
                          
                           $isi_pesan = (strip_tags($message -> getMessage()));
                           $isi = substr($isi_pesan, 0, 100);
                           $isi = substr($isi_pesan, 0, strrpos($isi, " "));
                   ?>
                        <li>
                            <a href="?module=inbox&action=replyMessage&messageId=<?php echo (int)$message -> getInbox_Id(); ?>">
                                <div>
                                    <strong><?php echo htmlspecialchars($message -> getSender()); ?></strong>
                                    <span class="pull-right text-muted">
                                        <em><?php echo timeAgo($message -> getTime_Sent()); ?></em>
                                    </span>
                                </div>
                                <div> <?php echo htmlspecialchars($isi); ?>...</div>
                            </a>
                        </li>
                        <li class="divider"></li>
                       
                        <?php endforeach; }  ?>
                        
                        <li>
                            <a class="text-center" href="<?php if (isset($countMessages) && $countMessages > 0) { print "?module=inbox"; }else{ print "#"; } ?>">
                                <strong><?php if ( isset($countMessages) && $countMessages == 0) { echo "Tidak ada pesan"; }else{ echo "Baca Semua Pesan"; } ?></strong>
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </li>
                        
                    </ul>
                    
                    <!-- /.dropdown-messages -->
              
                  </li>
                  <?php } ?>
                  
                  <!-- Order Masuk -->
                 
                 <?php if ( isset($user_level) && $user_level == 'superadmin' OR $user_level == 'admin') { ?>
                 <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-shopping-cart fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-tasks">
                         <?php 
                             foreach ( $orders as $order ) :
                         ?>
                        <li>
                            <a href="?module=orders&action=detailOrder&orderId=<?php  echo (int) $order -> getOrderId(); ?>">
                                <div>
                                    <strong><?php echo htmlspecialchars($order -> getCustomerFullname()); ?></strong>
                                    <span class="pull-right text-muted">
                                        <em><?php echo timeAgo($order -> getTimeOrder()); ?></em>
                                    </span>
                                </div>
                             
                             <div>Produk yang dipesan:<br>
                                <?php 
                                   $detail_pesanan = Order::getDetailOrder($order -> getOrderId());
                                   $detailOrders = $detail_pesanan['results'];
                                   $views = array();
                                   foreach ( $detailOrders as $detailOrder ) 
                                   {
                                       echo $detailOrder -> getProductName();
                                   }
                                ?>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <?php endforeach; ?>
                        <li>
                            <a class="text-center" href="<?php if (isset($countOrders) && $countOrders > 0) { echo "?module=orders"; }else{ echo "#";} ?>">
                                <strong><?php if (isset($countOrders) && $countOrders == 0){ echo "Order Kosong";}else{ echo "Lihat Semua Order"; } ?></strong>
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </li>
                    </ul> 
                    
                </li>  <!-- end of .order -->
                <?php } ?>
                
                <!-- Notifikasi Alert -->
                
                <?php  if ( isset($user_level) && $user_level == 'superadmin' OR $user_level == 'admin') {  ?>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-bell fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-alerts">
                        <li>
                            <a href="<?php if (isset($countMessages) && $countMessages > 0 ) { echo "?module=inbox"; } else{ echo "#"; } ?>">
                                <div>
                                    <i class="fa fa-envelope fa-fw"></i> <?php if (isset($countMessages) && $countMessages == 0){ echo "Tidak ada pesan ";} else{ echo "$countMessages Pesan baru"; } ?> 
                                    <span class="pull-right text-muted small"> <?php if ( $countMessages > 0 ) echo timeAgo($message -> getTime_Sent()); ?> </span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <!-- Order Notifikasi -->
                        <li>
                            <a href="<?php if (isset($countOrders) && $countOrders > 0) { echo "?module=orders"; } else { echo "#"; } ?>">
                                <div>
                                    <i class="fa fa-shopping-cart fa-fw"></i> <?php if (isset($countOrders) && $countOrders == 0) { echo "Order Kosong"; } else{ echo "$countOrders Order baru"; } ?>
                                    <span class="pull-right text-muted small"> <?php if ( $countOrders > 0) echo timeAgo($order -> getTimeOrder()); ?></span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <!-- Komentar -->
                        <?php 
                         
                          if ( isset($postComments)) { 
                        
                          	  foreach ( $postComments as $postComment ) :
                          	    
                          	  $waktu_komentar = $postComment -> getComment_timeCreated();
                          	  
                          	  endforeach;
                          }
                        ?>
                        <li>
                            <a href="<?php if (isset($countComments) && $countComments > 0) { echo "?module=comments"; } else{ echo "#"; }?>">
                                <div>
                                    <i class="fa fa-comment fa-fw"></i> <?php if (isset($countComments) && $countComments == 0 ){ echo "Tidak ada komentar"; } else{ echo "$countComments Komentar baru"; } ?>
                                    <span class="pull-right text-muted small"> <?php if ( $countComments > 0) echo timeAgo($waktu_komentar); ?></span>
                                </div>
                            </a>
                        </li>
                         <li class="divider"></li>
                         <!-- Member -->
                         <?php 
                            if (isset($members)) {
                            	
                            	foreach ($members as $member) :
                            	  $waktu_daftar = $member -> getCustomer_TimeRegistered();
                            	endforeach;
                            }
                         ?>
                         <li>
                            <a href="<?php if (isset($countMembers) && $countMembers > 0)  { echo "?module=customers"; } else { echo "#"; } ?>">
                                <div>
                                    <i class="fa fa-group fa-fw"></i> <?php if (isset($countMembers) && $countMembers == 0 ){ echo "Tidak ada Member"; } else{ echo "$countMembers Member baru"; } ?>
                                    <span class="pull-right text-muted small"> <?php if ( $countMembers > 0) echo timeAgo($waktu_daftar); ?></span>
                                </div>
                            </a>
                        </li>
                         <li class="divider"></li>
                        <li>
                            <a class="text-center" href="<?php if (isset($countNotifications) && $countNotifications > 0) { echo "?module=notification"; }else{ echo "#";} ?>">
                                <strong><?php if (isset($countNotifications) && $countNotifications == 0){ echo "Tidak ada notifikasi";}else{ echo "Lihat Semua Notifikasi"; } ?></strong>
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </li>
                    </ul>
                   
                    <!-- /.dropdown-alerts -->
                </li>
                <?php } ?>
                
           <!-- user -->
		<li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown"
			href="#"> <i class="fa fa-user fa-fw"></i> 
				<i class="fa fa-caret-down"></i>
		</a>
			<ul class="dropdown-menu dropdown-user">
				<li>
				<a href="?module=users&action=editUser&userId=<?php if (isset($userID)) echo $userID; ?>&sessionId=<?php if ( isset($user_session)) echo $user_session; ?>">
				<i class="fa fa-user fa-fw"></i> <?php if (isset($user_name)) echo $user_name; ?></a>
				</li>
				
				<li>
				<a href="<?php if (isset($siteURL)) echo $siteURL; ?>" target="_blank"><i class="fa fa-home fa-fw"></i> Lihat Situs</a>
				</li>
				<li class="divider"></li>
				<li><a href="?module=logout"><i class="fa fa-sign-out fa-fw"></i>
						Keluar</a>
				</li>
			</ul> <!-- /.dropdown-user -->
		</li>
		<!-- /.dropdown -->
	</ul>
	<!-- /.navbar-top-links -->

	<!-- Sidebar -->
	<div class="navbar-default sidebar" role="navigation">
		<div class="sidebar-nav navbar-collapse">
			<ul class="nav" id="side-menu">
				<!-- Dashboard -->
				<li><a
				<?php if (isset($pageTitle) && $pageTitle == 'dashboard') echo 'class="active"'; ?>
					href="?module=dashboard"> <i class="fa fa-dashboard fa-fw"></i>
						Dashboard
				</a>
				</li>

				<?php 

				if (isset($user_level) && $user_level == 'superadmin') {
			
			    ?>

				<!-- Tulisan -->
				<li <?php if ( $pageTitle == 'posts' || $pageTitle == 'postcats' || $pageTitle == 'tags') echo 'class="active"'; ?>><a
					href="#"><i class="fa fa-pencil fa-fw"></i> Tulisan<span
						class="fa arrow"></span> </a>
					<ul class="nav nav-second-level">
						<li><a href="?module=posts"> Semua Tulisan</a>
						</li>
						<li><a href="?module=posts&action=newPost&postId=0"> Tambah Tulisan</a>
						</li>
						<li><a href="?module=postcats"> Kategori</a>
						</li>
						<li><a href="?module=tags"> Label</a>
						</li>
						
					</ul> <!-- /.nav-second-level -->
				</li>

				<!-- Media -->
				<li <?php if ( $pageTitle == 'postimage' || $pageTitle == 'files') echo 'class="active"'; ?>><a href="#"><i class="fa fa-folder-open fa-fw"></i> Media<span
						class="fa arrow"></span> </a>
					<ul class="nav nav-second-level">
						<li><a href="?module=postimage"> Galeri Foto</a></li>
						<li><a href="?module=files"> Katalog Produk</a></li>
					</ul>
				</li>

				<!-- Halaman -->
				<li
				<?php if (isset($pageTitle) && $pageTitle == 'pages') echo 'class="active"'; ?>><a
					href="#"><i class="fa fa-file fa-fw"></i> Halaman<span
						class="fa arrow"></span> </a>
					<ul class="nav nav-second-level">
						<li><a href="?module=pages&action=listPages"> Semua Halaman</a>
						</li>
						<li><a href="?module=pages&action=newPage"> Tambah Halaman</a>
						</li>

					</ul> <!-- /.nav-second-level -->
				</li>

				<!-- Komentar -->
				<li><a
				<?php if (isset($pageTitle) && $pageTitle == 'comments') echo 'class="active"'; ?>
					href="?module=comments"><i class="fa fa-comment fa-fw"></i>
						Komentar</a>
				</li>

               <!-- Produk -->
				<li <?php if (isset($pageTitle) && $pageTitle == 'products') echo 'class="active"'; ?>><a
					href="#"><i class="fa fa-briefcase fa-fw"></i> Produk<span
						class="fa arrow"></span> </a>
					<ul class="nav nav-second-level">
						<li><a href="?module=products"> Kategori Produk</a>
						</li>
						<li><a href="?module=products&action=newProdCat"> Tambah Kategori
								Produk</a>
						</li>
					</ul> <!-- /.nav-second-level -->
				</li>

                <!-- Order -->
				<li <?php if ( $pageTitle == 'orders') echo 'class="active"'; ?>>
				<a <?php if ( $pageTitle == 'orders') echo 'class="active"'; ?>href="?module=orders"><i class="fa fa-shopping-cart fa-fw"></i>
						Order</a>
				</li>

				<!-- Pengiriman -->
				<li <?php if ( $pageTitle == 'districts' || $pageTitle == 'courier' || $pageTitle == 'provinces' ) echo 'class="active"'; ?>><a href="#"><i class="fa fa-truck fa-fw"></i> Pengiriman<span
						class="fa arrow"></span> </a>
					<ul class="nav nav-second-level">
					    <li><a href="?module=provinces"> Provinsi </a>
						</li>
						<li><a href="?module=districts"> Kota / Kabupaten</a>
						</li>
	
						<li><a href="?module=courier"> Jasa Pengiriman</a>
						</li>

					</ul> <!-- /.nav-second-level -->
				</li>

                <!-- Report -->
				<li
				<?php if (isset($pageTitle) && $pageTitle == 'report') echo 'class="active"'; ?>><a
					href="?module=report"><i class="fa fa-print fa-fw"></i> Laporan penjualan</a>
				</li>

               <!-- Tampilan -->
				<li <?php if ( $pageTitle == 'navigation' || $pageTitle == 'themes') echo 'class="active"'; ?>><a href="#"><i class="fa fa-desktop fa-fw"></i> Tampilan<span
						class="fa arrow"></span> </a>
					<ul class="nav nav-second-level">
						<li><a href="?module=navigation"> Menu</a>
						</li>
						<li><a href="?module=navigation&action=listMenuChilds"> Sub Menu</a>
						</li>
						<li><a href="?module=themes"> Template</a>
						</li>

					</ul> <!-- /.nav-second-level -->
				</li>
               
               <!-- Modul -->
				<li <?php if (isset($pageTitle) && $pageTitle == 'modules') echo 'class="active"'; ?>><a
					href="#"><i class="fa fa-gears fa-fw"></i> Modul<span
						class="fa arrow"></span> </a>
					<ul class="nav nav-second-level">
						
						
						<?php if (isset($modulSetup))  echo $modulSetup; ?>
						
						<li><a href="?module=modules"> Semua Modul</a></li>
						<li><a href="?module=modules&action=newModule"> Tambah Modul</a></li>
                        
                       

					</ul> <!-- /.nav-second-level -->
				</li>

                 <!-- Pengguna -->
				<li <?php if ( $pageTitle == 'users' || $pageTitle == 'customers' ) echo 'class="active"'; ?>><a href="#"><i class="fa fa-users fa-fw"></i> Pengguna<span
						class="fa arrow"></span> </a>
					<ul class="nav nav-second-level">
						<li><a href="?module=users"> Semua Staff</a></li>
						<li><a href="?module=users&action=newUser"> Tambah Staff</a></li>
                        <li><a href="?module=customers"> Kustomer</a></li>
					</ul> <!-- /.nav-second-level -->
				</li>

				<li><a href="?module=option"><i class="fa fa-wrench fa-fw"></i>
						Pengaturan</a>
				</li>

                <!-- Admin -->
				<?php } elseif (isset($user_level) && $user_level == 'admin') { ?>

				<!-- Tulisan Admin -->
				<li <?php if ( $pageTitle == 'posts' || $pageTitle == 'postcats' || $pageTitle == 'tags') echo 'class="active"'; ?>><a href="#"><i class="fa fa-pencil fa-fw"></i> Tulisan<span
						class="fa arrow"></span> </a>
					<ul class="nav nav-second-level">
						<li><a href="?module=posts"> Semua Tulisan</a>
						</li>
						<li><a href="?module=posts&action=newPost"> Tambah Tulisan</a>
						</li>
						<li><a href="?module=postcats"> Kategori</a>
						</li>
						<li><a href="?module=tags"> Label</a>
						</li>
						
					</ul> <!-- /.nav-second-level -->
				</li>

				<!-- Media -->
				<li <?php if ( $pageTitle == 'postimage' || $pageTitle == 'files') echo 'class="active"'; ?>><a href="#"><i class="fa fa-folder-open fa-fw"></i> Media<span
						class="fa arrow"></span> </a>
					<ul class="nav nav-second-level">
						<li><a href="?module=postimage"> Galeri Foto</a></li>
						<li><a href="?module=files">Download Katalog </a></li>
					</ul>
				</li>

               <!-- Halaman -->
				<li
				<?php if (isset($pageTitle) && $pageTitle == 'pages') echo 'class="active"'; ?>><a
					href="#"><i class="fa fa-file fa-fw"></i> Halaman<span
						class="fa arrow"></span> </a>
					<ul class="nav nav-second-level">
						<li><a href="?module=pages&action=listPages"> Semua Halaman</a>
						</li>
						<li><a href="?module=pages&action=newPage"> Tambah Halaman</a>
						</li>

					</ul> <!-- /.nav-second-level -->
				</li>
				
                <!-- Komentar -->
				<li><a
				<?php if (isset($pageTitle) && $pageTitle == 'comments') echo 'class="active"'; ?>
					href="?module=comments"><i class="fa fa-comment fa-fw"></i>
						Komentar</a>
				</li>
				
				<!-- Produk -->
				<li <?php if (isset($pageTitle) && $pageTitle == 'products') echo 'class="active"'; ?>><a href="#"><i class="fa fa-briefcase fa-fw"></i> Produk<span
						class="fa arrow"></span> </a>
					<ul class="nav nav-second-level">
						<li><a href="?module=products"> Kategori Produk</a>
						</li>
						<li><a href="?module=products&action=newProdCat"> Tambah Kategori
								Produk</a>
						</li>
					</ul> <!-- /.nav-second-level -->
				</li>

                 <!-- Order -->
				<li <?php if ( $pageTitle == 'orders') echo 'class="active"'; ?>>
				<a <?php if ( $pageTitle == 'orders') echo 'class="active"'; ?>href="?module=orders"><i class="fa fa-shopping-cart fa-fw"></i>
						Order</a>
				</li>
				
				<!-- Pengiriman -->
				<li <?php if ( $pageTitle == 'districts' || $pageTitle == 'courier' || $pageTitle == 'provinces') echo 'class="active"'; ?>><a href="#"><i class="fa fa-truck fa-fw"></i> Pengiriman<span
						class="fa arrow"></span> </a>
					<ul class="nav nav-second-level">
					    <li><a href="?module=provinces"> Provinsi </a>
						</li>
						<li><a href="?module=districts"> Kota / Kabupaten</a>
						</li>
						<li><a href="?module=courier"> Jasa Pengiriman</a>
						</li>

					</ul> <!-- /.nav-second-level -->
				</li>

				<li><a href="?module=report"><i class="fa fa-print fa-fw"></i>
						Laporan penjualan </a>
				</li>
 
				<li><a href="?module=users"><i class="fa fa-user fa-fw"></i>
						Profilku </a>
				</li>

                <!-- Editor -->
				<?php }elseif ( isset($user_level) && $user_level == 'editor' ) { ?>

				<!-- Tulisan Editor -->
				<li <?php if ( $pageTitle == 'posts' || $pageTitle == 'postcats' || $pageTitle == 'tags') echo 'class="active"'; ?> >
				<a href="#"><i class="fa fa-pencil fa-fw"></i> Tulisan<span
						class="fa arrow"></span> </a>
					<ul class="nav nav-second-level">
						<li><a href="?module=posts"> Semua Tulisan</a>
						</li>
						<li><a href="?module=posts&action=newPost"> Tambah Tulisan</a>
						</li>
						<li><a href="?module=postcats"> Kategori</a>
						</li>
						<li><a href="?module=tags"> Label</a>
						</li>
						

					</ul> <!-- /.nav-second-level -->
				</li>

				<!-- Media -->
				<li <?php if ( $pageTitle == 'postimage' || $pageTitle == 'files') echo 'class="active"'; ?>><a href="#"><i class="fa fa-folder-open fa-fw"></i> Media<span
						class="fa arrow"></span> </a>
					<ul class="nav nav-second-level">
						<li><a href="?module=postimage"> Galeri Foto</a></li>
						<li><a href="?module=files">Download Katalog </a></li>
					</ul>
				</li>

                <!-- Komentar -->
				<li><a
				<?php if (isset($pageTitle) && $pageTitle == 'comments') echo 'class="active"'; ?>
					href="?module=comments"><i class="fa fa-comment fa-fw"></i>
						Komentar</a>
				</li>
				
				<!-- Biodata Pengguna Editor -->
				<li><a href="?module=users"><i class="fa fa-user fa-fw"></i>
						Profilku </a>
				</li>

				<!--Author-->
				<?php  } elseif ( isset($user_level) && $user_level == 'author') { ?>

				<!-- Tulisan Author -->
				<li <?php if ( $pageTitle == 'posts' || $pageTitle == 'postcats' || $pageTitle == 'tags') echo 'class="active"'; ?> >
				<a href="#"><i class="fa fa-pencil fa-fw"></i> Tulisan<span
						class="fa arrow"></span> </a>
					<ul class="nav nav-second-level">
						<li><a href="?module=posts"> Semua Tulisan</a>
						</li>
						<li><a href="?module=posts&action=newPost"> Tambah Tulisan</a>
						</li>
					
					</ul> <!-- /.nav-second-level -->
				</li>

				<!-- Media Author -->
				<li <?php if ( $pageTitle == 'postimage') echo 'class="active"'; ?>><a href="#"><i class="fa fa-folder-open fa-fw"></i> Media<span
						class="fa arrow"></span> </a>
					<ul class="nav nav-second-level">
						<li><a href="?module=postimage"> Galeri Foto</a></li>
					</ul>
				</li>

				 <!-- Komentar -->
				<li><a <?php if (isset($pageTitle) && $pageTitle == 'comments') echo 'class="active"'; ?>
					href="?module=comments"><i class="fa fa-comment fa-fw"></i>
						Komentar</a>
				</li>
				
				<!-- Biodata Pengguna Author -->
				<li><a href="?module=users"><i class="fa fa-user fa-fw"></i>
						Profilku </a>
				</li>

				<?php  } else { ?>
				
				<!-- Kontributor -->
				
				<!-- Tulisan dari kontributor -->
				<li><a href="#"><i class="fa fa-pencil fa-fw"></i> Tulisan<span
						class="fa arrow"></span> </a>
					<ul class="nav nav-second-level">
						<li><a href="?module=posts"> Semua Tulisan</a>
						</li>
						<li><a href="?module=posts&action=newPost"> Tambah Tulisan</a>
						</li>
						
					</ul> <!-- /.nav-second-level -->
				</li>
				
				<!-- Biodata Pengguna Kontributor -->
				<li><a href="?module=users"><i class="fa fa-users fa-fw"></i>
						Profilku </a>
				</li>

				<?php } ?>

			</ul>
		</div>
		<!-- /.sidebar-collapse -->
	</div>
	<!-- /.navbar-static-side -->
</nav>