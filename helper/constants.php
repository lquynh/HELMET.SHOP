<?php
define("APP_URL", "http://localhost:7999/helmet_shop/");
//define("APP_URL", "http://192.168.1.97:7999/helmet_shop/");

// update these values if you change its corresponding data in db
define("ID_ROLE_ADMIN", 1);
define("ID_ROLE_MANAGER", 2);
define("ID_ROLE_APPROVER", 3);
define("ID_ROLE_SHIPPER", 4);
define("ID_ROLE_CUSTOMER", 5);

define("SHOP_NAME", "THE HELMET SHOP");
define("SHOP_PHONE", "0333.836.639");
define("SHOP_ADDRESS", "39 Lê Duẩn, Bến Nghé, Quận 1, TP.HCM");
define("SHOP_EMAIL", "abc@gmail.com");

// status
define("DEACTIVATED", 0);
define("ACTIVATED",1);

// trang thai don hang
define("ORDER_PENDING", 0);			// chua duyet
define("ORDER_WAITFORSHIPPER", 1);	// cho shipper xac nhan
define("ORDER_INDELIVERY", 2);		// dang giao
define("ORDER_FINISHED", 3);		// hoan tat
define("ORDER_CANCELLED", 4);		// huy
define("ORDER_MYSHIPORDERS", 5);	// for shipper only
define("ORDER_REVOKE", 6);			// thu hoi, dua ve pending

//define("MAX_SELECT_QTY", 1000); // số lượng tối đa mà user có thể chọn cho mỗi món hàng trong 1 session
define("PRICE_DECIMALS", 2);
?>