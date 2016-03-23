<?php
// +----------------------------------------------------------------------
// | WM--我们创意
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.everydayidea.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: balong <965584429@.qq.com>
// +----------------------------------------------------------------------
// | Date 2016/3/9
// +----------------------------------------------------------------------

namespace Addons\FlightId\Controller;
use Home\Controller\AddonsController;

class FlightIdController extends AddonsController
{
    //protected $flightid_model = M('flightid');
    function index()
    {
        $id = I('id', 0, 'intval');
        //var_dump($id);
        //$map ['token'] = get_token ();
        //$public_info = get_token_appinfo ( $map ['token'] );
        $overtime = $this->is_over($id);
        $where = 'id=' . $id;
        //$overtime = $overtime ? '1' : '0';
        $this->assign('status', $overtime);
        $info = M('flightid')->where($where)->find();
        $info['is_over'] = $overtime;
        $this->assign('info', $info);
        $this->assign('public_info', $public_info);
        $this->display('');
        //var_dump($info);
        //var_dump($overtime);
    }

    function preview()
    {
        $id = I('id', 0, 'intval');
        $url = U('index', array(
            'id' => $id
        ));
        //var_dump($url);
        $this->assign('url', $url);
        $this->display(SITE_PATH . '/Application/Home/View/default/Addons/preview.html');
    }

    //插入数据库调用
    public function flightid()
    {
        //$id = I ( 'get.id', 0, 'intval' );
        //$num = I ( 'num', 1, 'intval' );
        //$token = get_token ();
        //$survey = D ( 'Survey' )->getSurveyInfo ( $id );
        $name = I('name');
        $phone = I('phone', 0, 'intval');
        $date = time();
        $a = $_GET['id'];
//        $time = time();
        // http://120.55.162.48/weiphp3/Addons/FlightId/View/default/
        $data = array($name, $phone, $date);
//        var_dump($name);
        $this->assign('name', $name);
        $this->assign('date', $date);
        //$this->assign ( '' )
        $this->display();
        //dump($data);
        //$msg['id'] = '';
        $msg["name"] = $name;
        $msg["phone"] = $phone;
        $msg["time"] = strftime($date);
        $msg["client"] = $a;
        //var_dump($msg);
        $insert = M('flightid_userlog')->add($msg);
//        $insert = M ( 'flightid_userlog' )->execute("INSERT INTO `wp_flightid_userlog` (`name`, `phone`, `time`, `cc`) VALUES (['$name'],['$phone'],['$date'],['$a'])");
        if ($insert) {
            // echo "ok";
        } else {
            $this->error();
        }

    }

    private function is_over($id)
    {
        // 先看看投票期限过期与否
        $model = M('flightid');
        //$the_survey = M ( "fightid" )->find ( $id);
        $the_survey = $model->where('id=' . $id)->order('id asc')->find();
        //var_dump($the_survey);
        if (!empty ($the_survey ['start_time']) && $the_survey ['start_time'] < NOW_TIME && $the_survey['end_time'] > NOW_TIME) {
            $status = 0;//进行中
        }
        if (!empty ($the_survey ['start_time']) && $the_survey['start_time'] > NOW_TIME) {
            $status = 1;//未开始
        }
        if (!empty ($the_survey ['start_time']) && $the_survey['start_time'] > NOW_TIME) {
            $status = 2;//已结束
        }
        //var_dump($status);
        return $status;
        // $deadline = $the_survey ['end_date'] + 86400;
//        if (! empty ( $the_survey ['end_time'] ) && $the_survey ['end_time'] <= NOW_TIME)
//            return ture;

//        return false;
    }


    function lists()
    {
        $isAjax = I('isAjax');
        $isRadio = I('isRadio');
        $model = M('flightid');
        $list_data = $model->where('status=0')->order('id asc')->select();

//        $model = $this->getModel ( 'flightid' );
//        $list_data = $this->_get_model_list ( $model, 0, 'id desc', true );
        var_dump($list_data);

        if ($isAjax) {
            $this->assign('isRadio', $isRadio);``
            $this->assign($list_data);
            $this->display('ajax_lists_data');
        } else {
            $this->assign('data', $list_data);
            $this->display('lists');
        }
    }

