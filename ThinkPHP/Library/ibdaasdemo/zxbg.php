<?php
require_once("TaskUtil.php");
require_once("Config.php");
require_once("configRedis.php");
function getArrVal($data, $key) {
    return isset($data[$key]) ? $data[$key] : '';
}
$fieldsArr=array();
$fieldsArr['black']=array();
$fieldsArr['black']['title'] = '网贷黑名单';
$fieldsArr['black']['isBan'][0]='不在网贷黑名单里';
$fieldsArr['black']['isBan'][1]='在网贷黑名单里';
$fieldsArr['black']['isBan']['title'] = '在不在网贷黑名单里';
$fieldsArr['black']['createTime']['title'] = '数据获取时间';
$fieldsArr['black']['reports']['title'] = '全量的黑名单详情';
$fieldsArr['black']['filteredReports']['title'] = '按时间过滤后黑名单详情';

$fieldsArr['black']['earliestLoanTime']['title'] = '最早借款时间';
$fieldsArr['black']['historyLoanTimes']['title'] = '历史借款次数';
$fieldsArr['black']['historyOverDueTimes']['title'] = '逾期次数';
$fieldsArr['black']['historyDueTime']['title'] = '已经还清的历史逾期最长时间';
$fieldsArr['black']['historyDueTime']['M1'] = '小于1月';
$fieldsArr['black']['historyDueTime']['M2'] = '大于1月，小于2月';
$fieldsArr['black']['historyDueTime']['M3'] = '小于3月';
$fieldsArr['black']['historyDueTime']['M4'] = '3月及以上';
$fieldsArr['black']['currentOverDueTimes']['title'] = '处于逾期状态的借款笔数';
$fieldsArr['black']['currentDueAmount']['title'] = '当前逾期总金额';
$fieldsArr['black']['currentDueTime']['title'] = '最长逾期时间';
$fieldsArr['black']['isRefused']['title'] = '最后一次申请是否被拒贷';
$fieldsArr['black']['refuseTime']['title'] = '拒贷时间';
$fieldsArr['black']['refuseReason']['title'] = '拒贷原因';
$fieldsArr['black']['refuseReason']['100']='不法分子';
$fieldsArr['black']['refuseReason']['101']='多头借贷';
$fieldsArr['black']['refuseReason']['102']='高风险区域';
$fieldsArr['black']['refuseReason']['103']='涉嫌不法';
$fieldsArr['black']['refuseReason']['104']='身份规则限制';
$fieldsArr['black']['refuseReason']['105']='身份认证失败';
$fieldsArr['black']['refuseReason']['106']='失信名单';
$fieldsArr['black']['refuseReason']['107']='疑似恶意欺诈';
$fieldsArr['black']['refuseReason']['108']='信用评分不足';
$fieldsArr['black']['isRefused']['title']='最后一次申请是否被拒贷';
$fieldsArr['black']['isRefused'][0]='否';
$fieldsArr['black']['isRefused'][1]='是';


$fieldsArr['anti_fraud']=array();
$fieldsArr['anti_fraud']['title'] = '反欺诈';
$fieldsArr['anti_fraud']['riskCode']['title'] = '风险码';
$fieldsArr['anti_fraud']['riskCode']['1']='信贷中介';
$fieldsArr['anti_fraud']['riskCode']['2']='不法分子';
$fieldsArr['anti_fraud']['riskCode']['3']='虚假资料';
$fieldsArr['anti_fraud']['riskCode']['4']='羊毛党';
$fieldsArr['anti_fraud']['riskCode']['5']='身份认证失败';
$fieldsArr['anti_fraud']['riskCode']['6']='疑似恶意欺诈';
$fieldsArr['anti_fraud']['riskCode']['7']='失信名单';
$fieldsArr['anti_fraud']['riskCode']['8']='异常支付行为';
$fieldsArr['anti_fraud']['riskCode']['301']='恶意环境';
$fieldsArr['anti_fraud']['riskCode']['503']='其他异常行为';
$fieldsArr['anti_fraud']['riskCodeValue']['title'] = '风险详情值';
$fieldsArr['anti_fraud']['riskCodeValue']['1']='低风险';
$fieldsArr['anti_fraud']['riskCodeValue']['2']='中风险';
$fieldsArr['anti_fraud']['riskCodeValue']['3']='高风险';

