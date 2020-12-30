var mangTB=['Tên Ít Nhất 5 Ký Tự',
            'Mô Tả Ít Nhất 10 Ký Tự',
            'Đơn Giá Lớn Hơn 0',
            'Hình Ảnh Ít Nhất 7 Ký Tự'
           ];
function getEle(ele){
    return document.getElementById(ele);
}
function ktraMin(idField,idTB,indexChuoiTB,kyTu){
    var valueField=getEle(idField);
    var thongBao=getEle(idTB);
    if(valueField < kyTu){
        thongBao.innerHTML=mangTB[indexChuoiTB];
        return false;
    }
    return true;
}
getEle('btnCapNhat').addEventListener('click',function(){
    var cate=getEle(cate).value.trim();
    var name=getEle(ten).value.trim();
    var detail=getEle(mota).value.trim();
    var price=getEle(gia).value.trim();
    var image=getEle(hinhanh).value.trim();
    var quantityExist=getEle(soluongton).value.trim();
    var _new=getEle(moi).value.trim();
    ktraMin('ten','tb-ten',0);
    if(ktraMin('ten','tb-ten',0)){
        ktraMin('mota','tb-mota',1);
        if(ktraMin('mota','tb-mota',1)){
            ktraMin('hinhanh','tb-hinhanh',3);
            if(!(ktraMin('hinhanh','tb-hinhanh',3))){
                return;
            }
        }else{
            return;
        }
    }else{
        return;
    }
    $.ajax({
        type : "post",
        url : "../../views/edit-products.php",
        data : {
            cate : cate,
            name : name,
            detail : detail,
            price : price,
            image:image,
            quantityExist:quantityExist,
            _new:_new,
        },
        success : function(response) {
            if ($.trim(response) == "success") {
                $("#content").load("edit-products.php");
                /* $("#editModal").css("display", "none"); */
            }
        }
    });

    
    
});