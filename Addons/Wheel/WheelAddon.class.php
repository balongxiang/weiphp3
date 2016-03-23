<?php

namespace Addons\Wheel;
use Common\Controller\Addon;

/**
 * 大转盘插件
 * @author balong
 */

    class WheelAddon extends Addon{

        public $info = array(
            'name'=>'Wheel',
            'title'=>'大转盘',
            'description'=>'抽奖大转盘',
            'status'=>1,
            'author'=>'balong',
            'version'=>'0.1',
            'has_adminlist'=>1
        );

	public function install() {
		$install_sql = './Addons/Wheel/install.sql';
		if (file_exists ( $install_sql )) {
			execute_sql_file ( $install_sql );
		}
		return true;
	}
	public function uninstall() {
		$uninstall_sql = './Addons/Wheel/uninstall.sql';
		if (file_exists ( $uninstall_sql )) {
			execute_sql_file ( $uninstall_sql );
		}
		return true;
	}

        //实现的weixin钩子方法
        public function weixin($param){

        }

    }