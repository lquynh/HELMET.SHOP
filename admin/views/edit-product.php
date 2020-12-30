<?php
session_start();
if (!isset($_SESSION['login_email'])) header("../location:login.php");
if (!isset($_GET['id'])) header("location:views/manage-bill.php?status=0");
include_once('..\model\EditProductModel.php');
$model=new EditProductModel;

$product_code=$_GET['id'];
$product_change=$model->getProductsById($product_code);
$types=$model->getType();
?>

<head>
    <title>Sửa sản phẩm</title>
    <?php include_once('..\views\common.php'); ?>
    <style>
    select,input,textarea{margin-bottom:15px;}
    </style>
</head>

<body>
    <section id="container">
    <?php include_once 'header.php'?>
    <?php if (isset($_SESSION['login_email'])) include_once 'menu.php'; ?>
    <section id="main-content">
        <section class="wrapper">
            <div class="panel panel-body">
                <section class="content">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <b>Cập nhật sản phẩm "<?=$product_change->name?>"</b>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label class="col-sm-2">Chọn loại:</label>
                                <div class="col-sm-10">
                                    <select name="type" class="form-control" id="cate">
                                        <?php foreach ($types as $t): ?>
                                        <option value="<?=$t->cate_code?>"
                                        <?php if ($product_change->cate_code==$t->cate_code): ?> selected
                                        <?php endif?>><?=$t->name?></option>
                                        <?php endforeach?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2">Tên:</label>
                                <div class="col-sm-10">
                                    <input type="text" id="ten" class="form-control" name="name"
                                        placeholder="Nhập tên sản phẩm" value="<?=$product_change->name?>" required>
                                    <?php if (isset($_SESSION['message_ten'])): ?>
                                    <div style="color:red"><?php echo $_SESSION['message_ten'] ?></div>
                                    <?php endif?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2">Mô tả:</label>
                                <div class="col-sm-10">
                                    <textarea name="detail" id="mota" class="form-control" id="desc" required
                                        rows="5"><?=$product_change->description?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2">Đơn giá:</label>
                                <div class="col-sm-10">
                                    <input value="<?=$product_change->price?>" class="form-control" name="price" placeholder="Nhập giá tiền sản phẩm" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2">Tình trạng sản phẩm:</label>
                                <div class="col-sm-10">
                                    <select name="status" class="form-control">
                                    <option value="0" <?php if ($product_change->new == 0): ?> selected
                                        <?php endif?>>Cũ</option>
                                    <option value="1" <?php if ($product_change->new == 1): ?> selected
                                        <?php endif?>>Mới</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2">Hình ảnh:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="image" placeholder="Nhập tên ảnh" value="<?=$product_change->image?>" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2">Số lượng:</label>
                                <div class="col-sm-10">
                                    <input readonly type="number" class="form-control" name="quantity" placeholder="Nhập số lượng tồn" value="<?=$product_change->quantity_exist?>" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-10 col-sm-offset-2">
                                    <button type="submit" id="btnCapNhat" class="btn btn-primary" name="submit">Cập nhật</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </section>
    </section>
    <?php include_once('footer.php'); ?>
    </section>
    <script>
    $(document).ready(function() {

        let price = $("input[name='price']").val();
        let type = $("select[name='type']").val();
        let name = $("input[name='name']").val();
        let detail = $("textarea[name='detail']").val();
        let status = $("select[name='status']").val();
        let image = $("input[name='image']").val();
        let quantity = $("input[name='quantity']").val();

        let original_info=price+type+name+detail+status+image+quantity;
        let new_info="";

        $("#btnCapNhat").click(function(e) {
            price = $("input[name='price']").val();
            price = normalizePriceInput(price);
            $("input[name='price']").val(price);

            type = $("select[name='type']").val();
            name = $("input[name='name']").val();
            detail = $("textarea[name='detail']").val();
            status = $("select[name='status']").val();
            image = $("input[name='image']").val();
            quantity = $("input[name='quantity']").val();
            new_info=price+type+name+detail+status+image+quantity;

            if (original_info===new_info) {
                alert("Không có gì thay đổi!");
                return false;
            }

            let error = "";
            error += validateProductName(name);
            error += validateProductDetail(detail);
            error += validatePriceInput(price);
            // error += validateQuantity(quantity);
            error += image === '' ? "Đường dẫn ảnh không hợp lệ!\n" : "";

            if (error != "") {
                alert(error);
                return false;
            }

            $.ajax({
                type: "POST",
                url: "views/add-product.php",
                data: {
                    action: "editProduct",
                    product_code: "<?=$product_code?>",
                    type: type,
                    name: name,
                    detail: detail,
                    price: price,
                    status: status,
                    image: image,
                    quantity: quantity
                },
                success: function(res) {
                    if (res !== 'ok') {
                        alert(res);
                        return false;
                    }
                    alert("Cập nhật sản phẩm thành công!");
                    location.href = 'views/list-products.php?type='+type;
                }
            });
        });
    });
    </script>
</body>

</html>