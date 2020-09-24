<?php

namespace App\Services;

class FaceBaiduService {

    const APP_ID = '22731302';
    const API_KEY = 'ZFvjYZaZpcfTz6v4uu7rKBWu';
    const SECRET_KEY = 'UH3tB6VTRYhQ1dTWE7dtGv7xdkOxbash';

    private static $_client;

    public static function getClient() {
        require_once base_path() . '/app/SDK/Baidu/AipFace.php';

        if (!empty(static::$_client)) {
            return static::$_client;
        }

        static::$_client = new \AipFace(self::APP_ID, self::API_KEY, self::SECRET_KEY);

        return static::$_client;
    }

    public static function detect($image, $imageType = 'URL', $options = []) {
//        $image = "取决于image_type参数，传入BASE64字符串或URL字符串或FACE_TOKEN字符串";
//
//        $imageType = "BASE64";
//
//// 调用人脸检测
//        $client->detect($image, $imageType);
//
//// 如果有可选参数
//        $options = array();
//        $options["face_field"] = "age";
//        $options["max_face_num"] = 2;
//        $options["face_type"] = "LIVE";
//        $options["liveness_control"] = "LOW";
//
//// 带参数调用人脸检测
        $client = static::getClient();
        return $client->detect($image, $imageType, $options);
    }

    public static function base64EncodeImage($image_file) {
        $image_info = getimagesize($image_file);
        $base64_image_content = (base64_encode(file_get_contents($image_file)));
        //var_dump($base64_image_content);die;
        return $base64_image_content;
    }

    public static function addGroup($groupId) {
        $client = static::getClient();
        return $client->groupAdd($groupId);
    }

    public static function addUser($image, $imageType, $groupId, $userId, $options) {
        $client = static::getClient();
        return $client->addUser($image, $imageType, $groupId, $userId, $options);
    }

    public static function search($image, $imageType, $groupIdList, $options) {
        $client = static::getClient();
        return $client->search($image, $imageType, $groupIdList, $options);
    }

    public static function getImgDir() {
        $con = '/work/tmp/img/2020-09-22/12';
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
            $conname[] = $con . '/'.$v; // substr($v, 0, strpos($v, "."));
        }
        
        return $conname;
    }

}
