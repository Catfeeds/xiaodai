<?php
namespace Rest3\Service;

use Common\Service\IService;
use Common\Lib\PasswordUtil;
use Common\Lib\HtmlUtils;
use Common\Lib\Date;
use Common\Lib\FormatUtils;
use Common\Lib\CDNUtils;

class ReviewService extends IService{
	    

        #获取评论列表
        public function get_review_list($product_id,$customer_id,$vendor_id,$page,$limit,$orderby){

            $co=array(
                'status'=>1,
            );

            if($product_id){
                $co['product_id']=$product_id;
            }

            if($customer_id){
                $co['customer_id']=$customer_id;
            }

            if($vendor_id){
                $co['vendor_id']=$vendor_id;
            }

            $ReviewModel = D('Review');
            $list = $ReviewModel->where($co)->field($ReviewModel->main_fields)->page($page)->limit($limit)->order($orderby)->cache(false)->select();

            foreach ($list as &$li) {
                    
                if($li['imagelist_json']){

                    $li['imagelist_list'] = json_decode(html_entity_decode($li['imagelist_json']),true);
                    foreach ($li['imagelist_list'] as &$l) {
                        $l = CDNUtils::ImageCDN($l);
                    }
                }
                unset($li['imagelist_json']);

                $hk = D('Customer')->get_field($li['customer_id'],'header');

                $li['header'] = CDNUtils::ImageCDN($hk);
            }

            return $list;
        }


        public function get_review_count($product_id,$customer_id,$vendor_id){

            $co=array(
                'status'=>1,
            );

            if($product_id){
                $co['product_id']=$product_id;
            }

            if($customer_id){
                $co['customer_id']=$customer_id;
            }

            if($vendor_id){
                $co['vendor_id']=$vendor_id;
            }

            $ReviewModel = M('Review');

            return $ReviewModel->where($co)->count();
        }


}