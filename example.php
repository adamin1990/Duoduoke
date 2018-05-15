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
* Date: 2018/5/15
* Time: 23:48
* @link: https://www.lixiaopeng.top
**************************************************
*/
require_once("Duoduoke.php");
$config['client_id']="your client id";
$config['client_secret']="your client secret";
$config['duoduoke_redirect_url']="";  //可选
$config["access_token"]="";  //可选
$ddk=new Duoduoke($config);
echo $ddk->api_goods_cat_get();