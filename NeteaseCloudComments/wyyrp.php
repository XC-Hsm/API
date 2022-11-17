<?php
header('Content-type: text/json;charset=utf-8');
$format =$_GET['format'];;
$post = 'params=J3PNzHXmp1TEvtvB0T9U5etnBT1ZGPC3tgOfAGOzLnMRio8FftbMN8D8bLb6KJ93oxkPrEarnMb2ta5cIGNSt2K1RL4Ty6VtpPckma6qUh3gNj0kQUvRcLoE33QxcnOEx0Q12dTmuA3v3ZKM4rIGqrrfkYt8L%2Ffo2Mbl5esb90G8JjrDB7dgW77o9vee%2BnXQW8k3%2Bnlzh%2BiD9gL8njz0RmfncfzfBijP9OowgxV4iPxYzRgQLqEdM1czS8gw%2FEPjYB%2FZ8d0uiOwhwNYHS6Os5IDJZERYugcggu2COFwe95koUgSSBcpzEKXtfb1njWs%2BLF%2BO9DCV8WDvGoKy%2Bml5vVhMZ%2F%2FALNxTsVvAdPljQh4%3D&encSecKey=149b0e7ee1d86382e2fa2f3d263c4e20067c3a2b70e05e3c47a1459c6c28f953e973a34573a6cc72e23c70d8e6fda218890df18de71180f67b3f22bf7dc2f2ada6280d858d09f57acfb869ab7ba33e89072a067db04d4fdeeed0ecb3e9262e5a089099aeaaac9598ba1bf597c0b3c307053c79763769e07a5b759197cecb453c';
$music = get_music_list($post);

if($format == 'text') {
	$result	=	$music['content'].PHP_EOL;
	$result	.=	'来自@'. $music['nickname'].PHP_EOL;
	$result	.=	'在歌曲「'.$music['name'].'」下方的评论'.PHP_EOL;
	print_r($result);
}else{
	$result	=	json_encode(array(
		'code'	=>	1,
		'data'	=>	$music
	),320);
	print_r($result);
}
function get_url($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);  //设置访问的url地址
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);//不输出内容
    $result =  curl_exec($ch);
    curl_close ($ch);
    return $result;
}
function getSubstr($str, $leftStr)
{
    $left = strpos($str, $leftStr)+strlen($leftStr);
    return substr($str, $left);
}
function get_music_list($post){
	$rel=get_url("https://api.uomg.com/api/rand.music?sort=%E7%83%AD%E6%AD%8C%E6%A6%9C&format=json");

	$music=json_decode($rel,true)['data'];
	$id=getSubstr($music['url'],"id=");

	$id='516392300';
	/*$rel = G163_curl('https://music.163.com/weapi/playlist/detail', $post);
	$arr = json_decode($rel,true)['result']['tracks'];
	$music = $arr[array_rand($arr,1)];//862101001*/
	$rel = G163_curl('https://music.163.com/weapi/v1/resource/comments/R_SO_4_'.$id."?csrf_token=1ac15bcb947b3900d9e8e6039d121a81", $post);


	
	$arr = json_decode($rel,true)['hotComments'];

	if(sizeof($arr)>=1)
	$hotComments = $arr[array_rand($arr,1)];
	else
	return "";
  	$data = array(
  		'name'			=>	$music['name']
  		,'url'			=>	'http://music.163.com/song/media/outer/url?id='.$id.'.mp3'
  		,'picurl'		=>	$music['album']['picUrl']
  		,'artistsname'	=>	$music['artists'][0]['name']
		,'avatarurl'	=>	$hotComments['user']['avatarUrl']
		,'nickname'		=>	$hotComments['user']['nickname']
		,'content'		=>	$hotComments['content']
  	);

	return $data;
}
function G163_curl($url, $post=0, $referer=0, $cookie=0, $header=0, $ua=0, $nobaody=0){
	$ch = curl_init();
	$ip = rand(0,255).'.'.rand(0,255).'.'.rand(0,255).'.'.rand(0,255) ;
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	$httpheader[] = "Accept:application/json";
	$httpheader[] = "Accept-Encoding:gzip, deflate, br";
	$httpheader[] = "Accept-Language:zh-CN,zh;q=0.8";
	$httpheader[] = "Accept-Type:application/x-www-form-urlencoded";
	$httpheader[] = "Origin:https://music.163.com";
	$httpheader[] = "Origin:https://music.163.com";

	curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);
	if ($post) {
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	}
	if ($header) {
		curl_setopt($ch, CURLOPT_HEADER, true);
	}
	if ($cookie) {
		curl_setopt($ch, CURLOPT_COOKIE, $cookie);
	}
	if($referer){
		if($referer==1){
			curl_setopt($ch, CURLOPT_REFERER, 'https://music.163.com/outchain/player?type=0&id=2250011882&auto=1');
		}else{
			curl_setopt($ch, CURLOPT_REFERER, $referer);
		}
	}
	if ($ua) {
		curl_setopt($ch, CURLOPT_USERAGENT, $ua);
	}
	else {
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (iPhone; CPU iPhone OS 9_1 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13B143 Safari/601.1");
	}
	if ($nobaody) {
		curl_setopt($ch, CURLOPT_NOBODY, 1);
	}
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	curl_setopt($ch, CURLOPT_ENCODING, "gzip");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$ret = curl_exec($ch);
	
	curl_close($ch);

	return $ret;
}