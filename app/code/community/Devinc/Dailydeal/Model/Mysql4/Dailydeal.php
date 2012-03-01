<?php

class Devinc_Dailydeal_Model_Mysql4_Dailydeal extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the dailydeal_id refers to the key field in your database table.
        $this->_init('dailydeal/dailydeal', 'dailydeal_id');
    }
}