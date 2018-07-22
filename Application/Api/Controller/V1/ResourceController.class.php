<?php
namespace Rest3\Controller\V1;

use Think\Controller;
use Common\Controller\IController;
use Common\Lib\CDNUtils;
use Common\Lib\LangUtils;

class ResourceController extends IController{

    
    public function get_resource_list(){

        $page = IV('page','require');
        $limit = IV('limit','require');
        $type = IV('type','require');
        $lang = IV('lang','require');

        $co=array(
            'type'=>$type
        );

        $list = M('resource')->where($co)->page($page)->limit($limit)->order('order_no desc')->select();

        CDNUtils::VideoCDNArray($list,'file');
        CDNUtils::ImageCDNArray($list,'image');
        LangUtils::LangList($list,'title',$lang);

        $this->iSuccess($list,'resource_list');
    }

}