    function add()
    {
        $this->display('edit');
    }

    function edit()
    {
        $id = I('id', 0, 'intval');
        $model = $this->getModel('flightid');

        if (IS_POST) {
            $this->checkDate();
            $act = empty ($id) ? 'add' : 'save';
            $Model = D(parse_name(get_table_name($model ['id']), 1));
            // 获取模型的字段信息
            $Model = $this->checkAttr($Model, $model ['id']);
            $res = false;
            $Model->create() && $res = $Model->$act ();
            if ($res !== false) {
                $act == 'add' && $id = $res;

                $this->_setAttr($id, $_POST);

                $this->success('保存成功！', U('lists?model=' . $model ['name'], $this->get_param));
            } else {
                $this->error($Model->getError());
            }
        } else {
            // 获取数据
            $data = M(get_table_name($model ['id']))->find($id);
            $data || $this->error('数据不存在！');

            $token = get_token();
            if (isset ($data ['token']) && $token != $data ['token'] && defined('ADDON_PUBLIC_PATH')) {
                $this->error('非法访问！');
            }
            $this->assign('data', $data);

            // 字段信息
            $map ['survey_id'] = $id;
            $map ['token'] = $token;
            $list = M('survey_question')->where($map)->order('sort asc')->select();

            $this->assign('attr_list', $list);

            $this->display('edit');
        }
        function checkDate()
        {
            // 判断时间选择是否正确
            if (!I('post.start_time')) {
                $this->error('请选择开始时间');
            } else if (!I('post.end_time')) {
                $this->error('请选择结束时间');
            } else if (strtotime(I('post.start_time')) > strtotime(I('post.end_time'))) {
                $this->error('开始时间不能大于结束时间');
            }
        }
    }
    function a(){
        $this->display('lists');
    }
    public function export(){
        /*设计思路：client由前台单选框上传 ，plug未定*/
        //$plug = I('plug');
        $client = I('client');
        if (!empty($plug) && !empty($client)) {
            $plug = I('plug');
            $client = I('client');
            header("Content-type:application/octet-stream");
            header("Accept-Ranges:bytes");
            header("Content-type:application/vnd.ms-excel");
            header("Content-Disposition:attachment;filename=用户信息表".date("Y-m-d H:i").".xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            //导出xls 开始
            $tag0 = iconv("UTF-8", "GB2312",'用户ID');
            $tag1 = iconv('UTF-8', "GB2312",'姓名');
            $tag2 = iconv('UTF-8', "GB2312",'手机');
            $tag3 = iconv('UTF-8', "GB2312",'参加活动时间');

            echo "$tag0\t$tag1\t$tag2\t$tag3\n";
            ////查询的一张表
            //$arr=M ('textpage')->field('username,count(id) as allcount,sum(price) as allprice ')->group('username')->    select();
            $userList = M('flightid_userlog');
            $where = 'client='.$client;
            $arr = $userList->where ($where)->order ( 'id desc' )->select ();
            //var_dump($arr);
            //dump(M ('textpage')->getLastSql());die;
            foreach($arr as $key=>$val){
                //$date = date('Y-m-d',$val['pay_time']);
                $tid = iconv("UTF-8", "GB2312", $val['id']);
                $username = iconv("UTF-8", "GB2312", $val['name']);
                $username=$username?$username:'-';
                $allcount = iconv("UTF-8", "GB2312", $val['phone']);
                $allcount=$allcount?$allcount:'-';
                $allprice = iconv("UTF-8", "GB2312", $val['time']);
                //$allprice = date($allprice|)

                echo "$tid\t$username\t$allcount\t$allprice\n";
            }
        }else{
            $this->error('字段‘client’必传');
        }

    }
}