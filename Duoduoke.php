<?php

/*
                  _ooOoo_
                 o8888888o
                 88" . "88
                 (| -_- |)
                 O\  =  /O
              ____/`---'\____
            .'  \\|     |//  `.
           /  \\|||  :  |||//  \
          /  _||||| -:- |||||-  \
          |   | \\\  -  /// |   |
          | \_|  ''\---/''  |   |
          \  .-\__  `-`  ___/-. /
        ___`. .'  /--.--\  `. . __
     ."" '<  `.___\_<|>_/___.'  >'"".
    | | :  `- \`.;`\ _ /`;.`/ - ` : | |
    \  \ `-.   \_ __\ /__ _/   .-` /  /
======`-.____`-.___\_____/___.-`____.-'======
                  `=---='
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
                 佛祖保佑       永无BUG
                 
****************Powered by Adamin90***************
* @email: 14846869@qq.com
* Date: 2018/5/14
* Time: 17:21
* @link: https://www.lixiaopeng.top
**************************************************
*/

class Duoduoke
{
    const  DDK_API_BASE_URL = "http://gw-api.pinduoduo.com/api/router";  //api基础url
    const MERCHANT_OAUTH_BASE_URL = "http://mms.pinduoduo.com/open.html?";  //商家授权url
    const DDK_OAUTH_BASE_URL = "http://jinbao.pinduoduo.com/open.html?";  //多多客授权url
    const  DDK_BASEURL = "http://open-api.pinduoduo.com"; //多多客base url
    const  DDK_OAUTH_TOKEN_URL = "/oauth/token";  //多多客获取access_token api
    const  DDK_OAUTH_GRANT_TYPE = "authorization_code";  //授权类型

    const  DDK_API_OAUTH_CMS_PROM_URL_GENERATE = "pdd.ddk.oauth.cms.prom.url.generate";  //生成商城推广链接接口
    const  DDK_API_OAUTH_GOODS_PID_GENERATE = "pdd.ddk.oauth.goods.pid.generate";  //多多进宝推广位创建
    const DDK_API_OAUTH_GOODS_PID_QUERY = "pdd.ddk.oauth.goods.pid.query"; //多多客已生成推广位信息查询
    const DDK_API_OAUTH_GOODS_PROM_URL_GENERATE = "pdd.ddk.oauth.goods.prom.url.generate"; //生成多多进宝推广链接
    const DDK_API_OAUTH_ORDER_LIST_INCREMENT_GET = "pdd.ddk.oauth.order.list.increment.get"; //按照更新时间段增量同步推广订单信息
    const  DDK_API_OAUTH_ORDER_LIST_RANGE_GET = "pdd.ddk.oauth.order.list.range.get"; //按照时间段获取多多进宝推广订单信息
    const DDK_API_OAUTH_RP_PROM_URL_GENERATE = "pdd.ddk.oauth.rp.prom.url.generate"; //生成红包推广链接接口


    const  DDK_API_GOODS_DETAIL = "pdd.ddk.goods.detail";  //多多进宝商品详情查询
    const  DDK_API_GOODS_SEARCH = "pdd.ddk.goods.search"; //多多新报商品查询
    const  DDK_API_GOODS_PID_QUERY = "pdd.ddk.goods.pid.query"; //查询已生成的推广位信息
    const DDK_API_GOODS_PID_GENERATE = "pdd.ddk.goods.pid.generate";  //推广位创建
    const  DDK_API_GOODS_PROMOTION_URL_GENERATE = "pdd.ddk.goods.promotion.url.generate";  //推广链接生成
    const DDK_API_ORDER_LIST_RANGE_GET = "pdd.ddk.order.list.range.get";//用时间段查询推广订单接口
    const  DDK_API_ORDER_LIST_INCREMENT_GET = "pdd.ddk.order.list.increment.get";  //最后更新时间段增量同步推广订单信息
    const  DDK_API_CHECK_IN_PROM_BILL_INCR_GET = "pdd.ddk.check.in.prom.bill.incr.get"; //签到红包分享账单列表
    const  DDK_API_CHECK_IN_PROM_URL_GENERATE = "pdd.ddk.check.in.prom.url.generate";  //生成签到分享推广链接
    const DDK_API_RP_PROM_URL_GENERATE = "pdd.ddk.rp.prom.url.generate";  //生成红包推广链接
    const DDK_API_CMS_PROM_URL_GENERATE = "pdd.ddk.cms.prom.url.generate"; //生成商城推广链接
    const  DDK_API_GOODS_OPT_GET="pdd.goods.opt.get";  //获得平多多商品标签列表