$fieldsArr['anti_fraud']['createTime']['title'] = '数据获取时间';
$fieldsArr['anti_fraud']['found']['title'] = '记录能否查到';
$fieldsArr['anti_fraud']['found']['1']='能查到';
$fieldsArr['anti_fraud']['found']['-1']='查不到';
$fieldsArr['anti_fraud']['idFound']['title'] = '身份证能否查到';
$fieldsArr['anti_fraud']['idFound']['1']='能查到';
$fieldsArr['anti_fraud']['idFound']['-1']='查不到';

$fieldsArr['anti_fraud']['riskScore']['title']='欺诈分值';
$fieldsArr['anti_fraud']['riskInfo']['title']='风险类型说明';



  


$fieldsArr['blackcourt']=array();
$fieldsArr['blackcourt']['title'] = '高法黑名单数据';
$fieldsArr['blackcourt']['isBan'][0]='不在高法黑名单数据里';
$fieldsArr['blackcourt']['isBan'][1]='在高法黑名单数据里';
$fieldsArr['blackcourt']['isBan']['title'] = '在不在高法黑名单数据里';
$fieldsArr['blackcourt']['createTime']['title'] = '数据获取时间';
$fieldsArr['blackcourt']['reports']['title'] = '黑名单详情';
$fieldsArr['blackcourt']['case_code']['title']='案号';
$fieldsArr['blackcourt']['case_create_time']['title']='立案时间';
$fieldsArr['blackcourt']['publish_date']['title']='发布时间';
$fieldsArr['blackcourt']['unperform_part']['title']='未执行部分';
$fieldsArr['blackcourt']['business_entity']['title']='商业实体';
$fieldsArr['blackcourt']['type']['title']='高法黑名单类型';
$fieldsArr['blackcourt']['i_name']['title']='用户姓名';
$fieldsArr['blackcourt']['disrupt_type_name']['title']='失信原因';
$fieldsArr['blackcourt']['area_name']['title']='地区';
$fieldsArr['blackcourt']['court_name']['title']='法院名称';
$fieldsArr['blackcourt']['last_mod']['title']='最后更新时间';
$fieldsArr['blackcourt']['sexy']['title']='失信人员性别';
$fieldsArr['blackcourt']['performed_part']['title']='已执行部分';
$fieldsArr['blackcourt']['duty']['title']='判决结果';
$fieldsArr['blackcourt']['age']['title']='年龄';



