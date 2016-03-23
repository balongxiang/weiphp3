<?php

namespace Addons\house;
use Common\Controller\Addon;

/**
 * 房产证生成器插件
 * @author balong
 */

    class houseAddon extends Addon{

        public $info = array(
            'name'=>'house',
            'title'=>'房产证生成器',
            'description'=>'房产证生成器',
            'status'=>1,
            'author'=>'balong',
            'version'=>'0.1',
            'has_adminlist'=>0
        );

	public function install() {
		$install_sql = './Addons/house/install.sql';
		if (file_exists ( $install_sql )) {
			execute_sql_file ( $install_sql );
		}
		return true;
	}
	public function uninstall() {
		$uninstall_sql = './Addons/house/uninstall.sql';
		if (file_exists ( $uninstall_sql )) {
			execute_sql_file ( $uninstall_sql );
		}
		return true;
	}

        //实现的weixin钩子方法
        public function weixin($param){

        }

    }