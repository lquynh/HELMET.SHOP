<?php
if(session_status() !== PHP_SESSION_ACTIVE) session_start();
include_once 'model/DetailModel.php';
$d=new DetailModel;
$districts=$d->getDistricts();
?>
<section class="main-container col2-right-layout">
  <div class="main container">
    <div class="row">
      <div class="col-main col-sm-12 col-xs-12">
        <div class="page-content checkout-page"><div class="page-title">
          <h2>Thông tin người nhận</h2>
          <?php if(isset($_SESSION['success'])):?>
            <div class="alert alert-success">
                <?=$_SESSION['success']; unset($_SESSION['success'])?>
            </div>
          <?php endif?>
          <?php if(isset($_SESSION['error'])):?>
            <div class="alert alert-danger">
                <?=$_SESSION['error']; unset($_SESSION['error'])?>
            </div>
          <?php endif?>
        </div>
		<form method="POST" >
			<div class="box-border">
				<ul>
					<li class="row"> 
						<div class="col-xs-6">
							<label for="address" class="required">Tên người nhận</label>
							<input type="text" class="input form-control" name="name" id="name" value="<?=$_SESSION['customer']->name?>"required minlength="5" maxlength="20">
							
							<table style='width:100%;margin:15px 0px 15px 0px;padding:0px;'>
								<tr>
									<td><label for="address" class="required">Số nhà</label></td>
									<td><input type="text" class="input form-control" name="address" id="address" value="<?=$_SESSION['customer']->address?>"required  minlength="8" maxlength="100"></td>
								</tr>
								<tr>
									<td><label for="district" class="required">Quận/Huyện</label></td>
									<td>
									
										<select id='district_code' name='district_code' style='width:100%;'>
											<?php foreach($districts as $d):?>
											<?php if ($d->district_code==$_SESSION['customer']->district_code) { ?>
												<option selected='selected' value='<?=$d->district_code?>'><?=$d->name?></option>
											<?php } else { ?>
												<option value='<?=$d->district_code?>'><?=$d->name?></option>
											<?php } ?>
											<?php endforeach?>
										</select>
									</td>
								</tr>
								<tr>
									<td><label>Tỉnh/TP</label></td>
									<td>Hồ Chí Minh</td>
								</tr>
							</table>
							
							<label for="phone" class="required">Số điện thoại người nhận</label>
							<input type="text" class="input form-control" name="phone" id="phone" value="<?=$_SESSION['customer']->phone?>"required  minlength="8" maxlength="11">

							<label for="date_receive" class="required">Ngày nhận hàng (đề nghị)</label>
							<input type="text" class="input form-control" name="date_receive" id="date_receive" required minlength="8" maxlength="20">
							
						</div><!--/ [col] -->
					</li><!-- / .row -->
					<li>
						<button type="submit" name="btnCheckout" class="button" id="btnDH"><i class="fa fa-angle-double-right"></i>&nbsp; <span>Đặt hàng</span></button>
					</li>
					<?php 
						if(isset($_SESSION['addresserror'])){
							echo "<div style='color:red;' class='input-tb'>".$_SESSION['addresserror']."</div>";
						}
					?>	
				</ul>
			</div>
		</form>
        </div>
      </div>
      
    </div>
  </div>
  </section>
  <?php
    if(isset($_SESSION['addresserror'])){
      unset($_SESSION['addresserror']);
    }
  ?>
  <!-- Main Container End -->
  
<script>
$(document).ready(function() {
	$("#date_receive").datepicker({
		dateFormat: "d-m-yy",
		minDate: 0
	});
	Date.prototype.addDays = function(days) {
		var date = new Date(this.valueOf());
		date.setDate(date.getDate() + days);
		return date;
	}

	let now = new Date();
	let receive_date = now.addDays(3);
	
	let date_receive = document.getElementById("date_receive");
	date_receive.value = receive_date.getDate()+"-"+(receive_date.getMonth() + 1)+"-"+receive_date.getFullYear();
});
</script>