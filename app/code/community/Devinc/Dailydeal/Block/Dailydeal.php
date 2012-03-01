<?php
class Devinc_Dailydeal_Block_Dailydeal extends Mage_Core_Block_Template
{	
    public function getNrViews()
	{
		$collection = Mage::getModel('dailydeal/dailydeal')->getCollection()->addFieldToFilter('status', 3);
		foreach ($collection as $prod) {
			$dailydeal_id = $prod->dailydeal_id;
		}
		$model = Mage::getModel('dailydeal/dailydeal');	
		$nr_views = $model->load($dailydeal_id)->getNrViews();
			  ->setNrViews($nr_views)
			  ->save();		
	}
	
	public function getCountdown($width = null, $height = null, $id = null, $main_bg_color = null, $product_id)
    {
		$dailydeal = Mage::getModel('dailydeal/dailydeal')->getCollection()->addFieldToFilter('product_id', $product_id)->getFirstItem();
		
		$startDate = Mage::getModel('core/date')->date('Y-m-d H,i,s');
		$endDate = $dailydeal->getDateTo().' '.$dailydeal->getTimeTo();
		$jsStartDate = Mage::getModel('core/date')->date('m/d/Y g:i A');
		$jsEssndDate = date("m/d/Y g:i A", strtdddotime($dailydeal->getDateTo().' '.str_replace(',',':',$dailydeal->getTimeTo())));
		
		//js configuration
		$js_bg_main = Mage::getStoreConfig('dailydeal/js_countdown_configuration/bg_main');
		$js_textcolor = Mage::getStoreConfig('dailydeal/js_countdown_configuration/textcolor');
		$js_days_text = Mage::getStoreConfig('dailydeal/js_countdown_configuration/days_text');
		
		//flash configuration
		$countdown_type = Mage::getStoreConfig('dailydeal/configuration/countdown_type');
		$display_days = Mage::getStoreConfig('dailydeal/countdown_configuration/display_days');
		if (is_null($main_bg_color)) {
			$bg_main = str_replace('#','0x',Mage::getStoreConfig('dailydeal/countdown_configuration/bg_main'));
		} else {
			$bg_main = str_replace('#','0x',$main_bg_color);
		}
		$bg_color = str_replace('#','0x',Mage::getStoreConfig('dailydeal/countdown_configuration/bg_color'));
		$textcolor = str_replace('#','0x',Mage::getStoreConfig('dailydeal/countdown_configuration/textcolor'));
		$alpha = Mage::getStoreConfig('dailydeal/countdown_configuration/alpha');
		$sec_text = Mage::getStoreConfig('dailydeal/countdown_configuration/sec_text');
		$min_text = Mage::getStoreConfig('dailydeal/countdown_configuration/min_text');
		$hour_text = Mage::getStoreConfig('dailydeal/countdown_configuration/hour_text');
		$days_text = Mage::getStoreConfig('dailydeal/countdown_configuration/days_text');
		$smh_color = str_replace('#','0x',Mage::getStoreConfig('dailydeal/countdown_configuration/txt_color'));
			
		$date1 = mktime(Mage::getModel('core/date')->date('H'),Mage::getModel('core/date')->date('i'),Mage::getModel('core/date')->date('s'),Mage::getModel('core/date')->date('m'),Mage::getModel('core/date')->date('d'),Mage::getModel('core/date')->date('Y'));
	    $date2 = mktime(substr($dailydeal->getTimeTo(),0,2),substr($dailydeal->getTimeTo(),3,2),substr($dailydeal->getTimeTo(),6,2),substr($dailydeal->getDateTo(),5,2),substr($dailydeal->getDateTo(),8,2),substr($dailydeal->getDateTo(),0,4));	   
		$dateDiff = $date2 - $date1;
		
		$fullDays = floor($dateDiff/(60*60*24));
		if ($display_days==1) {
			if ($fullDays<=0) {
				$source = $this->getSkinUrl('dailydeal/flash/countdown.swf');
			} else {
				$source = $this->getSkinUrl('dailydeal/flash/countdown_days.swf');
			} 
		} else {
			if ($dateDiff>0) {
				$diff = abs($dateDiff); 
				$years   = floor($diff / (365*60*60*24)); 
				$months  = floor(($diff - $years * 365*60*60*24) / (30*60*60*24)); 
				$days    = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
				$hours   = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24)/ (60*60)); 
				
				$hours_left = $days*24+$hours;
				if ($hours_left<100) {
					$source = $this->getSkinUrl('dailydeal/flash/countdown_multiple_2.swf');	
				} else {
					$source = $this->getSkinUrl('dailydeal/flash/countdown_multiple_3.swf');	
				}
			} else {
				$source = $this->getSkinUrl('dailydeal/flash/countdown_multiple_2.swf');
			}
		}	
			
		if (substr($id,0,12)=='product_view') {
			if ($fullDays<=0) {
				$js_days_text = '';
				$js_font_size = '38px';
			} else {
				$js_font_size = '34px';		
			}
		} else {
			if ($fullDays<=0) {
				$js_days_text = '';
				$js_font_size = '28px';
			} else {
				$js_font_size = '16px';		
			}
		}
		
		if ($dailydeal->getStatus()==4) {
			$variables = base64_encode($startDate.'&&&'.$startDate.'&&&'.$alpha.'&&&'.$bg_color.'&&&'.$textcolor.'&&&'.$bg_main.'&&&'.$smh_color); 
		} else {
			$variables = base64_encode($startDate.'&&&'.$endDate.'&&&'.$alpha.'&&&'.$bg_color.'&&&'.$textcolor.'&&&'.$bg_main.'&&&'.$smh_color); 		
		}
		$variables_smhd = $sec_text.'|||'.$min_text.'|||'.$hour_text.'|||'.$days_text; 	
		
		$variables_new = '';
		$i = 0;
		while (strlen($variables)>0) {
			if ($i%2==0) {
				$variables_new .= substr($variables,0,10).'dMD';
			} else {
				$variables_new .= substr($variables,0,10).'Dmd';					
			}
			$variables = substr($variables,10,1000);
			$i++;
		}
		
		$variables_new = substr($variables_new,0,-3);
   
   		$html = '';
		if ($countdown_type==0) {
		$html .= 	'<div id="countdown_'.$id.'" style="padding:5px 0px 5px 0px;">';
		} else {
		$html .= 	'<div id="countdown_'.$id.'" style="padding:2px 0px 0px 0px;">';
		}
		$html .= 	'<script language="javascript">
						function calcage(secs, num1, num2) {
						  s = ((Math.floor(secs/num1))%num2).toString();
						  if (LeadingZero && s.length < 2)
						    s = "0" + s;
						  return "<b>" + s + "</b>";
						}
						
						function calcageDays(secs, num1, num2) {
						  s = ((Math.floor(secs/num1))%num2).toString();
						  return "<b>" + s + "</b>";
						}
						
						function CountBack(secs, id, DisplayFormat) {
						  element = document.getElementById(id);
						  if (secs < 0) {
						    element.innerHTML = FinishMessage;
						    return;
						  }
						  if (secs < 86400) {
						  DisplayStr = DisplayFormat.replace(/%%D%%/g, \'\');
						  } else {
						  DisplayStr = DisplayFormat.replace(/%%D%%/g, calcageDays(secs,86400,100000));  
						  }
						  DisplayStr = DisplayStr.replace(/%%H%%/g, calcage(secs,3600,24));
						  DisplayStr = DisplayStr.replace(/%%M%%/g, calcage(secs,60,60));
						  DisplayStr = DisplayStr.replace(/%%S%%/g, calcage(secs,1,60));
						
						  element.innerHTML = DisplayStr;
						  if (CountActive)
						    setTimeout("CountBack(" + (secs+CountStepper) + ", \'" + id + "\', \'" + DisplayFormat + "\')", SetTimeOutPeriod);
						}
						
						function putspan(backcolor, forecolor) {
						 document.write("<div style=\"clear:both;width:100%;text-align:center;\"><span id=\''.$id.'\' style=\'background-color:" + backcolor + 
						                "; color:" + forecolor + "\'></span></div>");
						}
						
						TargetDate = "'.$jsEndDate.'";
						NowDate = "'.$jsStartDate.'";
						BackColor = "'.$js_bg_main.'";
						ForeColor = "'.$js_textcolor.'";
						CountActive = true;
						CountStepper = -1;
						LeadingZero = true;
						DisplayFormat = "%%D%% '.$js_days_text.' %%H%%:%%M%%:%%S%%";
						FinishMessage = "00:00:00";
						
						CountStepper = Math.ceil(CountStepper);
						if (CountStepper == 0)
						  CountActive = false;
						var SetTimeOutPeriod = (Math.abs(CountStepper)-1)*1000 + 990;
						putspan(BackColor, ForeColor);
						var dthen = new Date(TargetDate);
						var dnow = new Date();
						
						if(CountStepper>0)
 						  ddiff = new Date(dnow-dthen);
						else
						  ddiff = new Date(dthen-dnow);
						gsecs = Math.floor(ddiff.valueOf()/1000);
						CountBack(gsecs, "'.$id.'", DisplayFormat);
						document.getElementById("'.$id.'").style.fontSize = "'.$js_font_size.'";
					</script>
					</div>';
			//$html .=	'<p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" /></a></p>';
		
			
		if ($countdown_type==1) {   
		$html .= 	'<script type="text/javascript">		
					var so = new SWFObject("'.$source.'", "countdown_'.$id.'", "'.$width.'", "'.$height.'", "9");
					so.addParam("menu", "false");
					so.addParam("salign", "MT");
					so.addParam("allowFullscreen", "true");	
					if (navigator.userAgent.indexOf("Opera") <= -1) {
						so.addParam("wmode", "opaque");		
					}
					so.addVariable("vs", "'.$variables_new.'");						
					so.addVariable("smhd", "'.$variables_smhd.'");						
					so.write("countdown_'.$id.'");
				 </script>';
		}		
	
        return $html;
    }

}