/* Validations */
let validateSuppName = name => /^[a-zA-ZàáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđÀÁẠẢÃÂẦẤẬẨẪĂẰẮẶẲẴÈÉẸẺẼÊỀẾỆỂỄÌÍỊỈĨÒÓỌỎÕÔỒỐỘỔỖƠỜỚỢỞỠÙÚỤỦŨƯỪỨỰỬỮỲÝỴỶỸĐ 0-9]{5,50}$/i.test(name)?"":"Tên NCC không hợp lệ!\n";
let validateName = name => /^[a-zA-ZàáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđÀÁẠẢÃÂẦẤẬẨẪĂẰẮẶẲẴÈÉẸẺẼÊỀẾỆỂỄÌÍỊỈĨÒÓỌỎÕÔỒỐỘỔỖƠỜỚỢỞỠÙÚỤỦŨƯỪỨỰỬỮỲÝỴỶỸĐ ]{5,50}$/i.test(name)?"":"Họ tên không hợp lệ!\n";
let validateAddress = address => /^[a-zA-ZàáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđÀÁẠẢÃÂẦẤẬẨẪĂẰẮẶẲẴÈÉẸẺẼÊỀẾỆỂỄÌÍỊỈĨÒÓỌỎÕÔỒỐỘỔỖƠỜỚỢỞỠÙÚỤỦŨƯỪỨỰỬỮỲÝỴỶỸĐ .,/0-9]{5,100}$/i.test(address)?"":"Địa chỉ không hợp lệ!\n";
let validateProductName = name => /^[a-zA-ZàáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđÀÁẠẢÃÂẦẤẬẨẪĂẰẮẶẲẴÈÉẸẺẼÊỀẾỆỂỄÌÍỊỈĨÒÓỌỎÕÔỒỐỘỔỖƠỜỚỢỞỠÙÚỤỦŨƯỪỨỰỬỮỲÝỴỶỸĐ \-.,/0-9]{5,100}$/i.test(name)?"":"Tên sản phẩm không hợp lệ!\n";
let validateProductDetail = detail => /^[a-zA-ZàáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđÀÁẠẢÃÂẦẤẬẨẪĂẰẮẶẲẴÈÉẸẺẼÊỀẾỆỂỄÌÍỊỈĨÒÓỌỎÕÔỒỐỘỔỖƠỜỚỢỞỠÙÚỤỦŨƯỪỨỰỬỮỲÝỴỶỸĐ .,/0-9\n]{5,1000}$/g.test(detail)?"":"Mô tả sản phẩm không hợp lệ!\n";

let validateSuppCode=suppCode=>/^[a-zA-Z0-9]{3,12}$/i.test(suppCode)?"":"Mã NCC không hợp lệ!\n";
let validateUsername=username=>/^[a-z0-9]{5,12}$/i.test(username)?"":"Tên đăng nhập không hợp lệ!\n";
let validatePhone=phone=>/^0[0-9]{9}$/i.test(phone)?"":"SĐT phải có 10 số và bắt đầu bằng số 0!\n";
let validatePassword=password=>/^[a-z0-9]{5,10}$/i.test(password)?"":"Mật khẩu không hợp lệ!\n";
let validateEmail=email=>/^[a-z0-9_\.]{1,40}@gmail.com$/i.test(email)?"":"Email không hợp lệ!\n";
let validatePlaceOrderCode=placeOrderCode=>/^[A-Z0-9]{4,20}$/g.test(placeOrderCode)?"":"Mã đặt hàng không hợp lệ!\n";
let validateImportCode=importCode=>/^[A-Z0-9]{4,20}$/g.test(importCode)?"":"Mã nhập hàng không hợp lệ!\n";
let validatePriceInput=price=>/^[0-9]*\.[0-9]{1,2}$/g.test(price)&&(parseFloat(price)>0)?"":"Nhập giá đúng định dạng và > 0!\n";
let validateOrdering=ordering=>/^[0-9]{1,2}$/g.test(ordering)?"":"Số thứ tự không được bỏ trống hoặc < 0!\n";
let validateGeneralText=(text,name)=>/^[a-zA-ZàáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđÀÁẠẢÃÂẦẤẬẨẪĂẰẮẶẲẴÈÉẸẺẼÊỀẾỆỂỄÌÍỊỈĨÒÓỌỎÕÔỒỐỘỔỖƠỜỚỢỞỠÙÚỤỦŨƯỪỨỰỬỮỲÝỴỶỸĐ .,/0-9]{1,100}$/g.test(text)?"":"Không được bỏ trống "+name+"!\n";
let validatePromotionCode=promotionCode=>/^[a-zA-Z0-9]{3,20}$/g.test(promotionCode)?"":"Mã khuyến mãi không hợp lệ!\n";
let validatePromotionPercent=percent=>/^[1-9][0-9]?[0-9]?$/g.test(percent) && percent<=100?"":"Phần trăm giảm giá không hợp lệ!\n";
let validateProductCode=code=>/^[a-zA-Z0-9]{4,20}$/g.test(code)?"":"Mã sản phẩm không hợp lệ!\n";
let validateQuantity=quantity=>/^[1-9][0-9]*$/g.test(quantity)?"":"Số lượng phải là số nguyên > 0!\n";

function validateDateRange(startDate, endDate) {
	let s=startDate.split("-");
	let e=endDate.split("-");
	let g1=new Date(s[2],s[1]-1,s[0]);
	let g2=new Date(e[2],e[1]-1,e[0]);
	return g1.getTime() > g2.getTime()?"Ngày bắt đầu ("+startDate+") không được < ngày kết thúc ("+endDate+")!\n":"";
}

/* Utilities */
let formatMoney = num => num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
let goToTop = () => $('html,body').scrollTop(0);

function normalizePriceInput(price) {
	pattern1=/^[0-9]+\.[0-9]{1}$/g;
	pattern2=/^[0-9]+$/g;
	if (pattern1.test(price)) return price+"0";
	if (pattern2.test(price)) return price+".00";
	return price;
}

function generateRandomCode() {
	var today=new Date();
	var year=today.getFullYear();
	var month=today.getMonth()+1;
	var day=today.getDate();
	var hours=today.getHours();
	var mins=today.getMinutes();
	var secs=today.getSeconds();

	if (month < 10) month="0"+month;
	if (day < 10) day="0"+day;
	if (hours < 10) hours="0"+hours;
	if (mins < 10) mins="0"+mins;
	if (secs < 10) secs="0"+secs;

	var randomCode=year+""+month+""+day+""+hours+""+mins+""+secs;
	return randomCode;
}