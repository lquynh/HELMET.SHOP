<?php
	if (isset($_SESSION['login_email']))
		header("location:manage-bill.php?status=0");
	else
		header("location:login.php");
?>
<h3 style="margin-top: 0%;padding-left:15%;">BÁO CÁO DOANH THU TỪ NGÀY <?php if(isset($newStart)) echo($newStart)?> ĐẾN NGÀY <?php if(isset($newEnd)) echo($newEnd)?></h3>
              <table class="table table-bordered" style="width:80%;margin:0 auto;">
                <thead>
                  <tr>
                    <th class="col-sm-1">STT</th>
                    <th class="col-sm-2">Mã Hàng:</th>
                    <th class="col-sm-2">Ngày:</th>
                    <th class="col-sm-1">Số Lượng</th>
                    <th class="col-sm-2">Đơn Giá:</th>
                    <th>Thành Tiền:</th>
                  </tr>
                </thead>             
                <tbody>                
                  <?php                    
                      $stt=0;
                      $total=0;
                      foreach($data as $t):
                  ?>
                  <tr>
                    <td><?=$stt++?></td>
                    <td><?= $t->id_product?></td>
                    <td><?=date("d-m-Y", strtotime($t->created_at))?></td>
                    <td><?=$t->quantity_out?></td>
                    <td><?=number_format($t->value,2)?></td>
                    <td><?=number_format($t->value*$t->quantity_out,2)?></td>
                    <?php $total+=$t->value*$t->quantity_out?>
                  </tr>
                  
                      <?php endforeach?>
                      <tr>
                    <td colspan="6" style="padding-left:60%;font-weight: bold;">Tổng Tiền: <?=number_format($total,2)?></td>                  
                  </tr>                    
                </tbody>
               
              </table>