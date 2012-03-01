<?php

require_once 'Mage/Catalog/Model/Product.php';
class Devinc_Dailydeal_Model_Catalog_Product extends Mage_Catalog_Model_Product
{
	public function getFinalPrice($qty=null)
    {
		$dailydeal = Mage::getModel('dailydeal/dailydeal')->getCollection()->addFieldToFilter('status', array('eq' => 3))->addFieldToFilter('product_id', $this->getId())->getFirstItem();
		$enabled = Mage::getStoreConfig('dailydeal/configuration/enabled');
		
		if (!is_null($dailydeal->getId()) && $enabled) {
			$product_id = $dailydeal->getProductId();
		} else {
			$product_id = null;
		}
		
		if ($this->getTypeId()!='configurable') {
			if (!is_null($product_id) && $dailydeal->getDealQty()>0) {
				$this->setSpecialPrice($dailydeal->getDealPrice());	
			}		
		} else {
			if (!is_null($product_id)) { 
				$this->setSpecialPrice($dailydeal->getDealPrice());	
			}				
		}
		
		
		if (is_null($product_id)) {			
			$price = $this->_getData('final_price');
		
			if ($price !== null) {
				return $price;
			}
		}
		
		return $this->getPriceModel()->getFinalPrice($qty, $this); 
		
    }
}