<?php
    session_start();
    include_once('..\model\AddProductModel.php');
    include_once('..\model\EditProductModel.php');
	if (!isset($_SESSION['login_email'])) header("../location:login.php");
    $model_add = new AddProductModel;
    $model_edit=new EditProductModel;
    $types=$model_edit->getType();
    $distributor=$model_add->getDistributor();

    if(isset($_POST['action']) && $_POST['action']=='addProduct'){
        $id_type=$_POST['type'];
        $product_code=$_POST['code'];
        $name=$_POST['name'];
        $detail=$_POST['detail'];
        $value=$_POST['price'];
        $img=$_POST['image'];
        $id_distributor=$_POST['distributor'];
        $quantity_exist=$_POST['quantity'];
        $date=date('Y-m-d');

        if ($model_add->checkProduct($product_code,$name)) {
            echo "Mã sản phẩm hoặc Tên sản phẩm đã tồn tại!";
            return false;
        }

        $check=$model_add->insertProduct($id_type,$product_code,$name,$detail,$value,$img,$id_distributor,$quantity_exist,$date);
        if(!$check) {
            echo "Cập nhật thất bại!";
            return false;
        }

        echo "ok";
        return true;
    }

    if (isset($_POST['action']) && $_POST['action']=='editProduct') {
        $id_category = $_POST['type'];
        $product_code=$_POST['product_code'];
        $name = $_POST['name'];
        $detail = $_POST['detail'];
        $price = $_POST['price'];
        $image = $_POST['image'];
        $quantityExist = $_POST['quantity'];
        $status = $_POST['status'];
        $date = date("Y-m-d");

        if ($model_edit->checkProductNameEdit($product_code,$name)) {
            echo "Tên sản phẩm đã tồn tại!";
            return false;
        }

        if (!$model_edit->updateProduct($product_code,$id_category,$name,$detail,$price,$image,$status,$quantityExist,$date)) {
            echo "Cập nhật sản phẩm thất bại!";
            return false;
        }

        echo "ok";
        return true;
    }
?>

<head>
    <title>Thêm sản phẩm</title>
    <?php include_once('..\views\common.php'); ?>
    <style>
    select,input,textarea{margin-bottom:15px;}
    </style>
</head>

<body>
    <section id="container">
        <?php include_once('header.php')?>
        <?php  if(isset($_SESSION['login_email'])) include_once('menu.php'); ?>
        <section id="main-content">
            <div>
                <section class="wrapper">
                    <div class="panel panel-body">
                        <section class="content">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <b>Thêm sản phẩm mới</b>
                                </div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <label class="col-sm-2">Chọn loại:</label>
                                        <div class="col-sm-10">
                                            <select name="type" class="form-control">
                                                <?php
                                                foreach($types as $t) {
                                                    echo "<option value=".$t->cate_code;
                                                    if ($t->cate_code == $_GET['type'])
                                                        echo " selected='selected'";
                                                    echo ">$t->name</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2">Mã sản phẩm:</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="code" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2">Tên sản phẩm:</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="name" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2">Mô tả:</label>
                                        <div class="col-sm-10">
                                            <textarea name="detail" class="form-control" id="desc" required></textarea>
                                            <!-- <script>
                                                CKEDITOR.replace('desc')
                                                </script> -->
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2">Đơn giá:</label>
                                        <div class="col-sm-10">
                                            <input min="0" max="10000" class="form-control" name="price"
                                                placeholder="Nhập giá sản phẩm" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2">Số lượng:</label>
                                        <div class="col-sm-10">
                                            <input readonly value="0" type="number" min="0" class="form-control" name="quantity" placeholder="Nhập số lượng tồn">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2">Ảnh:</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="image"
                                                placeholder="Nhập đường dẫn ảnh" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2">Nhà cung cấp:</label>
                                        <div class="col-sm-10">
                                            <select name="distributor" class="form-control">
                                                <?php foreach($distributor as $d):?>
                                                <option value="<?= $d->supp_code?>"><?= $d->name?></option>
                                                <?php endforeach?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-10 col-sm-offset-2">
                                            <button type="submit" id='btnAddProduct' class="btn btn-primary"
                                                name="submit">Thêm</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </section>
            </div>
        </section>
        <?php include_once('footer.php'); ?>
    </section>
    <script>
    $(document).ready(function() {
        $("#btnAddProduct").click(function(e) {
            let price = $("input[name='price']").val();
            price = normalizePriceInput(price);
            $("input[name='price']").val(price);

            let type = $("select[name='type']").val();
            let code = $("input[name='code']").val();
            let name = $("input[name='name']").val();
            let detail = $("textarea[name='detail']").val();
            let image = $("input[name='image']").val();
            let quantity = $("input[name='quantity']").val();
            let distributor = $("select[name='distributor']").val();

            let error = "";
            error += validateProductCode(code);
            error += validateProductName(name);
            error += validateProductDetail(detail);
            error += validatePriceInput(price);
            // error += validateQuantity(quantity);
            error += image === '' ? "Đường dẫn ảnh không hợp lệ!\n" : "";

            if (error !== '') {
                alert(error);
                return false;
            }

            $.ajax({
                type: "POST",
                url: "views/add-product.php",
                data: {
                    action: "addProduct",
                    type: type,
                    code: code,
                    name: name,
                    detail: detail,
                    price: price,
                    image: image,
                    distributor: distributor,
                    quantity: quantity
                },
                success: function(res) {
                    if (res !== 'ok') {
                        alert(res);
                        return false;
                    }
                    alert("Thêm sản phẩm thành công!");
                    location.href = 'views/list-products.php?type=<?=$_GET['type']?>';
                }
            });
        });
    });
    </script>
</body>

</html>