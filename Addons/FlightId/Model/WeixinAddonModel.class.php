<?php
        	
namespace Addons\FlightId\Model;
use Home\Model\WeixinModel;
        	
/**
 * FlightId的微信模型
 */
class WeixinAddonModel extends WeixinModel{
	function reply($dataArr, $keywordArr = array()) {
		$config = getAddonConfig ( 'FlightId' ); // 获取后台插件的配置参数	
		//dump($config);
	}
}
        	