$fieldsArr['blackcrime']=array();
$fieldsArr['blackcrime']['title'] = '负面信息记录';
$fieldsArr['blackcrime']['isBan'][0]='不在负面信息记录里';
$fieldsArr['blackcrime']['isBan'][1]='在负面信息记录里';
$fieldsArr['blackcrime']['isBan']['title'] = '在不在负面信息记录里';
$fieldsArr['blackcrime']['createTime']['title'] = '数据获取时间';
$fieldsArr['blackcrime']['reports']['title'] = '黑名单详情';
$fieldsArr['blackcrime']['caseType']['title'] = '犯罪类型';
$fieldsArr['blackcrime']['caseType']['CTYPE_0001']='其它';
$fieldsArr['blackcrime']['caseType']['CTYPE_0002']='背叛、分裂国家案';
$fieldsArr['blackcrime']['caseType']['CTYPE_0003']='投敌叛变案';
$fieldsArr['blackcrime']['caseType']['CTYPE_0004']='非法提供秘密情报案';
$fieldsArr['blackcrime']['caseType']['CTYPE_0005']='危害社会公共安全案';
$fieldsArr['blackcrime']['caseType']['CTYPE_0006']='破坏交通运输公共设施案';
$fieldsArr['blackcrime']['caseType']['CTYPE_0007']='实施恐怖案';
$fieldsArr['blackcrime']['caseType']['CTYPE_0008']='非法枪支弹药爆炸案';
$fieldsArr['blackcrime']['caseType']['CTYPE_0009']='违反枪支弹药管理案';
$fieldsArr['blackcrime']['caseType']['CTYPE_0010']='重大安全责任事故案';
$fieldsArr['blackcrime']['caseType']['CTYPE_0011']='生产、销售伪劣商品案';
$fieldsArr['blackcrime']['caseType']['CTYPE_0012']='走私案';
$fieldsArr['blackcrime']['caseType']['CTYPE_0013']='妨害对公司、企业的管理秩序案';
$fieldsArr['blackcrime']['caseType']['CTYPE_0014']='破坏金融管理秩序案';
$fieldsArr['blackcrime']['caseType']['CTYPE_0015']='金融诈骗案';
$fieldsArr['blackcrime']['caseType']['CTYPE_0016']='危害税收征管案';
$fieldsArr['blackcrime']['caseType']['CTYPE_0017']='侵犯知识产权案';
$fieldsArr['blackcrime']['caseType']['CTYPE_0018']='扰乱市场秩序案';
$fieldsArr['blackcrime']['caseType']['CTYPE_0019']='非法经营案';
$fieldsArr['blackcrime']['caseType']['CTYPE_0020']='侵犯人身权利案';
$fieldsArr['blackcrime']['caseType']['CTYPE_0021']='破坏民族平等、宗教信仰案';
$fieldsArr['blackcrime']['caseType']['CTYPE_0022']='侵犯民主权利案';
$fieldsArr['blackcrime']['caseType']['CTYPE_0023']='出售、非法提供公民个人信息案';
$fieldsArr['blackcrime']['caseType']['CTYPE_0024']='妨碍婚姻家庭权利案';
$fieldsArr['blackcrime']['caseType']['CTYPE_0025']='侵犯财产案';
$fieldsArr['blackcrime']['caseType']['CTYPE_0026']='扰乱公共秩序案';
$fieldsArr['blackcrime']['caseType']['CTYPE_0027']='妨害司法案';
$fieldsArr['blackcrime']['caseType']['CTYPE_0028']='妨害国（边）境管理案';
$fieldsArr['blackcrime']['caseType']['CTYPE_0029']='妨害文物管理案';
$fieldsArr['blackcrime']['caseType']['CTYPE_0030']='危害公共卫生案';
$fieldsArr['blackcrime']['caseType']['CTYPE_0031']='破坏环境资源保护案';
$fieldsArr['blackcrime']['caseType']['CTYPE_0032']='涉毒案';
$fieldsArr['blackcrime']['caseType']['CTYPE_0033']='卖淫、传播淫秽物品案';
$fieldsArr['blackcrime']['caseType']['CTYPE_0034']='危害国防利益案';
$fieldsArr['blackcrime']['caseType']['CTYPE_0035']='贪污贿赂案';
$fieldsArr['blackcrime']['caseType']['CTYPE_0036']='渎职案';
$fieldsArr['blackcrime']['caseType']['CTYPE_0037']='军人违反职责案';
$fieldsArr['blackcrime']['caseType']['CTYPE_0038']='在逃';
$fieldsArr['blackcrime']['caseType']['CTYPE_0039']='吸毒';
$fieldsArr['blackcrime']['caseTime']['title'] = '犯罪时间段';
$fieldsArr['blackcrime']['caseTime']['CTIME_0001']='0.25 年(即 3 个月)(不含)以内';
$fieldsArr['blackcrime']['caseTime']['CTIME_0002']='0.25 年(即 3 个月)以上， 0.5 年(即 6 个月)(不含)以内';
$fieldsArr['blackcrime']['caseTime']['CTIME_0003']='0.5 年(即 6 个月)以上，1 年(不含)以内';
$fieldsArr['blackcrime']['caseTime']['CTIME_0004']='1 年以上， 2 年(不含)以内';
$fieldsArr['blackcrime']['caseTime']['CTIME_0005']='2 年以上， 5 年(不含)以内';
$fieldsArr['blackcrime']['caseTime']['CTIME_0006']='5 年以上， 10 年(不含)以内';
$fieldsArr['blackcrime']['caseTime']['CTIME_0007']='10 年以上， 15 年(不含)以内';
$fieldsArr['blackcrime']['caseTime']['CTIME_0008']='15 年以上， 20(不含)以内';
$fieldsArr['blackcrime']['caseTime']['CTIME_0009']='20 年(含)以上';
$fieldsArr['blackcrime']['caseTime']['CTIME_0010']='未知';

