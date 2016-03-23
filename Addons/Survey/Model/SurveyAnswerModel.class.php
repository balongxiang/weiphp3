<?php

namespace Addons\Survey\Model;

use Think\Model;

/**
 * Survey模型
 */
class SurveyAnswerModel extends Model {
	function getAnswerInfo($survey_id, $question_id,$follow_id,$update = false, $data = array()) {
		$key = 'SurveyAnswer_getAnswerInfo_' . $survey_id.'_'.$question_id.'_'.$follow_id;
		$info = S ( $key );
		if ($info === false || $update) {
			$map ['survey_id'] = $survey_id;
			$map['question_id']=$question_id;
			$map ['uid'] = $follow_id;
			$info = ( array ) (empty ( $data ) ? $this->where ( $map )->find () : $data);
			
			S ( $key, $info, 86400 );
		}
		
		return $info;
	}
	function updateAnswer($survey_id,$question_id,$follow_id,$data=array()){
		$map ['survey_id'] = $survey_id;
		$map['question_id']=$question_id;
		$map ['uid'] = $follow_id;
		$res = $this->where ( $map )->save ( $data );
		if ($res) {
			$this->getAnswerInfo ( $survey_id,$question_id,$follow_id, true );
		}
		return $res;
	}
	//获取客户数量
	function getNumber(){
		$userlog = M('survey_userlog');
		$number = $userlog->query("SELECT count(*) FROM `wp_survey_userlog` ");
		return $number;
	}
	//获取用户列表
	function getUserList(){
		$userlist = M('survey_userlog');
		//$data = $userlist->where()->order('id')->limit(0,20)->select();
		$page = I ( 'p', 1, 'intval' ); // 默认显示第一页数据

		// 解析列表规则

		$row = 20;

		$order = 'id desc';
		// 读取模型数据列表
		$px = C ( 'DB_PREFIX' );
		$data = $userlist->where()->order ( $order )->page ( $page, $row )->select ();
//		foreach ( $data as $k => $d ) {
//			$id = $d['id'];
//			$name = $d['name'];
//			$phone = $d['phone'];
//		}
		return $data;
	}
}
