<?php

namespace Common\Lib;
use Common\Lib\Logger;

class AsynUtils{


    # 异步请求...
    static public function asynRequest($get_url){

        #-------
        $IP = gethostbyname(C('HOST'));
        $Port = C('PORT');
        #-------

        Logger::error('AsynRequest',"$IP@$Port@$get_url");

        $fp = stream_socket_client($IP.':'.$Port, $errno, $errstr, 30);

        if ($fp) {
            
            $out = "GET $get_url"." HTTP/1.1\r\n";
            $out .= "Host: ".$Port."\r\n";
            $out .= "Connection: Close\r\n\r\n";
            fwrite($fp, $out);
            fclose($fp);

            /*
            while (!feof($fp)) {
                echo fgets($fp, 1024);
            }*/

        }else{
            Logger::error('AsynRequest',"$IP@$Port@$get_url");
        }
    }
}