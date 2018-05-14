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
    const MERCHANT_OAUTH_BASE_URL="http://mms.pinduoduo.com/open.html?";  //商家授权url
    const DDK_OAUTH_BASE_URL="http://jinbao.pinduoduo.com/open.html?";  //多多客授权url
    const  DDK_BASEURL="http://open-api.pinduoduo.com"; //多多客base url
    const  DDK_OAUTH_TOKEN_URL="/oauth/token";  //多多客获取access_token api
    const  DDK_OAUTH_GRANT_TYPE="authorization_code";  //授权类型
    private  $client_id;
    private  $client_secret;
    private $duoduoke_redirect_url;
    public function  __construct($options)
    {
      $this->client_id=isset($options['client_id'])?$options['client_id']:"";
      $this->client_secret=isset($options["client_secret"])?$options["client_secret"]:"";
      $this->duoduoke_redirect_url=isset($options['duoduoke_redirect_url'])?$options['duoduoke_redirect_url']:"";
    }

    /**
     * GET 请求
     * @param string $url
     */
    private function http_get($url){
        $oCurl = curl_init();
        if(stripos($url,"https://")!==FALSE){
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
        }
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if(intval($aStatus["http_code"])==200){
            return $sContent;
        }else{
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
    private function http_post($url,$param,$post_file=false){
        $oCurl = curl_init();
        if(stripos($url,"https://")!==FALSE){
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
        }elseif($post_file) {
            if($is_curlFile) {
                foreach ($param as $key => $val) {
                    if (substr($val, 0, 1) == '@') {
                        $param[$key] = new \CURLFile(realpath(substr($val,1)));
                    }
                }
            }
            $strPOST = $param;
        } else {
            $aPOST = array();
            foreach($param as $key=>$val){
                $aPOST[] = $key."=".urlencode($val);
            }
            $strPOST =  join("&", $aPOST);
        }
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($oCurl, CURLOPT_POST,true);
        curl_setopt($oCurl, CURLOPT_POSTFIELDS,$strPOST);
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if(intval($aStatus["http_code"])==200){
            return $sContent;
        }else{
            return false;
        }
    }

    public function  duoduoke_oauth_code($clitent_id="",$redirect_uri="",$state="2333"){
           if(!$this->client_id||!$redirect_uri){
               $client_id=$this->client_id;
               $redirect_uri=$this->duoduoke_redirect_url;
           }


    }

    /**
     * 获取access token
     * @param string $client_id   等同于client_id，创建应用时获得
     * @param string $client_secret 等同于client_secret，创建应用时获得
     * @param string $redirect_uri   可填写应用注册时回调地址域名
     * @param string $state  可自定义，如1212等；维持应用的状态，传入值与返回值保持一致。
     * @param string $code     授权码 
     * @return string
     */
    public function  ddk_oauth_token($client_id="",$client_secret="",$redirect_uri="",$state="2333",$code=""){
        if(!$this->client_id||!$redirect_uri){
            $client_id=$this->client_id;
            $redirect_uri=$this->duoduoke_redirect_url;
            $client_secret=$this->client_secret;
        }
        $data["client_id"]=$client_id;
        $data["client_secret"]=$client_secret;
        $data["grant_type"]=self::DDK_OAUTH_GRANT_TYPE;
        $data["code"]=$code;
        $data["redirect_uri"]=$redirect_uri;
        $data["state"]=$state;
        $result=$this->http_post(self::DDK_BASEURL.self::DDK_OAUTH_TOKEN_URL,$data);
        return $result;
    }




}