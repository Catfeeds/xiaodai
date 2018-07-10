<?php
/**
 * Created by PhpStorm.
 * User: YJ
 * Date: 2017/5/12
 * Time: 10:32
 */

namespace Admin\Controller;


use Think\Controller;

class ApiController extends Controller
{

    /**
     * 导出游客信息Excel
     */
   public function expdata($type="",$status="",$applystatus="",$sourceid="",$producttypeid="",$levelid="",$memberstatusid="",$keyword="",$sex="",$staffid="")
    {//导出Excel
        Vendor("PHPExcel.PHPExcel.Cell.DataType");

        switch($type){
            case 'staff_follow':
                $xlsName = date('Ymd')."-导出客户";
                $xlsCell = array(
                    array('sequence', '序号',6),
                    array('username', '姓名',12),
                    array('nickname', '昵称',12),
                    array('telephone', '联系电话',13),
                    array('email', '邮箱'),
                    array('company', '公司'),
                    array('zhiwei', '职位'),
                    array('need', '需要什么'),
                    array('have', '有什么'),
                    array('addtime', '添加时间')
                );

                $xlsCell = array(
                    array('sequence', '序号'),
                    array('company', '公司名称'),
                    array('telephone', '联系电话'),
                    array('producttype', '产品名称'),
                    array('targetamount', '预估金额'),
                    array('memberstatus', '销售阶段'),
                    array('staffname', '客户经理'),
                    array('sex', '性别'),
                    array('address', '地址')
                );

                if (! isN ( $keyword )) {
                    $map ['company'] = array (
                        'like',
                        '%' . $keyword . '%'
                    );
                    $map ['userreal'] = array (
                        'like',
                        '%' . $keyword . '%'
                    );
                    $map ['address'] = array (
                        'like',
                        '%' . $keyword . '%'
                    );
                    $map ['telephone'] = array (
                        'like',
                        '%' . $keyword . '%'
                    );
                    $map ['targetamount'] = array (
                        'like',
                        '%' . $keyword . '%'
                    );

                    $map['_logic']='or';
                    $where['_complex']=$map;
                }

                if($sex>0){
                    $where['sex']=$sex;
                }

                if (is_numeric ( $status )) {
                    $where ['status'] = $status;
                }

                if(is_numeric ( $applystatus )){
                    $where ['applystatus'] = $applystatus;
                }

                if($staffid>0){
                    $where ['staffid'] = $staffid;
                }

                if($sourceid>0){
                    $where ['source'] = $sourceid;
                }
                if($producttypeid>0){
                    $where ['producttype'] = $producttypeid;
                }
                if($levelid>0){
                    $where ['level'] = $levelid;
                }
                if($memberstatusid>0){
                    $where ['memberstatus'] = $memberstatusid;
                }

                $where['telephone']=array('neq','');

                $xlsModel = M('member');

                $xlsData = $xlsModel->where($where)->order('addtime desc')->select();
                $expdata=array();
                foreach ($xlsData as $k => $v) {
                    $expdata[$k]['sequence'] = $k +1;
                    $expdata[$k]['company'] = $v['company'];
                    $expdata[$k]['telephone'] = $v['telephone'];
                    $expdata[$k]['producttype'] = get_cache_value('member_producttype',$v['producttype'],'name');
                    $expdata[$k]['targetamount'] = $v['targetamount'];
                    $expdata[$k]['memberstatus'] = get_cache_value('member_status',$v['memberstatus'],'name');
                    $expdata[$k]['staffname'] =get_cache_value('staff',$v['staffid'],'name');
                    $expdata[$k]['sex'] = $v['sex']==1?'男':'女';
                    $expdata[$k]['address'] = $v['address'];
                }
//                dump($expdata);die;
                $this->exportExcel($xlsName, $xlsCell, $expdata);
                break;
            default:
                break;
        }



    }


    public function exportExcel($expTitle, $expCellName, $expTableData)
    {
        $xlsTitle = iconv('utf-8', 'gb2312', $expTitle);//文件名称
        $fileName = $expTitle;//or $xlsTitle 文件名称可根据自己情况设定
        $cellNum = count($expCellName);
        $dataNum = count($expTableData);
        Vendor("PHPExcel.PHPExcel");
        Vendor("PHPExcel.PHPExcel.IOFactory");
        Vendor("PHPExcel.PHPExcel.Style.Border");
        $objPHPExcel = new \PHPExcel();
        $cellName = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ');

        //$objPHPExcel->getActiveSheet(0)->mergeCells('A1:' . $cellName[$cellNum - 1] . '1');//合并单元格
        // $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $expTitle.'  Export time:'.date('Y-m-d H:i:s'));
        for ($i = 0; $i < $cellNum; $i++) {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i] . '1', $expCellName[$i][1]);
        }
        // Miscellaneous glyphs, UTF-8
        for ($i = 0; $i < $dataNum; $i++) {
            for ($j = 0; $j < $cellNum; $j++) {
                if($expCellName[$j][3])
                    $objPHPExcel->getActiveSheet(0)->setCellValueExplicit($cellName[$j] . ($i + 2), $expTableData[$i][$expCellName[$j][0]],$expCellName[$j][3]);
                else
                    $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j] . ($i + 2), $expTableData[$i][$expCellName[$j][0]]);
            }
        }

        for ($i = 0; $i < $cellNum; $i++) {
            if($expCellName[$i][2])
                $objPHPExcel->getActiveSheet(0)->getColumnDimension($cellName[$i])->setWidth($expCellName[$i][2]);
        }
        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => \PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );
        $objPHPExcel->getDefaultStyle()->applyFromArray($styleArray);


