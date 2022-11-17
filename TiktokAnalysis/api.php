<?php

error_reporting(0);
$dy_share = $_GET['url'];
if(!isset($dy_share)||empty($dy_share)){
    $arr= ["code"=>-1,
    "msg"=>"URL ERROR!"];
    echo json_encode($arr, JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}
function douyin($shareurl){
    $header = get_headers($shareurl,1);
    $realurl = $header['Location'][1]; //获取真实链接
    //var_dump($realurl);
    $p_search='#(\d+)()?\s*?#s';    //  可以用explode()，这里用的正则
    preg_match_all($p_search,$realurl,$videoid);
    $videoid = $videoid[0][0];   //获取videoid
    if($realurl == NULL){
        $urlarr = explode('/',$shareurl);
        $videoid = $urlarr[4];
    }
    //var_dump($videoid);
    $vidjson = 'https://www.iesdouyin.com/web/api/v2/aweme/iteminfo/?item_ids='.$videoid;
    //var_dump($vidjson);
    $vidarr = file_get_contents($vidjson);
    //print_r($vidarr);
    $getvid = json_decode($vidarr, true);
    $dy_vid= $getvid['item_list'][0]['video']['vid'];//得到vid
    $cover=$getvid['item_list'][0]['video']['origin_cover']['url_list'][0];
    $GLOBALS['cover']=$cover;
    $GLOBALS['title']=$getvid['item_list'][0]['desc'];
    return $dy_vid;
}
global $vid;
global $cover;
global $title;
$vid = douyin($dy_share);
//echo $vid;
function dyzl(){
    if($GLOBALS['vid']==NULL){
        echo '链接有误，无法解析';
        exit;
    }
    header("Content-Type: text/json;charset=utf-8");
    $url = 'https://aweme.snssdk.com/aweme/v1/play/?video_id='.$GLOBALS['vid'].'&ratio=1080p&line=0';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //若给定url自动跳转到新的url,有了下面参数可自动获取新url内容：302跳转
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    //设置cURL允许执行的最长秒数。
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:47.0) Gecko/20100101 Firefox/47.0');
    curl_setopt($ch, CURLOPT_REFERER, $url);
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    $content = curl_exec($ch);
    //获取请求返回码，请求成功返回200
    $code = curl_getinfo($ch,CURLINFO_HTTP_CODE);
    //echo $code . "\n\n";
    
    //获取一个cURL连接资源句柄的信息。
    //$headers 中包含跳转的url路径 
    $headers = curl_getinfo($ch);
    //var_dump($headers);
    //print_r($headers);
    return $headers['url'];
}
$nomarkurl= dyzl();
$arr = ["code"=>200,
	"msg"=>"Success",
	"url"=>$nomarkurl,
	"cover"=>$cover,
	"title"=>$title];

 echo json_encode($arr, JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
 ?>