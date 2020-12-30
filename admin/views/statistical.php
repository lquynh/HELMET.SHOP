<?php
session_start();
if (!isset($_SESSION['login_email'])) header("location:../login.php");
?>
<head>
    <title>Báo cáo sản phẩm bán ra</title>
    <?php include_once('..\views\common.php'); ?>
</head>
<body>
    <section id="container">
        <?php include_once 'header.php'?>
        <?php if (isset($_SESSION['login_email'])) include_once 'menu.php'; ?>
        <section id="main-content">
        <section class="wrapper">
        <div class="panel panel-body" style="height:auto;">
          <center>
            <span>Từ</span>
            <input type="text" id="start" name="start" />
            <input style="display:none;" value="a" id="put" />
            <span>Đến</span>
            <input type="text" id="end" name="end" />
            <button type="submit" name="submit" class="btn btn-success" id="in">In</button>
          </center>
          <div id='report'></div>
        </div>
        </section>
      </section>
        <?php include_once('footer.php'); ?>
    </section>

    <script>
    function exportFileSanPham() {
      loading_on();
      let data=$("#export").text();
      let reportTitle=$("#reportTitle").text();
      let created_by="<?=$_SESSION['login_name']?> (<?=$_SESSION['login_username']?>)";
      console.log(data + " : " + reportTitle + " : " + created_by);
      $.ajax({
        url: "controller/ExportFileController.php",
        type: "POST",
        data: {
          action: "exportFileSanPham",
          data: data,
          reportTitle: reportTitle,
          created_by: created_by
        },
        success: function(res) {
          console.log(res);
          loading_off();
          location.href=res;
        }
      });
    }

    $(document).ready(function() {

        $("#start").datepicker({
            dateFormat: "d-m-yy"
        }).datepicker("setDate", "-1m");

        $("#end").datepicker({
            dateFormat: "d-m-yy"
        }).datepicker("setDate", "0");

        $("#in").click(function() {
            $('#contain').css("display", "block");
            $('#put').val("a");
            var dateStart = $('#start').val();
            var dateEnd = $('#end').val();
            if (dateStart === "" || dateEnd === "") {
              alert('Vui lòng chọn ngày!');
              return;
            }
            let error=validateDateRange(dateStart,dateEnd);
            if (error!="") {
              alert(error);
              return;
            }

            $.ajax({
              url: "controller/StatisticalController.php",
              type: "POST",
              data: {
                action: "sanpham",
                start: dateStart,
                end: dateEnd
              },
              success: function(res) {
                console.log(res);
                $("#report").html(res);
              }
            });
        });
    });
    </script>
</body>

</html>