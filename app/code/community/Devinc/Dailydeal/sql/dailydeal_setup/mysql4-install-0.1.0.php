<?php

$installer = $this;

$installer->startSetup();

$installer->run("

DROP TABLE IF EXISTS {$this->getTable('dailydeal')};
CREATE TABLE {$this->getTable('dailydeal')} (
  `dailydeal_id` int(11) unsigned NOT NULL auto_increment,
  `product_id` int(11) NOT NULL,
  `deal_price` decimal(12,2) NOT NULL,
  `deal_qty` int(11) NOT NULL,
  `date_from` date NULL,
  `date_to` date NULL,
  `time_from` varchar(255) NOT NULL default '',
  `time_to` varchar(255) NOT NULL default '',
  `qty_sold` int(11) NOT NULL,
  `nr_views` int(11) NOT NULL,
  `disable` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`dailydeal_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");
	
$installer->setConfigData('dailydeal/configuration/enabled',					1);
$installer->setConfigData('dailydeal/configuration/header_links',				1);
$installer->setConfigData('dailydeal/configuration/qty',	    				1);
$installer->setConfigData('dailydeal/configuration/no_deal_message',	    	'There is no deal setup for today. Please try again later.');
$installer->setConfigData('dailydeal/configuration/notify',	    				0);
$installer->setConfigData('dailydeal/configuration/admin_email',	    		'');

$installer->setConfigData('dailydeal/sidebar_configuration/left_sidebar',		0);
$installer->setConfigData('dailydeal/sidebar_configuration/right_sidebar',		1);
$installer->setConfigData('dailydeal/sidebar_configuration/display_name',		1);
$installer->setConfigData('dailydeal/sidebar_configuration/display_price',		1);
$installer->setConfigData('dailydeal/sidebar_configuration/display_qty',		1);

$installer->setConfigData('dailydeal/countdown_configuration/display_days',	    0);
$installer->setConfigData('dailydeal/countdown_configuration/bg_main',	   		'#FFFFFF');
$installer->setConfigData('dailydeal/countdown_configuration/bg_color',	   		'#333333');
$installer->setConfigData('dailydeal/countdown_configuration/alpha',	   		'70');
$installer->setConfigData('dailydeal/countdown_configuration/textcolor',	   	'#FFFFFF');
$installer->setConfigData('dailydeal/countdown_configuration/txt_color',	   	'#333333');
$installer->setConfigData('dailydeal/countdown_configuration/sec_text',	   		'SECONDS');
$installer->setConfigData('dailydeal/countdown_configuration/min_text',	   		'MINUTES');
$installer->setConfigData('dailydeal/countdown_configuration/hour_text',	   	'HOURS');
$installer->setConfigData('dailydeal/countdown_configuration/days_text',	   	'DAYS');

$installer->endSetup(); 