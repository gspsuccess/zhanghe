<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/9/26
 * Time: 10:50
 */

/**
 * @param $url
 * @param int $httpCode
 * @return mixed
 */
function curl_get($url,&$httpCode = 0)
{
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,10);
    $file_contents = curl_exec($ch);
    $httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
    curl_close($ch);

    return $file_contents;
}

/**
 * @param $url
 * @param array $params
 * @return mixed
 */
function curl_post($url,$params = [])
{
    $data_string = json_encode($params);
    $ch = curl_init();

    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_HEADER,0);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,10);
    curl_setopt($ch,CURLOPT_POST,1);
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$data_string);

    curl_setopt(
        $ch,CURLOPT_HTTPHEADER,
        array(
            'Content-Type:application/json'
        )
    );

    $data = curl_exec($ch);
    curl_close($ch);

    return $data;
}


/**
 * 返回一定长度的随机字符串
 * @param $length
 * @return null|string
 */
function getRandChars($length)
{
    $str = null;
    $strPol = 'ABCDEFGHIJKLMNOPTRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
    $max = strlen($strPol) -1;

    for($i = 0;$i<$length;$i++)
    {
        $str .= $strPol[rand(0,$max)];
    }

    return $str;
}
