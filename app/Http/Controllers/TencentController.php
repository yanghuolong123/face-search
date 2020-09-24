<?php

namespace App\Http\Controllers;

use App\Services\FaceTencentService;

class TencentController {

    public function detect() {
        echo '<pre>';
        $paths = FaceTencentService::getImgDir();
        $failed = [];
        foreach ($paths as $path) {
            //$path = '/work/tmp/img/2020-09-22/2/2_2020-09-22_17-04-48.jpg';
            $data = FaceTencentService::detect($path);
            $data = json_decode($data, true);
            if ($data['ret'] != 0) {
                var_dump("================失败 $path =====================");
                $failed[] = $path;
            } else {
                var_dump("=============== 成功 $path =============================");
            }

            var_dump($data);
            var_dump("------------------------------------------------------");
            sleep(1);
        }
        var_dump("======================== 检测失败记录 ==================");
        var_dump($failed);
    }

    public function addUser() {
        echo '<pre>';
        $paths = FaceTencentService::getImgDir();
        $failed = [];
        foreach ($paths as $path) {
//            $path = '/work/tmp/img/2020-09-22/0/0_2020-09-22_19-40-26.jpg';
//            
            $dir = basename(dirname($path));
//            var_dump($dir);die;

            $options['group'] = 'group1';
            $options['personId'] = 'user_' . $dir;
            $options['personName'] = 'username_' . $dir;

            $data = FaceTencentService::addUser($path, $options);
            $data = json_decode($data, true);
            if ($data['ret'] != 0) {
                var_dump("================失败 $path =====================");
                $failed[] = $path;
            } else {
                var_dump("=============== 成功 $path =============================");
            }

            var_dump($data);
            var_dump("------------------------------------------------------");
            die;
        }
        var_dump("======================== 检测失败记录 ==================");
        var_dump($failed);
    }

    public function search() {
        echo '<pre>';
        $paths = FaceTencentService::getImgDir();
        $failed = [];
        foreach ($paths as $path) {
//            $path = '/work/tmp/img/2020-09-22/0/0_2020-09-22_16-52-33.jpg';
//            
            $dir = basename(dirname($path));
//            var_dump($dir);die;

            $options['group'] = 'group1';

            $data = FaceTencentService::search($path, $options);
            $data = json_decode($data, true);
            if ($data['ret'] != 0) {
                var_dump("================失败 $path =====================");
                $failed[] = $path;
            } else {
                var_dump("=============== 成功 $path =============================");
            }

            var_dump($data);
            var_dump("------------------------------------------------------");
            die;
            sleep(1);
        }
        var_dump("======================== 搜索失败记录 ==================");
        var_dump($failed);
    }

}
