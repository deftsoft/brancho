<?php

class Devinc_Dailydeal_Block_Adminhtml_Dailydeal_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('dailydeal_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('dailydeal')->__('Deal Information'));
  }

  protected function _beforeToHtml()
  {	  
      $this->addTab('form_section', array(
          'label'     => Mage::helper('dailydeal')->__('Deal Settings'),
          'title'     => Mage::helper('dailydeal')->__('Deal Settings'),
          'content'   => $this->getLayout()->createBlock('dailydeal/adminhtml_dailydeal_edit_tab_form')->toHtml(),
      ));
	  
      $this->addTab('products_section', array(
          'label'     => Mage::helper('dailydeal')->__('Select a Product'),
          'title'     => Mage::helper('dailydeal')->__('Select a Product'),
          'content'   => $this->getLayout()->createBlock('dailydeal/adminhtml_dailydeal_edit_tab_products')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}