/*
$fieldsArr['accdetect']=array();
$fieldsArr['accdetect']['title'] = '借贷类产品多头';
$fieldsArr['accdetect']['codes']['title']='';
$fieldsArr['accdetect']['registered']['title']='已注册的网贷平台编码列表';
$fieldsArr['accdetect']['unregistered']['title']='未注册的网贷平台编码列表';
$fieldsArr['accdetect']['unknown']['title']='状态未知的网贷平台编码列表';

$fieldsArr['accdetect']['codes']['ADT0001']= '2345贷款王';
$fieldsArr['accdetect']['codes']['ADT0002']= '贷上钱';
$fieldsArr['accdetect']['codes']['ADT0003']= '卡卡贷';
$fieldsArr['accdetect']['codes']['ADT0004']= '信用钱包';
$fieldsArr['accdetect']['codes']['ADT0005']= '曹操贷';
$fieldsArr['accdetect']['codes']['ADT0006']= '小赢卡贷';
$fieldsArr['accdetect']['codes']['ADT0007']= '简单借款';
$fieldsArr['accdetect']['codes']['ADT0008']= '人人贷';
$fieldsArr['accdetect']['codes']['ADT0009']= '现金白卡';
$fieldsArr['accdetect']['codes']['ADT0010']= '现金宝';
$fieldsArr['accdetect']['codes']['ADT0011']= '趣店';
$fieldsArr['accdetect']['codes']['ADT0012']= '马上消费';
$fieldsArr['accdetect']['codes']['ADT0013']= '玖富叮当';
$fieldsArr['accdetect']['codes']['ADT0014']= '蜡笔分期';
$fieldsArr['accdetect']['codes']['ADT0015']= '拉卡拉';
$fieldsArr['accdetect']['codes']['ADT0016']= '水象分期';
$fieldsArr['accdetect']['codes']['ADT0017']= '发薪贷';
$fieldsArr['accdetect']['codes']['ADT0018']= '宜信惠民';
$fieldsArr['accdetect']['codes']['ADT0019']= '布丁小贷';
$fieldsArr['accdetect']['codes']['ADT0020']= '指尖贷';
$fieldsArr['accdetect']['codes']['ADT0021']= '光速贷';
$fieldsArr['accdetect']['codes']['ADT0022']= '极速现金侠';
$fieldsArr['accdetect']['codes']['ADT0023']= '牛呗借钱借款';
$fieldsArr['accdetect']['codes']['ADT0024']= '杏仁钱包';
$fieldsArr['accdetect']['codes']['ADT0025']= '信用白条';
$fieldsArr['accdetect']['codes']['ADT0026']= '豆豆钱';
$fieldsArr['accdetect']['codes']['ADT0027']= '奇速贷';
$fieldsArr['accdetect']['codes']['ADT0028']= '缺钱么';
$fieldsArr['accdetect']['codes']['ADT0029']= '宜人贷';
$fieldsArr['accdetect']['codes']['ADT0030']= '借乐花';
$fieldsArr['accdetect']['codes']['ADT0031']= '钱有路';
$fieldsArr['accdetect']['codes']['ADT0032']= '麦芽贷';
$fieldsArr['accdetect']['codes']['ADT0033']= '人人花';
$fieldsArr['accdetect']['codes']['ADT0034']= '松鼠贷';
$fieldsArr['accdetect']['codes']['ADT0035']= '新易贷微贷款';
$fieldsArr['accdetect']['codes']['ADT0036']= '千百块';
$fieldsArr['accdetect']['codes']['ADT0037']= '贷你嗨';
$fieldsArr['accdetect']['codes']['ADT0038']= '读秒钱包';
$fieldsArr['accdetect']['codes']['ADT0039']= '你我贷';
$fieldsArr['accdetect']['codes']['ADT0040']= '拍拍贷';
$fieldsArr['accdetect']['codes']['ADT0041']= '零零期';
$fieldsArr['accdetect']['codes']['ADT0042']= '优分期';
*/

