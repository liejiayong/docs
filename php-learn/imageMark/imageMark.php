<?php

//open pic
$src = 'image/bg.png';//pic path

$info= getimagesize($src);//pic attrubute

$type = image_type_to_extension($info[2],false);//false,出去png前面的.

$fun = "imagecreatefrom{$type}";

$image = $fun($src);

//control pic
$image_mark = 'image/mark.jpg';//set mark

$info_mark = getimagesize($image_mark);

$type_mark = image_type_to_extension($info_mark[2],false);

$fun_mark = "imagecreatefrom{$type_mark}";

$water = $fun_mark($image_mark);

imagecopymerge($image, $water, 20, 30, 0, 0, $info_mark[0], $info_mark[1], 30);//copy

imagedestroy($water);//destory water

//output picc

header("Content-type:",$info['mime']);

$funs = "image{$type}";

$funs($image);

$funs($image,'imageMark.'.$type);
//destory pic
imagedestroy($image);



?>