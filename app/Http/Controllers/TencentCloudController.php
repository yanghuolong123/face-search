<?php

namespace App\Http\Controllers;

use App\Services\FaceTencentCloudService;

class TencentCloudController {

    public function detect() {
        echo '<pre>';

        $imgs = FaceTencentCloudService::getImgDir();
        foreach ($imgs as $img) {
            //$img = file_get_contents('/work/tmp/img/2020-09-22/0/0_2020-09-22_16-52-24.jpg');
            $img = file_get_contents($img);
            $img = base64_encode($img);

            $params = [];
            $params['Image'] = $img;
            $data = FaceTencentCloudService::detect($params);

            var_dump("=============== begin ==================");
            var_dump($data);
            var_dump("============== end ================");
            sleep(1);
        }
    }

    public function addUser() {
        echo '<pre>';

        $imgs = FaceTencentCloudService::getImgDir();
        foreach ($imgs as $img) {
            //$img = file_get_contents('/work/tmp/img/2020-09-22/0/0_2020-09-22_16-52-24.jpg');
            $old = $img;
            $dir = basename(dirname($img));
            $img = file_get_contents($img);
            $img = base64_encode($img);


            $data = FaceTencentCloudService::addUser($img, 'linkdome', 'user_' . $dir, 'username_' . $dir);

            var_dump("=============== begin $old ==================");
            var_dump($data);
            var_dump("============== end ================");
            die;
        }
    }

    public function search() {
        echo '<pre>';

        $imgs = FaceTencentCloudService::getImgDir();
        foreach ($imgs as $img) {
//            $img = '/work/tmp/img/gg.png';
            $old = $img;
            $dir = basename(dirname($img));
            $img = file_get_contents($img);
            $img = base64_encode($img);


            $data = FaceTencentCloudService::searchImg($img, ['linkdome']);

            var_dump("=============== begin $old ==================");
            var_dump($data);
            var_dump("============== end ================");
//           die;
            sleep(1);
        }
    }

}
