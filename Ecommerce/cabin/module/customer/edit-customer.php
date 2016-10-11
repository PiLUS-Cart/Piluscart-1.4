<?php 

if (!defined('PILUS_SHOP'))
{
	header("Location: ../../../studio/403.php");
	exit;
}

?>

<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header"><?php  echo $views['pageTitle']; ?></h1>
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<?php 
	if (isset($views['errorMessage'])) { ?>

	<div class="alert alert-danger ">
		<h4>Error!</h4>
		<p>
			<?php echo $views['errorMessage']; ?>
			<button type="button" class="btn btn-danger"
				onClick="self.history.back();">Ulangi</button>
		</p>
	</div>
	
	<?php } else { ?>
	
	<!-- /.row -->
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<?php  echo $views['pageTitle']; ?>

				</div>

				<div class="panel-body">
					<div class="row">
						<div class="col-lg-6">

					<form method="post" action="index.php?module=customers&action=<?php echo $views['formAction']; ?>" role="form">
								<?php if (isset($views['customerId'])) { ?>
						<input type="hidden" name="customer_id" value="<?php if (isset($views['customerId'])) echo htmlspecialchars($views['customerId']); ?>" />
	                           <?php  } ?>
	                           
	                           <?php if (isset($views['sessionId'])) {?>
						<input type="hidden" name="customer_session" value="<?php if ( isset($views['sessionId']))  echo htmlspecialchars($views['sessionId']); ?>" />
								
								<?php } ?>
								
						<!-- customer_fullname -->
						<div class="form-group">
							<label>Nama Lengkap</label> 
						    <input type="text" name="fullname" class="form-control" placeholder="fullname" value="<?php if (isset($views['Customer'])) echo htmlspecialchars($views['Customer'] -> getCustomerFullname()); ?>" required>
						</div>
								
						<!-- customer_email -->
								<div class="form-group">
									<label>Alamat Surat Elektronik(E-mail)</label> <input
										type="text" name="email" class="form-control"
										placeholder="E-mail address"
										value="<?php if (isset($views['Customer']))  echo htmlspecialchars($views['Customer'] -> getCustomerEmail());  ?>"
										required>
								</div>
								
								<!-- customer_pass -->
								<div class="form-group">
									<label>Kata Sandi</label> <input type="password"
										name="password" class="form-control" placeholder="password">
								</div>
								
								<!-- confirm_customer_password -->
								<?php if (isset($views['Customer']) && !$email = $views['Customer'] -> getCustomerEmail()) { ?>
								<div class="form-group">
									<label>Ketik ulang kata sandi</label> <input type="password"
										name="confirmed" class="form-control"
										placeholder="confirm password">
								</div>
								<?php } ?>

                                <!-- customer_address -->
                                <div class="form-group">
									<label>Alamat</label> 
									<input type="text" name="address" class="form-control"
										placeholder="Alamat"
										value="<?php if (isset($views['Customer']))  echo htmlspecialchars($views['Customer'] -> getCustomerAddress()); ?>"
										required>
								</div>
								
								<!-- customer_phone -->
								<div class="form-group">
									<label>Telepon</label> 
									<input type="text" name="phone" class="form-control" placeholder="phone" value="<?php if (isset($views['Customer']))  echo htmlspecialchars($views['Customer'] -> getCustomerPhone()); ?>" required>
								</div>
								
								<!-- district -->
								<div class="form-group">
								     <?php if (isset($views['district'])) echo $views['district'];  ?>
								</div>
								
								<!-- Jasa Pengiriman -->
								<div class="form-group">
								     <?php if (isset($views['shipping'])) echo $views['shipping']; ?>
								</div>
								
								<!-- customer_type -->
								<div class="form-group">
									 <label>Tipe Kustomer</label>
                                         <div class="radio">
                                            <label>
                                            <input type="radio" name="customer_type" id="optionsRadios1" value="member" <?php if ($views['Customer'] -> getCustomerType() == 'member') echo 'checked="checked"'; ?>>Member
                                            </label>
                                          </div>
                                       <div class="radio">
                                           <label>
                                           <input type="radio" name="customer_type" id="optionsRadios2" value="guest" <?php if ($views['Customer'] -> getCustomerType() == 'guest') echo 'checked="checked"'; ?>>Guest
                                           </label>
                                        </div>    
								</div>

								<input type="submit" class="btn btn-primary" name="saveCustomer" value="Simpan" />

								<button type="button" class="btn btn-danger"
									onClick="self.history.back();">Batal</button>

							</form>
						</div>
						<!-- /.col-lg-6 (nested) -->
						<div class="col-lg-6"></div>
						<!-- /.col-lg-6 (nested) -->
					</div>
					<!-- /.row (nested) -->
				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<?php } ?>
	<!-- /.row -->
</div>
<!-- #Page-Wrapper -->