<?php
namespace Rest3\Service;

use Common\Service\IService;
use Common\Lib\ArrayUtils;
use Common\Lib\SortUtils;
use Common\Lib\MapUtils;
use Common\Lib\String;


class MapService {
    const GOOGLEMAPURI = 'http://maps.googleapis.com';

    // 返回国家，城市，地址
    public function location($longitude,$latitude){
        $city = '';
        $country = '';
        $address = '';
        $url = MapService::GOOGLEMAPURI.'//maps/api/geocode/json?latlng='.$latitude.','.$longitude."&language=ar";
        $response = get_by_curl($url);
        if($response){
            $json_address = json_decode($response,true);
            if($json_address){
                foreach ($json_address['results'] as $result) {
                    if(isset($result['address_components'])){
                        $address_info = $result['address_components'];
                        foreach ($address_info as $addr_item){
                            $types = $addr_item['types'];
                            if(in_array('country',$types)&&$country==''){
                                $country = $addr_item['long_name'];
                            }
                            if(in_array('locality',$types)&&$city==''){
                                $city = $addr_item['long_name'];
                            }
                            if(in_array('administrative_area_level_1',$types)&&$city==''){
                                $city = $addr_item['long_name'];
                            }
                        }
                        if($address==''){
                            $address = $result['formatted_address'];
                        }
                    }
                }

            }else{
                return null;
            }
        }else{
            return null;
        }
        if($address&&$city&&$country){
            /**
             *  by zhao ，返回国家，城市，地址，失败返回NULL
             */
            return array(
                'city'=>$city,
                'country'=>$country,
                'address'=>$address
            );
        }else{
            return null;
        }
    }

}