$data = array(
    "idCardNo"=>"520103198908304841", //身份证号
    "name"=>"安婷婷", //姓名
    "phoneNo"=>"18685056811",
    "basicInfo"=>array(
        "idNumber"=>"101123198008082910",
        "phoneNumber"=>"13910892098", //必填
        "bankCardNumber"=>"62220002",
        "userIp"=>"",
        "imei"=>"",
        "idfa"=>""
    ),
    "otherInfo"=>array(
        "name"=>"张三",
        "emailAddress"=>"",
        "address"=>"", //用户住址
        "mac"=>"",
        "imsi"=>""
    )
); 







function getRequest($taskType,$dataArr,$fieldsArr,$Redis=null){
    $blackAllArr=array();
            switch($taskType){
            case 'black':
                $data=array();
                if(getArrVal($dataArr,'phoneNo')){
                    $data= array(
                        "sync"=>1,
                        "idCardNo"=>getArrVal($dataArr,'idCardNo'),
                        "name"=>getArrVal($dataArr,'name'),
                        "phoneNo"=>getArrVal($dataArr,'phoneNo'),
                        "startTime"=>'20170101',
                        "endTime"=>date('Ym').'01'
                    );
                }
                break;
            case 'anti_fraud':
                $data=array();
                if(getArrVal($dataArr,'basicInfo')){
                    $data= array(
                        "sync"=>1,
                        "basicInfo"=>getArrVal($dataArr,'basicInfo'), 
                        "otherInfo"=>getArrVal($dataArr,'otherInfo'), 
                    );
                }
                break;
            case 'blackcourt':
                $data=array();
                if(getArrVal($dataArr,'phoneNo') && getArrVal($dataArr,'idCardNo') && getArrVal($dataArr,'name')){
                    $data= array(
                        "phoneNo"=>getArrVal($dataArr,'phoneNo'), 
                        "name"=>getArrVal($dataArr,'name'),
                        'idCardNo'=>getArrVal($dataArr,'idCardNo'),
                    );
                }
                break;
            case 'blackcrime':
                $data=array();
                if(getArrVal($dataArr,'phoneNo') && getArrVal($dataArr,'idCardNo')){
                    $data= array(
                        "sync"=>1, 
                        // "phoneNo"=>getArrVal($dataArr,'phoneNo'), 
                        "name"=>getArrVal($dataArr,'name'),
                        'idCardNo'=>getArrVal($dataArr,'idCardNo'),
                    );
                }
                break;
            case 'accdetect':
                $data=array();
                if(getArrVal($dataArr,'phoneNo')){
                    $data= array(
                        "phoneNo"=>getArrVal($dataArr,'phoneNo'), // 手机号，必填
                    );
                }
                break;
        }

        try {
            if($data){
                $post = array(
                    "callbackUrl" => "",
                    "data" => $data
                );
                $key = MD5($taskType.':'.json_encode($post));
                if($Redis->get($key)){
                   $result = json_decode($Redis->get($key),true);
                }else{
                    $result = TaskUtil::submitTask($taskType, $post);
                    if('fail' == getArrVal($result,'taskStatus')){
                        // continue;
                    }
                    if('processing' == getArrVal($result,'taskStatus')){
                        $taskNo=getArrVal($result,'taskNo');
                        $result = TaskUtil::queryTask($taskNo);
                    }
                    if('success' == getArrVal($result,'taskStatus')){
                        $taskNo=getArrVal($result,'taskNo');
                        $result = TaskUtil::queryTask($taskNo);
                        $Redis->set($key,json_encode($result));
                    }
                    if($taskType=='accdetect'){
                    }
                }
        // echo $taskType,PHP_EOL;
        // echo "<hr>";
        //             print_r($post);
        //             echo PHP_EOL;
        //             echo "<hr>";
        //         var_dump($result);
        //                 echo PHP_EOL;
        //                 echo "<hr>";
            // $blackAllArr[$taskType]['taskResult']=getArrVal($result,'taskResult');
                // $blackAllArr[$taskType]['response']=$result;
                // $blackAllArr[$taskType]['title']=getArrVal($fieldsArr[$taskType],'title');
                $blackAllArr['response']=$result;
                // var_dump($post);
// var_dump($blackAllArr);
 
            }
        } catch (Exception $e) {
            echo $e;
        }
        $blackAllArr['title']=getArrVal($fieldsArr[$taskType],'title');
        return $blackAllArr;
}

