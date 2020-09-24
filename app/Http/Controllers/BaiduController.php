<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use App\Services\FaceBaiduService;

class BaiduController {

    public static function request_post($url = '', $param = '') {
        if (empty($url) || empty($param)) {
            return false;
        }

        $postUrl = $url;
        $curlPost = $param;
        $curl = curl_init(); //初始化curl
        curl_setopt($curl, CURLOPT_URL, $postUrl); //抓取指定网页
        curl_setopt($curl, CURLOPT_HEADER, 0); //设置header
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); //要求结果为字符串且输出到屏幕上
        curl_setopt($curl, CURLOPT_POST, 1); //post提交方式
        curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
        $data = curl_exec($curl); //运行curl
        curl_close($curl);

        return $data;
    }

    public function getAccessToken() {
        $key = md5(__CLASS__ . __METHOD__);
        if (Cache::has($key)) {
            return Cache::get($key);
        }

        $url = 'https://aip.baidubce.com/oauth/2.0/token';
        $post_data['grant_type'] = 'client_credentials';
        $post_data['client_id'] = 'ZFvjYZaZpcfTz6v4uu7rKBWu';
        $post_data['client_secret'] = 'UH3tB6VTRYhQ1dTWE7dtGv7xdkOxbash';
        $o = "";
        foreach ($post_data as $k => $v) {
            $o .= "$k=" . urlencode($v) . "&";
        }
        $post_data = substr($o, 0, -1);

        $res = static::request_post($url, $post_data);
        $res = json_decode($res, true);

//        echo '<pre>';
//        var_dump($res);

        $accessToken = $res['access_token'] ?? '';
        if (!empty($accessToken)) {
            Cache::add($key, $accessToken, 180);
        }

        return $accessToken;
    }

    public function detect() {
        echo '<pre>';
//        $res = FaceBaiduService::addGroup('test3');
//        var_dump($res);
//        die;
        //$image = 'https://dss1.bdstatic.com/70cFvXSh_Q1YnxGkpoWK1HF6hhy/it/u=3742147359,936145178&fm=26&gp=0.jpg';
        // 获取当前文件的上级目录
        $con = '/work/tmp/img/2020-09-22/0';
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
            $conname[] = $v; // substr($v, 0, strpos($v, "."));
        }
        //echo '<pre>';var_dump($conname);
//die;
//        echo '<pre>';
        var_dump("总共：" . count($conname));
        $success = 0;
        $failed = [];
        foreach ($conname as $file) {
            $image_file = $con . '/' . $file;
            $image = FaceBaiduService::base64EncodeImage($image_file);

            $options = array();
            $options["face_field"] = "age,gender";
            $options["max_face_num"] = 2;
            $options["face_type"] = "LIVE";
            $options["liveness_control"] = "LOW";

            $imageType = 'BASE64';
            $res = FaceBaiduService::detect($image, $imageType, $options);
            if ($res['error_code'] == 0) {
                $success++;
            } else {
                //unlink($image_file);
                //echo '<pre>';
                $failed[] = $image_file;
                //var_dump($image_file);
                var_dump($res);
                //var_dump("===================");
            }
            sleep(1);
//            echo '<pre>';
//            var_dump($res);
        }

        var_dump('成功：' . $success);
        var_dump('失败：', $failed);
    }

    public function addUser() {
        $image = "/work/tmp/img/2020-09-22/12/12_2020-09-22_19-22-21.jpg";
        $image = FaceBaiduService::base64EncodeImage($image);

        $imageType = "BASE64";

        $groupId = "20200923";

        $userId = "user_12";

// 如果有可选参数
        $options = array();
        $options["user_info"] = "user's info";
        $options["quality_control"] = "NORMAL";
        $options["liveness_control"] = "LOW";
        $options["action_type"] = "REPLACE";

// 带参数调用人脸注册
        $res = FaceBaiduService::addUser($image, $imageType, $groupId, $userId, $options);

        echo '<pre>';
        var_dump($res);
    }

    public function search() {

        $images = FaceBaiduService::getImgDir();
        $imageType = "BASE64";
        $options = array();
        $groupIdList = "20200923";

        echo '<pre>';
        $errors = [];
        foreach ($images as $image) {
            $old = $image;
            $image = FaceBaiduService::base64EncodeImage($image);
            $res = FaceBaiduService::search($image, $imageType, $groupIdList, $options);
            sleep(1);            
                       
            if($res['error_code']!=0) {
                var_dump('========失败=================');
                var_dump($res['error_msg']);
            } else {
                var_dump('========成功=================');
            }
            var_dump($old);
            var_dump($res); 
            var_dump('=======================================');
        }
       

       

//
//        $image = "/work/tmp/img/2020-09-22/4/4_2020-09-22_16-02-13.jpg";
//        $image = FaceBaiduService::base64EncodeImage($image);
//
//        $imageType = "BASE64";
//
//        $groupIdList = "test2";
//
//
//// 如果有可选参数
//        $options = array();
////        $options["max_face_num"] = 3;
////        $options["match_threshold"] = 70;
////        $options["quality_control"] = "NORMAL";
////        $options["liveness_control"] = "LOW";
////        $options["user_id"] = "233451";
////        $options["max_user_num"] = 3;
//// 带参数调用人脸搜索
//        $res = FaceBaiduService::search($image, $imageType, $groupIdList, $options);
//        echo '<pre>';
//        var_dump($res);
    }

}
