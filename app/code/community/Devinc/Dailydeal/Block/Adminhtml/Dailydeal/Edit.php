<?php

class Devinc_Dailydeal_Block_Adminhtml_Dailydeal_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'dailydeal';
        $this->_controller = 'adminhtml_dailydeal';
        
        $this->_updateButton('save', 'label', Mage::helper('dailydeal')->__('Save Deal'));
        $this->_updateButton('delete', 'label', Mage::helper('dailydeal')->__('Delete Deal'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);	
	
        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('dailydeal_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'dailydeal_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'dailydeal_content');
                }
            }

            function saveAndContinueEdit(){
				if (document.getElementById('product_id').value!='') {
					editForm.submit($('edit_form').action+'back/edit/');
				} else {
					document.getElementById('advice-required-entry-product_details_select').style.display = 'none';
					$('advice-required-entry-product_details_select').appear({ duration: 1 });
					if (document.getElementById('display_on').value=='' || document.getElementById('price').value=='' || document.getElementById('qty').value=='') {
						editForm.submit($('edit_form').action+'back/edit/');
					}
				}
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('dailydeal_data') && Mage::registry('dailydeal_data')->getId() ) {
            return Mage::helper('dailydeal')->__("Edit Deal");
        } else {
            return Mage::helper('dailydeal')->__('Add Deal');
        }
    }
}