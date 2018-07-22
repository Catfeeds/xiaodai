<?php
namespace Rest3\Model;

use Think\Model;
use Common\Lib\FormatUtils;
use Common\Lib\SortUtils;

class BannerModel extends IModel{

    
    #根据语言获取banner列表
    public function get_banner_list($key,$country_id,$language_id){
        
        $co=array(
            'name'=>$key,
        );
        $it= $this->where($co)->field('banner_id')->cache(false)->find();
        $banner_id = $it['banner_id'];

        $BannerImageDescription=M('BannerImageDescription');
        $co=array(
            'banner_id'=>$banner_id,
            'country_id'=>array('in',array(0,$country_id))
        );
        
        $list = $BannerImageDescription->selectWithLanguage($co,$language_id,'title,link,image,type,sort_order');

        foreach ($list as &$li) {
            
            FormatUtils::HtmlDecoder($li,'link');

            $li['image'] = ImageCDN($li['image'],720,226);
            if($li['type']=='product'){

                $li['id'] = $li['link'];
                $p = D('Product')->get_info($li['id'],$language_id,null,'name');
                $li['title'] = $p['name'];
               
                unset($li['link']);
            }elseif($li['type']=='category'){
                $li['id'] = $li['link'];
                $t= D('Category')->get_info($li['id'],$language_id,null,'name');
                $li['title'] = $t['name'];
                
                unset($li['link']);
            }
        }

        SortUtils::InsertionSort($list,"sort_order","desc");

        return $list;
    }
    
}