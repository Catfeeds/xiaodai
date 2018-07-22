<?php
namespace Rest3\Model;

use Common\Lib\HtmlUtils;
use Common\Lib\FormatUtils;
use Common\Lib\CDNUtils;

class CategoryModel extends IModel{


    var  $main_fields=array(
        'category_id','image','parent_id'
    );

    var $desc_fields=array(

        'category_id','name'
    );


    public function get_info($id,$language_id,$main_fields,$desc_fields){

            if(!empty($main_fields)){
                $info = $this->field($main_fields)->cache(true)->find($id);
            }else{
                $info=array();
            }

            if(!empty($desc_fields)){
                $desc_info = M('CategoryDescription')->findWithLanguage(array('category_id'=>$id),$language_id,$desc_fields);
            }else{
                $desc_info=array();
            }

            $info = array_merge($info,$desc_info);

            FormatUtils::HtmlDecode($info);
            CDNUtils::ImageCDNObj($info,'image');

            return $info;
    }


    # 获取某个产品的目录id
    public function get_category_id_of_product($product_id){

            $co=array(
                'product_id'=>$product_id
            );
            M('product_to_category')->where($co)->field('category_id')->select();
    }

    /**
     * 获取子分类列表   
     */
    public function get_sub_category_list($category_id,$language_id,$country_id,$membership_level){
        
        $CategoryDescriptionModel=M('CategoryDescription');

        $co=array(
            'parent_id'=>$category_id,
            'status'=>1
        );
        
        $category2_list = M('Category')->where($co)->field('category_id,logo,image')->order('sort_order')->select();
        
        $list=array();
        foreach ($category2_list as &$cat) {

            $c = $this->get_info($cat['category_id'],$language_id,null,'name');
            $cat = array_merge($cat,$c);
            CDNUtils::ImageCDNObj($cat,'image');
            CDNUtils::ImageCDNObj($cat,'logo');
            $cat['count'] = intval(D('vendor')->get_vendor_count_of_category($cat['category_id'],$country_id,$membership_level));

            if($cat['count']!=0){
                array_push($list,$cat);
            }
        }
        return $list;
    }

    // @dep
    public function get_product_ids_by_category_id($category_id){

        if($category_id){
            $list = M('product_to_category')->where(array('category_id'=>$category_id))->select();
        }else{
            $list = M('product_to_category')->select();
        }

        $ret=array();

        foreach ($list as $li) {
            array_push($ret,$li['product_id']);
        }

        return $ret;
    }

    // @dep
    public function get_product_ids_by_category_ids($category_id_list,$order,$page=1,$limit=10){
        
        $list = M('product_to_category')->where(array('category_id'=>array('in'=>$category_id_list)))->order('rand()')->select();

        $ret=array();

        foreach ($list as $li) {
            array_push($ret,$li['product_id']);
        }

        return $ret;
    }

    // @dep
    public function get_all_product_ids_in_category($category_id_list){

        $list = M('product_to_category')->where(array('category_id'=>array('in'=>$category_id_list)))->select();

        $ret=array();

        foreach ($list as $li) {
            array_push($ret,$li['product_id']);
        }

        return $ret;
    }



    #获取所有的子目录ids
    public function get_all_sub_category_ids($category_id){

        //获取子目录
        $co=array(
            'parent_id'=>$category_id
        );
        $list = $this->where($co)->field('category_id')->select();

        $category_ids=array();      
        foreach ($list as $li) {
            array_push($category_ids,$li['category_id']);
        }

        return $category_ids;
    }

    
    #获取自己和子目录的ids
    public function get_all_category_ids_in_category($category_id){

        $category_ids;

        array_push($category_ids,$category_id);

        $sub_category_ids = $this->get_all_sub_category_ids($category_id);
        foreach ($sub_category_ids as $cid) {
            
            $this->_put_category_id($cid,$category_ids);

            $sub_sub_category_ids = $this->get_all_sub_category_ids($cid);

            foreach ($sub_sub_category_ids as $ccid) {
                
                $this->_put_category_id($ccid,$category_ids);
            }
        }
        return $category_ids;
    }
    
    /*
    #获取自己和子目录的ids
    public function get_all_category_ids_in_category_list($category_id_list){

        $category_ids;

        foreach ($category_id_list as $category_id) {
                
            $cids = $this->get_all_category_ids_in_category($category_id);

            foreach ($cids as $cid) {
                $this->_put_category_id($cid,&$category_id_list)
            }
        }

        return $category_ids;

    }*/

    #放入目录id
    private function _put_category_id($cid,&$category_ids){
        if(!in_array($cid,$category_ids)){
                array_push($category_ids,$cid);
        }
    }

}