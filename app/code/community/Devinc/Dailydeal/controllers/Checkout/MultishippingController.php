<?php
require_once 'Mage/Checkout/controllers/MultishippingController.php';
class Devinc_Dailydeal_Checkout_MultishippingController extends Mage_Checkout_MultishippingController
{
	public function overviewAction()
    {
		Mage::getModel('dailydeal/dailydeal')->refreshCart();
		if (Mage::getSingleton('core/session')->getMultiShippingError()) {
			Mage::getSingleton('core/session')->setMultiShippingError(false);
			$this->_redirect('*/*/addresses');
			return $this;
		}
		
        parent::overviewAction();			
    }
	
	public function successAction()
    {
        $ids = $this->_getCheckout()->getOrderIds();
       
		foreach ($ids as $order_id) {		
			$items = Mage::getModel('sales/order_item')->getCollection()->addFieldToFilter('order_id', $order_id);
			
			foreach ($items as $item) {			
				$dailydeal = Mage::getModel('dailydeal/dailydeal')->getCollection()->addFieldToFilter('product_id',$item->getProductId())->addFieldToFilter('status', 3)->getFirstItem();
							
				$_product = Mage::getModel('catalog/product')->load($dailydeal->getProductId());
				$enabled = Mage::getStoreConfig('dailydeal/configuration/enabled');
				
				if ($dailydeal->getId()!='' && ($_product->getTypeId()=='simple' || $_product->getTypeId()=='virtual' || $_product->getTypeId()=='downloadable') && $enabled) {		
					$new_qty = $dailydeal->getDealQty()-$item->getQtyOrdered();
					
					$model = Mage::getModel('dailydeal/dailydeal');	
					
					$model->load($dailydeal->getId())
						  ->setDealQty($new_qty)		
						  ->save();		
					
					Mage::getModel('dailydeal/dailydeal')->refreshDeals();
				}
			}
		}
		
		parent::successAction();
	}
	
}