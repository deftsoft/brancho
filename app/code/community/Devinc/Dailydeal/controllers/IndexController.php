<?php
class Devinc_Dailydeal_IndexController extends Mage_Core_Controller_Front_Action
{	 
    public function indexAction()
    {
		$product_id = Mage::getModel('dailydeal/dailydeal')->getDailyDeal();
		
        if ($product_id!=0 && Mage::getStoreConfig('dailydeal/configuration/enabled')) {
            $_product = Mage::getModel('catalog/product')->load($product_id);			
			header('Location: '.$_product->getProductUrl());
			exit;
        } elseif (Mage::getStoreConfig('dailydeal/configuration/enabled')) {
			Mage::getSingleton('catalog/session')->addError(Mage::helper('dailydeal')->__(Mage::getStoreConfig('dailydeal/configuration/no_deal_message')));	
			if (Mage::getStoreConfig('dailydeal/configuration/notify')) {			
				$mail = new Zend_Mail();
				$content = 'A customer tried to view tgh gdoday\'s deal.';
				$mail->setBodyHtml($content);
				$mail->setFrom('customer@dailydeal.com');
				$mail->addTo(Mage::getStoreConfig('dailydeal/configuration/admin_email'));
				$mail->setSubject('There is no deal set up for today.');	
				$mail->send();
			}
			$this->_redirect('');
        } else {
			$this->_redirect('no-route');		
		}
    }
}