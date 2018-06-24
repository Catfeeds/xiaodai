<?php
require_once("Config.php");
require_once("Util.php");
require_once("Exception.php");
require_once("TokenHelper.php");

class TaskUtil {

    private static $appKey = Config::APPKEY;
    private static $host = Config::HOST;
    private static $tokenVersion = Config::TOKENVERSION;
    private static $accessKey = Config::ACCESSKEY;
    private static $secretKey = Config::SECRETKEY;

    /**
     * Describe : 正常提交任务
     * Date: 2017年7月13日
     * @param string $taskType 任务类型
     * @param array $bodyJson 请求体
     * @param string $method 请求方法
     * @param int $ttTime 超时时间
     * @return array 返回执行结果
     */
    public static function submitTask($taskType, $bodyJson = array(), $method = 'POST', $ttTime = -1) {

        //验证任务类型
        if (empty($taskType)) {
            throw new Yunfeng_Exception("general_1", "Invalid taskType");
        }

        #生成获取Token的参数
        $urlPath = Config::URLPATH_PREFIX;
        $queryParam = "appKey=" . self::$appKey . "&taskType=" . $taskType;

        $result = self::runTask($urlPath, $queryParam, $bodyJson, $method, $ttTime);

        return $result;
    }

    /**
     * Describe : 查询任务
     * Date: 2017年7月13日
     * @param string $taskNo 任务号
     * @param string $method 提交方法
     * @param int $ttTime 过期时间
     * @return array 返回执行结果
     */
    public static function queryTask($taskNo, $method = 'GET', $ttTime = -1) {

        //验证任务号
        if (empty($taskNo)) {
            throw new Yunfeng_Exception("general_1", "Invalid taskNo");
        }

        #生成获取Token的参数
        $urlPath = Config::URLPATH_PREFIX . "/" . $taskNo . "/result";
        $queryParam = "appKey=" . self::$appKey;

        $result = self::runTask($urlPath, $queryParam, '', $method, $ttTime);

        return $result;

    }

    /**
     * Describe : 补充任务
     * Date: 2017年7月13日
     * @param string $taskNo 任务号
     * @param array $bodyJson 请求体
     * @param string $method 请求方法
     * @param int $ttTime 超时时间
     * @return array 返回执行结果
     */
    public static function patchTask($taskNo, $bodyJson = array(), $method = 'POST', $ttTime = -1) {

        //验证任务号
        if (empty($taskNo)) {
            throw new Yunfeng_Exception("general_1", "Invalid taskNo");
        }

        #生成获取Token的参数
        $urlPath = Config::URLPATH_PREFIX . "/" . $taskNo;
        $queryParam = "appKey=" . self::$appKey;

        $result = self::runTask($urlPath, $queryParam, $bodyJson, $method, $ttTime);

        return $result;

    }

    /**
     * Describe : 通用任务执行方法
     * Date: 2017年7月13日
     * @param string $urlPath API调用url
     * @param string $queryParam API调用url的参数
     * @param array $bodyJson Body请求体
     * @param string $method 请求方法
     * @param int $ttTime 过期时间
     * @return array 返回执行结果
     */
    private static function runTask($urlPath, $queryParam, $bodyJson = array(), $method = 'POST', $ttTime) {

        //验证appkey
        if (empty(self::$appKey)) {
            throw new Yunfeng_Exception("general_1", "Invalid appkey");
        }

        #设置默认过期时间1小时
        if ($ttTime < 0) {
            $ttTime = time() + 3600;
        }

        #生成获取Token的参数
        if (is_array($bodyJson)) {
            $bodyJson = json_encode($bodyJson, 320);
        }

        #get token
        $TokenHelper = new TokenHelper(self::$accessKey, self::$secretKey, self::$tokenVersion);
        $token = $TokenHelper->generateToken($urlPath, $method, $queryParam, $bodyJson, $ttTime);

        #request
        $Util = new Util();
        $url = 'https://' . self::$host . $urlPath . '?' . $queryParam;

        $header[] = "X-IbeeAuth-Token:{$token}";
        $result = $Util->get_curl($url, $method, $bodyJson, $header);

        return $result;

    }


}