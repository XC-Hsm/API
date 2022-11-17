<?php
$counter = intval(file_get_contents("./jhz.php"));  
$_SESSION['#'] = true;  
$counter++;  


$bigImgPath = "./data/jhz.png";
$bigImg = imagecreatefromstring(file_get_contents($bigImgPath));

header("content-type:image/jpeg");
$husband=$_GET['husband'];
$wife=$_GET['wife'];


$husband=$husband?$husband:"星尘";
$wife=$wife?$wife:"星尘";
$image = imagecreatetruecolor(500, 500);
$Color = imagecolorallocate($image,120, 99, 80);
$font = 'C:/wwwroot/api.xcrobot.top/60s/ttf/simsun.ttc';
imagettftext($bigImg, 19, -1, 105, 570, $Color, $font, $husband);
imagettftext($bigImg, 19,-3, 104, 737, $Color, $font, $wife);
if($_GET['hsex']){
	imagettftext($bigImg, 19, 6, 461, 579, $Color, $font, $_GET['hsex']);
}else{
	imagettftext($bigImg, 19, 6, 461, 579, $Color, $font, "男");
}
if($_GET['wsex']){
	imagettftext($bigImg, 19, 5,478, 741, $Color, $font, $_GET['wsex']);
}else{
	imagettftext($bigImg, 19, 5, 478, 741, $Color, $font, "女");
}

if($_GET['hnationality']){
	imagettftext($bigImg,  19, -1, 105, 613,$Color, $font, $_GET['hnationality']);
}else{
	imagettftext($bigImg, 19, -1, 105, 613, $Color, $font, "中国");
}
if($_GET['wnationality']){
	imagettftext($bigImg,  19, -3, 96, 783,$Color, $font, $_GET['wnationality']);
}else{
	imagettftext($bigImg, 19, -3, 96, 783, $Color, $font, "中国");
}
//ob_clean();

imagepng($bigImg);


imagedestroy($bigImg);


?>