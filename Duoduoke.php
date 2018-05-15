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
    const  DDK_API_GOODS_OPT_GET = "pdd.goods.opt.get";  //获得多多商品标签列表
    const DDK_API_GOODS_CAT_GET = "pdd.goods.cats.get"; //商品标准类目


    const  DDK_DEFAULT_DATA_TYPE = "JSON"; //默认返回数据类型
    const DDK_DEFAULT_ACCESS_TOKEN = "";  //默认访问令牌
    const DDK_DEFAULT_VERSION = "v1"; //api协议版本号
    const  DDK_DEFAULT_PAGESIZE = 50; //默认大小


    private $ddk_common_params;   //公共配置
    private $client_id;
    private $client_secret;
    private $duoduoke_redirect_url;


    public function __construct($options)
    {
        $this->client_id = isset($options['client_id']) ? $options['client_id'] : "";
        $this->client_secret = isset($options["client_secret"]) ? $options["client_secret"] : "";
        $this->duoduoke_redirect_url = isset($options['duoduoke_redirect_url']) ? $options['duoduoke_redirect_url'] : "";
        $this->ddk_common_params['client_id'] = $this->client_id;
        $this->ddk_common_params["timestamp"] = time();
        $this->ddk_common_params["data_type"] = self::DDK_DEFAULT_DATA_TYPE;
        $this->ddk_common_params["access_token"] = self::DDK_DEFAULT_ACCESS_TOKEN;
        $this->ddk_common_params["version"] = self::DDK_DEFAULT_VERSION;
        $this->ddk_common_params["client_secret"] = $this->client_secret;
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


        return $this->merge_sign_return($data);


    }

    /**
     * 查询商品标签列表
     * @param $parent_opt_id
     * @author Adam
     * Time: 9:03
     */
    public function api_goods_opt_get($parent_opt_id = 0)
    {
        $data['parent_opt_id'] = $parent_opt_id;
        $data["type"] = self::DDK_API_GOODS_OPT_GET;

        return $this->merge_sign_return($data);
    }

    /**
     * 商品标准类目列表
     * @param int $parent_cat_id
     * @return bool|string
     * @author Adam
     * Time: 9:32
     */
    public function api_goods_cat_get($parent_cat_id = 0)
    {
        $data['parent_cat_id'] = $parent_cat_id;
        $data['type'] = self::DDK_API_GOODS_CAT_GET;

        return $this->merge_sign_return($data);
    }

    /**
     * 多多进宝商品查询
     * @param string $keyword
     * @param int $opt_id 商品关键词，与opt_id字段选填一个或全部填写
     * @param int $page
     * @param int $page_size
     * @param int $sort_type 排序方式：0-综合排序；1-按佣金比率升序；2-按佣金比例降序；3-按价格升序；4-按价格降序；5-按销量升序；6-按销量降序；7-优惠券金额排序升序；8-优惠券金额排序降序；9-券后价升序排序；10-券后价降序排序；11-按照加入多多进宝时间升序；12-按照加入多多进宝时间降序
     * @param bool $with_coupon 是否只返回优惠券的商品，false返回所有商品，true只返回有优惠券的商品  这个地方用字符串
     * @param string $range_list 范围列表，可选值：[{"range_id":0,"range_from":1,"range_to":1500},{"range_id":1,"range_from":1,"range_to":1500}]
     * 如果左区间不限制，range_from传空就行，右区间不限制，range_to传空就行
     * range_id 查询维度ID，枚举值如下：0-商品拼团价格区间，1-商品券后价价格区间，2-佣金比例区间，3-优惠券金额区间，4-加入多多进宝时间区间，5-销量区间，6-佣金金额区间
     * @param int $cat_id 商品类目ID，使用pdd.goods.cats.get接口获取
     * @param string $goods_id_list 商品ID列表。例如：[123456,123]，当入参带有goods_id_list字段，将不会以opt_id、 cat_id、keyword维度筛选商品
     * @return bool|string
     * @author Adam
     * Time: 10:15
     */
    public function api_goods_search($keyword = "", $opt_id = 0, $page = 1, $page_size = self::DDK_DEFAULT_PAGESIZE, $sort_type = 0, $with_coupon = true, $range_list = "", $cat_id = 0, $goods_id_list = "")
    {
        $data['type'] = self::DDK_API_GOODS_SEARCH;
        if (!empty($keyword)) {
            $data['keyword'] = urlencode($keyword);
        }
        if (!empty($opt_id)) {
            $data['opt_id'] = $opt_id;
        }
        $data["page"] = $page;
        $data["page_size"] = $page_size;
        $data["sort_type"] = $sort_type;
        $data['with_coupon'] =$with_coupon?"true":"false";
        if (!empty($range_list)) {
            $data["range_list"] = $range_list;

        }
        if (!empty($cat_id)) {
            $data["cat_id"] = $cat_id;
        }
        if (!empty($goods_id_list)) {
            $data["goods_id_list"] = $goods_id_list;
        }


        return $this->merge_sign_return($data);
    }


    /**
     * 查询已经生成的推广位信息
     * @param int $page
     * @param int $page_size
     * @return bool|string
     * @author Adam
     * Time: 15:17
     */
    public function api_goods_pid_query($page = 1, $page_size = self::DDK_DEFAULT_PAGESIZE)
    {
        $data["type"] = self::DDK_API_GOODS_PID_QUERY;
        $data["page"] = $page;
        $data["page_size"] = $page_size;
        return $this->merge_sign_return($data);

    }


    /**
     * 创建多多进宝推广位
     * @param int $number 创建数量
     * @return bool|string
     * @author Adam
     * Time: 16:50
     */
    public function  api_goods_pid_generate($number=1){
        $data["type"]=self::DDK_API_GOODS_PID_GENERATE;
        $data["number"]=$number;
        return $this->merge_sign_return($data);
    }

    /**
     * 多多进宝推广链接生成
     * @param string $pid  推广位ID
     * @param $goodid  商品ID，仅支持单个查询
     * @param bool $generate_short  是否生成短链接，true-是，false-否
     * @param bool $multi_group true--生成多人团推广链接 false--生成单人团推广链接（默认false）1、单人团推广链接：用户访问单人团推广链接H5页面，可直接购买商品无需拼团。（若用户访问APP，则按照多人团推广链接处理）2、多人团推广链接：用户访问双人团推广链接开团，若用户分享给他人参团，则开团者和参团者的佣金均结算给推手。
     * @param string $custom_parameters  自定义参数，为链接打上自定义标签。自定义参数最长限制64个字节。
     * @return bool|string
     * @author Adam
     * Time: 17:05
     */
    public function  api_goods_promotion_url_generate($goodid,$pid="1005378_14197486",$generate_short=true,$multi_group=false,$custom_parameters=""){
        $data["type"]=self::DDK_API_GOODS_PROMOTION_URL_GENERATE;
        $data['p_id']=$pid;
        $data["goods_id_list"]="[".$goodid."]";
        $data["generate_short_url"]=$generate_short?"true":"false";
        $data["multi_group"]=$multi_group?"true":"false";
        $data["custom_parameters"]=$custom_parameters;
        return $this->merge_sign_return($data);

    }

    /**
     * 用时间段查询推广订单接口
     * @param $start_time  查询最近90天内多多进宝商品创建订单开始时间。格式:yyyy-MM-dd
     * @param $end_time    查询最近90天内多多进宝商品创建订单结束时间。格式:yyyy-MM-dd
     * @param string $pid  推广位ID
     * @param int $page_size  返回的每页结果订单数，默认为100，范围为10到100，建议使用40~50，可以提高成功率，减少超时数量。
     * @param int $page  第几页，从1到10000，默认1，注：使用最后更新时间范围增量同步时，必须采用倒序的分页方式（从最后一页往回取）才能避免漏单问题。
     * @param int $time_type  过滤的时间类型：0--创建时间，1--支付时间， 9--最后更新时间 （默认0）
     * @return bool|string
     * @author Adam
     * Time: 17:29
     */
    public function  api_order_list_range_get($start_time,$end_time,$pid="",$page_size=50,$page=1,$time_type=0){
        $data["type"]=self::DDK_API_ORDER_LIST_RANGE_GET;
        $data["start_time"]=$start_time;
        $data["end_time"]=$end_time;
        if(!empty($pid)){
            $data["p_id"]=$pid;
        }
        $data["page_size"]=$page_size;
        $data["page"]=$page;
        $data["time_type"]=$time_type;
        return $this->merge_sign_return($data);
    }

    /**
     * 最后更新时间段增量同步推广订单信息
     * @param $start_update_time 最近90天内多多进宝商品订单更新时间--查询时间开始。note：此时间为时间戳，指格林威治时间 1970 年01 月 01 日 00 时 00 分 00 秒(北京时间 1970 年 01 月 01 日 08 时 00 分 00 秒)起至现在的总秒数
     * @param $end_update_time  最近90天内多多进宝商品订单更新时间--查询时间结束。note：此时间为时间戳，指格林威治时间 1970 年01 月 01 日 00 时 00 分 00 秒(北京时间 1970 年 01 月 01 日 08 时 00 分 00 秒)起至现在的总秒数
     * @param string $p_id  推广位ID
     * @param int $page_size 返回的每页结果订单数，默认为100，范围为10到100，建议使用40~50，可以提高成功率，减少超时数量。
     * @param int $page  第几页，从1到10000，默认1，注：使用最后更新时间范围增量同步时，必须采用倒序的分页方式（从最后一页往回取）才能避免漏单问题。
     * @return bool|string
     * @author Adam
     * Time: 17:39
     */
    public function  api_order_list_increment_get($start_update_time,$end_update_time,$p_id="",$page_size=50,$page=1){
        $data["type"]=self::DDK_API_ORDER_LIST_INCREMENT_GET;
        $data["start_update_time"]=$start_update_time;
        $data["end_update_time"]=$end_update_time;
        if(!empty($pid)){
            $data["p_id"]=$p_id;
        }
        $data["page_size"]=$page_size;
        $data["page"]=$page;
        return $this->merge_sign_return($data);

    }

    /**
     * 签到红包分享账单列表
     * @param $start_time  最后更新时间--查询时间开始。note：此时间为时间戳，指格林威治时间 1970 年01 月 01 日 00 时 00 分 00 秒(北京时间 1970 年 01 月 01 日 08 时 00 分 00 秒)起至现在的总秒数
     * @param $end_time   最后更新时间--查询时间结束。note：此时间为时间戳，指格林威治时间 1970 年01 月 01 日 00 时 00 分 00 秒(北京时间 1970 年 01 月 01 日 08 时 00 分 00 秒)起至现在的总秒数
     * @param int $page_size 返回的每页结果订单数，默认为100，范围为10到100，建议使用40~50，可以提高成功率，减少超时数量。
     * @param int $page   第几页，从1到10000，默认1，注：使用最后更新时间范围增量同步时，必须采用倒序的分页方式（从最后一页往回取）才能避免漏单问题
     * @param string $p_id
     * @return bool|string
     * @author Adam
     * Time: 22:22
     */
    public function  api_check_in_prom_bill_incr_get($start_time,$end_time,$page_size=50,$page=1,$p_id="1005378_14197486"){
        $data["type"]=self::DDK_API_CHECK_IN_PROM_BILL_INCR_GET;
        $data["start_time"]=$start_time;
        $data["end_time"]=$end_time;
        $data["page_size"]=$page_size;
        $data["page"]=$page;
        $data["p_id"]=$p_id;
        return $this->merge_sign_return($data);

    }

    /**
     * 生成签到分享推广链接
     * @param string $p_id
     * @author Adam
     * Time: 2018/5/15 22:37
     */
    public function  api_check_in_prom_url_generate($p_id="1005378_14197486"){
        $data["type"]=self::DDK_API_CHECK_IN_PROM_URL_GENERATE;
        $data["p_id"]=$p_id;
        return $this->merge_sign_return($data);
    }

    /**
     * 生成红包推广链接
     * @param $p_id_list  推广位列表，例如：["60005_612"]
     * @param bool $generate_short_url   是否生成短链接。true-是，false-否，默认false
     * @return bool|string
     * @author Adam
     * Time: 2018/5/15 23:04
     */
    public function  api_rp_prom_url_generate($p_id_list,$generate_short_url=true){

          $data["type"]=self::DDK_API_RP_PROM_URL_GENERATE;
          $data["generate_short_url"]=$generate_short_url?"true":"false";
          $data["p_id_list"]=$p_id_list;
          return $this->merge_sign_return($data);
    }

    /**
     * 生成商城推广链接
     * @param $p_id_list  推广位列表，例如：["60005_612"]
     * @param bool $multi_group   单人团多人团标志。true-多人团，false-单人团 默认false
     * @param bool $generate_short_url  是否生成短链接，true-是，false-否
     * @param bool $generate_mobile  是否生成手机跳转链接。true-是，false-否，默认false
     * @return bool|string
     * @author Adam
     * Time: 2018/5/15 23:09
     */
    public function api_cms_prom_url_generate($p_id_list,$multi_group=true,$generate_short_url=true,$generate_mobile=true){
        $data["type"]=self::DDK_API_CMS_PROM_URL_GENERATE;
        $data["p_id_list"]=$p_id_list;
        $data["generate_short_url"]=$generate_short_url?"true":"false";
        $data["generate_mobile"]=$generate_mobile?"true":"false";
        $data["multi_group"]=$multi_group?"true":"false";
        return $this->merge_sign_return($data);

    }

    /**
     *
     * 合并参数数组，签名 发起请求并返回数据
     * @param $data
     * @return bool|string
     * @author Adam
     * Time: 10:11
     */
    private function merge_sign_return($data)
    {
        $data1 = array_merge($data, $this->ddk_common_params);

        $sign = $this->ddk_sign($data1);
        if (!empty($sign)) {
            $data1['sign'] = $sign;

        } else {
            return false;
        }

        //way 1
//        $str="?";
//        foreach ($data1 as $k=>$v){
//            $str.=$k."=".$v."&";
//        }
//       $str= substr($str,0,-1);
//        return $this->http_post(self::DDK_API_BASE_URL.$str);
        return $this->http_post(self::DDK_API_BASE_URL, $data1);
    }


}