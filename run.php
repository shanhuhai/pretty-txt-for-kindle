<?php
/**
 * Created by PhpStorm.
 * User: shanhuhai
 * Date: 14-1-24
 * Time: 下午11:40
 */

$filePath = $argv[1];
$content = file_get_contents($filePath);
$length = strlen($content);
$newContent = array();
for($i=0 ;$i<$length-1;){
$str = substr($content,$i,1);
    $str2 = substr($content, $i,3);
    $str4 = substr($content, $i, 5);
    $strf2 = substr($content, $i-2, 3);

    $ascii2 = string2ascii($str2);
    $ascii4 = string2ascii($str4);
    $asciif2 = string2ascii($strf2);

   // echo $ascii2."***\n";
    //echo $ascii4."\n";
    //echo $asciif2."\n";
    if($ascii2 == '0D0A' && $ascii4 !='0D0A0D0A' && $asciif2 != '0D0A'){
        $i = $i+2;
        continue;
    } else {
        $i++;
    }
    $newContent[]  = $str;
    //if($i>100){exit;}
}

function string2ascii($string){
    $length = strlen($string);
    $hex = '';
    for($i=0; $i<$length-1; $i++){
        $ord = ord(substr($string, $i, 1));
        //echo $ord."\n";
        if($ord<=15){
            $thex = '0'. strval(dechex($ord));
        } else {
            $thex = dechex($ord);
        }
        $hex .= strtoupper($thex);
    }
    return $hex;
}

function ascii2string($hex){
    $length = strlen($hex);
    $str = array();
    for($i=0; $i<$length-1; $i+=2){
        $ord =  hexdec(substr($hex,$i,2));
        $str[] = chr($ord);
    }
    return implode('', $str);
}
$pathInfo = pathinfo($filePath);
$newFilePath = $pathInfo['dirname'].'/'.$pathInfo['filename'].'-new.'.$pathInfo['extension'];
file_put_contents($newFilePath,implode('',$newContent));