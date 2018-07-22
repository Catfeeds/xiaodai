<?php

namespace Common\Lib;

class Paypallogin
{
 
    //沙箱token链接
    private $_sanbox_oauth2_auth_uri = 'https://www.sandbox.paypal.com/webapps/auth/protocol/openidconnect/v1/authorize';
    private $_live_oauth2_auth_uri = 'https://www.paypal.com/webapps/auth/protocol/openidconnect/v1/authorize';
     
    private $_acquire_user_profile_sandbox_url = 'https://www.sandbox.paypal.com/webapps/auth/protocol/openidconnect/v1/userinfo?schema=openid&access_token=';
    private $_acquire_user_profile_live_url = 'https://www.paypal.com/webapps/auth/protocol/openidconnect/v1/userinfo?schema=openid&access_token=';
 
    //沙箱token链接
    private $_token_service_sandbox_url = 'https://www.sandbox.paypal.com/webapps/auth/protocol/openidconnect/v1/tokenservice'; 
    private $_token_service_live_url  = 'https://www.paypal.com/webapps/auth/protocol/openidconnect/v1/tokenservice';
    private $_sanbox_flag = true;
    private $_client_id = null;
    private $_client_secret = null;
    private $_redirect_uri = null;
    private $_state = '';
    private $_scope = 'openid email phone profile address https://uri.paypal.com/services/paypalattributes'; 

    //scope 参数决定访问令牌的访问权限 各个参数详解url;:https://www.paypal-biz.com/product/login-with-paypal/index.html#configureButton
 
    public $token = null;
    public $protocol = "http";
 
 
    /**
    * @name 构造函数
    * @param $flag 是否沙箱环境
    */
    public function __construct($redirect_uri, $client_id,$client_secret,$scope,$state,$flag = true)
    {
        $this->_sanbox_flag = $flag;
        $this->_redirect_uri = $redirect_uri;
        $this->_client_id = $client_id;
        $this->_client_secret = $client_secret;
        $this->_scope = $scope;
        $this->_state = $state;
    }
 
    /**
     * 创建paypal request url
     * @return string
     */
    public function create_request_url()
    {
        $oauth2_auth_uri = $this->_sanbox_flag ? $this->_sanbox_oauth2_auth_uri :$this->_live_oauth2_auth_uri;
        $url =  $oauth2_auth_uri.'?'.
        http_build_query(
            array(
                'client_id' => $this->_client_id, //通过应用程序注册流程获得的唯一客户端标识符。必需。
                'response_type' =>'code', //表明授权代码被发送回应用程序返回URL。为了使访问令牌在用户代理中不可见， 建议使用<code>code</code>一值。如果您希望在响应中同时收到授权代码和 id_token ，请传递 code+id_token。另一个可能的 response_type 值是 token ——大部分由javascript和移动客户端等公共客户端使用。
                'scope' => $this->_scope,//;implode(',', $this->scope),
                'redirect_uri' => urlencode($this->_redirect_uri), //应用程序的返回URL。结构、主机名和端口必须与您在注册应用程序时设置的返回URL相符。
                'nonce' => time().rand(), //不透明的随机标识符，可减少重放攻击风险。简单的函数是：(timestamp + Base64 encoding (random\[16\]))。
                'state' => $this->_state, // CSRF验证码
            )
        );
        return $url;
    }
 
    /**
     * get PayPal access token
     * @param  string $code ?
     * @return string       access token
     */
    public function acquire_access_token($code ) {
        $accessToken = null;
 
        try {
            $postvals = sprintf("client_id=%s&client_secret=%s&grant_type=authorization_code&code=%s",$this->_client_id,$this->_client_secret,$code);
            if($this->_sanbox_flag)
               $ch = curl_init($this->_token_service_sandbox_url);
            else
               $ch = curl_init($this->_token_service_live_url);  
 
            $options = array(
                CURLOPT_POST           => 1,
                CURLOPT_VERBOSE        => 1,
                CURLOPT_POSTFIELDS     => $postvals,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_SSL_VERIFYPEER => FALSE,
                //CURLOPT_SSLVERSION => 2
            );
 
            curl_setopt_array($ch, $options);
            $response = curl_exec($ch);
            $error = curl_error($ch);
 
            curl_close( $ch );
 
            if (!$response ) {
                throw new Exception( "Error retrieving access token: " . curl_error($ch));
            }
            $jsonResponse = json_decode($response );
 
            if ( isset( $jsonResponse->access_token) ) {
                $accessToken = $jsonResponse->access_token;
            }
 
        } catch( Exception $e) {
            throw new Exception($e->getMessage(), 1);
        }
 
        return $accessToken;
    }
 
    /**
     * get the PayPal user profile, decoded
     * @param  string $accessToken
     * @return object
     */
    public function acquire_paypal_user_profile($accessToken ) {
        try {
            if($this->_sanbox_flag)
               $url = $this->_acquire_user_profile_sandbox_url . $accessToken;
            else
                $url = $this->_acquire_user_profile_live_url . $accessToken;    
 
            $ch = curl_init( $url );
            $options = array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_SSL_VERIFYPEER => FALSE,
                //CURLOPT_SSLVERSION => 2
            );
            curl_setopt_array($ch, $options);
 
            $response = curl_exec($ch);
            $error = curl_error( $ch);
            curl_close( $ch );
 
            if (!$response ) 
            {
                return false;
            }
            return json_decode($response);
        } catch( Exception $e ) {
            return false;
        }
    }
}
?>