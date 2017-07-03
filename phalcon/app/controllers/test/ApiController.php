<?php

namespace App\Controllers\Test;

use Gregwar\Captcha\CaptchaBuilder;
use limx\func\Curl;

class ApiController extends Controller
{

    public function indexAction()
    {
        return $this->view->render('test/api', 'index');
    }

    public function yunpianAction()
    {
        return $this->view->render('test/api', 'yunpian');
    }

    public function captchaAction()
    {
        /** composer require gregwar/captcha */
        $builder = new CaptchaBuilder;
        $builder->build();
        session('captcha', $builder->getPhrase());
        header('Content-type: image/jpeg');
        return $builder->output();
    }

    public function sendMsgAction($code = '')
    {
        if ($code == session('captcha')) {
            $url = 'https://sms.yunpian.com/v2/sms/single_send.json';
            $mobile = '18678017521';
            $address = "山东省潍坊市奎文区";
            $text = "【佑骏信息】有人下单了，快去看看吧。查询地址是{$address}。";
            $apikey = env('YUNPIAN_APIKEY');
            $data = ['text' => $text, 'apikey' => $apikey, 'mobile' => $mobile];
            $res = Curl::post($url, $data);
            $arr = json_decode($res, true);
            return success([$arr]);
        }
        return error('发送失败');
    }

    public function huanxinAction()
    {
        $option = [
            'client_id' => env('EASEMOB_ID'),
            'client_secret' => env('EASEMOB_SECRET'),
            'org_name' => env('EASEMOB_ORG_NAME'),
            'app_name' => env('EASEMOB_APP_NAME'),
        ];
        $easemob = new \limx\tools\Easemob($option);

        // $easemob->setStorageAdapter(function ($data) {
        //     $file = BASE_PATH . '/storage/cache/data/huanxin2';
        //     if ($data) {
        //         // 存储
        //         file_put_contents($file, $data);
        //     } else {
        //         if (file_exists($file)) {
        //             return file_get_contents($file);
        //         }
        //         return false;
        //     }
        // });
        $easemob->setTokenPath(BASE_PATH . '/storage/cache/data/huanxin');

        // $easemob->userAuthorizedRegister('limx2', '910123');
        // $res = $easemob->sendToUsers(['pt982'], 'pt948', ['msg' => '环信测试']);
        $res = $easemob->userInfoByName('pt982');
        dump($res);
    }

    public function apiAction()
    {
        $data = $this->request->get();
        $header = $this->request->getHeaders();
        $json = $this->request->getJsonRawBody();
        $method = $this->request->getMethod();

        $res = [
            'header' => $header,
            'body' => $data,
            'json' => $json,
            'method' => $method,
        ];

        return self::success($res);
    }

    public function filterAction()
    {
        return $this->view->render('test/file', 'filter');
    }

    public function pfnFilterAction()
    {
        $filter = di('filter');
        $filter->add('test', function ($value) {
            return $value . ' test';
        });

        $id = $this->request->get('id', 'int');
        $say = $this->request->get('say', 'test');
        return self::success([$id, $say]);
    }

    public function validationAction()
    {
        return $this->view->render('test/file', 'validation');
    }

    public function pfnValidationAction()
    {
        $validation = new \Phalcon\Validation();
        $validation->add(
            'id',
            new \Phalcon\Validation\Validator\Callback(
                [
                    'callback' => function ($data) {
                        return $data['id'] % 2 == 0;
                    },
                    'message' => 'Id 必须能被２整除'
                ]
            )
        );

        $messages = $validation->validate($this->request->get());

        if ($messages->valid()) {
            $error = '';
            foreach ($messages as $msg) {
                $error .= $msg->getMessage();
            }
            return self::error($error);
        }
        $id = $this->request->get('id', 'int');
        $say = $this->request->get('say');
        return self::success([$id, $say]);
    }
}

