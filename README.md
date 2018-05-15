# Duoduoke
多多客 多多进宝 PHP SDK，封装了多多客和多多客工具API

# 调用方式


    require_once("Duoduoke.php");
    $config['client_id']="your client id";
    $config['client_secret']="your client secret";
    $config['duoduoke_redirect_url']="";  //可选
    $config["access_token"]="";  //可选
    $ddk=new Duoduoke($config);
    echo $ddk->api_goods_cat_get();

#Author

Adamin90  
email: adamin1990@gmail.com  
website: https://www.lixiaopeng.top

#Lisence 

MIT.

