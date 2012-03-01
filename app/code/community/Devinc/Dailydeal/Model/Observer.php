<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Checkout
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Checkout observer model
 *
 * @category   Mage
 * @package    Mage_Checkout
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Devinc_Dailydeal_Model_Observer
{	
	public function refreshCart() {		
		//$this->refreshDeals();
						
		if (Mage::getStoreConfig('dailydeal/configuration/enabled')) {			
			
			$quote_product_ids = Mage::getSingleton('checkout/session')->getQuote()->getAllItems();
			$product_ids = Array();
			
			foreach ($quote_product_ids as $item) {
				$product_ids[] = $item->getProductId();
			}	
			
			$quote_items_ids = Mage::getModel('checkout/cart')->getQuote()->getAllItems();
			
			$i = 0;
			$qtys = array();

			foreach ($quote_items_ids as $quote_id) {
				$dailydeal = Mage::getModel('dailydeal/dailydeal')->getCollection()->addFieldToFilter('product_id',$product_ids[$i])->addFieldToFilter('status', 3)->getFirstItem();
				$_product = Mage::getModel('catalog/product')->load($dailydeal->getProductId());
				if ($dailydeal->getId()!='' && ($_product->getTypeId()=='simple' || $_product->getTypeId()=='virtual' || $_product->getTypeId()=='downloadable')) {	
					if (!isset($qtys[$dailydeal->getProductId()])) {
						$qtys[$dailydeal->getProductId()] = 0;
					}
					$max_qty = $dailydeal->getDealQty();
					$qtys[$dailydeal->getProductId()] = $qtys[$dailydeal->getProductId()]+$quote_id->getQty();
					$product_name = $_product->getName();
					
					if ($max_qty<$qtys[$dailydeal->getProductId()]) {
						Mage::getSingleton('checkout/session')->getQuote()->setHasError(true);
						Mage::getSingleton('checkout/session')->addError('The maximum order qty available for the "'.$product_name.'" DEAL is '.$max_qty.'.');
						Mage::getSingleton('core/session')->setMultiShippingError(true);
					}							
				}       
				$i++;
			}					
			
		}
		return $this;
	}
	
	public function updateDealQty($observer)
    {
		$items = $observer->getEvent()->getOrder()->getItemsCollection();
		
		foreach ($items as $item) {			
			$dailydeal = Mage::getModel('dailydeal/dailydeal')->getCollection()->addFieldToFilter('product_id',$item->getProductId())->addFieldToFilter('status', 3)->getFirstItem();
						
			$_product = Mage::getModel('catalog/product')->load($dailydeal->getProductId());
			$enabled = Mage::getStoreConfig('dailydeal/configuration/enabled');
			
			if ($dailydeal->getId()!='' && ($_product->getTypeId()=='simple' || $_product->getTypeId()=='virtual' || $_product->getTypeId()=='downloadable') && $enabled) {		
				$new_qty = $dailydeal->getDealQty()-$item->getQtyOrdered();
				$new_sold_qty = $dailydeal->getQtySold()+$item->getQtyOrdered();				
				
				$model = Mage::getModel('dailydeal/dailydeal');	
				
				$model->load($dailydeal->getId())
					  ->setDealQty($new_qty)		
					  ->setQtySold($new_sold_qty)		
					  ->save();		
				
				Mage::getModel('dailydeal/dailydeal')->refreshDeals();
			}
		}
		
		return $this;
	}
}
