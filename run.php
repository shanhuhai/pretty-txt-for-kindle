<?php
/**
 * Created by PhpStorm.
 * User: shanhuhai
 * Date: 14-1-24
 * Time: 下午11:40
 */

$filePaths = array();
$newPathType = 1; //新建目录的方式:1、处理单文件，2、创建多文件

if (isset($argv[1])) {
    if (is_file($argv[1])) {
        $filePaths[] = $argv[1];
        $newPathType = 1;
    } elseif (is_dir($argv[1])) {
        $filePaths = rscandir($argv[1]);
        $newPathType = 2;
    }
} else {
    exit('dir dosent exists');
}

//print_r($filePaths);exit;

function rscandir($base = '', $return = 'all', &$data = array())
{
    $ds = '/'; // DIRECTORY_SEPARATOR
    $base = rtrim($base, $ds) . $ds;
    $array = array_diff(scandir($base), array('.', '..', '.svn'));
    foreach ($array as $value) {
        if (is_dir($base . $value)) {
            if ($return != 'file')
                $data[] = $base . $value . $ds;
            $data = rscandir($base . $value . $ds, $return, $data);
        } elseif (is_file($base . $value)) {
            if ($return == 'dir') continue;
            $data[] = $base . $value;
        }
    }
    return $data;
}

foreach ($filePaths as $filePath) {
    $fileName = array_pop(explode('/', $filePath));
    if($fileName == '.DS_Store'){
        continue;
    }


    $content = file_get_contents($filePath);
    $length = strlen($content);
    $newContent = array();
    for ($i = 0; $i < $length - 1;) {
        $str = substr($content, $i, 1);
        $str2 = substr($content, $i, 3);
        $str4 = substr($content, $i, 5);
        $strf2 = substr($content, $i - 2, 3);

        $ascii2 = string2ascii($str2);
        $ascii4 = string2ascii($str4);
        $asciif2 = string2ascii($strf2);

        // echo $ascii2."***\n";
        //echo $ascii4."\n";
        //echo $asciif2."\n";
        if ($ascii2 == '0D0A' && $ascii4 != '0D0A0D0A' && $asciif2 != '0D0A') {
            $i = $i + 2;
            continue;
        } else {
            $i++;
        }
        $newContent[] = $str;

    }

    $pathInfo = pathinfo($filePath);
    if($newPathType ==1 ){
        $newFilePath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '-new.' . $pathInfo['extension'];
    } elseif($newPathType ==2){
        $newFileDir = $pathInfo['dirname'].'-new';
        if(!file_exists($newFileDir)){
            mkdir($newFileDir, 0755, true);
        }
        $newFilePath = $newFileDir . '/' . $pathInfo['filename']  .'.' .$pathInfo['extension'];
    }
    file_put_contents($newFilePath, implode('', $newContent));
}
function string2ascii($string)
{
    $length = strlen($string);
    $hex = '';
    for ($i = 0; $i < $length - 1; $i++) {
        $ord = ord(substr($string, $i, 1));
        //echo $ord."\n";
        if ($ord <= 15) {
            $thex = '0' . strval(dechex($ord));
        } else {
            $thex = dechex($ord);
        }
        $hex .= strtoupper($thex);
    }
    return $hex;
}

function ascii2string($hex)
{
    $length = strlen($hex);
    $str = array();
    for ($i = 0; $i < $length - 1; $i += 2) {
        $ord = hexdec(substr($hex, $i, 2));
        $str[] = chr($ord);
    }
    return implode('', $str);
}