    const  DDK_DEFAULT_DATA_TYPE="JSON"; //默认返回数据类型
    const DDK_DEFAULT_ACCESS_TOKEN="";  //默认访问令牌
    const DDK_DEFAULT_VERSION="v1"; //api协议版本号



   private  $ddk_common_params;   //公共配置
    private $client_id;
    private $client_secret;
    private $duoduoke_redirect_url;


    public function __construct($options)
    {
        $this->client_id = isset($options['client_id']) ? $options['client_id'] : "";
        $this->client_secret = isset($options["client_secret"]) ? $options["client_secret"] : "";
        $this->duoduoke_redirect_url = isset($options['duoduoke_redirect_url']) ? $options['duoduoke_redirect_url'] : "";
        $this->ddk_common_params['client_id']=$this->client_id;
        $this->ddk_common_params["timestamp"]=$_SERVER["REQUEST_TIME"];
        $this->ddk_common_params["data_type"]=self::DDK_DEFAULT_DATA_TYPE;
        $this->ddk_common_params["access_token"]=self::DDK_DEFAULT_ACCESS_TOKEN;
        $this->ddk_common_params["version"]=self::DDK_DEFAULT_VERSION;
    }

    /**
     * GET 请求
     * @param string $url
     */
    private function http_get($url)
    {
        $oCurl = curl_init();
        if (stripos($url, "https://") !== FALSE) {
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
        }
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if (intval($aStatus["http_code"]) == 200) {
            return $sContent;
        } else {
            return false;
        }
    }


    /**
     * POST 请求
     * @param string $url
     * @param array $param
     * @param boolean $post_file 是否文件上传
     * @return string content
     */
    private function http_post($url, $param, $post_file = false)
    {
        $oCurl = curl_init();
        $header[] = 'Accept:application/json';
        $header[] = 'Content-Type:application/json;charset=utf-8';
        if (stripos($url, "https://") !== FALSE) {
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
        }
        if (PHP_VERSION_ID >= 50500 && class_exists('\CURLFile')) {
            $is_curlFile = true;
        } else {
            $is_curlFile = false;
            if (defined('CURLOPT_SAFE_UPLOAD')) {
                curl_setopt($oCurl, CURLOPT_SAFE_UPLOAD, false);
            }
        }
        if (is_string($param)) {
            $strPOST = $param;
        } elseif ($post_file) {
            if ($is_curlFile) {
                foreach ($param as $key => $val) {
                    if (substr($val, 0, 1) == '@') {
                        $param[$key] = new \CURLFile(realpath(substr($val, 1)));
                    }
                }
            }
            $strPOST = $param;
        } else {
//            $aPOST = array();
//            foreach($param as $key=>$val){
//                $aPOST[] = $key."=".urlencode($val);
//            }
//            $strPOST =  join("&", $aPOST);
            $strPOST = json_encode($param);
        }
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($oCurl, CURLOPT_POST, true);
        curl_setopt($oCurl, CURLOPT_HTTPHEADER, $header);
        if (!empty($strPOST)) {
            curl_setopt($oCurl, CURLOPT_POSTFIELDS, $strPOST);
        }

        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if (intval($aStatus["http_code"]) == 200) {
            return $sContent;
        } else {
            return false;
        }
    }

