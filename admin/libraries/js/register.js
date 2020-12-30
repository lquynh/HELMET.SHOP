
function getEle(element) {
	return document.getElementById(element);
}
function kiemTraMin(idTag, TB, content, min) {
	var inputTag = getEle(idTag).value.trim();
	var thongbao = getEle(TB);
	if (inputTag.length < min) {
		thongbao.innerHTML = "(*) " + content + "không nhỏ hơn " + min
				+ "kí tự";
		thongbao.style.display = "block";
		return false;
	} else {
		thongbao.style.display = "none";
		return true;
	}
}
function kiemTraPhone() {
	var inputTag = getEle('phone');
	var thongbao = getEle('TB');
	var phone = "/^[0-9]+$/";
	if (inputTag.value.match(phone)) {
		thongbao.style.display = 'none';
		return true;
	} else {
		thongbao.style.display = 'block';
		thongbao.innerHTML = "(*) Điện Thoại Không hợp lệ ";
		return false;
	}
}
function kiemTraMinPhone() {
	var inputTag = getEle('phone').value.trim();
	var thongbao = getEle('TB');
	if (inputTag.length < 10 || inputTag.length > 10){
		thongbao.innerHTML ="(*) điện thoại phải đủ 10 số";
		thongbao.style.display = "block";
		return false;
	} else {
		thongbao.style.display = "none";
		return true;
	}
}
function kiemTra(){
	var kq=kiemTraMin('fullname','TB','Tên','5');
	if(kq){
		var kqAddress=kiemTraMin('address','TB','Địa Chỉ','5');
		if(kqAddress){
			var kqPhone=kiemTraPhone();
			if(kqPhone){
				var kqPhone2=kiemTraMinPhone();
				if(kqPhone2){
					kiemTraMin('password','TB','password','6');
				}
			}
		}
	}
}