//        $objPHPExcel->getDefaultStyle()
//            ->getBorders()
//            ->getTop()
//            ->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

        header('pragma:public');
        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="' . $xlsTitle . '.xls"');
        header("Content-Disposition:attachment;filename=$fileName.xls");//attachment新窗口打印inline本窗口打印
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }




    public function importstudent(){
        $tmp_file = $_FILES ['file'] ['tmp_name'];

        $file_types = explode ( ".", $_FILES ['file']['name'] );
        $file_type = $file_types [count ( $file_types ) - 1];
        /*判别是不是.xls文件，判别是不是excel文件*/
        if (strtolower ( $file_type ) !="xlsx")
        {
            $this->error ( '不是标准上传模板文件，重新上传' );
        }
        /*设置上传路径*/
        $savePath = $_SERVER['DOCUMENT_ROOT'].'/Public/uploadfile/Excel/';

        if(!file_exists($savePath))
            mkdir($savePath);
        /*以时间来命名上传的文件*/
        $str = date ( 'Ymdhis' );
        $file_name = $str . "." . $file_type;

        /*是否上传成功*/
        if (! move_uploaded_file ( $tmp_file, $savePath . $file_name ))
        {
            $this->error ( '上传失败' );
        }

        vendor("PHPExcel.PHPExcel");
        vendor("PHPExcel.PHPExcel.IOFactory");

        $root=$_SERVER[DOCUMENT_ROOT];
        //echo $root;
        $file_name=$savePath.$file_name;
        $objReader = new \PHPExcel_Reader_Excel2007();
        $objPHPExcel = $objReader->load($file_name,$encode='utf-8');

        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow(); // 取得总行数
        $highestColumn = $sheet->getHighestColumn(); // 取得总列数



        $result=array();
        $error=array();
        $err=0;
        $data=array();

        $error='';
        //执行导入前检查数据
        $j=0;


        for($i=2;$i<=$highestRow;$i++)
        {
            $username=$objPHPExcel->getActiveSheet()->getCell("A".$i)->getValue();
            if($username==''){
                $error=$error.'【第A'.$i.'单元格内容为空，请输入考生姓名！】<br/>';
            }
            if(is_object($username))
                $username= $username->__toString();

            $data[$j]['username']=$username;

            $telephone=$objPHPExcel->getActiveSheet()->getCell("B".$i)->getValue();
            if($telephone==''){
                $error=$error.'【第B'.$i.'单元格内容为空，请输入考生联系电话！】<br/>';
            }
            $find=M('student')->where(array('telephone'=>$telephone))->find();
            if($find){
                $error=$error.'【第B'.$i.'单元格联系电话在系统中已存在！】<br/>';
            }
            if(is_object($telephone))
                $telephone= $telephone->__toString();
            $data[$j]['telephone']=$telephone;

            $no=$objPHPExcel->getActiveSheet()->getCell("C".$i)->getValue();
            if($no==''){
                $error=$error.'【第C'.$i.'单元格内容为空，请输入考生学号！】<br/>';
            }
            $find=M('student')->where(array('no'=>$no))->find();
            if($find){
                $error=$error.'【第C'.$i.'单元格学号在系统中已存在！】<br/>';
            }
            if(is_object($no))
                $no= $no->__toString();
            $data[$j]['no']=$no;


            $userpwd=$objPHPExcel->getActiveSheet()->getCell("D".$i)->getValue();
            if($userpwd==''){
                $error=$error.'【第D'.$i.'单元格密码为空，请输入密码！】<br/>';
            }
            if(is_object($userpwd))
                $userpwd= $userpwd->__toString();
            $data[$j]['userpwd']=md5($userpwd);


            $major=$objPHPExcel->getActiveSheet()->getCell("E".$i)->getValue();
            if(is_object($major))
                $major= $major->__toString();
            $data[$j]['major']=$major;


            $grade=$objPHPExcel->getActiveSheet()->getCell("F".$i)->getValue();
            if(is_object($grade))
                $grade= $grade->__toString();
            $data[$j]['grade']=$grade;


            $class=$objPHPExcel->getActiveSheet()->getCell("G".$i)->getValue();
            if(is_object($class))
                $class= $class->__toString();
            $data[$j]['class']=$class;


            $qq=$objPHPExcel->getActiveSheet()->getCell("H".$i)->getValue();
            if(is_object($qq))
                $qq= $qq->__toString();
            $data[$j]['qq']=$qq;

            $email=$objPHPExcel->getActiveSheet()->getCell("I".$i)->getValue();
            if(is_object($email))
                $email= $email->__toString();
            $data[$j]['email']=$email;

            $data[$j]['status']=1;

            $j++;
        }

        if($error!='')
        {
            $result['status']=0;
            $result['allnum']=$highestRow-1;
            $result['info']=$error;

            $this->ajaxReturn($result);
            exit;
        }

        $add=M('student')->addAll($data);
        if($add===false){
            $result['status']=0;
            $result['allnum']=$highestRow-1;
            $result['info']="导入失败，请稍后再试";

            $this->ajaxReturn($result);
        }

        $result['status']=1;
        $result['allnum']=$highestRow;
        $result['info']="成功导入".($highestRow-1)."条数据";

        $this->ajaxReturn($result);

    }

    public function applymember(){
        $memberid=$_POST['memberid'];
        $status=$_POST['status'];
		
        $result=array();
        $find=M('member')->where(array('id'=>$memberid,'applystatus'=>1))->find();
        if(!$find){
            $result['status']=0;
            $result['info']='状态错误，请稍后重试';
            $this->ajaxReturn($result);
        }

        $findrec=M('member_apply')->where(array('memberid'=>$memberid,'status'=>0))->find();
        if(!$findrec){
            $result['status']=0;
            $result['info']='该申请可能已经审核，请核实后再试';
            $this->ajaxReturn($result);
        }

		
        if($status==1){
            //同意
            //设置会员表信息
            $set=M('member')->where(array('id'=>$memberid,'applystatus'=>1))->setField(array('applystatus'=>2,'staffid'=>$findrec['staffid'],'lastfollowtime'=>date('Y-m-d H:i:s')));
           
			if($set===false){
                $result['status']=0;
                $result['info']='审核失败';
                $this->ajaxReturn($result);
            }
            //更改申请表信息
            $setrec=M('member_apply')->where(array('memberid'=>$memberid,'staffid'=>$findrec['staffid']))->setField(array('status'=>$status,'updatetime'=>date('Y-m-d H:i:s')));
            
			//dump(M('member_apply')->getLastSql());die;
			if($setrec===false){
                $result['status']=0;
                $result['info']='审核失败';
                $this->ajaxReturn($result);
            }

        }else{
            //不同意
            $set=M('member')->where(array('id'=>$memberid,'applystatus'=>1))->setField(array('applystatus'=>0));
            if($set===false){
                $result['status']=0;
                $result['info']='审核失败';
                $this->ajaxReturn($result);
            }

            $setrec=M('member_apply')->where(array('member'=>$memberid,'status'=>0,'staffid'=>$findrec['staffid']))->setField('status',$status);
            if($setrec===false){
                $result['status']=0;
                $result['info']='审核失败';
                $this->ajaxReturn($result);
            }
        }
        $result['status']=1;
        $result['info']='审核私有会员成功';
        $this->ajaxReturn($result);
    }
	
	 public function getanalysis(){
        $type=$_POST['type'];
        $ddays=$_POST['ddays'];
        switch($type){
            case 'all'://概览
                    //文章总数
                    $allnum=M('content_news')->where(array('status'=>1))->count();
                    $date=date("Y-m-d",strtotime("-1 day"));
                    $yesterdaynum=M('content_news')->where(array('status'=>1,'addtime'=>array('like',$date.'%')))->count();

                    //总阅读量
                    $allread=M('content_news')->where(array('status'=>1))->getField('sum(hits)');
                    $yesterdayread=M('article_view')->where(array('addtime'=>array('like',$date.'%')))->count();

                    //总转发量
                    $allshare=M('content_news')->where(array('status'=>1))->getField('sum(shares)');
                    $yesterdayshare=M('article_share')->where(array('addtime'=>array('like',$date.'%')))->count();

                    //总点赞量
                    $allpraise=M('content_news')->where(array('status'=>1))->getField('sum(praises)');
                    $yesterdaypraise=M('article_praise')->where(array('addtime'=>array('like',$date.'%')))->count();

                    $result=array();
                       $result['allnum']=$allnum?$allnum:0;
                       $result['yesterdaynum']=$yesterdaynum;
                       $result['allread']=$allread;
                       $result['yesterdayread']=$yesterdayread;
                       $result['allshare']=$allshare;
                       $result['yesterdayshare']=$yesterdayshare;
                       $result['allpraise']=$allpraise;
                       $result['yesterdaypraise']=$yesterdaypraise;
                       $this->ajaxReturn($result);
                break;
            case 'detail':
                $dates=array();
                $reads=array();
                $reads['name']='阅读数';
                $reads['type']='line';
                $shares=array();
                $shares['name']='分享数';
                $shares['type']='line';
                $praises=array();
                $praises['name']='点赞数';
                $praises['type']='line';
                $j=0;
                for($i=$ddays-1;$i>=0;$i--){
                    $dates[$j]=date('Y-m-d',strtotime('- '.$i.' day'));
                    //阅读数
                    $reads['data'][$j]=M('article_view')->where(array('addtime'=>array('like',$dates[$j].'%')))->count();
                    //分享数
                    $shares['data'][$j]=M('article_share')->where(array('addtime'=>array('like',$dates[$j].'%')))->count();
                    //点赞数
                    $praises['data'][$j]=M('article_praise')->where(array('addtime'=>array('like',$dates[$j].'%')))->count();
                    $j++;
                };
                $result=array();
                $result['dates']=$dates;
                $result['reads']=$reads;
                $result['shares']=$shares;
                $result['praises']=$praises;
                $this->ajaxReturn($result);
                break;

            case 'single':
                $id=$_POST['id'];
                $source=M('article_view')->where(array('articleid'=>$id))->group('`from`')->field('sum(num) as num,from')->select();
                $datas=array();
                foreach($source as $k=>$v){
                    $datas[$k]['value']=$v['num'];
                    $from="";
                    switch(intval($v['from'])){
                        case 0:
                            $from="公众号";
                            break;
                        case 1:
                            $from="朋友圈";
                            break;
                        case 2:
                            $from="微信群";
                            break;
                        case 3:
                            $from="好友分享";
                            break;
                        default:
                            break;
                    }
                    $datas[$k]['name']=$from;
//                    $datas[$k]['itemStyle']=array( 'color'=> '#c23531',
//                                            'shadowBlur'=> 200,
//                                            'shadowColor'=> 'rgba(0, 0, 0, 0.5)');
                }
//                $this->ajaxReturn(json_encode($datas,JSON_UNESCAPED_UNICODE));
                $this->ajaxReturn($datas);
                break;
            default:
                break;
        }
    }

    public function transformmember(){

        $id=$_POST['id'];
        $opra=$_POST['opra'];//1-放入公海，2-转移给员工
        $act=$_POST['act'];//1-设置离职，2-转移员工客户
        $tostaffid=$_POST['tostaffid'];

        $result=array();
        M('')->startTrans();
        //先转移客户
        if($opra==1){
            //1-修改申请表状态
            $trans1=M('member_apply')->where(array('staffid'=>$id))->setField(array('status'=>3,'updatetime'=>date("Y-m-d H:i:s")));
            if($trans1===false){
                $result['status']=0;
                $result['info']='操作失败，请稍后再试';
                M('')->rollback();
                $this->ajaxReturn($result);
            }
            //2-修改客户表
            $trans2=M('member')->where(array('staffid'=>$id))->setField(array('applystatus'=>0,'staffid'=>0));
            if($trans2===false){
                $result['status']=0;
                $result['info']='操作失败，请稍后再试';
                M('')->rollback();
                $this->ajaxReturn($result);
            }
        }else{
            //转移给员工
            //1-修改申请表状态
            $trans1=M('member_apply')->where(array('staffid'=>$id))->setField(array('staffid'=>$tostaffid,'updatetime'=>date("Y-m-d H:i:s")));
            if($trans1===false){
                $result['status']=0;
                $result['info']='操作失败，请稍后再试';
                M('')->rollback();
                $this->ajaxReturn($result);
            }
            //2-修改客户表
            $trans2=M('member')->where(array('staffid'=>$id))->setField(array('staffid'=>$tostaffid));
            if($trans2===false){
                $result['status']=0;
                $result['info']='操作失败，请稍后再试';
                M('')->rollback();
                $this->ajaxReturn($result);
            }
        }

        if($act==1){//设置离职
            $set=M('staff')->where(array('id'=>$id))->setField('status',0);
            if($set===false){
                $result['status']=0;
                $result['info']='操作失败，请稍后再试';
                M('')->rollback();
                $this->ajaxReturn($result);
            }
        }

        M('')->commit();
        $result['status']=1;
        $result['info']='操作成功';
        $this->ajaxReturn($result);




    }

    public function setcomplate(){
        $id=$_POST['id'];
        M('member_status')->where(array('id'=>array('gt',0)))->setField('complate',0);
        M('member_status')->where(array('id'=>$id))->setField('complate',1);
        $this->ajaxReturn(array('status'=>1,'info'=>'操作成功'));
    }
	
	
	
    public function sendtempmsg($id="",$msg="",$url=""){

        $ids=explode(',',$id);

        $ids=array_filter($ids);
  
        foreach($ids as $k=>$v ){
            $datatplmsg=array(
                "template_id"=>"vAhlIQoVctlDwCdttULrZkv8xf7t6RhW6XR5zVCX0qc",
                "url"=>$url,
                "topcolor"=>"#FF0000",
                "data"=>array(

                    "first"=>array(
                        "value"=>$msg,
                        "color"=>"#173177"
                    ),
                    "keyword1"=>array(
                        "value"=>"智能推送",
                        "color"=>"#173177"
                    ),
                    "keyword2"=>array(
                        "value"=>date("Y-m-d H:i:s"),
                        "color"=>"#173177"
                    ),
                    "remark"=>array(
                        "value"=>"点击该消息前往查看推荐活动或信息",
                        "color"=>"#173177"
                    )
                )
            );
            sendtemplatemessage($v,$datatplmsg);
        }


        $this->ajaxReturn(array('status'=>1,'info'=>'发送消息成功'));

    }

    public function importmember(){
        $tmp_file = $_FILES ['file'] ['tmp_name'];

        $file_types = explode ( ".", $_FILES ['file']['name'] );
        $file_type = $file_types [count ( $file_types ) - 1];
        /*判别是不是.xls文件，判别是不是excel文件*/
        if (strtolower ( $file_type ) !="xlsx")
        {
            $this->error ( '不是标准上传模板文件，重新上传' );
        }
        /*设置上传路径*/
        $savePath = $_SERVER['DOCUMENT_ROOT'].'/Public/uploadfile/Excel/';

        if(!file_exists($savePath))
            mkdir($savePath);
        /*以时间来命名上传的文件*/
        $str = date ( 'Ymdhis' );
        $file_name = $str . "." . $file_type;

        /*是否上传成功*/
        if (! move_uploaded_file ( $tmp_file, $savePath . $file_name ))
        {
            $this->error ( '上传失败' );
        }

        vendor("PHPExcel.PHPExcel");
        vendor("PHPExcel.PHPExcel.IOFactory");

        $root=$_SERVER[DOCUMENT_ROOT];
        //echo $root;
        $file_name=$savePath.$file_name;
        $objReader = new \PHPExcel_Reader_Excel2007();
        $objPHPExcel = $objReader->load($file_name,$encode='utf-8');

        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow(); // 取得总行数
        $highestColumn = $sheet->getHighestColumn(); // 取得总列数



        $result=array();
        $error=array();
        $err=0;
        $data=array();
        M('')->startTrans();
        $error='';
        //执行导入前检查数据
        $j=0;
        for($i=2;$i<=$highestRow;$i++)
        {
            $username=$objPHPExcel->getActiveSheet()->getCell("A".$i)->getValue();
            if($username==''){
                $error=$error.'【第A'.$i.'单元格内容为空，请输入客户姓名！】<br/>';
            }
            $data[$j]['userreal']=$username;
            $telephone=$objPHPExcel->getActiveSheet()->getCell("B".$i)->getValue();
            if($telephone==''){
                $error=$error.'【第B'.$i.'单元格内容为空，请输入电话号码！】<br/>';
            }
            if(!is_mobile($telephone)){
                $error=$error.'【第B'.$i.'单元格电话号码格式不正确！】<br/>';
            }

            $find=M('member')->where(array('telephone'=>$telephone))->find();
            if($find){
                $error=$error.'【第B'.$i.'单元格电话号码在系统中已存在！】<br/>';
            }

            $data[$j]['telephone']=$telephone;

            $company=$objPHPExcel->getActiveSheet()->getCell("C".$i)->getValue();
            if($company==''){
                $error=$error.'【第C'.$i.'单元格内容为空，请输入公司名称！】<br/>';
            }
            $data[$j]['company']=$company;

            $address=$objPHPExcel->getActiveSheet()->getCell("D".$i)->getValue();
            if($address==''){
                $error=$error.'【第D'.$i.'单元格内容为空，请输入地址！】<br/>    ';
            }
            $data[$j]['address']=$address;
            $j++;
        }

        if($error!='')
        {
            $result['status']=0;
            $result['allnum']=$highestRow-1;
            $result['info']=$error;
            M('')->rollback();
            $this->ajaxReturn($result);
            exit;
        }

        $add=M('member')->addAll($data);
        if($add===false){
            $result['status']=0;
            $result['allnum']=$highestRow-1;
            $result['info']="导入失败，请稍后再试";
            M('')->rollback();
            $this->ajaxReturn($result);
        }

        $result['status']=1;
        $result['allnum']=$highestRow-1;
        $result['info']="成功导入".($highestRow-1)."条数据";
        M('')->commit();
        $this->ajaxReturn($result);

    }



    public function importhouse(){
        $tmp_file = $_FILES ['file'] ['tmp_name'];

        $file_types = explode ( ".", $_FILES ['file']['name'] );
        $file_type = $file_types [count ( $file_types ) - 1];
        /*判别是不是.xls文件，判别是不是excel文件*/
        if (strtolower ( $file_type ) !="xlsx")
        {
            $this->error ( '不是标准上传模板文件，重新上传' );
        }
        /*设置上传路径*/
        $savePath = $_SERVER['DOCUMENT_ROOT'].'/Public/uploadfile/Excel/';

        if(!file_exists($savePath))
            mkdir($savePath);
        /*以时间来命名上传的文件*/
        $str = date ( 'Ymdhis' );
        $file_name = $str . "." . $file_type;

        /*是否上传成功*/
        if (! move_uploaded_file ( $tmp_file, $savePath . $file_name ))
        {
            $this->error ( '上传失败' );
        }

        vendor("PHPExcel.PHPExcel");
        vendor("PHPExcel.PHPExcel.IOFactory");

        $root=$_SERVER[DOCUMENT_ROOT];
        //echo $root;
        $file_name=$savePath.$file_name;
        $objReader = new \PHPExcel_Reader_Excel2007();
        $objPHPExcel = $objReader->load($file_name,$encode='utf-8');

        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow(); // 取得总行数
        $highestColumn = $sheet->getHighestColumn(); // 取得总列数



        $result=array();
        $error=array();
        $err=0;
        $data=array();
        M('')->startTrans();
        $error='';
        //执行导入前检查数据
        $j=0;
        for($i=2;$i<=$highestRow;$i++)
        {
            $title=$objPHPExcel->getActiveSheet()->getCell("A".$i)->getValue();
            if($title==''){
                $error=$error.'【第A'.$i.'单元格内容为空，请输入房源名称！】<br/>';
            }

            $find=M('house')->where(array('title'=>$title))->find();
            if($find){
                $error=$error.'【第A'.$i.'房源名称重复，该房源已存在！】<br/>';
            }
            $data[$j]['title']=$title;

            $remark=$objPHPExcel->getActiveSheet()->getCell("B".$i)->getValue();
            $data[$j]['remark']=$remark;

            $fangstatus=$objPHPExcel->getActiveSheet()->getCell("C".$i)->getValue();
            switch($fangstatus){
                case "未开售":
                    $fangstatus=0;
                    break;
                case "在售":
                    $fangstatus=1;
                    break;
                case "预定":
                    $fangstatus=2;
                    break;
                case "已售":
                    $fangstatus=3;
                    break;
            }
            $data[$j]['fangstatus']=$fangstatus;

            $provinceid=$objPHPExcel->getActiveSheet()->getCell("D".$i)->getValue();
            if($provinceid==''){
                $error=$error.'【第D'.$i.'单元格内容为空,请选择省！】<br/>';
            }
            else{
                $provinceid=51;//四川省
            }
            $data[$j]['provinceid']=$provinceid;

            $cityid=$objPHPExcel->getActiveSheet()->getCell("E".$i)->getValue();
            if($cityid==''){
                $error=$error.'【第E'.$i.'单元格内容为空,请选择市！】<br/>';
            }
            else{
                $cityid=5101;//成都市
            }
            $data[$j]['cityid']=$cityid;

            $quid=$objPHPExcel->getActiveSheet()->getCell("F".$i)->getValue();
            if($quid==''){
                $error=$error.'【第F'.$i.'单元格内容为空,请选择区！】<br/>';
            }
            else{
                $quid=M('pcd')->where(array('name'=>$quid))->getField('code');
            }
            $data[$j]['districtid']=$quid;

            $address=$objPHPExcel->getActiveSheet()->getCell("G".$i)->getValue();
            if($address==''){
                $error=$error.'【第G'.$i.'单元格内容为空，请输入地址！】<br/>    ';
            }
            $data[$j]['address']=$address;


            $price=$objPHPExcel->getActiveSheet()->getCell("H".$i)->getValue();
            $data[$j]['price']=$price;
            $unitprice=$objPHPExcel->getActiveSheet()->getCell("I".$i)->getValue();
            $data[$j]['unitprice']=$unitprice;
            $area=$objPHPExcel->getActiveSheet()->getCell("J".$i)->getValue();
            $data[$j]['area']=$area;
            $traffic=$objPHPExcel->getActiveSheet()->getCell("K".$i)->getValue();
            $data[$j]['traffic']=$traffic;
            $rentprice=$objPHPExcel->getActiveSheet()->getCell("L".$i)->getValue();
            $data[$j]['rentprice']=$rentprice;
            $businessscope=$objPHPExcel->getActiveSheet()->getCell("M".$i)->getValue();
            $data[$j]['businessscope']=$businessscope;
            $houseuseage=$objPHPExcel->getActiveSheet()->getCell("N".$i)->getValue();
            $data[$j]['houseuseage']=$houseuseage;
            $opentime=$objPHPExcel->getActiveSheet()->getCell("O".$i)->getValue();
            $data[$j]['opentime']=$opentime;
            $gettime=$objPHPExcel->getActiveSheet()->getCell("P".$i)->getValue();
            $data[$j]['gettime']=$gettime;
            $minipayment=$objPHPExcel->getActiveSheet()->getCell("Q".$i)->getValue();
            $data[$j]['minipayment']=$minipayment;
            $saleaddress=$objPHPExcel->getActiveSheet()->getCell("R".$i)->getValue();
            $data[$j]['saleaddress']=$saleaddress;
            $decoration=$objPHPExcel->getActiveSheet()->getCell("S".$i)->getValue();
            $data[$j]['decoration']=$decoration;
            $developers=$objPHPExcel->getActiveSheet()->getCell("T".$i)->getValue();
            $data[$j]['developers']=$developers;
            $investor=$objPHPExcel->getActiveSheet()->getCell("U".$i)->getValue();
            $data[$j]['investor']=$investor;
            $floors=$objPHPExcel->getActiveSheet()->getCell("V".$i)->getValue();
            $data[$j]['floors']=$floors;
            $tenement=$objPHPExcel->getActiveSheet()->getCell("W".$i)->getValue();
            $data[$j]['tenement']=$tenement;
            $j++;
        }

        if($error!='')
        {
            $result['status']=0;
            $result['allnum']=$highestRow-1;
            $result['info']=$error;
            M('')->rollback();
            $this->ajaxReturn($result);
            exit;
        }

        $add=M('house')->addAll($data);
        if($add===false){
            $result['status']=0;
            $result['allnum']=$highestRow-1;
            $result['info']="导入失败，请稍后再试";
            M('')->rollback();
            $this->ajaxReturn($result);
        }

        $result['status']=1;
        $result['allnum']=$highestRow;
        $result['info']="成功导入".($highestRow-1)."条数据";
        M('')->commit();
        $this->ajaxReturn($result);

    }


    public function getcustom(){
        $type=$_POST['type'];
        $ddays=$_POST['ddays'];
        $staffid=$_POST['staffid'];
        switch($type){
            case 'all'://概览
                //文章总数
                $allnum=M('content_news')->where(array('status'=>1))->count();
                $date=date("Y-m-d",strtotime("-1 day"));
                $yesterdaynum=M('content_news')->where(array('status'=>1,'addtime'=>array('like',$date.'%')))->count();

                //总阅读量
                $allread=M('content_news')->where(array('status'=>1))->getField('sum(hits)');
                $yesterdayread=M('article_view')->where(array('addtime'=>array('like',$date.'%')))->count();

                //总转发量
                $allshare=M('content_news')->where(array('status'=>1))->getField('sum(shares)');
                $yesterdayshare=M('article_share')->where(array('addtime'=>array('like',$date.'%')))->count();

                //总点赞量
                $allpraise=M('content_news')->where(array('status'=>1))->getField('sum(praises)');
                $yesterdaypraise=M('article_praise')->where(array('addtime'=>array('like',$date.'%')))->count();

                $result=array();
                $result['allnum']=$allnum?$allnum:0;
                $result['yesterdaynum']=$yesterdaynum;
                $result['allread']=$allread;
                $result['yesterdayread']=$yesterdayread;
                $result['allshare']=$allshare;
                $result['yesterdayshare']=$yesterdayshare;
                $result['allpraise']=$allpraise;
                $result['yesterdaypraise']=$yesterdaypraise;
                $this->ajaxReturn($result);
                break;
            case 'index':


                $dates=array();
                $news=array();
                $news['name']='新增客户';
                $news['type']='line';
                $sees=array();
                $sees['name']='带看客户';
                $sees['type']='line';
                $gens=array();
                $gens['name']='跟进客户';
                $gens['type']='line';
                $coms=array();
                $coms['name']='成交客户';
                $coms['type']='line';
                $j=0;
                for($i=$ddays-1;$i>=0;$i--){
                    if(!isN($staffid)){
                        $dates[$j]=date('Y-m-d',strtotime('- '.$i.' day'));
                        //新增
                        $news['data'][$j]=M('member as m')->join('my_member_apply as mma on mma.memberid=m.id')->where(array('mma.updatetime'=>array('like',$dates[$j].'%'),'m.staffid'=>$staffid))->count();
                        //带看
                        $sees['data'][$j]=M('staff_follow')->where(array('addtime'=>array('like',$dates[$j].'%'),'type'=>2,'staffid'=>$staffid))->count();
                        //跟进
                        $gens['data'][$j]=M('staff_follow')->where(array('addtime'=>array('like',$dates[$j].'%'),'type'=>1,'staffid'=>$staffid))->count();
                        //成交
                        $coms['data'][$j]=M('member')->where(array('complatetime'=>array('like',$dates[$j].'%'),'memberstatus'=>getcomplatestatus(),'staffid'=>$staffid))->count();
                    }else{
                        $dates[$j]=date('Y-m-d',strtotime('- '.$i.' day'));
                        //新增
                        $news['data'][$j]=M('member')->where(array('addtime'=>array('like',$dates[$j].'%')))->count();
                        //带看
                        $sees['data'][$j]=M('staff_follow')->where(array('addtime'=>array('like',$dates[$j].'%'),'type'=>2))->count();
                        //跟进
                        $gens['data'][$j]=M('staff_follow')->where(array('addtime'=>array('like',$dates[$j].'%'),'type'=>1))->count();
                        //成交
                        $coms['data'][$j]=M('member')->where(array('complatetime'=>array('like',$dates[$j].'%'),'memberstatus'=>getcomplatestatus()))->count();
                    }

                    $j++;
                };
                $result=array();
                $result['dates']=$dates;
                $result['news']=$news;
                $result['sees']=$sees;
                $result['gens']=$gens;
                $result['coms']=$coms;
                $this->ajaxReturn($result);
                break;

            case 'single':
                $id=$_POST['id'];
                $source=M('article_view')->where(array('articleid'=>$id))->group('`from`')->field('sum(num) as num,from')->select();
                $datas=array();
                foreach($source as $k=>$v){
                    $datas[$k]['value']=$v['num'];
                    $from="";
                    switch(intval($v['from'])){
                        case 0:
                            $from="公众号";
                            break;
                        case 1:
                            $from="朋友圈";
                            break;
                        case 2:
                            $from="微信群";
                            break;
                        case 3:
                            $from="好友分享";
                            break;
                        default:
                            break;
                    }
                    $datas[$k]['name']=$from;
//                    $datas[$k]['itemStyle']=array( 'color'=> '#c23531',
//                                            'shadowBlur'=> 200,
//                                            'shadowColor'=> 'rgba(0, 0, 0, 0.5)');
                }
//                $this->ajaxReturn(json_encode($datas,JSON_UNESCAPED_UNICODE));
                $this->ajaxReturn($datas);
                break;
            default:
                break;
        }
    }
	
	
	
	 public function allaudit($ids=""){
        $ids=explode(',',$ids);
        $ids=array_filter($ids);
        $succ=0;
        $fal=0;
        $did=0;
        foreach($ids as $k=>$v){
            $find=M('member_apply')->where(array('memberid'=>$v,'status'=>0))->find();
            if($find){
                //同意
                //设置会员表信息
                $set=M('member')->where(array('id'=>$v,'applystatus'=>1))->setField(array('applystatus'=>2,'staffid'=>$find['staffid'],'lastfollowtime'=>date('Y-m-d H:i:s')));
                if($set===false){
                    $fal++;
                    continue;
                }
                //更改申请表信息
                $setrec=M('member_apply')->where(array('memberid'=>$v,'staffid'=>$find['staffid']))->setField(array('status'=>1,'updatetime'=>date('Y-m-d H:i:s')));
                //dump(M('member_apply')->getLastSql());die;
                if($setrec===false){
                    $fal++;
                    continue;
                }else{
                    $succ++;
                }
            }else{
                $did++;
            }
        }
        $result=array();
        $result['status']=1;
        $result['did']="包含已审核的".$did."条";
        $result['succ']="包含审核成功的".$succ."条";
        $result['fal']="包含审核失败的".$fal."条";
        $this->ajaxReturn($result);

    }


    public function getranklist(){
        $type=$_POST['type'];
        $html="";
        switch($type){
            case 'custom':
                //客户拥有数排行前6名
                $where=array();
                $where['m.telephone']=array('neq','');
                $cusrank=M('member as m')->join('my_staff as s on m.staffid=s.id')
                    ->where($where)
                    ->field('count(1) as sum,m.staffid,s.name')->group('m.staffid')->order('sum desc')->select();
                foreach($cusrank as $k=>$v){
                    if($k>2){
                        $css="list2";
                    }else{
                        $css="list1";
                    }
                    $html.='<li data-field="custom" data-id="'.$v['staffid'].'" class="list-group-item '.$css.'"><span class="badge"> '.$v['sum'].'</span>
                    <span style=\"\">'.($k+1).'</span>、'.$v['name'].'<div style="position: absolute; top: 0px;left: 20px;">';
                    if($k<=2){
                        $html.='<img style="width: 30px;" src="/Public/Admin/stylesheets/images/'.($k+1).'.png" />';
                    }
                   $html.='</div> </li>';
                }
                break;
            case 'contract':

                //合同拥有数排行前6名
                $contractrank=M('contract as c')->join('my_staff as s on c.masterid=s.id')
                    ->field('count(1) as sum,c.masterid,s.name')->group('c.masterid')->order('sum desc')->select();

                foreach($contractrank as $k=>$v){
                    if($k>2){
                        $css="list2";
                    }else{
                        $css="list1";
                    }
                    $html.='<li data-field="contract" data-id="'.$v['masterid'].'" class="list-group-item '.$css.'"><span class="badge"> '.$v['sum'].'</span>
                    <span style=\"\">'.($k+1).'</span>、'.$v['name'].'<div style="position: absolute; top: 0px;left: 20px;">';
                    if($k<=2){
                        $html.='<img style="width: 30px;" src="/Public/Admin/stylesheets/images/'.($k+1).'.png" />';
                    }
                    $html.='</div> </li>';
                }

                break;
            case 'contractamount':
                //合同金额排行前6名
                $conamountrank=M('contract as c')->join('my_staff as s on c.masterid=s.id')
                    ->field('sum(amount) as sum,c.masterid,s.name')->group('c.masterid')->order('sum desc')->select();
                foreach($conamountrank as $k=>$v){
                    if($k>2){
                        $css="list2";
                    }else{
                        $css="list1";
                    }
                    $html.='<li data-field="contractamount" data-id="'.$v['masterid'].'" class="list-group-item '.$css.'"><span class="badge"> '.$v['sum'].'</span>
                    <span style=\"\">'.($k+1).'</span>、'.$v['name'].'<div style="position: absolute; top: 0px;left: 20px;">';
                    if($k<=2){
                        $html.='<img style="width: 30px;" src="/Public/Admin/stylesheets/images/'.($k+1).'.png" />';
                    }
                    $html.='</div> </li>';
                }
                break;
            case 'item':
                //项目数排行前6名
                $itemrank=M('item as i')->join('my_staff as s on i.masterid=s.id')
                    ->where(array('i.status'=>array('neq',2)))
                    ->field('count(1) as sum,i.masterid,s.name')->group('i.masterid')->order('sum desc')->select();
                foreach($itemrank as $k=>$v){
                    if($k>2){
                        $css="list2";
                    }else{
                        $css="list1";
                    }
                    $html.='<li data-field="item" data-id="'.$v['masterid'].'" class="list-group-item '.$css.'"><span class="badge"> '.$v['sum'].'</span>
                    <span style=\"\">'.($k+1).'</span>、'.$v['name'].'<div style="position: absolute; top: 0px;left: 20px;">';
                    if($k<=2){
                        $html.='<img style="width: 30px;" src="/Public/Admin/stylesheets/images/'.($k+1).'.png" />';
                    }
                    $html.='</div> </li>';
                }
                break;

            case 'follow':
                //项目数排行前6名
                //跟进排行前6名
                $followrank=M('staff_follow as f')->join('my_staff as s on f.staffid=s.id')
                    ->field('count(1) as sum,f.staffid,s.name')->group('f.staffid')->order('sum desc')->select();

                foreach($followrank as $k=>$v){
                    if($k>2){
                        $css="list2";
                    }else{
                        $css="list1";
                    }
                    $html.='<li data-field="follow" data-id="'.$v['staffid'].'" class="list-group-item '.$css.'"><span class="badge"> '.$v['sum'].'</span>
                    <span style=\"\">'.($k+1).'</span>、'.$v['name'].'<div style="position: absolute; top: 0px;left: 20px;">';
                    if($k<=2){
                        $html.='<img style="width: 30px;" src="/Public/Admin/stylesheets/images/'.($k+1).'.png" />';
                    }
                    $html.='</div> </li>';
                }
                break;

        }
        $this->ajaxReturn($html);

    }


    public function sendcontracttip(){
        $id=$_POST['id'];
        $contract=M('contract')->where(array('id'=>$id))->find();
        $now=date('Y-m-d H:i:s');
        $left=diffBetweenTwoDaysreal($now,$contract['end']);
        $left=intval($left);
        $custom=M('member')->where(array('id'=>$contract['customid']))->find();
        $staff=M('staff')->where(array('id'=>$contract['masterid']))->find();
        $msgcus='尊敬的客户您好，您在我司的的合同将于'.$contract['end'].'到期，剩余时间'.$left.'天,请及时续约。【易焯科技】';
        $msgstaff=$staff['name'].',您好，您的客户'.$custom['company'].'在我司的合同'.$contract['name'].'即将到期，请及时联系客户续约。【易焯科技】';
        $errors="";
        $succ=1;
       $sendcus= send_tip_sms($custom['telephone'],$msgcus);
        if($sendcus){

        }else{
            $succ=0;
            $errors.='客户短信未发送成功';
        }
        $sendstaff= send_tip_sms($staff['telephone'],$msgstaff);
        if($sendstaff){

        }else{
            $succ=0;
            $errors.='员工短信未发送成功';
        }

        if($succ==0){
            $this->ajaxReturn($errors);
        }else{
            M('contract')->where(array('id'=>$id))->setField('notice',1);
            $this->ajaxReturn("发送成功");
        }

    }



    public function getmessage(){
        $memberid=$_POST['memberid'];
        $message=M('message')->where(array('memberid'=>$memberid))->find();
        $content=json_decode($message['content'],true);
        //设置已读
        M('message')->where(array('memberid'=>$memberid))->setField('isquesnew',0);
        $this->ajaxReturn($content);
    }

    public function sendmessage(){
        $content=$_POST['content'];
        $memberid=$_POST['memberid'];
        $headimgurl="/Public/Home/images/xuanshou.jpg";

        $exist=M('message')->where(array('memberid'=>$memberid))->find();
        $addtime=date('Y-m-d H:i:s');
        if($exist){
            $contents=json_decode($exist['content'],true);
            $count=count($contents);
            $contents[$count]['addtime']=$addtime;
            $contents[$count]['type']='ques';
            $contents[$count]['headimgurl']=$headimgurl;
            $contents[$count]['content']=$content;
        }else{
            $contents=array();
            $contents[0]['addtime']=$addtime;
            $contents[0]['type']='ques';
            $contents[0]['headimgurl']=$headimgurl;
            $contents[0]['content']=$content;
        }


        $data=array();
        $data['isreplynew']=1;
        $data['content']=json_encode($contents,JSON_UNESCAPED_UNICODE);
        if($exist){
            $add=M('message')->where(array('memberid'=>$memberid))->data($data)->save();

        }else{
            $data['memberid']=$memberid;
            $data['addtime']=date('Y-m-d H:i:s');

            $add=M('message')->data($data)->add();
        }

        $result=array();
        if($add===false){
            $result['status']=0;
            $result['info']='发送失败，请稍后再试';
            $this->ajaxReturn($result);
        }

        sendmessagenotice($memberid,$content);//发送消息提醒

        $result['status']=1;
        $result['headimgurl']=$headimgurl;
        $result['addtime']=$addtime;
        $this->ajaxReturn($result);
    }


    public function sendnotice(){
        $id=$_POST['id'];

        $loan=M('loan')->where(array('id'=>$id))->find();
        $memberid=$loan['memberid'];
        $member=M('member')->where(array('id'=>$memberid))->find();
        $telephone=$loan['telephone'];

        $msg="尊敬的".$member['username'].",您在我公司的贷款已于".$loan['deadline'].'逾期，特发此短信提醒，未避免不必要的麻烦，请及时还款。【U易钱包】';

        $sendsms= send_tip_sms($telephone,$msg);
        $result=array();
        if(!$sendsms){
            $result['status']=0;
            $result['info']="提醒失败";
            $this->ajaxReturn($result);
        }
        $data=array();
        $data['orderno']=$loan['orderno'];
        $data['memberid']=$memberid;
        $data['act']="发送贷款逾期短信提醒";
        $data['type']=1;
        $addnotice=M('loan_notice')->data($data)->add();
        $result['status']=1;
        $result['info']="发送贷款逾期短信成功";
        $this->ajaxReturn($result);

    }

    public function confirmshenhe(){
        $id=$_POST['id'];
        //var_dump($id);die;
        $status=$_POST['status'];
        $shenhestatus=$_POST['shenhestatus'];
        $msg=$_POST['msg'];
        $money=$_POST['money'];
        $loan=M('loan')->where(array('id'=>$id))->find();
		$days=$loan['days'];
        $result=array();
        /*if($status<$loan['status']){
            $result['status']=0;
            $result['info']="贷款状态不能倒退";
            $this->ajaxReturn($result);
        }
*/
        $statusname="";
        $dataloan=array();
        $dataloan['status']=$status;
        $step=json_decode($loan['step'],true);
        $stepnum=count($step);
        $step[$stepnum]['addtime']=date("Y-m-d H:i:s");

        switch($status){
            case 0:
                $statusname="待审核";
                break;
            case 1:
                $rs['loanid']=$id;
                $rs['status']='';
                $rs['msg'] ='';
                M('msg')->add($rs);
                $dataloan['shenhestatus']=$shenhestatus;
                if($shenhestatus==1){
                    $statusname="已审核通过";
                    //添加审批金额
                    $proinfo = M('loan')->where(['id'=>$id])->find()['productinfo'];
                    $pro=json_decode($proinfo,true);
                    $a['damount'] = $money;
                    $a['interest'] =$money*($pro['interest']/100);
                    $a['interestrate'] =$pro['interest'];
                    $a['amount'] = $a['damount']+$a['interest'];
                    $a['daozhang']=$money*0.7;
					$a['deadline']=get_date_add(strtotime(date("Y-m-d H:i:s")),$days);
                    $success = M('loan')->where(['id'=>$id])->save($a);

                    $rs['status']=1;
                    $rs['msg'] ='已审核通过';
                    M('msg')->where(['loanid'=>$id])->save($rs);

                    if($success){
                        $result['status']=1;
                        $result['info']="操作成功";
                    }else{
                        $result['status']=0;
                        $result['info']="操作失败";
                    }

                    //应还款修改
                }else{


                    $rs['status']=0;
                    $rs['msg'] ="审核被拒绝,拒绝原因：".$msg;
                    M('msg')->where(['loanid'=>$id])->save($rs);
                    $dataloan['refusereason']=$msg;
                    $statusname="审核被拒绝,拒绝原因：".$msg;
                }
                break;
            case 2:
                $statusname="已放款";
                $days = 7;
                $deadline = date('Y-m-d',strtotime('+ '.($days-1).' days')).' 23:59:59';
                $dataloan['paiedtime']=date('Y-m-d H:i:s');
                $dataloan['deadline']=$deadline;

                $id=$_POST['id'];
                $loan=M('loan')->where(array('id'=>$id))->find();
                //$memberid=$loan['memberid'];
                //$member=M('member')->where(array('id'=>$memberid))->find();

                /*$telephone=$loan['telephone'];

                $msg="尊敬的".$member['username'].",您在我公司的贷款".$loan['damount'].'元已发放，特发此短信提醒，未避免不必要的麻烦，请及时还款。【U易钱包】';

                $sendsms= send_tip_sms($telephone,$msg);
                $result=array();
                if(!$sendsms){
                    $result['status']=0;
                    $result['info']="提醒失败";
                    $this->ajaxReturn($result);
                }
                $data=array();
                $data['orderno']=$loan['orderno'];
                $data['memberid']=$memberid;
                $data['act']="发送放款短信提醒";
                $data['type']=1;
                $addnotice=M('loan_notice')->data($data)->add();
                $result['status']=1;
                $result['info']="发送放款短信成功";*/
                break;

            case 3:
                $statusname="已逾期";
                break;
            case 4:
                $statusname="已还款";
                $fee=0;
                if(strtotime($loan['deadline'])<strtotime(date("Y-m-d H:i:s"))){
                    $overdue=intval(diffBetweenTwoDays($loan['deadline'],date("Y-m-d H:i:s")));
                    $fee=$overdue*$loan['damount']*$loan['overduefee']/100;
                }
                $dataloan['refundamount']=$loan['amount']+$fee;
                $dataloan['refundtime']=date("Y-m-d H:i:s");
                $info=array();
                $info['refundtime']=date("Y-m-d H:i:s");
                $info['act']="后台操作还款";
                $dataloan['refundinfo']=json_encode($info,JSON_UNESCAPED_UNICODE);
                break;
            case 5:
                $statusname="申请延期";
                break;
            case 6:
                if($loan['status'] != 6)
                {
                    $dataloan['deadline'] =  date("Y-m-d H:i:s",strtotime("+4 day",strtotime($loan['deadline'])));
                }
                $statusname="确认延期";
                break;

            default:
                break;
        }



        $step[$stepnum]['act']=$statusname;
        $step=json_encode($step,JSON_UNESCAPED_UNICODE);
        $dataloan['step']=$step;

        $save=M('loan')->where(array('id'=>$id))->data($dataloan)->save();

        if($save===false){
            $result['status']=0;
            $result['info']="操作失败";
            $this->ajaxReturn($result);
        }

        $data=array();
        $data['orderno']=$loan['orderno'];
        $data['memberid']=$loan['memberid'];
        $data['act']="管理员操作订单状态";
        $addnotice=M('loan_notice')->data($data)->add();

        if($status==2){
            $datarecord=array();
            $datarecord['orderno']=$loan['orderno'];
            $datarecord['memberid']=$loan['memberid'];
            $datarecord['amount']=$loan['damount'];//贷款金额
            $datarecord['type']=1;
            $findre=M('loan_record')->where(array('orderno'=>$loan['orderno'],'type'=>1))->find();
            if(!$findre){
                $addrecord=M('loan_record')->data($datarecord)->add();
            }
        }


        if($status==4){
            $datainte=array();//添加利息记录
            $datainte['orderno']=$loan['orderno'];
            $datainte['memberid']=$loan['memberid'];
            $datainte['amount']=$loan['interest'];//利息金额
            $datainte['interestrate']=$loan['interestrate'];
            $add=M('loan_interest')->data($datainte)->add();

            $overdue=intval(diffBetweenTwoDaysreal($loan['deadline'],date("Y-m-d H:i:s")));
            if($overdue>=1){
                $dataover=array();//添加逾期记录
                $dataover['orderno']=$loan['orderno'];
                $dataover['memberid']=$loan['memberid'];
                $dataover['amount']=$overdue*$loan['damount']*$loan['overduefee']/100;//逾期金额
                $dataover['overduefee']=$loan['overduefee'];
                $dataover['days']=$overdue;
                $add=M('loan_overdue')->data($dataover)->add();
            }
        }

        $result['status']=1;
        $result['info']="操作成功";
        $this->ajaxReturn($result);

    }


    public function writexiaoji(){
        $id=$_POST['id'];
        $id=explode(',',$id);
        $id=array_filter($id);
        $id=$id[0];

        $content=$_POST['content'];
        $loan=M('loan')->where(array('memberid'=>$id))->order("addtime desc")->select();
        $loan=$loan[0];
        $result=array();
        $data=array();
        $data['orderno']=$loan['orderno'];
        $data['memberid']=$loan['memberid'];
        $data['act']=$content;
        $add=M('loan_notice')->data($data)->add();
        if($add===false){
            $result['status']=0;
            $result['info']="操作失败，请稍后再试";
            $this->ajaxReturn($result);
        }

        $result['status']=1;
        $result['info']="操作成功";
        $this->ajaxReturn($result);
    }
    public function saveimg(){
        $pic=$_POST['pic'];
        $result=array();
        $picurl=base64Toimg($pic);
        if(!$picurl){
            $result['status']=0;
            $result['info']="上传失败";
            $this->ajaxReturn($result);
        }
        $memberid=get_memberid();

        $set=M('member')->where(array('id'=>$memberid))->setField('headimgurl',$picurl);

        if($set===false){
            $result['status']=0;
            $result['info']="上传失败";
            $this->ajaxReturn($result);
        }

        $result['status']=1;
        $result['info']=$picurl;
        $this->ajaxReturn($result);
    }


}