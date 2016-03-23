<?php

namespace Addons\FlightId;
use Common\Controller\Addon;

/**
 * 飞行证生成插件
 * @author balong
 */

    class FlightIdAddon extends Addon{

        public $info = array(
            'name'=>'FlightId',
            'title'=>'飞行证生成',
            'description'=>'飞行证生成',
            'status'=>1,
            'author'=>'balong',
            'version'=>'0.1',
            'has_adminlist'=>1
        );

	public function install() {
		$install_sql = './Addons/FlightId/install.sql';
		if (file_exists ( $install_sql )) {
			execute_sql_file ( $install_sql );
		}
		return true;
	}
	public function uninstall() {
		$uninstall_sql = './Addons/FlightId/uninstall.sql';
		if (file_exists ( $uninstall_sql )) {
			execute_sql_file ( $uninstall_sql );
		}
		return true;
	}

        //实现的weixin钩子方法
        public function weixin($param){

        }

    }