function getBlackAll($dataArr,$fieldsArr,$Reids=null){
    $blackAllArr=array();
    foreach ($fieldsArr as $taskType => $row) {
        $blackAllArr[$taskType]=getRequest($taskType,$dataArr,$fieldsArr,$Reids);
        // echo PHP_EOL;
        // echo $taskType;

    }
        return $blackAllArr;
        // die();
    // print_r($blackAllArr);

}
// getBlackAll($data,$fieldsArr,$Reids);

// $idCardNo = "31010719841107045X"; //身份证号
// $name="朱殿撰"; //姓名
// $phoneNo ="13917625306";

// $data = array(
//     "idCardNo"=>$idCardNo,
//     "name"=>$name, //姓名
//     "phoneNo"=>$phoneNo,
//     "basicInfo"=>array(
//         "idNumber"=>$idCardNo,
//         "phoneNumber"=>$phoneNo, //必填
//         "bankCardNumber"=>"62220002",
//         "userIp"=>"",
//         "imei"=>"",
//         "idfa"=>""
//     ),
//     "otherInfo"=>array(
//         "name"=>"张三",
//         "emailAddress"=>"",
//         "address"=>"", //用户住址
//         "mac"=>"",
//         "imsi"=>""
//     )
// ); 

// $taskType='accdetect';
// $taskNo='f256a0b6ef40459592d19f499a3cde661528808373408';
// // $arr = getRequest($taskType,$data,$fieldsArr,$Reids);


// $arr=TaskUtil::queryTask($taskNo);
// var_dump($arr);

try {
    // 网贷黑名单
/*    $post = array(
        "callbackUrl" => "", //根据实际情况设置,如果采用查询方式获取结果，可以设为“”
        "data" => array(
            "sync"=>1, //1为同步返回结果，其他数字或者不传代表异步
            "idCardNo"=>"520103198908304841", //身份证号
            "name"=>"安婷婷", //姓名
            "phoneNo"=>"18685056811", // 手机号，必填
            "startTime"=>"20160901", // 开始时间
            "endTime"=>"20171012" // 结束时间
        )
    );
    $taskType='black';
    $result = TaskUtil::submitTask(Config::TYPE_BLACK, $post);
    print_r($result);
*/

// 反欺诈

/*    $post = array(
        "callbackUrl" => "", //根据实际情况设置,如果采用查询方式获取结果，可以设为“”
        "data" => array(
            "sync"=>1,
            "basicInfo"=>array(
                "idNumber"=>"101123198008082910",
                "phoneNumber"=>"13910892098", //必填
                "bankCardNumber"=>"62220002",
                "userIp"=>"",
                "imei"=>"",
                "idfa"=>""
            ),
            "otherInfo"=>array(
                "name"=>"张三",
                "emailAddress"=>"",
                "address"=>"", //用户住址
                "mac"=>"",
                "imsi"=>""
            )
        )
    );
    $taskType='anti_fraud';
    $result = TaskUtil::submitTask('anti_fraud', $post);
    print_r($result);
*/

/*$post = array(
        "callbackUrl" => "", //根据实际情况设置,如果采用查询方式获取结果，可以设为“”
        "data" => array(
            "sync"=>1, //1为同步返回结果，其他数字或者不传代表异步
            "idCardNo"=>"520103198908304841", //身份证号
            "name"=>"安婷婷", //姓名
            "phoneNo"=>"18685056811", // 手机号，必填
        )
    );
    $result = TaskUtil::submitTask(Config::TYPE_BLACKCOURT, $post);
    print_r($result);*/
// 负面信息记录

/*$post = array(
        "callbackUrl" => "", //根据实际情况设置,如果采用查询方式获取结果，可以设为“”
        "data" => array(
            "sync"=>1, //1为同步返回结果，其他数字或者不传代表异步
            "idCardNo"=>"520103198908304841", //身份证号
            "name"=>"安婷婷", //姓名
        )
    );
    $result = TaskUtil::submitTask(Config::TYPE_BLACKCRIME, $post);
    print_r($result);
*/
// 借贷类产品多头


/*$post = array(
        "callbackUrl" => "", //根据实际情况设置,如果采用查询方式获取结果，可以设为“”
        "data" => array(
            "phoneNo"=>"18685056811", // 手机号，必填
        )
    );
    $result = TaskUtil::submitTask('accdetect', $post);
    print_r($result);
*/


} catch (Exception $e) {
    return $e;
}



