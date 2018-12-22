<?php
/**
*     FileName: cache.php
*         Desc:  PHP简单文件缓存
*       Author: Lenix
*        Email: yyb8@vip.qq.com
*     HomePage: http://www.p2hp.com
*      Version: 0.0.1
*   LastChange: 2018-12-22 09:50:50
*      History:
*/
define("HC_CACHE",true);//启用压缩

if (!isset($_GET['phpfilecache']) && HC_CACHE) {
    define("HC_PATH", dirname(__FILE__)."/cache/");
    define("HC_TIME", 86400);//缓存时间:秒
    define("HC_COMPRESS", true);//缓存压缩
    echo HC_getcache();
    exit;
}


function HC_getcache($iscache='')
{
    $url="http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
    $cacheurl=strpos($url, "?")?$url."&phpfilecache=true":$url."?phpfilecache=true";
    $cachename=HC_PATH.md5($url).".c";
    $cachetime=$iscache?time()+1:time()-(HC_TIME);

    if (file_exists($cachename) && filemtime($cachename)>=$cachetime) {
        if (HC_COMPRESS) {
            $return=file_get_contents($cachename);
            $data=function_exists('gzcompress')?@gzuncompress($return):$return;
            return unserialize($data);
        } else {
            $return=file_get_contents($cachename);
            $data=$return;
            return $data;
        }
    } else {
        $return=file_get_contents($cacheurl);
        HC_writecache($cachename, $return);
        return $return;
    }
}
function HC_writecache($name, $array)
{
    if (HC_COMPRESS) {
        function_exists('gzcompress')?$return =gzcompress(serialize($array)):$return=serialize($array);
        @file_put_contents($name, $return);
    } else {
        $return=$array;
        @file_put_contents($name, $return);
    }
}
