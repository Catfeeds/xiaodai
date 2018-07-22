<?php

namespace Common\Lib;
use Common\Lib\Logger;

class EtisalatUtils{


    public function register($order_id,$order_name,$order_info,$amount,$type){


        try{
            $soapClient = self::_soapClient();
            $result = $soapClient->Register(array(
                    "request" => array(
                        'Customer' => C('ETISALAT_CUSTOMER'),
                        'Channel' => 'Web',
                        'Address'   => C('ETISALAT_ADDRESS'),
                        'Language' => 'en',
                        'Password' => C('ETISALAT_PASSWORD'),
                        'version' => '2.0',
                        'Amount' => $amount,
                        'Currency' => 'AED',
                        'OrderID' => $order_id,
                        'OrderInfo' => 'Greadeal',
                        'OrderName' => 'Greadeal',
                        'ReturnPath' => C('ETISALAT_RETURNPATH').'&order_id='.$order_id.'&type='.$type,
                        'TransactionHint' => 'CPT:Y'
                ))
            );

            $resp=array(
                'ResponseCode'=>$result->RegisterResult->ResponseCode,
                'PaymentPortal'=>$result->RegisterResult->PaymentPortal,
                'TransactionID'=>$result->RegisterResult->TransactionID
            );

            if($resp->RegisterResult->ResponseCode!=0){
                IE('ETISALAT_EXCEPTION');
            }

            return $resp;

        }catch(Exception $e){
            Logger::warn('pay_from_web','fail',$e);
            IE('ETISALAT_EXCEPTION');
        }
    }


    public function finalize($transaction_id){

        try{

            $soapClient = self::_soapClient();
            $result = $soapClient->Finalize(
                        array(
                            "request" => array(
                                'Customer' => C('ETISALAT_CUSTOMER'),
                                'Language' => 'en',
                                'version' => '2.0',
                                'TransactionID'=>$transaction_id
                            )
                        )
            );

            if($result->FinalizeResult->ResponseCode!=0){
               IE('ETISALAT_UNPAID');
            }else{          
                return true;
            }

        }catch(Exception $e){
            Logger::warn('pay_from_web','fail',$e);
            IE('ETISALAT_EXCEPTION');
        }
    }


    private function _soapClient(){

        #构造请求
        $opts = array(
                'ssl' => array('ciphers'=>'RC4-SHA', 'verify_peer'=>false, 'verify_peer_name'=>false)
        );
        $params = array (
            'encoding' => 'UTF-8',
            'verifypeer' => false,
            'verifyhost' => false,
            'trace' => 1,
            'exceptions' => 1,
            'connection_timeout' => 1800,
            #'stream_context' => stream_context_create($opts),
            'local_cert'    => C('ETISALAT_CERT'),
        );
        
        $SoapClient = new \SoapClient ( C('ETISALAT_SOAP') , $params );

        return $SoapClient;
    }
}