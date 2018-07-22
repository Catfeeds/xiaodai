<?php
namespace Rest3\Service;

use Common\Service\IService;
use Common\Lib\ArrayUtils;
use Common\Lib\SortUtils;
use Common\Lib\MapUtils;
use Common\Lib\String;


class SearchService {
    

    
    public function search_product($keyword,$country_id,$latitude,$longitude,$language_id,$page,$limit){

        $keyword = str_replace("'",'', $keyword);

        $kws = explode(" ",$keyword);
        $like_str = "";
        $first=true;
        foreach ($kws as $w) {
            if($first){
                $like_str = sprintf("(search_index_nospace LIKE '%s')","%$w%");
            }else{
                $like_str .= sprintf(" and (search_index_nospace LIKE '%s')","%$w%");
            }
            $first=false;
        }

        $co=array(
            //'zone_id'=>$country_id,
            'language_id'=>$language_id
        );
        $co['_string'] = $like_str;

        $plist = D('product_index')->cache(false)->selectWithLanguage($co,$language_id,'product_id');

        $plist = ArrayUtils::Paging($plist,$page,$limit);

        $product_ids = ArrayUtils::Collect($plist,'product_id');

        //产品列表
        $ProductModel = D('Product');
        $product_list = Service('Product')->get_product_view_list($product_ids,$language_id,$latitude,$longitude);
        
        return array(
            'count'=>count($product_list),
            'list'=>$product_list
        );
    }


    #联想搜素产品
    public function associate_product($keyword,$country_id,$latitude,$longitude,$language_id,$page,$limit){
        
        $kws = explode(" ",$keyword);
        $like_str = "";
        $first=true;
        foreach ($kws as $w) {
            if($first){
                $like_str = sprintf("(search_index_nospace LIKE '%s')","%$w%");
            }else{
                $like_str .= sprintf(" and (search_index_nospace LIKE '%s')","%$w%");
            }
            $first=false;
        }

        $co=array(
//            'zone_id'=>$zone_id,
            '_string'=>$like_str
        );

        $list = D('product_index')->cache(false)->page($page)->limit($limit)->selectWithLanguage($co,$language_id,'product_id,search_index_nospace,search_index');

            
        $words=array();
        foreach ($list as $li) {
            
            $index = $li['search_index_nospace'];
            $arr = explode('#', $index);
            $p_name = $arr[0];
            $v_name = $arr[1];

            $real_arr = explode('#',$li['search_index']);
            $real_p_name = $real_arr[0];
            $real_v_name = $real_arr[1];

            $f = $this->_match($v_name,$kws);

            if($f){

                if(in_array($real_v_name,$words)){
                    continue;
                }else{
                    array_push($words,$real_v_name);
                }
            }else{

                # 检测产品名
                $pf = $this->_match($p_name,$kws);

                if($pf){
                    if(in_array($real_p_name,$words)){
                        continue;
                    }else{
                        array_push($words,$real_p_name);
                    }
                }
            }
        }

        return ArrayUtils::Paging($words,$page,$limit);
    }


    private function _match($str,$ks){

        foreach ($ks as $k) {   
            if(!String::contains($str,$k)){
                return false;
            }
        }
        return true;
    }

    #搜索
    public function search_vendor($keyword,$country_id,$latitude,$longitude,$page,$limit,$language_id){
        
        $Vendor=D('Vendor');

        $co=array(
            'vendor_name'=>array('like',"%$keyword%"),
            'entree'=>array('like',"%$keyword%"),
            'main_service'=>array('like',"%$keyword%"),
        );
        $co['_logic'] = 'OR';
        $list = M('vendor_description')->field('vendor_id')->cache(true)->where($co)->select();  #selectWithLanguage($co,$language_id,'product_id');


        $vendor_ids = ArrayUtils::Collect($list,'vendor_id');
        ArrayUtils::Group($vendor_ids);

        $vendor_list = $Vendor->get_list($vendor_ids,$language_id,
            'vendor_id,vendor_image,country_id,zone_id,area_id,latitude,longitude,fee_per_person,membership_level,status',
            'vendor_id,main_service,vendor_name');

        ArrayUtils::StatusFilter($vendor_list);

        $ReviewModel = D('Review');
        foreach ($vendor_list as &$li) {
            $li['distance'] = MapUtils::getDistance($latitude, $longitude, $li['latitude'], $li['longitude']);
            $li['rating'] = $ReviewModel->get_rating_of_vendor($li['vendor_id']);
        }

        $ProductService = Service('Product');
        foreach ($vendor_list as &$v) {
            $v['product_list'] = $ProductService->get_product_list_of_vendor($v['vendor_id'],$language_id);
            ArrayUtils::StatusFilter($v['product_list']);
        }

        SortUtils::InsertionSort($vendor_list,'distance','asc');

        return array(
            'count'=>count($vendor_ids),
            'list'=>ArrayUtils::Paging($vendor_list,$page,$limit)
        );
    }
    

    # 获取
    public function get_hot_words(){

        $language_id = IV('language_id','require');


        $SearchWordModel = M('search_word');


        $co=array(
            'language_id'=>$language_id,
        );

        $list = $SearchWordModel->field('word')->cache(true)->where($co)->select();

        $words = ArrayUtils::Collect($list,'word'); // 将数组=>进行group

        ArrayUtils::Count($words); # 统计
        SortUtils::InsertionSort($words,'count','desc'); #排序
    }

}