$reports='[{
            "earliestLoanTime": "2017-03-03 00:00:00",
            "historyLoanTimes": 4,
            "historyOverDueTimes": 2,
            "currentOverDueTimes": 1,
            "historyDueTime": "M2",
            "currentDueAmount": 3,
            "currentDueTime": "M1",
            "isRefused":"1",
            "refuseReason":"100",
            "refuseTime":"201704"
        },{
            "earliestLoanTime": "",
            "historyLoanTimes": 0,
            "historyOverDueTimes": 0,
            "currentOverDueTimes": 0,
            "historyDueTime": "",
            "currentDueAmount": 0,
            "currentDueTime": "",
            "isRefused": "1",
            "refuseReason": "106",
            "refuseTime": "201708"
        }]';


$reportsArr=(json_decode($reports,true));

/*Array
(
    [code] => general_0
    [message] => 
    [taskNo] => fc534ae849be481185f5b21928ade69d1528467992872
    [taskStatus] => success
    [taskResult] => Array
        (
            [reports] => Array
                (
                    [0] => Array
                        (
                            [isRefused] => 1
                            [refuseReason] => 104
                            [refuseTime] => 201712
                            [earliestLoanTime] => 
                            [historyLoanTimes] => 0
                            [historyOverDueTimes] => 0
                            [currentOverDueTimes] => 0
                            [currentDueAmount] => 0
                            [currentDueTime] => 
                            [historyDueTime] => 
                        )

                )

            [createTime] => 2018-06-08 22:26:33
            [isBan] => 1
            [filteredReports] => Array
                (
                )

        )

)

Array
(
    [code] => general_6
    [message] => 您的账号还未开通访问此类数据的权限，请联系商务处理
    [taskStatus] => fail
)


Array
(
    [code] => general_0
    [message] => 
    [taskStatus] => processing
    [taskNo] => 7a33ee9e22294ea699776e3de0d2aa4f1528468996700
)

Array
(
    [code] => general_0
    [message] => 
    [taskNo] => 7a33ee9e22294ea699776e3de0d2aa4f1528468996700
    [taskStatus] => success
    [taskResult] => Array
        (
            [reports] => Array
                (
                )

            [createTime] => 2018-06-08 22:43:20
            [isBan] => 0
        )

)


Array
(
    [code] => general_0
    [message] => 
    [taskNo] => 7a33ee9e22294ea699776e3de0d2aa4f1528468996700
    [taskStatus] => success
    [taskResult] => Array
        (
            [reports] => Array
                (
                )

            [createTime] => 2018-06-08 22:43:20
            [isBan] => 0
        )

)


Array
(
    [code] => general_0
    [message] => 
    [taskNo] => 7a33ee9e22294ea699776e3de0d2aa4f1528468996700
    [taskStatus] => success
    [taskResult] => Array
        (
            [reports] => Array
                (
                )

            [createTime] => 2018-06-08 22:43:20
            [isBan] => 0
        )

)


*/