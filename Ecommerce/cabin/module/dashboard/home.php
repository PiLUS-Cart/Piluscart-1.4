<?php

if (!defined('PILUS_SHOP'))
{
	header("Location: ../../../cabin/403.php");
	exit;
}

$dbh = new Pldb;

?>

<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">
				<?php if (isset($views['pageTitle'])) echo $views['pageTitle']; ?>
			</h1>
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->

<!-- /.row kolom kotak xs-9 -->
	<div class="row">
		<div class="col-lg-3 col-md-6">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<div class="row">
						<div class="col-xs-3">
							<i class="fa fa-shopping-cart fa-5x"></i>
						</div>
						<div class="col-xs-9 text-right">
							<?php if (isset($views['countOrders']) && $views['countOrders'] >=0 ) { ?>
							<div class="huge">
								<?php echo htmlspecialchars($views['countOrders']); ?>
							</div>
							<div>
								<?php if ($views['countOrders'] > 0 ) { 
									echo  "Order Baru!";
								} else { echo "kosong";
}?>
							</div>
							<?php } ?>
						</div>
					</div>
				</div>
				<a <?php if ($views['countOrders'] == 0 ) { ?> href="#"
				<?php } else { ?> href="index.php?module=orders" <?php } ?>>
					<div class="panel-footer">
						<?php if ($views['countOrders'] > 0 ) { ?>
						<span class="pull-left">Details</span> <span class="pull-right"> <i
							class="fa fa-arrow-circle-right"></i>
						</span>
						<div class="clearfix"></div>

						<?php } else { ?>

						<span class="pull-left">Belum ada Order</span> <span
							class="pull-right"> <i class="fa fa-arrow-circle-right"></i>
						</span>
						<div class="clearfix"></div>

						<?php } ?>
					</div>
				</a>
			</div>
		</div>
		<div class="col-lg-3 col-md-6">
			<div class="panel panel-green">
				<div class="panel-heading">
					<div class="row">
						<div class="col-xs-3">
							<i class="fa fa-briefcase fa-5x"></i>
						</div>
						<div class="col-xs-9 text-right">
							<?php if (isset($views['countProducts']) && $views['countProducts'] >= 0) { ?>
							<div class="huge">
								<?php echo htmlspecialchars($views['countProducts']); ?>
							</div>
							<div>
								<?php if ($views['countProducts'] > 0 ) { 
									echo  "Produk";
								} else { echo "kosong";
}?>
							</div>
							<?php } ?>
						</div>
					</div>
				</div>
				
				<a <?php if ($views['countProducts'] == 0 ) { ?> href="#"
				<?php }else { ?> href="index.php?module=products" <?php } ?>>
				
					<div class="panel-footer">
						<?php if ($views['countProducts'] > 0 ) { ?>
						<span class="pull-left">Details</span> <span class="pull-right"> <i
							class="fa fa-arrow-circle-right"></i>
						</span>
						<div class="clearfix"></div>

						<?php }else{?>

						<span class="pull-left">Produk kosong</span> <span
							class="pull-right"> <i class="fa fa-arrow-circle-right"></i>
						</span>
						<div class="clearfix"></div>

						<?php } ?>
					</div>
				</a>
			</div>
		</div>
		<!-- panel yellow -->
		<div class="col-lg-3 col-md-6">
			<div class="panel panel-yellow">
				<div class="panel-heading">
					<div class="row">
						<div class="col-xs-3">
							<i class="fa fa-comments fa-5x"></i>
						</div>
						<div class="col-xs-9 text-right">
							<?php if (isset($views['countComments']) && $views['countComments'] >= 0) { ?>
							<div class="huge">
								<?php echo htmlspecialchars($views['countComments']); ?>
							</div>
							<div>
								<?php if ($views['countComments'] > 0 ) { 
									echo  "komentar!";
								} else { echo "kosong";
}?>
							</div>
							<?php } ?>
						</div>
					</div>
				</div>
				<a <?php if ($views['countComments'] == 0 ) { ?> href="#"
				<?php }else { ?> href="index.php?module=comments" <?php } ?>>
					<div class="panel-footer">
						<?php if ($views['countComments'] > 0 ) { ?>
						<span class="pull-left">Details</span> <span class="pull-right"> <i
							class="fa fa-arrow-circle-right"></i>
						</span>
						<div class="clearfix"></div>
						<?php }else { ?>
						<span class="pull-left">Tidak ada komentar</span> <span
							class="pull-right"> <i class="fa fa-arrow-circle-right"></i>
						</span>
						<div class="clearfix"></div>
						<?php } ?>
					</div>
				</a>
			</div>
		</div>

		<!-- Panel Merah -->
		<div class="col-lg-3 col-md-6">
			<div class="panel panel-red">
				<div class="panel-heading">
					<div class="row">
						<div class="col-xs-3">
							<i class="fa fa-envelope fa-5x"></i>
						</div>
						<div class="col-xs-9 text-right">
							<?php if (isset($views['countMessages']) && $views['countMessages'] >= 0) { ?>
							<div class="huge">
								<?php echo htmlspecialchars($views['countMessages']); ?>
							</div>
							<div>
								<?php if ($views['countMessages'] > 0 ) { 
									echo  "pesan baru";
								} else { echo "kosong";
}?>
							</div>
							<?php } ?>
						</div>
					</div>
				</div>
				<a <?php if ($views['countMessages'] == 0 ) { ?> href="#"
				<?php }else { ?> href="index.php?module=inbox" <?php } ?>>
					<div class="panel-footer">
						<?php if ($views['countMessages'] > 0 ) { ?>
						<span class="pull-left">Details</span> <span class="pull-right"> <i
							class="fa fa-arrow-circle-right"></i>
						</span>
						<div class="clearfix"></div>
						<?php } else {?>
						<span class="pull-left">Tida ada pesan</span> <span
							class="pull-right"> <i class="fa fa-arrow-circle-right"></i>
						</span>
						<div class="clearfix"></div>
						<?php } ?>
					</div>
				</a>
			</div>
		</div>
	</div>
	<!-- /.row -->

	<div class="row">

		<div class="col-lg-12">

			<div class="panel panel-info">

				<div class="panel-heading">
					<i class="fa fa-bar-chart-o fa-fw"></i>
					<?php if (isset($views['stats'])) echo $views['stats'];  ?>
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<div class="table-responsive">

                             <!-- Diagram batang 3D -->
                             <table id="mytable" class="table">
                             
                             <tr><th>Tanggal</th>
                      <?php 
                        
                      $tgl1=date("Y-m-d");
                      $tgl_bawah = strtotime("-1 week +1 day",strtotime($tgl1));
                      $hasil_tgl_bawah = date('Y-m-d', $tgl_bawah);
                      for ($i2=0; $i2 <= 6; $i2++){
                      $urutan = strtotime("+$i2 day",strtotime($hasil_tgl_bawah));
                      $hasil_urutan = date('Y-m-d', $urutan);
                      
                      ?>
                      
                       <th>  <?php echo tgl_Lokal($hasil_urutan); ?></th>
                      
                      <?php } ?>
                      
                      </tr><tr><td>Visitor</td>
                      
                      <?php 
                     
                      $tgl2=date("Y-m-d");
                      $tgl_bawah2 = strtotime("-1 week +1 day",strtotime($tgl2));
                      $hasil_tgl_bawah2 = date('Y-m-d', $tgl_bawah2);
                      for ($i=0; $i <= 6; $i++){
                      	$tgl_pengujung = strtotime("+$i day",strtotime($hasil_tgl_bawah2));
                      	$hasil_tgl_pengujung = date('Y-m-d', $tgl_pengujung);
                      	$sth = $dbh -> query("SELECT statistic_id, ip, browser, date_visit, time_visit, hits, online 
                      			             FROM pl_statistics WHERE date_visit='$hasil_tgl_pengujung' GROUP BY ip");
                      	$sql_tgl_pengunjung = $sth -> rowCount(); ?>
                      
                      <td align='center'><font color='#afd8f8'><b><?php echo $sql_tgl_pengunjung; ?></b></td>
                    
                     <?php  }  ?>
                      
                      </tr><tr><td>Hits</td>
                      
                      <?php 
                      $tgl3=date("Y-m-d");
                      $tgl_bawah3 = strtotime("-1 week +1 day",strtotime($tgl3));
                      $hasil_tgl_bawah3 = date('Y-m-d', $tgl_bawah3);
                      for ($i3=0; $i3 <= 6; $i3++){
                      	$tgl_hits = strtotime("+$i3 day",strtotime($hasil_tgl_bawah3));
                      	$hasil_tgl_hits = date('Y-m-d', $tgl_hits);
                      	$stmt = $dbh -> query("SELECT SUM(hits) AS hitstoday FROM pl_statistics WHERE date_visit = '$hasil_tgl_hits' GROUP BY date_visit");
                      	$hits = $stmt -> fetch(); ?>
                      
                      
                     <td align='center'><font color='#f6bd0f'><b><?php echo $hits['hitstoday']; ?></b></td>
                      
                      <?php } ?>
                      
                     </tr></table>
                   
					</div>
					<!-- /table-responsive -->

				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->
	
	<div class="row">
	    <div class="col-lg-6">
	       <div class="panel panel-default">
	           <div class="panel-heading">
	           <i class="fa fa-bar-chart-o fa-fw"></i>
                    <?php if (isset($views['statistic_summary'])) echo $views['statistic_summary'];  ?>
               </div>
               
               <div class="panel-body">
                    <div class="table-responsive">
                         <table class="table table-striped table-bordered table-hover">
                            <tr><td> Pengunjung hari ini </td><td> : <?php echo $views['visitor_today']; ?> </td></tr>
                            <tr><td> Total pengunjung </td><td> : <?php echo $views['total_visitor']; ?></td></tr>
                            <tr><td>Hits hari ini </td><td> : <?php echo $views['hits_hari_ini']; ?> </td></tr>
                            <tr><td>Total Hits </td><td> :   <?php echo $views['total_hits']; ?>  </td></tr>        
                        </table>
                    </div>
                            <!-- /.table-responsive -->
               </div>
                        <!-- /.panel-body -->
	       </div>
	              <!-- /.panel default -->
	    </div>
	        <!-- /.col-lg-6 -->
	        
	        <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                        <i class="fa fa-pencil fa-fw"></i>
                            <?php if (isset($views['new_post'])) echo $views['new_post']; ?> (<?php echo $views['totalRows']; ?>)
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Judul</th>
                                            <th>Tgl.Posting</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $no = 0;
                                        	
                                        foreach ( $views['articles'] as $article ) :
                                        	
                                        $no++;
                                        ?>
                                          <tr>
                                               <td><?php echo $no; ?></td>
                                               <td>
                                               <a href="index.php?module=posts&action=editPost&postId=<?php echo $article -> getId(); ?>"><?php echo htmlspecialchars($article -> getPost_Title()); ?></a>
                                               </td>
                                               <td><?php echo htmlspecialchars(tgl_Lokal($article -> getPost_Date())); ?></td>
                                          </tr>
                                          
                                          <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-6 -->
	</div>
	<!-- /.row -->
</div>
<!-- /#page-wrapper -->
<script type="text/javascript">
	$('#mytable').convertToFusionCharts({
		swfPath: "Charts/",
		type: "MSColumn2D",
		data: "#mytable",
		dataFormat: "HTMLYTable",
		width: "900",
		height: "400"
	});
</script>