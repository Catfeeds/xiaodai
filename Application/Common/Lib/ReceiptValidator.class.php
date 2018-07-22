<?php

namespace Common\Lib;

class ReceiptValidator {

    const SANDBOX_URL    = 'https://sandbox.itunes.apple.com/verifyReceipt';
    const PRODUCTION_URL = 'https://buy.itunes.apple.com/verifyReceipt';
    const PUBLIC_KEY = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAhs/Hccc1AdoWFZqrwTaU9udTL7XEEPCM19ZgX0urLGnXsCQhRQlA4FUaxNGoch8KyTwfy+OFWqei3bg2xJD7LTwselgcwXJqpTZtC9sEPfGUPc0DFW65KgkO6iCx1HHLg2pKs6PLbBdDWrk7fsf+l5Ar7qISBkI0F95pE8+JAw6vp7iXZv9R3vATnmIon4xofZTUnSW0dNGRygJvq4Aeb6capSsE87aBe0kP8xssPLH+k6hfGIynKvIwWR1WKvsvgEHRPIKPXvRTjLCsJO54k3v0VIcaA0dxLZW1kJe3Q8lO+EBS03lFh5rgiGxVVmmHZam5MCR/RatPAsfqShisHQIDAQAB';

    function getReceipt() {
        return $this->receipt;
    }

    function setReceipt($receipt) {
        if (strpos($receipt, '{') !== false) {
            $this->receipt = base64_encode($receipt);
        } else {
            $this->receipt = $receipt;
        }
    }
    

     //验证Android
    function validateAndroidReceipt($signed_data, $signature) 
    {
         
        $key =  "-----BEGIN PUBLIC KEY-----\n".
            chunk_split(ReceiptValidator::PUBLIC_KEY, 64,"\n").
            '-----END PUBLIC KEY-----';   
        //using PHP to create an RSA key
        $key = openssl_get_publickey($key);
        //$signature should be in binary format, but it comes as BASE64. 
        //So, I'll convert it.
        $signature = base64_decode($signature);   
        //using PHP's native support to verify the signature
        $result = openssl_verify(
                $signed_data,
                $signature,
                $key,
                OPENSSL_ALGO_SHA1);
        if (0 === $result) 
        {
            return false;
        }
        else if (1 !== $result)
        {
            return false;
        }
        else 
        {
            return true;
        }
    }




    //验证IOS
    //
    //
    function validateIOSSandBoxReceipt($receipt) {
        $endpoint = ReceiptValidator::SANDBOX_URL;
        $this->setReceipt($receipt);
        $response = $this->makeRequest($endpoint);

        $decoded_response = $this->decodeResponse($response);
        
        if (!isset($decoded_response->status) || $decoded_response->status != 0) {
            return false;
        }

        if (!is_object($decoded_response)) {
            return false;
        }

        if($decoded_response->receipt){

            return true;
        }else{
            return false;
        }
    }


    function validateIOSReceipt($endpoint,$receipt) {
        $endpoint = ReceiptValidator::PRODUCTION_URL;
        $this->setReceipt($receipt);
        $response = $this->makeRequest($endpoint);

        $decoded_response = $this->decodeResponse($response);
        
        if (!isset($decoded_response->status) || $decoded_response->status != 0) {
            if($decoded_response->status==21007){
                return $this->validateIOSSandBoxReceipt($receipt);
            }else{
                return false;
            }
            
        }

        if (!is_object($decoded_response)) {
            return false;
        }

        if($decoded_response->receipt){

            return true;
        }else{
            return false;
        }
    }

    private function encodeRequest() {
        return json_encode(array('receipt-data' => $this->getReceipt()));
    }

    private function decodeResponse($response) {
        return json_decode($response);
    }

    private function makeRequest($endpoint) {
        $ch = curl_init($endpoint);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->encodeRequest());

        $response = curl_exec($ch);
        $errno    = curl_errno($ch);
        $errmsg   = curl_error($ch);
        curl_close($ch);

        if ($errno != 0) {
            throw new Exception($errmsg, $errno);
        }

        return $response;
    }
}
