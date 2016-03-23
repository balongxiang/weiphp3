<?php
// +----------------------------------------------------------------------
// | WM
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.everydayidea.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: balong <965584429@.qq.com>
// +----------------------------------------------------------------------
// | Date 2016/3/11
// +----------------------------------------------------------------------
namespace Addons\Wheel\Controller;
use Home\Controller\AddonsController;
//大转盘
class WheelController extends AddonsController{


    function index1()
    {
        $id = I('id', 0, 'intval');
        if($id){
            $map ['token'] = get_token ();
            $openid = get_openid();
            $public_info = get_token_appinfo ( $map ['token'] );
            $overtime = $this->is_over($id);

            $where = 'id=' . $id;
            $info = M('wheel')->where($where)->find();
//            $info['is_over'] = $overtime;
            $this->assign('info', $info);
            $this->assign('public_info', $public_info);
            $this->assign('status' , $overtime);

            $this->display();
        }else{
            $this->error('缺少游戏id');
        }


        //var_dump($info);
        //var_dump($overtime);
        /* }elseif ($overtime == 1) {
             //$this->assign('date' , $date );
             $this->display('notstarted');
         }else{
             //$this->assign('date' , $date);
             $this->display('finish');
         }*/
    }
    function index()
    {
        $code = I('code');
        $appid = "wx760ee41d6ae85680";
        $secret = "dd4f142d10e62f7940be4a77d5248e72";
        $get_token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$secret.'&code='.$code.'&grant_type=authorization_code';
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$get_token_url);
        curl_setopt($ch,CURLOPT_HEADER,0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        $res = curl_exec($ch);
        curl_close($ch);
        $json_obj = json_decode($res,true);
//根据openid和access_token查询用户信息
        $access_token = $json_obj['access_token'];
        $openid = $json_obj['openid'];
        $get_user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';

        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$get_user_info_url);
        curl_setopt($ch,CURLOPT_HEADER,0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        $res = curl_exec($ch);
        curl_close($ch);

//解析json
        $noOpenid = '"""';
        $user_obj = json_decode($res,true);
        $_SESSION['user'] = $user_obj;
        $openid = $user_obj['openid'];
        $openid = "'$openid'";
        $wheelUserlog = M('wheel_userlog');
        $isPrize = $wheelUserlog->query('SELECT * FROM `wp_wheel_userlog` where `ndate`>DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND `openid` ='.$openid);
        if($isPrize ) {

            $this->display('no_number');
        }elseif($openid != "''"){
            $userMsg['nickname'] = $user_obj['nickname'];
            $userMsg['openid'] = $user_obj['openid'];
            $userMsg['sex'] = $user_obj['sex'];
            $insert = M('wheel_userlog')->add($userMsg);

            $this->display();
        }else{
            $this->display('no_number');
        }


    }
    function del_wheel(){
        $id = I('id',0,'intval');
        var_dump($id);
       $delApply = M('wheel');
       $where = 'id='.$id;
       //$data = 'status=1';
       $isSucceed = $delApply->where($where)->setField('status',1);
       if($isSucceed) {
           $this->success('删除成功');
           }else{
           $this->error('出错啦！');
       }
    }
    function preview()
    {
        $id = I('id', 0, 'intval');
        $url = U('index', array(
            'id' => $id
        ));
        $newurl = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx760ee41d6ae85680&redirect_uri=http://zochdesign.com/weiphp3/index.php?s=/addon/Wheel/Wheel/index/id/'.$id.'html&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect';
        echo $newurl ;
//        http://zochdesign.com/weiphp3/index.php?s=/addon/Wheel/Wheel/index/id/'.$id.'html
        $this->assign('url', $newurl);
        $this->display(SITE_PATH . '/Application/Home/View/default/Addons/preview.html');
    }

