<section id="breadcrumbRow" class="row">
        <h2>checkout</h2>
        <div class="row pageTitle m0">
            <div class="container">
                <h4 class="fleft">Đặt hàng</h4>
                <ul class="breadcrumb fright">
                    <li><a href="index-2.html">home</a></li>
                    <li class="active">Đặt hàng</li>
                </ul>
            </div>
        </div>
    </section>
    
    <section class="row contentRowPad">
        <div class="container">
            <div class="row">
            <?php
              if(isset($_COOKIE['error'])){
              ?>
              <div class="alert alert-danger alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <?=$_COOKIE['error']?>
              </div>

              <?php
              }
              if(isset($_COOKIE['success'])){
              ?>
              <div class="alert alert-success alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <?=$_COOKIE['success']?>
              </div>

              <?php
              }
              ?>
            </div>
            <form action="dat-hang" method="post" role="form" class="row checkoutForm form-horizontal">
                <div class="row">
                    <div class="col-sm-12  orderSummaryRow">
                        <div class="row orderSummary m0">
                        <h4 class="heading">Thông tin giỏ hàng</h4>
                        <div class="row m0 orderSummaryInner table-responsive">
                            <table class="table table-condensed">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Sản phẩm</th>
                                        <th>Tổng tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $stt = 1;
                                        foreach($data->items as $id=>$sanpham):  ?>
                                    <tr>
                                        <td style="width:10%"><?=$stt++?></td>
                                        <td><?=$sanpham['item']->name?></td>
                                        <td> <?=number_format($sanpham['price'])?> vnđ</td>
                                    </tr>
                                    <?php endforeach?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td> </td>
                                        <td> </td>
                                        <td><?=number_format($data->totalPrice)?> vnđ</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row m0">
                    <div class="col-sm-6 " id="billingAddress">
                        <h4 class="heading">Thông tin khách hàng</h4>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="name">Họ tên:</label>
                            <div class="col-sm-10">
                            <input type="text" class="form-control" name="name" id="name" placeholder="Họ tên"  pattern=".{5,40}" required title="Họ tên ít nhất 5 kí tự">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="gender">Giới tính:</label>
                            <div class="col-sm-10">
                                <label class="radio-inline"><input type="radio" name="gender" required value="nữ">Nữ</label>
                                <label class="radio-inline"><input type="radio" name="gender" required value="nam">Nam</label>
                                <label class="radio-inline"><input type="radio" name="gender" required value="khac">Khác</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="email">Email:</label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control" name="email" id="email" placeholder="Email"  pattern=".{5,30}" required title="Email ít nhất 5 kí tự, không quá 30 kí tự">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="address">Địa chỉ:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="address" id="address" placeholder="Address"  pattern=".{10,}" required title="Địa chỉ ít nhất 10 kí tự">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="phone">Điện thoại:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="phone" id="phone" placeholder="Điện thoại"  pattern=".{10,20}" required title="Điện thoại ít nhất 10 kí tự, không quá 20 kí tự">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="note">Ghi chú:</label>
                            <div class="col-sm-10">
                                <textarea rows="5" class="form-control" name="note" id="note"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2">Phương thức thanh toán:</label>
                            <div class="col-sm-10">
                                <div class="checkbox">
                                    <label><input type="radio" name="payment-method" value="thanhtoanonline" checked>
                                            <b>Chuyển khoản online</b> 
                                            <br>Quý khách sẽ được di chuyển đến trang thanh toán trực tuyến
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label><input type="radio" name="payment-method"  value="thanhtoantructiep">
                                            <b>Tiền mặt </b>
                                            <br>Quý khách có thể thanh toán bằng tiền mặt tại cửa hàng Nhà Xinh, hoặc nhân viên Nhà Xinh sẽ đến tận nơi để thu tiền trước khi giao hàng (áp dụng cho đơn hàng trên 10 triệu đồng). 
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="phone"></label>
                            <div class="col-sm-10 checkbox">
                                <label><input type="checkbox" name="except" value="true" id="accept" required title="Bạn vui lòng đồng ý các điều khoản và chính sách bán hàng"><b>Tôi đã đọc và đồng ý *</b></label>
                            </div> 
                        </div>                
                    </div>
                    <div class="col-sm-6  orderSummaryRow">
                        <div class="row orderSummary m0">
                        <h4>Quý khách vui lòng duyệt qua các điều khoản và chính sách bán hàng của chúng tôi, và nhấn vào nút "đồng ý"</h4>
                            <div class="row m0 orderSummaryInner table-responsive">
                                <div style="height:500px; border: 1px; overflow: scroll-y; line-height: 1.5;">
                                    <b>Quy định đổi - trả sản phẩm</b><br>
                                    Nhà Xinh sẽ đổi hàng miễn phí cho Quý khách trong các trường hợp sau:<br>
                                    •	Không đúng chủng loại, mẫu mã như quý khách đặt hàng.<br>
                                    •	Không đủ số lượng, không đủ bộ như trong đơn hàng.<br>
                                    •	Tình trạng bên ngoài bị ảnh hưởng như bong tróc, bể vỡ xảy ra trong quá trình vận chuyển,…<br>
                                    •	Khách muốn đổi sản phẩm khác trước khi giao hàng<br>
                                    <b>Nhà Xinh sẽ tính phí vận chuyển và lắp đặt trong các trường hợp đổi hàng sau:</b><br>
                                    •	Quý khách chịu trách nhiệm về thông tin giao hàng nên trong trường hợp Sản phẩm không di chuyển được đến vị trí cần đặt ( do không vào được thang máy, thang bộ, cửa…) Nhà Xinh có thể đổi hàng cho Quý khách, nhưng sẽ tính phí vận chuyển và lắp đặt.<br>
                                    <b>Nhà Xinh sẽ không đổi trả hàng trong các trường hợp sau:</b><br>
                                    •	Quý khách không được trả hàng  ( chỉ được đổi hàng) trong trường hợp đã chuyển tiền đặt cọc<br>
                                    •	Quý khách muốn thay đổi chủng loại, mẫu mã nhưng không thông báo trước khi giao<br>
                                    •	Quý khách tự làm ảnh hưởng tình trạng bên ngoài như rách bao bì, bong tróc, bể vỡ,…<br>
                                    •	Quý khách vận hành không đúng chỉ dẫn gây hỏng hóc hàng hóa.<br>
                                    •	Quý khách không thực hiện các quy định theo yêu cầu để được hưởng chế độ bảo hành ( không gửi phiếu bảo hành về đúng nơi quy định trong thời gian quy định).<br>
                                    •	Quý khách đã kiểm tra và ký nhận tình trạng hàng hóa nhưng không có phản hồi trong vòng 24h kể từ lúc ký nhận hàng.<br>
                                    <b>Thời gian đổi hàng</b><br>
                                    •	Quy trình xử lý thủ tục đổi hoặc trả hàng được thực hiện trong vòng hai (02) tuần tính từ ngày nhận đủ thông tin và chứng từ từ khách hàng.<br>
                                    •	Việc đổi hàng, sửa chữa sẽ được thực hiện theo đúng quy định của Nhà Xinh<br>
                                    <b>Quy định thanh toán</b><br>
                                    •	Quý khách vui lòng thanh toán 100% giá trị đơn hàng trước khi giao hàng<br>
                                    <b>Quy định bảo hành</b><br>
                                    •	Nhà Xinh bảo hành một năm cho các trường hợp có lỗi về kỹ thuật trong quá trình sản xuất hay lắp đặt. Quý khách không nên tự sửa chữa mà hãy báo ngay cho Nhà Xinh qua hotline: 1800 7200.<br>
                                    •	Sau thời gian hết hạn bảo hành, nếu quý khách có bất kỳ yêu cầu hay thắc mắc thì vui lòng liên hệ với Nhà Xinh để được hướng dẫn và giải quyết các vấn đề gặp phải.<br>
                                    <b>Nhà Xinh sẽ không bảo hành trong các trường hợp sau</b><br>
                                    •	Sản phẩm được sử dụng không đúng quy cách của sổ bảo hành (được trao gửi khi quý khách mua sản phẩm) gây nên trầy xước, móp, dơ bẩn hay mất màu.<br>
                                    •	Sản phẩm bị biến dạng do môi trường bên ngoài bất bình thường (quá ẩm, quá khô, mối hay do tác động từ các thiết bị điện nước, các hóa chất hay dung môi khách hàng sử dụng không phù hợp.)<br>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row m0">
                    <div class="col-sm-12">
                        <button class="btn btn-primary filled btn-sm" name="btnCheckout" type="submit">Gửi đơn hàng</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
    <script>
        function echeck(str) {
			var at="@";
			var dot=".";
			var lat=str.indexOf(at);
			var lstr=str.length;
			var ldot=str.indexOf(dot);
			if (str.indexOf(at)==-1){			   
			   return false;
			}

			if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr){			   
			   return false;
			}

			if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr){			    
			    return false;
			}
	
			 if (str.indexOf(at,(lat+1))!=-1){			    
			    return false;
			 }

			 if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){			    
			    return false;
			 }

			 if (str.indexOf(dot,(lat+2))==-1){			    
			    return false;
			 }
		
			 if (str.indexOf(" ")!=-1){
			    return false;
			 }
	 		 return true;
		} 
    $(document).on('click', 'form button[type=submit]', function(e){
        var isValid = $(e.target).parents('form').isValid();
        if(!isValid) {
        e.preventDefault(); //prevent the default action
        }
        $("form").validate({
        rules: {
            name: "required",
            email: {
            required: true,
            email: true
            }
        },
        messages: {
            name: "Vui lòng điền email",
            email: {
                required: "Chúng tôi cần địa chỉ email của bạn để liên hệ với bạn",
                email: "Địa chỉ email của bạn phải đúng định dạng name@domain.com"
            }
        }
    })
    var except = document.getElementById('accept');
    except.oninvalid = function(event) {
        event.target.setCustomValidity('Bạn vui lòng đồng ý các điều khoản và chính sách bán hàng');
    }
});  

    </script>