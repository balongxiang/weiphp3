<?php
        	
namespace Addons\Wheel\Model;
use Home\Model\WeixinModel;
        	
/**
 * Wheel的微信模型
 */
class WeixinAddonModel extends WeixinModel{
	function reply($dataArr, $keywordArr = array()) {
		$config = getAddonConfig ( 'Wheel' ); // 获取后台插件的配置参数	
		//dump($config);
	}
}
        	