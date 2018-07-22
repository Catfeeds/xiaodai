<?php

namespace Common\Lib;

use Common\Lib\CurlUtils;
use \Think\Log;

class EmailUtils{
    

    /** 
     *  这里需要对商家
     */

    public function sendEmail($to_email,$to_name,$body,$subject,$log_info=""){

        date_default_timezone_set(date_default_timezone_get());
        $mail= new \PHPMailer();

        $mail->SMTPOptions = array(    
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        
        $mail->CharSet = "utf-8";
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPDebug = 3;
        $mail->Host       = "smtp.sparkpostmail.com";    
        $mail->Username = "SMTP_Injection";
        $mail->Password = "f9b240286362c0feefac8e4167dc317ba35cde58";
        $mail->Port =587;
        $mail->From       = "service@greadeal.com";
        $mail->FromName   = "Greadeal Service";
        $mail->Subject    = $subject;
        $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!";
        $mail->WordWrap = 50;
        $mail->MsgHTML($body);
        $mail->AddAddress($to_email, $to_name);

        if(!$mail->Send()) {
            
          $r = $mail->ErrorInfo;
          $s = json_encode($r);
          Log::write("can't send email # $to_email # $s # $log_info",'WARN');

          return false;
        }

        return true;
    }



    // 发送该邮件
    static public function sendCloudsMail($to,$from,$subject,$body){

        # 使用api_user和api_key进行验证  
        $username = "Greadeal_test_v6q1Nb"; 
        $password = "vrZQ3nLe15VPAYCa";
        $host = "smtp.sendcloud.net";
        $port = 25;
        $post_data=array(
            'apiUser' =>$username,
            'apiKey'=>$password,
            'from'=>$from,
            'to'=>$to,
            'subject'=>$subject,
            'html'=>$body
        );
        
        $json = array();
        $uri = 'http://api.sendcloud.net/apiv2/mail/send';
        $response = post_by_curl($uri,$post_data);
    
        if(empty($response))
            return false;
        $json_data = json_decode($response,true);

        return $json_data;
    }

}