<?php
include_once 'model/DetailModel.php';
$d=new DetailModel;
$districts=$d->getDistricts();
?>
<section class="main-container col1-layout">
    <div class="main container">
        <div class="page-content">
            <div class="account-login">
                <div class="box-authentication">
                    <div class="container-fluid">
                        <h4>Đăng nhập</h4>
                        <p class="before-login-text">Đăng nhập vào tài khoản</p>
                        <label for="emmail_login">Tên đăng nhập<span class="required">*</span></label>
                        <input id="emmail_login" class="form-control" name="email" minlength="4" maxlength="50">
                        <label for="password_login">Mật khẩu<span class="required">*</span></label>
                        <input id="password_login" type="password" class="form-control" name="pass" minlength="6"
                            maxlength="10">
                        <button class="button" id='btnDangNhap' value="login" name="login"><i
                                class="fa fa-lock"></i>&nbsp; <span>Đăng nhập</span></button>
                    </div>
                </div>
                <div class="box-authentication">
                    <h4>Đăng ký</h4>
                    <p>Tạo tài khoản mới</p>
                    <label for="user_name_register">Tên đăng nhập<span class="required">*</span></label>
                    <input id="user_name_register" type="text" class="form-control" name="user_name_regis" minlength="4"
                        maxlength="50">
                    <label for="fullname_register">Họ tên<span class="required">*</span></label>
                    <input id="fullname_register" type="text" class="form-control" name="fullname_regis" minlength="5"
                        maxlength="20">
                    <label for="gender_register">Giới tính<span class="required">*</span></label><br>
                    <input type="radio" name="gender" value="Nam" checked style="width:20px;">Nam
                    <input type="radio" name="gender" value="Nữ" style="width:20px;margin-left:30px;">Nữ<br>
                    <label for="address_register">Số nhà + tên đường<span class="required">*</span></label>
                    <input id="address_register" type="text" class="form-control" name="address_regis" minlength="5"
                        maxlength="50">
                    <label for="district_register">Quận/Huyện<span class="required">*</span></label>&nbsp;
                    <select id='district_register' name='district_regis'>
                        <?php foreach($districts as $d) { ?>
                        <option value='<?=$d->district_code?>'><?=$d->name?></option>
                        <?php } ?>
                    </select>
                    <br />
                    <label for="phone_register">Số điện thoại<span class="required">*</span></label>
                    <input id="phone_register" type="text" class="form-control" name="phone_regis" minlength="10"
                        maxlength="10">
                    <label for="pass_register">Mật khẩu<span class="required">*</span></label>
                    <input id="pass_register" type="password" class="form-control" name="pass_regis" minlength="6"
                        maxlength="10">
                    <label for="email_register">Email<span class="required">*</span></label>
                    <input id="email_register" type="email" class="form-control" name="email_regis" minlength="10"
                        maxlength="50">
                    <button class="button" id='btnDangKy'><i class="fa fa-user"></i>&nbsp; <span>Đăng ký</span></button>
                    <div class="register-benefits">
                    </div>
                </div>
            </div>

        </div>
</section>
<script src="../helmet_shop/admin/libraries/js/my-utilities.js"></script>
<div id='loading-screen'
    style='z-index:9999;display:none;background:#000;opacity:0.7;color:#fff;position:fixed;top:0;left:0;width:100%;height:100%;text-align:center;margin:0 auto;'>
    <span style='position:absolute;top:50%;'>Đang xử lý...</span></div>

<script>
$(document).ready(function() {
    let loading_on = () => $("#loading-screen").css("display", "block");
    let loading_off = () => $("#loading-screen").css("display", "none");

    $("#btnDangKy").click(function(e) {
        let error = "";

        let username = $("#user_name_register").val();
        let name = $("#fullname_register").val();
        let gender = $("input[name='gender']:checked").val();
        let address = $("#address_register").val();
        let district = $("#district_register").val();
        let phone = $("#phone_register").val();
        let password = $("#pass_register").val();
        let email = $("#email_register").val();

        error += validateUsername(username);
        error += validateName(name);
        error += validateAddress(address);
        error += validatePhone(phone);
        error += validatePassword(password);
        error += validateEmail(email);

        if (error != "") {
            alert(error);
            return false;
        }

        loading_on();
        $.ajax({
            type: "POST",
            url: "login.php",
            data: {
                action: "register",
                user_name_regis: username,
                fullname_regis: name,
                gender: gender,
                email_regis: email,
                address_regis: address,
                district_regis: district,
                phone_regis: phone,
                pass_regis: password
            },
            success: function(res) {
                loading_off();
                if (res != 'ok') {
                    alert(res);
                    return false;
                }
                alert("Đăng ký thành công!");
                location.reload();
            }
        });
    });

    $("#btnDangNhap").click(function(e) {
        let error = "";
        let email = $("#emmail_login").val();
        let password = $("#password_login").val();
        error += email==='' ? "Email/Tên đăng nhập không được bỏ trống!\n":"";
        error += password==='' ? "Mật khẩu không được bỏ trống!\n":"";

        if (error !== '') {
            alert(error);
            return false;
        }

        $.ajax({
            type: "POST",
            url: "login.php",
            data: {
                action: "login",
                email: email,
                pass: password
            }, success: function(res) {
                if (res !== 'ok') {
                    alert(res);
                    return false;
                }
                location.href='index.php';
            }
        });
    });
});
</script>