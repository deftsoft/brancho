<?php

class Devinc_Dailydeal_Block_Adminhtml_Dailydeal_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('dailydeal_form', array('legend'=>Mage::helper('dailydeal')->__('Deal information')));
    
	 
	  $field = $fieldset->addField('product_id', 'text', array(
            'label'     => Mage::helper('dailydeal')->__('Product Details'),
            'name'      => 'product_id',
            'class'     => 'required-entry',
            'required'  => true,
      ));	
	
	  $field->setRenderer($this->getLayout()->createBlock('dailydeal/adminhtml_dailydeal_edit_renderer_product'));
	 
	  if (substr(Mage::app()->getLocale()->getLocaleCode(),0,2)!='en') {
		  $dateFormatIso = Mage::app()->getLocale()->getDateFormat(
				Mage_Core_Model_Locale::FORMAT_TYPE_SHORT
		  );
	  } else {		
		  $dateFormatIso = Mage::app()->getLocale()->getDateFormat(
				Mage_Core_Model_Locale::FORMAT_TYPE_LONG
		  );
	  }	  
	 
	  $field = $fieldset->addField('deal_price', 'text', array(
            'label'     => Mage::helper('dailydeal')->__('Deal Price'),
            'name'      => 'deal_price',
            'class'     => 'required-entry',
            'required'  => true,
      ));	
	  $field->setRenderer($this->getLayout()->createBlock('dailydeal/adminhtml_dailydeal_edit_renderer_input'));
	 
	  $field = $fieldset->addField('deal_qty', 'text', array(
            'label'     => Mage::helper('dailydeal')->__('Deal Qty'),
            'name'      => 'deal_qty',
            'class'     => 'required-entry',
            'required'  => true,
      ));	
	  $field->setRenderer($this->getLayout()->createBlock('dailydeal/adminhtml_dailydeal_edit_renderer_input'));
		
	  $fieldset->addField('date_from', 'date', array(
          'name'      => 'date_from',
          'label'     => Mage::helper('dailydeal')->__('From Date'),
          'image'     => $this->getSkinUrl('images/grid-cal.gif'),
          'class'     => 'required-entry',
          'required'  => true,
          'format'    => $dateFormatIso
      ));

      $fieldset->addField('time_from', 'time', array(
          'name'      => 'time_from',
          'label'     => Mage::helper('dailydeal')->__('From Time'),
          'required'  => false,
      ));	

      $fieldset->addField('date_to', 'date', array(
          'name'      => 'date_to',
          'label'     => Mage::helper('dailydeal')->__('To Date'),
          'image'     => $this->getSkinUrl('images/grid-cal.gif'),
          'class'     => 'required-entry',
          'required'  => true,
          'format'    => $dateFormatIso
      ));	

      $fieldset->addField('time_to', 'time', array(
          'name'      => 'time_to',
          'label'     => Mage::helper('dailydeal')->__('To Time'),
          'required'  => false,
      ));		   
		
	  $fieldset->addField('disable', 'select', array(
          'label'     => Mage::helper('dailydeal')->__('Disable product after deal ends'),
          'name'      => 'disable',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('dailydeal')->__('No'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('dailydeal')->__('Yes'),
              ),
          ),
		  'note'     => 'If Yes - the product will be disabled from the catalog &amp; search after the deal ends to prevent it from appearing on search engines.'
      ));	
		
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('dailydeal')->__('Deal status'),
          'name'      => 'status',
          'class'     => 'required-entry validate-select',
          'required'  => true,
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('dailydeal')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('dailydeal')->__('Disabled'),
              ),
          ),
      ));
	  
     
      if ( Mage::getSingleton('adminhtml/session')->getDailydealData() ) {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getDailydealData());
          Mage::getSingleton('adminhtml/session')->setDailydealData(null);
      } elseif ( Mage::registry('dailydeal_data') ) {
          $form->setValues(Mage::registry('dailydeal_data')->getData());
      }
      return parent::_prepareForm();
  }
}