<?php

namespace App\Services;

use TencentCloud\Common\Credential;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;
use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Iai\V20200303\IaiClient;
use TencentCloud\Iai\V20200303\Models\DetectFaceRequest;
use TencentCloud\Iai\V20200303\Models\CreatePersonRequest;
use TencentCloud\Iai\V20200303\Models\SearchFacesRequest;

class FaceTencentCloudService {

    const APP_ID = '1303250656';
    const SecretId = 'AKIDFDvNYdM1Qf7JSPCUbRzlMittVsynf4Sd';
    const SecretKey = '9xUkiQtUp191lERdXAZnmdDVzF0YkMia';

    public static function getImgDir() {
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
            $conname[] = $con . '/' . $v; // substr($v, 0, strpos($v, "."));
        }

        return $conname;
    }

    public static function detect($params) {
        try {

            $cred = new Credential(static::SecretId, static::SecretKey);
            $httpProfile = new HttpProfile();
            $httpProfile->setEndpoint("iai.tencentcloudapi.com");

            $clientProfile = new ClientProfile();
            $clientProfile->setHttpProfile($httpProfile);
            $client = new IaiClient($cred, "ap-chengdu", $clientProfile);

            $req = new DetectFaceRequest();


            $req->fromJsonString(json_encode($params));

            $resp = $client->DetectFace($req);

//            var_dump($resp);
            $resp = json_encode($resp);
            return json_decode($resp, true);
        } catch (TencentCloudSDKException $e) {
            echo $e;
        }
    }

    public static function createUserGroup($groupName, $groupId) {
        try {

            $cred = new Credential(static::SecretId, static::SecretKey);
            $httpProfile = new HttpProfile();
            $httpProfile->setEndpoint("iai.tencentcloudapi.com");

            $clientProfile = new ClientProfile();
            $clientProfile->setHttpProfile($httpProfile);
            $client = new IaiClient($cred, "ap-chengdu", $clientProfile);

            $req = new CreateGroupRequest();

            $params = [];
            $req->fromJsonString(json_encode($params));

            $resp = $client->CreateGroup($req);

            print_r($resp->toJsonString());
        } catch (TencentCloudSDKException $e) {
            echo $e;
        }
    }

    public static function addUser($Image, $groupId, $personId, $personName) {
        try {

            $cred = new Credential(static::SecretId, static::SecretKey);
            $httpProfile = new HttpProfile();
            $httpProfile->setEndpoint("iai.tencentcloudapi.com");

            $clientProfile = new ClientProfile();
            $clientProfile->setHttpProfile($httpProfile);
            $client = new IaiClient($cred, "ap-chengdu", $clientProfile);

            $req = new CreatePersonRequest();

            $params = [];
            $params['GroupId'] = $groupId;
            $params['PersonId'] = $personId;
            $params['PersonName'] = $personName;
            $params['Image'] = $Image;
            $req->fromJsonString(json_encode($params));

            $resp = $client->CreatePerson($req);

//            print_r($resp->toJsonString());
            $resp = json_encode($resp);
            return json_decode($resp, true);
        } catch (TencentCloudSDKException $e) {
            echo $e;
        }
    }

    public static function searchImg($image, $GroupIds) {
        try {

            $cred = new Credential(static::SecretId, static::SecretKey);
            $httpProfile = new HttpProfile();
            $httpProfile->setEndpoint("iai.tencentcloudapi.com");

            $clientProfile = new ClientProfile();
            $clientProfile->setHttpProfile($httpProfile);
            $client = new IaiClient($cred, "ap-chengdu", $clientProfile);

            $req = new SearchFacesRequest();

            $params = [];
            $params['GroupIds'] = $GroupIds;
            $params['Image'] = $image;
            $params['MaxPersonNum'] = 1;
            $req->fromJsonString(json_encode($params));

            $resp = $client->SearchFaces($req);

//            print_r($resp->toJsonString());
            $resp = json_encode($resp);
            return json_decode($resp, true);
        } catch (TencentCloudSDKException $e) {
            echo $e;
        }
    }

}
