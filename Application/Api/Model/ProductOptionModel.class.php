<?php
namespace Rest3\Model;

use Think\Model;
use Common\Lib\PasswordUtil;
use Common\Lib\Date;
use Common\Lib\HtmlUtils;

class ProductOptionModel extends IModel{

    
    #获取产品的option_id
    public function get_product_option_id($product_id){

         $co=array(
            'product_id'=>$product_id
         );
         $tmp=$this->field('option_id')->where($co)->cache(true)->find();
         return $tmp['option_id'];
    }

    #产品选项名称
    public function get_option_name($option_id,$language_id){

        $OptionDescriptionModel = D('option_description');
        $co=array(
            'option_id'=>$option_id
        );
        $it=$OptionDescriptionModel->cache(false)->findWithLanguage($co,$language_id);

        return $it['name'];
    }

    #产品选项值的名称
    public function get_product_option_value_name($option_value_id,$language_id){

        $OptionValueDescriptionModel = D('option_value_description');
        $co=array(
            'option_value_id'=>$option_value_id
        );
        $it=$OptionValueDescriptionModel->cache(true)->findWithLanguage($co,$language_id);
        return $it['name'];
    }


    #获取选项的价钱
    public function get_price_of_option($product_id,$option_value_id){

        $ProductModel = D('Product');

        $co=array(
            'product_id'=>$product_id,
            'status'=>1
        );

        $fields=array(
            'quantity',     #数量
            'price',        #价格
        );
        
        $p=$ProductModel->field($fields)->where($co)->find();
        $price = $p['price'];

        //$special_price_info = $ProductModel->getProductSpecialInfo($product_id);
        if($special_price_info){
            $price = $special_price_info['price'];
        }

        $option = $this->getProductOption($product_id,$price);

        if($option['price']){
            $price = $option['price'];
        }

        return $price;
    }


    #获取产品的单个选项
    private function getProductOption($product_id,$option_value_id,$price){
        
        $ProductOptionModel = D('ProductOption');

        $option_id=$ProductOptionModel->get_product_option_id($product_id);
        if(!$option_id){
            return null;
        }

        $ProductOptionValueModel = D('product_option_value');
        $co=array(
            'product_id'=>$product_id,
            'option_value_id'=>$option_value_id
        );

        $fs=array(
            'product_id',
            'quantity',
            'price',
            'price_prefix'
        );

        $li = $ProductOptionValueModel->field($fs)->where($co)->find();

        if(!$li){
            return null;
        }

        $li['price']=PriceFormat($li['price']);

        if($li['price_prefix']=='+'){
            $li['price']=(string)($price+$li['price']);
        }else{
            $li['price']=(string)($price-$li['price']);
        }

        unset($li['price_prefix']);
        unset($li['product_id']);

        return $li;
    }


    # 获取订单的产品选项信息
    public function get_order_product_option_by_option_value_id($product_id,$option_value_id,$language_id){

        $OptionValueDescriptionModel = D('option_value_description');
        $co=array(
            'option_value_id'=>$option_value_id
        );
        $it=$OptionValueDescriptionModel->findWithLanguage($co,$language_id);

        $ret=array();
        $ret['value'] = $this->get_product_option_value_name($option_value_id,$language_id);
        $ret['name'] = $this->get_option_name($it['option_id'],$language_id);
        $ret['type'] = 'Select'; #目前暂时没想到其他的选项类型.
        $ret['option_id'] = $it['option_id'];

        //$ret['price']= $this->get_price_of_option($product_id,$option_value_id);
        
        return $ret;
    }
}