    /**
     * 返回授权url
     * @param string $clitent_id
     * @param string $redirect_uri
     * @param string $state
     */
    public function duoduoke_oauth_code($clitent_id = "", $redirect_uri = "", $state = "2333")
    {
        if (!$clitent_id || !$redirect_uri) {
            $clitent_id = $this->client_id;
            $redirect_uri = $this->duoduoke_redirect_url;
        }
//http://jinbao.pinduoduo.com/open.html?client_id=ec049d08df8b5b082ba8566e73b94992&response_type=code&redirect_uri=http://hanshan.com
        return self::DDK_OAUTH_BASE_URL . "client_id=" . $clitent_id . "&response_type=code&redirect_uri=" . $redirect_uri;


    }

    /**
     * 获取access token
     * @param string $client_id 等同于client_id，创建应用时获得
     * @param string $client_secret 等同于client_secret，创建应用时获得
     * @param string $redirect_uri 可填写应用注册时回调地址域名
     * @param string $state 可自定义，如1212等；维持应用的状态，传入值与返回值保持一致。
     * @param string $code 授权码
     * @return string
     */
    public function ddk_oauth_token($client_id = "", $client_secret = "", $redirect_uri = "", $state = "2333", $code = "")
    {
        if (!$this->client_id || !$redirect_uri) {
            $client_id = $this->client_id;
            $redirect_uri = $this->duoduoke_redirect_url;
            $client_secret = $this->client_secret;
        }
        $data["client_id"] = $client_id;
        $data["client_secret"] = $client_secret;
        $data["grant_type"] = self::DDK_OAUTH_GRANT_TYPE;
        $data["code"] = $code;
        $data["redirect_uri"] = $redirect_uri;
        $data["state"] = $state;
        $result = $this->http_post(self::DDK_BASEURL . self::DDK_OAUTH_TOKEN_URL, $data);
        return $result;
    }

    /**
     * 参数排序并签名
     * 1.所有参数进行按照首字母先后顺序排列
     * 2-把排序后的结果按照参数名+参数值的方式拼接
     * 3-拼装好的字符串首尾拼接client_secret进行md5加密后转大写，secret的值是拼多多开放平台后台分配的client_secret
     * @param $params
     * @author Adam
     * @return  string
     */
    public function ddk_sign($params)
    {
        if (is_array($params)) {
            $str = "";
            ksort($params);
            foreach ($params as $k => $v) {
                $str .= ($k . $v);
            }
            //    return $str;
            $str = $this->client_secret . $str . $this->client_secret;
            return strtoupper(md5($str));

        } else {
            return "";
        }

    }

    /**
     * 产品详情api
     * @param $goodid
     * @return bool|string
     * @author Adam
     */
    public function api_goods_detail($goodid)
    {
        $data["type"] = self::DDK_API_GOODS_DETAIL;
        $data["goods_id_list"] = "[" . $goodid . "]";
          $data=  array_merge($data,$this->ddk_common_params);
        $sign = $this->ddk_sign($data);
        if (!empty($sign)) {
            $data['sign'] = $sign;
        } else {
            return false;
        }


        return $this->http_post(self::DDK_API_BASE_URL, $data);


    }

    /**
     * 查询商品标签列表
     * @param $parent_opt_id
     * @author Adam
     * Time: 9:03
     */
    public function api_goods_opt_get($parent_opt_id=0){
        $data['parent_opt_id']=$parent_opt_id;
        $data["type"]=self::DDK_API_GOODS_OPT_GET;
        $data=array_merge($data,$this->ddk_common_params);
        $sign = $this->ddk_sign($data);
        if (!empty($sign)) {
            $data['sign'] = $sign;
        } else {
            return false;
        }
        return $this->http_post(self::DDK_API_BASE_URL, $data);
    }


}