    //插入数据库调用
    public function Wheel()
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
        $this->assign('name', $name);
        $this->assign('date', $date);
        //$this->assign ( '' )
        $this->display();
        //dump($data);
        //$msg['id'] = '';
        $msg["name"] = $name;
        $msg["phone"] = $phone;
        $msg["time"] = strftime($date);
        $msg["cc"] = $a;
        //var_dump($msg);
        $insert = M('wheel_userlog')->add($msg);
//        $insert = M ( 'flightid_userlog' )->execute("INSERT INTO `wp_flightid_userlog` (`name`, `phone`, `time`, `cc`) VALUES (['$name'],['$phone'],['$date'],['$a'])");
        if ($insert) {
            // echo "ok";
        } else {
            $this->error();
        }

    }

    function is_over($id){



            $id = I('id');
            // 先看看投票期限过期与否
            $model = M('wheel');
            //$the_survey = M ( "fightid" )->find ( $id);
            $the_survey = $model->where('id=' . $id)->order('id asc')->find();
            $time = time();
            for ($i = 0; $i < $count; $i++) {
                $data[$i]['start_time'] = explode(",", $data[$i]['start_time']);
                $data[$i]['end_time'] = explode(",", $data[$i]['end_time']);
            }
            $res['start_time'] = explode(",", $the_survey['start_time']);
            $res['end_time'] = explode(",", $the_survey['end_time']);
            $value = $res;
//        foreach ($res as $key => $v) {
            $count = count($res['start_time']);

            for ($i = 0; $i < $count; $i++) {
//                var_dump(intval($value['start_time'][$i]));
//                var_dump(intval($value['end_time'][$i]));
                if (intval($value['start_time'][$i]) < NOW_TIME && intval($value['end_time'][$i]) > NOW_TIME) {
                    $status = 0;//进行中
                }
                if (intval($value['start_time'][$i]) > NOW_TIME) {
                    $status = 1;//未开始
                }
                if (intval($value['start_time'][$i]) < NOW_TIME && intval($value['end_time'][$i]) > NOW_TIME) {
                    $status = 2;//今天已结束，还有下次
                }
                if (intval($value['start_time'][$i]) < NOW_TIME && intval($value['end_time'][$i]) < NOW_TIME) {
                    $status = 0;//已结束
                }

            }
            echo $status;

    }
    function insert(){
        $id = I('wheel_id');
        $name = I('name');
        $phone = I('phone', 0, 'intval');
        $date = time();
//        $a = $_GET['id'];
        $data = array($name, $phone, $date);
        $this->assign('name', $name);
        $this->assign('date', $date);
        //$this->assign ( '' )
        $this->display();
        //dump($data);
        //$msg['id'] = '';
        $msg["name"] = $name;
        $msg["phone"] = $phone;
        $msg['wheel_id'] = $id;
        $msg["date"] = strftime($date);
//        var_dump($msg);
        $insert = M('wheel_prizelog')->add($msg);
//        $insert = M ( 'flightid_userlog' )->execute("INSERT INTO `wp_flightid_userlog` (`name`, `phone`, `time`, `cc`) VALUES (['$name'],['$phone'],['$date'],['$a'])");
        if ($insert) {
//            echo "ok";
        } else {
            $this->error();
        }


    }


    function lists()
    {
        $apply = '抽奖大转盘';
        $isAjax = I('isAjax');
        $isRadio = I('isRadio');
        $model = M('wheel');
        $list_data = $model->where('status=0')->order('id asc')->select();

//        $model = $this->getModel ( 'flightid' );
//        $list_data = $this->_get_model_list ( $model, 0, 'id desc', true );
        // var_dump($list_data);

        if ($isAjax) {
            $this->assign('isRadio', $isRadio);
            $this->assign($list_data);
            $this->display('ajax_lists_data');
        } else {
            $this->assign('apply',$apply);
            $this->assign('data', $list_data);
            $this->display('newlists');
            //var_dump($list_data);
        }
    }

    function add()
    {
        $this->display();
    }
    function save(){
        $title = I('title');
//        $data = I('data');


        /* if(!empty($data)){

             $msg['title'] = $data['title'];

             $msg['cTime'] = time();

             $msg['start_time'] = $data['start_time'];

             $msg['end_time'] = $data['end_time'];

             $msg['total_frequency'] = $data['total_frequency'];

             $msg['user_frequency'] = $data['user_ferquency'];

             $wheel = M('wheel');

             $wheel_prizeList = M('wheel_prizelist');

             $insertWheel = $wheel->add($msg);

             $msgPrize['probability'] = $data['probability'];

             $msgPrize['prize_num'] = $data['prize_num'];

             $msgPrize['wheel_id'] = $insertWheel;

             $insertPrize = $wheel_prizeList->add($msgPrize);

             if($insertWheel && $insertPrize){
                 $this->success('保存成功');
             }else{
                 $this->error('错误，请检查SQL');
             }

             }else{
                 $this->error('data不能为空！');
             }*/
    }
    function bb(){

        $code = I('code');
        $appid = "wx760ee41d6ae85680";
        $secret = "dd4f142d10e62f7940be4a77d5248e72";
        $get_token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$secret.'&code='.$code.'&grant_type=authorization_code';
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$get_token_url);
        curl_setopt($ch,CURLOPT_HEADER,0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        $res = curl_exec($ch);
        curl_close($ch);
        $json_obj = json_decode($res,true);
//根据openid和access_token查询用户信息
        $access_token = $json_obj['access_token'];
        $openid = $json_obj['openid'];
        $get_user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';

        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$get_user_info_url);
        curl_setopt($ch,CURLOPT_HEADER,0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        $res = curl_exec($ch);
        curl_close($ch);

//解析json
        $user_obj = json_decode($res,true);
        $_SESSION['user'] = $user_obj;
//
        $userMsg['sex'] = $user_obj['sex'];
        $userMsg['nickname'] = $user_obj['nickname'];
        $userMsg['openid'] = $user_obj['openid'];
        $insert = M('wheel_userlog')->add($userMsg);
        if($insert){
            echo "ok";
        }else{
            echo "SQL error";
        }
        var_dump($userMsg);
    }
    function edit(){
        $id = I('id', 0, 'intval');
        $table = M('wheel');
        $where = 'a.id=b.wheel_id and a.id='.$id;
        $field = 'a.start_time,a.end_time,a.total_frequency,a.user_frequency,a.title,b.*';
        $data = $table->table('wp_wheel a,wp_wheel_prizelist b')->where($where)->field($field)->select();
        $wheelList = M('wheel_prizelist');
        $num = 'wheel_id='.$id;
        $count = $wheelList->where($num)->count();
        for ($i=0; $i<$count; $i++){
            $data[$i]['start_time'] = explode("," , $data[$i]['start_time']);
            $data[$i]['end_time'] = explode(",", $data[$i]['end_time']);
        }
        $this->assign('data',$data);
        $this->assign('joinurl',$joinUrl);
        $joinUrl=addons_url('Wheel://Wheel/is_over',array('id'=>1));
//        var_dump($joinUrl);
        $this->display();

    }
    function aa(){
        $id = I('id', 0, 'intval');
        $wheel = M('wheel');
        $where = 'a.id=b.wheel_id and a.id='.$id;
        $field = 'a.start_time,a.end_time,a.total_frequency,a.user_frequency,a.title,b.*';
        $data = $wheel->table('wp_wheel a,wp_wheel_prizelist b')->where($where)->field($field)->select();
        $wheelList = M('wheel_prizelist');
        $num = 'wheel_id='.$id;
        $count = $wheelList->where($num)->count();
        for ($i = 0; $i < $count; $i++) {
            $a[$i] = $data[$i]['prize_id'];
            $b[$i] = $data[$i]['probability'];
            $c[$i] = $data[$i]['prize_name'];
        }
//        var_dump($count);
        if($count != 0) {

            for ($i = 0; $i < $count; $i++){
                $list[$i] = array('id' => intval($i), 'prize' => $a[$i], 'prize_name' => $c[$i] ,'v' => $b[$i]);
            }
            for($i = $count; $i < 6; $i++){
                $list[$i] = array('id' => intval($i), 'prize' => null, 'prize_name' => $c[$i] ,'v' => ((100-array_sum($b))/(6-$count)));
            }

        }else{
            for($i = $count; $i < 6; $i++) {
                $list[$i] = array('id' => intval($i), 'prize' => null, 'prize_name' => $c[$i], 'v' => ((100 - array_sum($b)) / (6 - $count)));
            }
        }
//        var_dump($list);
        foreach ($list as $k=>$v) {
            $arr[$v['id']] = $v['v'];

        }
        $prize_id = getRand($arr);
        foreach($list as $k=>$v){ //获取前端奖项位置
            if($v['id'] == $prize_id && $v['prize']){
                $prize_site = $k;
                $pri_id = intval($v);
                break;
            }
        }
//        var_dump($prize_site);
        $res = $list[$prize_id]; //中奖项
        $result['id'] = $prize_id;
        $result['prize_site'] = $prize_site;
        $result['prize_name'] = $res['prize_name'];
        $result['pri_id'] = $pri_id;
        $w = 6;
//        $save = 'prize_num=prize_num+1';
        $updatePri = 'prize_id='.$pri_id;
        if($result['prize_name']){
            $updateM = M('wheel_prizelist');
            $update = $updateM->where($updatePri)->setDec('prize_num',1);//奖品数量-1
//            $this->assign('');
            if($update){
//                echo "success";
            }else{
                echo 'SQL error';
            }
        }
        $this->ajaxReturn($result);
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

function ajax(){
    //读取数据库信息
    $id = I('id', 0, 'intval');
    $wheel = M('wheel');
    $where = 'a.id=b.wheel_id and a.id='.$id;
    $field = 'a.start_time,a.end_time,a.total_frequency,a.user_frequency,a.title,b.*';
    $data = $wheel->table('wp_wheel a,wp_wheel_prizelist b')->where($where)->field($field)->select();
    $wheelList = M('wheel_prizelist');
    $num = 'wheel_id='.$id;
    $count = $wheelList->where($num)->count();
    for ($i = 0; $i < $count; $i++) {
        $a[$i] = $data[$i]['prize_id'];
        $b[$i] = $data[$i]['probability'];
        $c[$i] = $data[$i]['prize_name'];
    }
//        var_dump($count);
    if($count != 0) {

        for ($i = 0; $i < $count; $i++){
            $list[$i] = array('id' => intval($i), 'prize' => $a[$i], 'prize_name' => $c[$i] ,'v' => $b[$i]);
        }
        for($i = $count; $i < 10; $i++){
            $list[$i] = array('id' => intval($i), 'prize' => null, 'prize_name' => $c[$i] ,'v' => ((100-array_sum($b))/(10-$count)));
        }

    }else{
        for($i = $count; $i < 10; $i++) {
            $list[$i] = array('id' => intval($i), 'prize' => null, 'prize_name' => $c[$i], 'v' => ((100 - array_sum($b)) / (10 - $count)));
        }
    }
//        var_dump($list);
    foreach ($list as $k=>$v) {
        $arr[$v['id']] = $v['v'];

    }
    $prize_id = getRand($arr);
    foreach($list as $k=>$v){ //获取前端奖项位置
        if($v['id'] == $prize_id && $v['prize']){
            $prize_site = $k;
            $pri_id = intval($v);
            break;
        }
    }
//        var_dump($prize_site);
    $res = $list[$prize_id]; //中奖项
    $result['id'] = $prize_id;
    $result['prize_site'] = $prize_site;
    $result['prize_name'] = $res['prize_name'];
    $result['pri_id'] = $pri_id;
    $w = 6;
//        $save = 'prize_num=prize_num+1';
    $updatePri = 'prize_id='.$pri_id;
    if($result['prize_name']){
        $updateM = M('wheel_prizelist');
        $update = $updateM->where($updatePri)->setDec('prize_num',1);//奖品数量-1
//            $this->assign('');
        if($update){
//                echo "success";
        }else{
            echo 'SQL error';
        }
    }

}
function getRand($proArr) {
    $data = '';
    $proSum = array_sum($proArr); //概率数组的总概率精度

    foreach ($proArr as $k => $v) { //概率数组循环
        $randNum = mt_rand(1, $proSum);
        if ($randNum <= $v) {
            $data = $k;
            break;
        } else {
            $proSum -= $v;
        }
    }
    unset($proArr);

    return $data;
}

   function del11(){
       $id = I('id',0,'intval');
       var_dump($id);
//       $delApply = M('wheel');
//       $where = 'id='.$id;
//       //$data = 'status=1';
//       $isSucceed = $delApply->where($where)->setField('status',1);
//       if($isSucceed) {
//           $this->success('删除成功');
//           }else{
//           $this->error('出错啦！');
//       }
   }
    function delele(){
        $id = ('id');
        var_dump($id);
    }
function getcode(){

    $code = $_GET['code'];//获取code
    $weixin =  file_get_contents("https://api.weixin.qq.com/sns/oauth2/access_token?appid=wx760ee41d6ae85680&secret=dd4f142d10e62f7940be4a77d5248e72".$code."&grant_type=authorization_code");//通过code换取网页授权access_token
    $jsondecode = json_decode($weixin); //对JSON格式的字符串进行编码
    $array = get_object_vars($jsondecode);//转换成数组
    $openid = $array['openid'];//输出openid
    echo $openid;
}
function get(){
    $code = I('code');//获取code
    $state = I('state');
    $this->assign('code',$code);
    $this->display();
//        $weixin =  file_get_contents("https://api.weixin.qq.com/sns/oauth2/access_token?appid=这里是你的APPID&secret=这里是你的SECRET&code=".$code."&grant_type=authorization_code");//通过code换取网页授权access_token
//        $jsondecode = json_decode($weixin); //对JSON格式的字符串进行编码
//        $array = get_object_vars($jsondecode);//转换成数组
//        $openid = $array['openid'];//输出openid
    echo $_GET['code'];
    var_dump($code);
    var_dump($state);

}
function aaaa(){
//  https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx760ee41d6ae85680&redirect_uri=http://zochdesign.com/jhz/code.php&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect
}
function wheel_list(){
    $client = I('id');
    //var_dump($client);
    $userList = M('wheel_prizelist');
    $where = 'client='.$client;
    $data = $userList->where($where)->order('id asc')->select();
    $this->assign('data', $data);
    $this->display('datalist');
}
function export(){
    /*设计思路：client由前台单选框上传 ，plug未定*/
    //$plug = I('plug');
    $client = I('client');
//        if ( !empty($client)) {
    //$plug = I('plug');
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
    $userList = M('wheel_userlog');
    //$where = 'client='.$client;
    $arr = $userList->where ()->order ( 'id desc' )->select ();
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
//        }else{
//            $this->error('字段‘client’必传');
//        }



}