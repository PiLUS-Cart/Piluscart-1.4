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
	
	<div class="row">

		<div class="col-lg-12">

			<div class="panel panel-info">

				<div class="panel-heading">
					<i class="fa fa-bar-chart-o fa-fw"></i>
					<?php if (isset($views['visitor_stat'])) echo $views['visitor_stat'];  ?>
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
			<!-- /.panel info -->
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->
	
	<div class="row">
	
	        <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                        <i class="fa fa-pencil fa-fw"></i>
                            <?php if (isset($views['new_articles'])) echo $views['new_articles']; ?> (<?php echo $views['totalRows']; ?>)
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