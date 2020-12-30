<?php
include_once($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\helper\constants.php');
class Cart {
	public $items = [];
	public $totalQtity = 0;	// total quantity of all items in cart
	public $totalPrice = 0;	// total original price of all items in cart
	public $promtPrice = 0;	// total promotion price of all items in cart
    
	public function __construct($oldCart=null){
		if($oldCart){
			$this->items = $oldCart->items;
			$this->totalQtity = $oldCart->totalQtity;
			$this->totalPrice = $oldCart->totalPrice;
			$this->promtPrice = $oldCart->promtPrice;
		}
	}
	
	public function add($item, $qty){
		$idProduct=$item->product_code;
		$discountPrice=round($item->price-($item->price*$item->percent), PRICE_DECIMALS);
		
		$new_item=[
			'totalQtity'=>0,
			'unitPrice'=>$discountPrice,	// promotion as price per unit
			'totalPrice'=>$item->price,
			'promtPrice'=>$discountPrice,
			'item'=>$item
		];

		if($this->items)
			if(array_key_exists($item->product_code,$this->items))
				$new_item=$this->items[$idProduct];
        
		$new_item['totalQtity']+=$qty;
		$new_item['unitPrice']=$discountPrice;
		$new_item['totalPrice']=$new_item['totalQtity']*$item->price;
		$new_item['promtPrice']=$new_item['totalQtity']*$discountPrice;
		
		$new_item['totalPrice']=$new_item['totalPrice'];
		$new_item['promtPrice']=$new_item['promtPrice'];

		$this->items[$idProduct]=$new_item;
		$this->totalQtity+=$qty;
		$this->totalPrice+=($qty*($item->price));
		$this->promtPrice+=($qty*$discountPrice);
		
		$this->totalPrice=$this->totalPrice;
		$this->promtPrice=$this->promtPrice;
	}
	
	//xóa sản phẩm khỏi cart
	public function removeItem($id){
		$this->totalQtity-=$this->items[$id]['totalQtity'];
		$this->totalPrice-=$this->items[$id]['totalPrice'];
		$this->promtPrice-=$this->items[$id]['promtPrice'];
		
		// $this->totalPrice=round($this->totalPrice,PRICE_DECIMALS);
		// $this->promtPrice=round($this->promtPrice,PRICE_DECIMALS);
		if ($this->totalPrice<0) $this->totalPrice=0;
		if ($this->promtPrice<0) $this->promtPrice=0;
		if ($this->totalQtity<0) $this->totalQtity=0;
		
		unset($this->items[$id]);
	}
}
?>