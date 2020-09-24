<?php

namespace App\Services;

class FaceTencentService {

    const APP_ID = '2156926534';
    const APP_KEY = 'SOZYLufvRmtozS6i';

    private static $_client;

    public static function init() {
        require_once base_path() . '/app/SDK/Tencent/aiplat/SDK/Configer.php';
        require_once base_path() . '/app/SDK/Tencent/aiplat/SDK/Signature.php';
        require_once base_path() . '/app/SDK/Tencent/aiplat/SDK/HttpUtil.php';

        \Configer::setAppInfo(self::APP_ID, self::APP_KEY);
    }

//    public static function getClient() {
//        require_once base_path() . '/app/SDK/Tencent/aiplat/include.php';
//
//        if (!empty(static::$_client)) {
//            return static::$_client;
//        }
//
//
////设置AppID与AppKey
//        static::$_client = \Configer::setAppInfo(self::APP_ID, self::APP_KEY);
//        return static::$_client;
//    }

    public static function getImgDir() {
        $con = '/work/tmp/img/2020-09-22/11';
        // 扫描$con目录下的所有文件
        $filename = scandir($con);
        // 定义一个数组接收文件名
        $conname = array();
        foreach ($filename as $k => $v) {
            // 跳过两个特殊目录   continue跳出循环
            if ($v == "." || $v == "..") {
                continue;
            }
            //截取文件名，我只需要文件名不需要后缀;然后存入数组。如果你是需要后缀直接$v即可
            $conname[] = $con . '/' . $v; // substr($v, 0, strpos($v, "."));
        }

        return $conname;
    }

    public static function detect($path) {
        static::init();
        // 图片base64编码
        //$path = '/path/to/image';
        $data = file_get_contents($path);
        $base64 = base64_encode($data);

// 设置请求数据
        $appkey = static::APP_KEY;
        $params = array(
            'app_id' => static::APP_ID,
            'image' => $base64,
            'mode' => '0',
            'time_stamp' => strval(time()),
            'nonce_str' => strval(rand()),
            'sign' => '',
        );

        $params['sign'] = \Signature::getReqSign($params, $appkey);


// 执行API调用
        $url = 'https://api.ai.qq.com/fcgi-bin/face/face_detectface';

        $response = \HttpUtil::doHttpPost($url, $params);

        return $response;
    }

    public static function addUser($path, $options) {
        static::init();
        // 图片base64编码
        //$path = '/path/to/image';
        $data = file_get_contents($path);
        $base64 = base64_encode($data);

// 设置请求数据
        $appkey = static::APP_KEY;
        $params = array(
            'app_id' => static::APP_ID,
            'group_ids' => $options['group'], // 'group0|group1',
            'person_id' => $options['personId'], // 'person0',
            'image' => $base64,
            'person_name' => $options['personName'], // 'name',
            'tag' => 'moreinfo',
            'time_stamp' => strval(time()),
            'nonce_str' => strval(rand()),
            'sign' => '',
        );
        $params['sign'] = \Signature::getReqSign($params, $appkey);
        //var_dump($params);

// 执行API调用
        $url = 'https://api.ai.qq.com/fcgi-bin/face/face_newperson';
        $response = \HttpUtil::doHttpPost($url, $params);
        return $response;
    }

    public static function search($path, $options) {
         static::init();
        // 图片base64编码
        //$path = '/path/to/image';
        $data = file_get_contents($path);
        $base64 = base64_encode($data);

// 设置请求数据
        $appkey = static::APP_KEY;
        $params = array(
            'app_id' => static::APP_ID,
            'image' => $base64,
            'group_id' =>  $options['group'],//'group_id',
            'topn' => '5',
            'time_stamp' => strval(time()),
            'nonce_str' => strval(rand()),
            'sign' => '',
        );
        $params['sign'] = \Signature::getReqSign($params, $appkey);

// 执行API调用
        $url = 'https://api.ai.qq.com/fcgi-bin/face/face_faceidentify';
        $response = \HttpUtil::doHttpPost($url, $params);
        return $response;
    }

}
