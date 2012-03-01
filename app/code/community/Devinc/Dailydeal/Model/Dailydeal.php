<?php

class Devinc_Dailydeal_Model_Dailydeal extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('dailydeal/dailydeal');
    }
	
	public function getDailyDeal()
    {		
		$this->refreshDeals();
        $dailydeal = Mage::getModel('dailydeal/dailydeal')->getCollection()->addFieldToFilter('status', 3)->getFirstItem();
		
		if ($dailydeal->getId()!='') {
			return $dailydeal->getProductId();
		} else {
			return null;
		}
	}
	
	public function refreshDeals() {
		$dailydeal_collection = Mage::getModel('dailydeal/dailydeal')->getCollection()->setOrder('dailydeal_id', 'DESC');
		$run = true;
		
		foreach ($dailydeal_collection as $dailydeal) {	
			$model = Mage::getModel('dailydeal/dailydeal');		
			
			// get in stock value
			$_product = Mage::getModel('catalog/product')->load($dailydeal->getProductId());	
			$stockItem = $_product->getStockItem();
						
			if ($stockItem->getIsInStock()) {
				if ($_product->getTypeId()=='simple' || $_product->getTypeId()=='virtual' || $_product->getTypeId()=='downloadable') {
					if ($dailydeal->getDealQty()>0) {
						$in_stock = true;
					} else {
						$in_stock = false;						
					}
				} else {
					$in_stock = true;						
				}
			} else {
				$in_stock = false;
			}
				
			$product_status = $_product->getStatus();
			$current_date_time = Mage::getModel('core/date')->date('Y-m-d H,i,s');	
			
			if ($current_date_time>=($dailydeal->getDateFrom().' '.$dailydeal->getTimeFrom()) && $current_date_time<=($dailydeal->getDateTo().' '.$dailydeal->getTimeTo()) && $dailydeal->getStatus()!=2 && $run) {
				if ($in_stock && $product_status==1) {
					$model->setId($dailydeal->getId())
						  ->setStatus('3')
						  ->save();
					$run = false;
				} else {
					$model->setId($dailydeal->getId())
						  ->setStatus('2')
						  ->save();
				}
			} elseif ($current_date_time>=($dailydeal->getDateFrom().' '.$dailydeal->getTimeFrom()) && $current_date_time<=($dailydeal->getDateTo().' '.$dailydeal->getTimeTo()) && $dailydeal->getStatus()==3) {
				if ($in_stock && $product_status==1) {
					$model->setId($dailydeal->getId())
						  ->setStatus('1')
						  ->save();
				} else {
					$model->setId($dailydeal->getId())
					  ->setStatus('2')
					  ->save();	
				}
			} elseif ($current_date_time>=($dailydeal->getDateTo().' '.$dailydeal->getTimeTo())) {
				$model->setId($dailydeal->getId())
					  ->setStatus('2')
					  ->save();			
			} elseif ($product_status==2) {
				$model->setId($dailydeal->getId())
					  ->setStatus('2')
					  ->save();			
			}

			if (($current_date_time>=($dailydeal->getDateTo().' '.$dailydeal->getTimeTo()) || $dailydeal->getStatus()==2) && $dailydeal->getDisable()==2 && $product_status==1) {
				$updateProduct = Mage::getModel('catalog/product')->setStoreId(0)->load($dailydeal->getProductId())->setStatus(2)->save();
			}
		}
	 
        return $this;
	}
}